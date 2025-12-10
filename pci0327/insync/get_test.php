<?php

// 응답 헤더 설정
header('Content-Type: application/json; charset=UTF-8');
include "config/cors.php";

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 데이터베이스 연결 정보
include "config/_db.php";

// 데이터베이스 연결 확인
if (!$conn) {
    $response = array(
        'success' => false,
        'error' => 'Database connection failed: ' . mysqli_connect_error()
    );
    echo json_encode($response);
    exit;
}

// 임시접근 권한 확인
require 'config/Util.php';
Util::checkHeader();

// $botIp = isset($data['botIp']) ? $data['botIp'] : (isset($_GET['botIp']) ? $_GET['botIp'] : '');

$query = "SELECT * FROM DaeriMember ";
$where = "WHERE push = 1 ";
$order = "ORDER BY wdate ASC";
$limit = " LIMIT 0 , 1";

$query = $query . $where . $order . $limit;
$result = mysqli_query($conn, $query);

if (!$result) {
    $response = array(
        'success' => false,
        'error' => 'Query failed: ' . mysqli_error($conn)
    );
    echo json_encode($response);
    mysqli_close($conn);
    exit;
}

$members = array();
while ($row = mysqli_fetch_array($result)) {
    $members[] = array(
        'num' => (int)$row['num'],
        'Name' => $row['Name'],
        'Jumin' => Util::decryptData($row['Jumin']),
    );
}

// 결과 없는 경우
if (empty($members)) {
    $response = array(
        'success' => true,
        'tours found' => 'No tours found with status 1',
        // 'query' => $query,
        'data' => array()
    );
} else {
    $response = array(
        'success' => true,
        'tours found' => count($members),
        'data' => $members
    );
}

echo json_encode($response);

// 리소스 정리
mysqli_free_result($result);
mysqli_close($conn);
?>