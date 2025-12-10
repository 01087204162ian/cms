<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// POST 데이터 받기
$adjustmentAmount = isset($_POST['adjustmentAmount']) ? $_POST['adjustmentAmount'] : '';
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$thisMonthDueDate = isset($_POST['thisMonthDueDate']) ? $_POST['thisMonthDueDate'] : '';
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

// 필수 매개변수 검증
if (empty($adjustmentAmount) || empty($dNum) || empty($thisMonthDueDate)) {
    $response = array(
        "success" => false,
        "message" => "필수 매개변수가 누락되었습니다."
    );
    echo json_encode_php4($response);
    exit;
}

// 현재 날짜 및 시간
$currentDateTime = date('Y-m-d H:i:s');

// 이름 UTF-8 변환
$userName_euckr = @iconv("UTF-8", "EUC-KR", $userName);
if ($userName_euckr === false) {
    $userName_euckr = $userName; // 변환 실패 시 원본 사용
}

// 이미 저장된 데이터가 있는지 확인
$check_sql = "SELECT * FROM AdjustmentPremium 
              WHERE dNum = '$dNum' AND thisMonthDueDate = '$thisMonthDueDate'";
$check_result = mysql_query($check_sql, $conn);

if (!$check_result) {
    $response = array(
        "success" => false,
        "message" => iconv("EUC-KR","UTF-8",  "데이터베이스 조회 오류: ") . mysql_error($conn)
    );
    echo json_encode_php4($response);
    exit;
}

if (mysql_num_rows($check_result) > 0) {
    // 기존 데이터 업데이트
    $update_sql = "UPDATE AdjustmentPremium 
                   SET adjustmentAmount = '$adjustmentAmount', 
                       updateDate = '$currentDateTime', 
                       updateUser = '$userName_euckr'
                   WHERE dNum = '$dNum' AND thisMonthDueDate = '$thisMonthDueDate'";
    $result = mysql_query($update_sql, $conn);
} else {
    // 새 데이터 삽입
    $insert_sql = "INSERT INTO AdjustmentPremium 
                  (dNum, adjustmentAmount, thisMonthDueDate, createDate, createUser) 
                  VALUES 
                  ('$dNum', '$adjustmentAmount', '$thisMonthDueDate', '$currentDateTime', '$userName_euckr')";
    $result = mysql_query($insert_sql, $conn);
}

// 쿼리 실행 결과 확인
if ($result) {
    $response = array(
        "success" => true,
        "message" => iconv("EUC-KR","UTF-8",  "확정보험료가 성공적으로 저장되었습니다.")
    );
} else {
    $error = mysql_error($conn);
    $response = array(
        "success" => false,
        "message" => iconv("EUC-KR","UTF-8", "데이터베이스 오류: ") . $error
    );
}

// 디버깅 정보 추가
$debug = array(
    "adjustmentAmount" => $adjustmentAmount,
    "dNum" => $dNum,
    "thisMonthDueDate" => $thisMonthDueDate,
    "userName" => $userName
  //  "query" => isset($insert_sql) ? $insert_sql : $update_sql
);

// 응답에 디버깅 정보 추가
$response["debug"] = $debug;

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>