<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 데이터 가져오기
$jumin = isset($_POST['jumin']) ? $_POST['jumin'] : ''; 

// SQL 인젝션 방지를 위한 입력값 검증
$jumin = mysql_real_escape_string($jumin);

// 주민번호로 메모 정보 조회
$sql = "SELECT * FROM ssang_c_memo WHERE c_number='$jumin' ORDER BY num";
$result = mysql_query($sql, $conn);

// 조회 결과 처리
$response = array();
if ($result) {
    $dataArray = array();
    while ($row = mysql_fetch_assoc($result)) {
		foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        $dataArray[] = $row;
    }
    
    $response['status'] = 'success';
    $response['data'] = $dataArray;
    $response['count'] = count($dataArray);
} else {
    $response['status'] = 'error';
    $response['message'] = '데이터 조회 중 오류가 발생했습니다: ' . mysql_error();
}

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>