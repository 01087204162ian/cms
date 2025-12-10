<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// GET 파라미터 가져오기
$gita = isset($_GET['gita']) ? $_GET['gita'] : ''; 
  
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  



if (!empty($cNum) && !empty($gita)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    $sql_main = "
        UPDATE  2012CertiTable SET gita='$gita' 
        WHERE num = '$cNum'";

     mysql_query($sql_main, $conn);
}

// 응답 데이터 구성
$response = array(
    "success" => true,
);





// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
