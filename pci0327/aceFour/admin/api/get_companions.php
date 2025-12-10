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

// 로그인 체크
if (!isset($_SESSION['client_id'])) {
    sendResponse(false, '로그인이 필요합니다.');
}

// GET 파라미터 확인
if (!isset($_GET['application_id'])) {
    sendResponse(false, '신청 ID가 필요합니다.');
}

try {
    $pdo = getDbConnection();
    $client_id = $_SESSION['client_id'];
    $application_id = intval($_GET['application_id']);
    
    // 해당 신청이 본인의 것인지 확인
    $stmt = $pdo->prepare("
        SELECT id FROM holeinone_applications 
        WHERE id = ? AND client_id = ?
    ");
    $stmt->execute([$application_id, $client_id]);
    
    if (!$stmt->fetch()) {
        sendResponse(false, '권한이 없습니다.');
    }
    
    // 동반자 정보 조회
    $stmt = $pdo->prepare("
        SELECT 
            id,
            companion_name,
            companion_phone,
            created_at
        FROM holeinone_companions
        WHERE application_id = ?
        ORDER BY id ASC
    ");
    $stmt->execute([$application_id]);
    $companions_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 개인정보 복호화
    $companions = [];
    foreach ($companions_raw as $index => $comp) {
        $decrypted_name = decryptData($comp['companion_name']);
        $decrypted_phone = decryptData($comp['companion_phone']);
        
        $companions[] = [
            'id' => $comp['id'],
            'number' => $index + 1,
            'companion_name' => $decrypted_name ?: '복호화 실패',
            'companion_phone' => $decrypted_phone ?: '복호화 실패',
            'created_at' => $comp['created_at']
        ];
    }
    
    sendResponse(true, '동반자 정보 조회 성공', [
        'companions' => $companions,
        'count' => count($companions)
    ]);
    
} catch (Exception $e) {
    error_log("Get Companions Error: " . $e->getMessage());
    sendResponse(false, '동반자 정보 조회 중 오류가 발생했습니다.');
}
?>