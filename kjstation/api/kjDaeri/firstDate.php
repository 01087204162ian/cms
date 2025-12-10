<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 데이터 가져오기
$dNum = isset($_POST['dNum']) ? trim($_POST['dNum']) : ''; 
$firstDate = isset($_POST['firstDate']) ? trim($_POST['firstDate']) : ''; 


// 응답 배열 초기화
$response = array(
    "success" => false,
    "error" => ""
);

// 필수 파라미터 검증
if(empty($dNum)) {
    $response["error"] = "대리운전회사 번호(dNum)가 필요합니다.";
    echo json_encode_php4($response);
    exit;
}

if(empty($firstDate)) {
    $response["error"] = "시작 날짜(firstDate)가 필요합니다.";
    echo json_encode_php4($response);
    exit;
}

// SQL 인젝션 방지
$dNum = mysql_real_escape_string($dNum);
$firstDate = mysql_real_escape_string($firstDate);

// 날짜 형식 검증 (YYYY-MM-DD)
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $firstDate)) {
    $response["error"] = "날짜 형식이 잘못되었습니다. YYYY-MM-DD 형식이어야 합니다.";
    echo json_encode_php4($response);
    exit;
}

// 날짜 형식 분리 (YYYY-MM-DD)
$m = explode("-", $firstDate);

// 데이터베이스 업데이트 쿼리
$update = "UPDATE `2012DaeriCompany` SET FirstStartDay='" . $m[2] . "', FirstStart='" . $firstDate . "'";

// WHERE 조건 추가 (기본 키 사용)
$update .= " WHERE num='" . $dNum . "'";

// 쿼리 실행
$result = mysql_query($update, $conn);

// 결과 확인
if($result) {
    $response["success"] = true;
} else {
    $response["error"] = "데이터베이스 업데이트 실패: " . mysql_error();
}

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>