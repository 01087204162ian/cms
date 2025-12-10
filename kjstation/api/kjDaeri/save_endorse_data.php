<?php
/**
 * 보험료 데이터 저장 API - FormData 처리 버전 
 * PHP 4.4.9 버전 호환
 */

// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
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
            'juminNo' => $_POST['data'][$i]['juminNo'],
            'phoneNo' => $_POST['data'][$i]['phoneNo'],
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
    $juminNo = $member['juminNo'];
    $age = $member['age'];
    $phoneNo = $member['phoneNo'];
    
    $insertSql = "INSERT INTO `2012DaeriMember` (`2012DaeriCompanyNum`, InsuranceCompany, CertiTableNum, ";
    $insertSql .= "Name, Jumin, nai, push, etag, sangtae, Hphone, InPutDay, EndorsePnum, wdate, endorse_day,dongbuCerti)";
    $insertSql .= "VALUES ('$dNum', '$InsuraneCompany', '$cNum', '$name', ";
    $insertSql .= "'$juminNo', '$age', '1', '$gita', '1', '$phoneNo', '$endorse_day', '$endorse_num', now(), '$endorse_day','$policyNum')";
    
    // 쿼리 실행 (테스트 모드에서는 주석 처리)
     $insert_result = mysql_query($insertSql, $conn);
    
    // 테스트를 위한 쿼리 출력
    //echo $insertSql . "<br>\n";
}

include "./php/endorse_num_store.php";
// POST 데이터 전체 저장 (디버깅 목적)
$allPostData = $_POST;

// 응답 생성
$response = array(
    "success" => true,
    "message" => "데이터가 성공적으로 수신되었습니다 (저장은 수행하지 않음)",
    "data" => array(
        "mainData" => $receivedData,
        "endorseData" => $endorseData,
        "baseDate" => $m,
        "endorseNum" => $endorse_num,
        "endorseSangtae" => $e_sangtae,
        "oldEndorseDay" => $old_endorse_day,
        "newEndorseDay" => $endorse_day,
        "endorseCase" => $ak,
        "allPostData" => $allPostData
    )
);

// 응답 반환
echo json_encode_php4($response);
exit();
?>