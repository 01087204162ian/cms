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


?>