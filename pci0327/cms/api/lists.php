<?php
require_once '../../DatabaseConnection.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $menu = $_GET['menu'] ?? '';
    $type = $_GET['type'] ?? '';
    
    $db = new DatabaseConnection();
    $pdo = $db->connect();
    
    // 메뉴별 쿼리 분기
    switch ($menu) {
        case 'foreign':
            $stmt = $pdo->prepare("
                SELECT * FROM foreign_insurance 
                WHERE type = ? 
                ORDER BY apply_date DESC
            ");
            break;
        // 다른 메뉴들에 대한 케이스 추가
    }
    
    $stmt->execute([$type]);
    $data = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 