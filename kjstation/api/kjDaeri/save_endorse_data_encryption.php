<?php
/**
 * 보험료 데이터 저장 API - FormData 처리 버전 
 * PHP 4.4.9 버전 호환
 * 주민번호 및 휴대전화번호 암호화 적용
 */

// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함
include "./php/monthlyFee.php"; // 월보험료 산출 함수 

// 암호화/복호화 함수 포함
include "../kjDaeri/php/encryption.php"; // 

// 오류 보고 설정
error_reporting(E_ALL ^ E_NOTICE);

// POST 데이터 검증
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $response = array(
        "success" => false,
        "message" => "잘못된 요청 방식입니다."
    );
    echo json_encode_php4($response);
    exit();
}

// 받은 모든 POST 데이터 수집
$receivedData = array(
    "cNum" => isset($_POST['cNum']) ? $_POST['cNum'] : null,
    "dNum" => isset($_POST['dNum']) ? $_POST['dNum'] : null,
    "gita" => isset($_POST['gita']) ? $_POST['gita'] : null,
    "InsuraneCompany" => isset($_POST['InsuraneCompany']) ? $_POST['InsuraneCompany'] : null,
    "endorseDay" => isset($_POST['endorseDay']) ? $_POST['endorseDay'] : null,
    "policyNum" => isset($_POST['policyNum']) ? $_POST['policyNum'] : null,
    "userName" => isset($_POST['userName']) ? $_POST['userName'] : null
);

// 파라미터 변수로 설정 (이후 코드에서 사용)
$cNum = $receivedData["cNum"];
$dNum = $receivedData["dNum"];
$gita = $receivedData["gita"];
$InsuraneCompany = $receivedData["InsuraneCompany"];
$endorse_day = $receivedData["endorseDay"];
$policyNum = $receivedData["policyNum"];
$userName = $receivedData["userName"];

// DB에서 보험 정보 가져오기
include "./php/certi_search.php";
include "./php/endorse_num.php";
include "./php/nai.php"; 

// 보험 가입자 데이터 추출 및 나이 계산
$endorseData = array();
for ($i = 0; $i < 6; $i++) {
    if (isset($_POST['data'][$i]['name']) && !empty($_POST['data'][$i]['name'])) {
        // 주민번호 분리 (하이픈 제거)
        $juminNo = str_replace('-', '', $_POST['data'][$i]['juminNo']);
        $jumin1 = substr($juminNo, 0, 6);
        $jumin2 = substr($juminNo, 6, 7);
        
        // 만나이 및 성별 계산
        $personInfo = calculateAge($jumin1, $jumin2, $m); //$m 기준일 (보험증권의 시작일 또는 배서기준일)
        
        $rowData = array(
            'rowNum' => $_POST['data'][$i]['rowNum'],
            'name' => $_POST['data'][$i]['name'],
            'juminNo' => $juminNo,  // 원본 주민번호 저장 (DB 저장 전에 암호화)
            'phoneNo' => $_POST['data'][$i]['phoneNo'],  // 원본 전화번호 저장 (DB 저장 전에 암호화)
            'age' => $personInfo['age'],
            'sex' => $personInfo['sex'],
            'birthday' => $personInfo['birthday'],
            'to_day' => $personInfo['to_day']
        );
        
        $endorseData[] = $rowData;
    }
}

// 각 가입자별로 INSERT 쿼리 생성
foreach ($endorseData as $member) {
    $name = addslashes(iconv("UTF-8", "EUC-KR", $member['name']));
    
    // 주민번호와 휴대전화번호 암호화
    $encryptedJumin = encryptData($member['juminNo']);
	$plain_jumin = decryptData($encryptedJumin);
		if (!empty($plain_jumin)) {
			$jumin_hash = sha1($plain_jumin);
		} else {
			$error_occurred = true;
			$error_message = "주민번호 복호화 실패";
		}
    $encryptedPhone = encryptData($member['phoneNo']);
    
    $age = $member['age'];
    
    $insertSql = "INSERT INTO `DaeriMember` (`2012DaeriCompanyNum`, InsuranceCompany, CertiTableNum, ";
    $insertSql .= "Name, Jumin, nai, push, etag, sangtae, Hphone, InPutDay, EndorsePnum, wdate, endorse_day, dongbuCerti,JuminHash)";
    $insertSql .= "VALUES ('$dNum', '$InsuraneCompany', '$cNum', '$name', ";
    $insertSql .= "'$encryptedJumin', '$age', '1', '$gita', '1', '$encryptedPhone', '$endorse_day', '$endorse_num', now(), '$endorse_day', '$policyNum','$jumin_hash')";
    
    // 쿼리 실행
    $insert_result = mysql_query($insertSql, $conn);
    
    // 쿼리 실행 결과 확인 (오류 처리)
    if (!$insert_result) {
        $error = mysql_error();
        // 오류 로깅 또는 처리
    }
}

include "./php/endorse_num_store.php";

// 응답 생성 (개인정보는 마스킹 처리하여 반환)
$maskedEndorseData = array();
foreach ($endorseData as $member) {
    // 주민번호와 전화번호 마스킹 처리
    $maskedJumin = substr($member['juminNo'], 0, 6) . "-*******";
    $maskedPhone = preg_replace('/(\d{3})(\d{3,4})(\d{4})/', '$1-****-$3', $member['phoneNo']);
    
    $maskedMember = $member;
    $maskedMember['juminNo'] = $maskedJumin;
    $maskedMember['phoneNo'] = $maskedPhone;
    
    $maskedEndorseData[] = $maskedMember;
}

$response = array(
    "success" => true,
    "message" => "데이터가 성공적으로 저장되었습니다",
    "data" => array(
        "mainData" => $receivedData,
        "endorseData" => $maskedEndorseData,  // 마스킹된 데이터 반환
        "baseDate" => $m,
        "endorseNum" => $endorse_num,
        "endorseSangtae" => $e_sangtae,
        "oldEndorseDay" => $old_endorse_day,
        "newEndorseDay" => $endorse_day,
        "endorseCase" => $ak
    )
);

// 응답 반환
echo json_encode_php4($response);
exit();
?>