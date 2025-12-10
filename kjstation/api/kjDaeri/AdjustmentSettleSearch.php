<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// POST 데이터 받기
$lastDate = isset($_POST['lastDate']) ? $_POST['lastDate'] : '';
$thisDate = isset($_POST['thisDate']) ? $_POST['thisDate'] : '';
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

// 필수 매개변수 검증
if (empty($lastDate) || empty($thisDate)) {
    $response = array(
        "success" => false,
        "message" => "필수 매개변수가 누락되었습니다."
    );
    echo json_encode_php4($response);
    exit;
}

// 받을 보험료 조회 
$sql = "SELECT * FROM AdjustmentPremium 
        WHERE thisMonthDueDate >= '$lastDate' AND thisMonthDueDate <= '$thisDate'";

// SQL 인젝션 방지를 위해 준비된 구문 사용 권장 (mysql_* 함수 대신)
$rs = mysql_query($sql, $conn);

if (!$rs) {
    $response = array(
        "success" => false,
        "message" => "데이터베이스 조회 오류: " . mysql_error()
    );
    echo json_encode_php4($response);
    exit;
}

// 결과 데이터 수집
$data = array();
while ($row = mysql_fetch_assoc($rs)) {
	foreach ($row as $key => $value) {
		if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
			$converted = @iconv("EUC-KR", "UTF-8", $value);
			$row[$key] = ($converted !== false) ? $converted : $value;
		}
	}
	$dNum=$row['dNum'];
	include "./php/dNum_search.php";
	$row['company']=@iconv("EUC-KR", "UTF-8",$Drow['company']);
    $data[] = $row;
}

// 응답 생성
$response = array(
    "success" => true,
    "count" => count($data),
    "data" => $data,
    "debug" => array(
        "lastDate" => $lastDate,
        "thisDate" => $thisDate,
        "dNum" => $dNum,
        "userName" => $userName
    )
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>