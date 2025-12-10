<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// 로그인 확인
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

// 필수 파라미터 확인
if (!isset($_POST['item_id']) || !isset($_POST['field_type']) || !isset($_POST['content'])) {
    echo json_encode(['success' => false, 'message' => '필수 파라미터가 누락되었습니다.']);
    exit;
}

$itemId = intval($_POST['item_id']);
$fieldType = $_POST['field_type'];
$content = trim($_POST['content']);

// 필드 타입 유효성 검사
if (!in_array($fieldType, ['result', 'dev_status'])) {
    echo json_encode(['success' => false, 'message' => '잘못된 필드 타입입니다.']);
    exit;
}

// 내용 유효성 검사
if (empty($content)) {
    echo json_encode(['success' => false, 'message' => '저장할 내용이 없습니다.']);
    exit;
}

try {
    require_once './includes/db.php';
    $db = new DatabaseConnection();
    $pdo = $db->pdo;

    // 해당 항목이 존재하는지 확인
    $checkStmt = $pdo->prepare("SELECT id FROM checklist WHERE id = :id");
    $checkStmt->execute(['id' => $itemId]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => '존재하지 않는 항목입니다.']);
        exit;
    }

    // 개별 필드 업데이트
    $sql = "UPDATE checklist SET {$fieldType} = :content, updated_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'content' => $content,
        'id' => $itemId
    ]);

    if ($result) {
        // 로그 기록 (선택사항)
        $logStmt = $pdo->prepare("INSERT INTO update_log (admin_user, item_id, field_type, action, created_at) VALUES (:admin, :item_id, :field_type, 'individual_save', NOW())");
        $logStmt->execute([
            'admin' => $_SESSION['admin'],
            'item_id' => $itemId,
            'field_type' => $fieldType
        ]);

        echo json_encode([
            'success' => true, 
            'message' => '성공적으로 저장되었습니다.',
            'item_id' => $itemId,
            'field_type' => $fieldType,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 저장에 실패했습니다.']);
    }

} catch (PDOException $e) {
    error_log("Individual save error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
} catch (Exception $e) {
    error_log("Individual save error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '서버 오류가 발생했습니다.']);
}
?>