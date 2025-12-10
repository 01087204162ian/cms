<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 응답 함수
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 로그아웃 로그 기록 (로그인되어 있는 경우)
    if (isset($_SESSION['client_id'])) {
        $pdo = getDbConnection();
        
        // 로그아웃 시간 업데이트 (가장 최근 로그인 기록에)
        $stmt = $pdo->prepare("
            UPDATE holeinone_login_logs 
            SET logout_time = NOW(), 
                session_duration = TIMESTAMPDIFF(SECOND, login_time, NOW()),
                login_status = 'logout'
            WHERE client_id = ? AND logout_time IS NULL 
            ORDER BY login_time DESC 
            LIMIT 1
        ");
        $stmt->execute([$_SESSION['client_id']]);
    }
    
    // 세션 데이터 완전 삭제
    $_SESSION = array();
    
    // 세션 쿠키 삭제
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // 세션 파괴
    session_destroy();
    
    sendResponse(true, '로그아웃 되었습니다.');
    
} catch (Exception $e) {
    // 에러가 있어도 세션은 삭제
    session_destroy();
    error_log("Logout Error: " . $e->getMessage());
    sendResponse(true, '로그아웃 되었습니다.');
}
?>