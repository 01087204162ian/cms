<?php
// 세션 시작
session_start();

// CORS 헤더 설정
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';

// 세션 삭제
session_destroy();

// 응답 반환
$response = array("success" => true, "message" => iconv("EUC-KR","UTF-8","로그아웃 되었습니다."));
echo json_encode_php4($response);
?>