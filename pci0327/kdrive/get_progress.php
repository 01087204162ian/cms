<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// 로그인 확인
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

try {
    require_once './includes/db.php';
    $db = new DatabaseConnection();
    $pdo = $db->pdo;

    // 필터 처리 (세션에서 필터 정보를 가져오거나 GET 파라미터 사용)
    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
    
    // 전체 항목 수 조회
    $totalSql = "SELECT COUNT(*) FROM checklist";
    $completedSql = "SELECT COUNT(*) FROM checklist WHERE result IS NOT NULL AND result != '' AND dev_status IS NOT NULL AND dev_status != ''";
    $completedItemsSql = "SELECT id FROM checklist WHERE result IS NOT NULL AND result != '' AND dev_status IS NOT NULL AND dev_status != ''";
    
    // 카테고리 필터 적용
    if (!empty($categoryFilter)) {
        $totalSql .= " WHERE category = :category";
        $completedSql .= " AND category = :category";
        $completedItemsSql .= " AND category = :category";
        
        $totalStmt = $pdo->prepare($totalSql);
        $totalStmt->execute(['category' => $categoryFilter]);
        $total = $totalStmt->fetchColumn();
        
        $completedStmt = $pdo->prepare($completedSql);
        $completedStmt->execute(['category' => $categoryFilter]);
        $completed = $completedStmt->fetchColumn();
        
        $completedItemsStmt = $pdo->prepare($completedItemsSql);
        $completedItemsStmt->execute(['category' => $categoryFilter]);
        $completedItems = $completedItemsStmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        $total = $pdo->query($totalSql)->fetchColumn();
        $completed = $pdo->query($completedSql)->fetchColumn();
        $completedItems = $pdo->query($completedItemsSql)->fetchAll(PDO::FETCH_COLUMN);
    }

    // 진행률 계산
    $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

    // 추가 통계 정보
    $resultOnlySql = "SELECT COUNT(*) FROM checklist WHERE result IS NOT NULL AND result != '' AND (dev_status IS NULL OR dev_status = '')";
    $devOnlySql = "SELECT COUNT(*) FROM checklist WHERE dev_status IS NOT NULL AND dev_status != '' AND (result IS NULL OR result = '')";
    
    if (!empty($categoryFilter)) {
        $resultOnlySql .= " AND category = :category";
        $devOnlySql .= " AND category = :category";
        
        $resultOnlyStmt = $pdo->prepare($resultOnlySql);
        $resultOnlyStmt->execute(['category' => $categoryFilter]);
        $resultOnly = $resultOnlyStmt->fetchColumn();
        
        $devOnlyStmt = $pdo->prepare($devOnlySql);
        $devOnlyStmt->execute(['category' => $categoryFilter]);
        $devOnly = $devOnlyStmt->fetchColumn();
    } else {
        $resultOnly = $pdo->query($resultOnlySql)->fetchColumn();
        $devOnly = $pdo->query($devOnlySql)->fetchColumn();
    }

    // 최근 업데이트 정보
    $lastUpdateSql = "SELECT MAX(updated_at) FROM checklist WHERE (result IS NOT NULL AND result != '') OR (dev_status IS NOT NULL AND dev_status != '')";
    if (!empty($categoryFilter)) {
        $lastUpdateSql .= " AND category = :category";
        $lastUpdateStmt = $pdo->prepare($lastUpdateSql);
        $lastUpdateStmt->execute(['category' => $categoryFilter]);
        $lastUpdate = $lastUpdateStmt->fetchColumn();
    } else {
        $lastUpdate = $pdo->query($lastUpdateSql)->fetchColumn();
    }

    echo json_encode([
        'success' => true,
        'total' => intval($total),
        'completed' => intval($completed),
        'percentage' => $percentage,
        'completed_items' => array_map('intval', $completedItems),
        'statistics' => [
            'result_only' => intval($resultOnly),
            'dev_only' => intval($devOnly),
            'both_completed' => intval($completed),
            'not_started' => intval($total - $completed - $resultOnly - $devOnly)
        ],
        'last_update' => $lastUpdate,
        'category_filter' => $categoryFilter,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (PDOException $e) {
    error_log("Progress fetch error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
} catch (Exception $e) {
    error_log("Progress fetch error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '서버 오류가 발생했습니다.']);
}
?>