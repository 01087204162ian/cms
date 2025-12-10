<?php

// 응답 헤더 설정
header('Content-Type: application/json; charset=UTF-8');
include "../config/cors.php";

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 데이터베이스 연결 정보
include "../config/_db.php";
if (!$conn) {
    $response = array(
        'success' => false,
        'error' => 'Database connection failed: ' . mysqli_connect_error()
    );
    echo json_encode($response);
    exit;
}

// 임시접근 권한 확인
require '../config/Util.php';
Util::checkHeader();

// if (empty($accidents) || !is_array($accidents)) {
//     $response = array(
//         'success' => false,
//         'error' => 'No accident data provided'
//     );
//     echo json_encode($response);
//     exit;
// }

// 입력전 이전데이터 삭제
$sql = "DELETE FROM inSync_accidents_data";
if (!mysqli_query($conn, $sql)) {
    $response = array(
        'success' => false,
        'error' => 'Failed to clear previous data: ' . mysqli_error($conn)
    );
    echo json_encode($response);
    exit;
}else {
    $response = array(
        'success' => true,
        'message' => 'InSync accident(s) deleted successfully'
    );
}

echo json_encode($response);
mysqli_close($conn);
?>