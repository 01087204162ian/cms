<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 응답 함수
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, '잘못된 요청 방식입니다.');
}

// 입력값 검증
$id = trim($_POST['id'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($id) || empty($password)) {
    sendResponse(false, '아이디와 비밀번호를 모두 입력해주세요.');
}

try {
    // 데이터베이스 연결 (PDO)
    $pdo = getDbConnection();
    
    // clients 테이블에서 manager_email과 manager_phone으로 사용자 조회
    $stmt = $pdo->prepare("
        SELECT id, name, manager_name, total_quota 
        FROM clients 
        WHERE manager_email = ? AND manager_phone = ?
    ");
    
    $stmt->execute([$id, $password]);
    $user = $stmt->fetch();
    
    if ($user) {
        // 로그인 성공
        // 세션에 사용자 정보 저장
        $_SESSION['client_id'] = $user['id'];
        $_SESSION['client_name'] = $user['name'];
        $_SESSION['manager_name'] = $user['manager_name'] ?? '';
        $_SESSION['total_quota'] = $user['total_quota'] ?? 0;
        $_SESSION['login_time'] = time();
        
        // 로그인 로그 기록
        try {
            $log_stmt = $pdo->prepare("
                INSERT INTO holeinone_login_logs (client_id, login_ip, user_agent, login_time) 
                VALUES (?, ?, ?, NOW())
            ");
            $client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $log_stmt->execute([$user['id'], $client_ip, $user_agent]);
        } catch (Exception $e) {
            // 로그 테이블이 없어도 로그인은 성공
        }
        
        sendResponse(true, '로그인 성공', [
            'client_name' => $user['name'],
            'manager_name' => $user['manager_name'] ?? ''
        ]);
        
    } else {
        // 로그인 실패
        sendResponse(false, '아이디 또는 비밀번호가 올바르지 않습니다.');
    }
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Login Error: " . $e->getMessage());
    sendResponse(false, '로그인 처리 중 오류가 발생했습니다: ' . $e->getMessage());
}
?>