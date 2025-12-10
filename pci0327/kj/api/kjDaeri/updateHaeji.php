<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// 오류 보고 설정
error_reporting(E_ALL & ~E_NOTICE);

// POST 데이터 검증
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $response = array(
        "success" => false,
        "message" => "잘못된 요청 방식입니다."
    );
    // json_encode() 사용 (json_encode_php4는 사용자 정의 함수로 보임)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// 받은 모든 POST 데이터 수집 - PHP 8.2 null coalescing operator 사용
$receivedData = array(
    "DariMemberNum" => $_POST['DariMemberNum'] ?? null,
    "cNum" => $_POST['cNum'] ?? null,
    "dNum" => $_POST['dNum'] ?? null,
    "InsuranceCompany" => $_POST['InsuranceCompany'] ?? null,
    "endorseDay" => $_POST['endorseDay'] ?? null,
    "policyNum" => $_POST['policyNum'] ?? null,
    "userName" => $_POST['userName'] ?? null
);

// 파라미터 변수로 설정 (이후 코드에서 사용)
$DariMemberNum = $receivedData["DariMemberNum"];
$cNum = $receivedData["cNum"];
$dNum = $receivedData["dNum"];
$InsuranceCompany = $receivedData["InsuranceCompany"]; 
$endorseDay = $receivedData["endorseDay"];
$policyNum = $receivedData["policyNum"];
$userName = $receivedData["userName"];

// 변수 확인 및 초기화
$endorse_day = $endorseDay ?? '';
$endorse_num = ''; // 이 변수가 정의되지 않았으므로 초기화 필요

// PDO를 사용한 DB 업데이트
try {
    $updateSql = "UPDATE DaeriMember SET 
                  sangtae = '1',
                  OutPutDay = :endorse_day,
                  cancel = '42',
                  wdate = NOW(),
                  endorse_day = :endorse_day2
                  WHERE num = :DariMemberNum";
    
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([
        ':endorse_day' => $endorse_day,
        ':endorse_day2' => $endorse_day,
        ':DariMemberNum' => $DariMemberNum
    ]);
    
    $message = '해지 신청 되었습니다!!';
} catch (PDOException $e) {
    $message = '데이터베이스 오류가 발생했습니다: ' . $e->getMessage();
}

// POST 데이터 전체 저장 (디버깅 목적)
$allPostData = $_POST;

// 정의되지 않은 변수들 초기화 (에러 방지)
$endorseData = null;
$m = null;
$e_sangtae = null;
$old_endorse_day = null;
$ak = null;

// 응답 생성
$response = array(
    "success" => true,
    "message" => $message, // iconv 제거 - UTF-8 파일로 직접 작성하면 필요 없음
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
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>