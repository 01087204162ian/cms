<?php
session_start(); // ← 제일 위에 와야 안전
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // PHP 4 호환 JSON 인코딩 함수

// 응답 배열 초기화
$response = array();
$response["valid"] = false;
$response["message"] = iconv("EUC-KR","UTF-8","세션이 유효하지 않습니다.");

// 세션에 사용자 ID가 있는지 확인
if (isset($userId) && $userId != '') {
    // 세션 타임아웃 확인
    if (isset($_SESSION["last_activity"])) {
        $session_lifetime = 1800; // 30분 (초 단위)
        $current_time = time();
        
        // 마지막 활동 시간이 session_lifetime을 초과하는지 확인
        if (($current_time - $_SESSION["last_activity"]) > $session_lifetime) {
            // 세션 만료 처리
            session_destroy();
            $response["message"] = iconv("EUC-KR","UTF-8","세션이 만료되었습니다. 다시 로그인해주세요.");
        } else {
            // 세션이 유효함
            $response["valid"] = true;
            $response["message"] = iconv("EUC-KR","UTF-8","세션이 유효합니다.");
            
            // 마지막 활동 시간 업데이트
            $_SESSION["last_activity"] = $current_time;
        }
    } else {
        // 최초 세션 활동 시간 기록
        $_SESSION["last_activity"] = time();
        $response["valid"] = true;
        $response["message"] = iconv("EUC-KR","UTF-8","세션이 유효합니다.");
    }
} else {
    // 세션에 사용자 ID가 없는 경우
    $response["message"] = iconv("EUC-KR","UTF-8","로그인 정보가 없습니다. 다시 로그인해주세요.");
}

// 쿠키에 저장된 정보로 추가 검증 (옵션)
$cookie_userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : '';
$cookie_userseq = isset($_COOKIE['userseq']) ? $_COOKIE['userseq'] : '';

if ($response["valid"] && ($cookie_userId == '' || $cookie_userseq == '')) {
    $response["valid"] = false;
    $response["message"] = iconv("EUC-KR","UTF-8","쿠키 정보가 유효하지 않습니다. 다시 로그인해주세요.");
}

// JSON 응답 반환
echo json_encode_php4($response);
exit();
?>