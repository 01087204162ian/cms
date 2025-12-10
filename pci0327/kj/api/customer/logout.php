<?php
// 세션 시작
session_start();

// CORS 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션에 저장된 모든 데이터 삭제
$_SESSION = [];

// 세션 쿠키 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params["path"],
        'domain' => $params["domain"],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// 관련 쿠키들 삭제
$cookiesToDelete = [
    "host_id", "user_ip", "small_sid", "userId", 
    "userseq", "nAme", "cNum", "readIs"
];

foreach ($cookiesToDelete as $cookie) {
    setcookie($cookie, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => true, 
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// 세션 삭제
session_destroy();

// 응답 데이터 구성
$response = array(
    "success" => true, 
    "message" => "로그아웃 되었습니다."
);

// 직접 JSON 인코딩 및 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>