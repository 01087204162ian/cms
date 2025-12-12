<?php
/**
 * KJ 대리운전 업체 담당자 목록 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-company-managers.php
 * 호출: GET /api/insurance/kj-company/managers
 * - 2012Member 테이블에서 담당자 목록 조회
 */

header('Content-Type: application/json; charset=utf-8');

// CORS(필요 시 도메인 제한 가능)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    require_once __DIR__ . '/../config/db_config.php';
    $pdo = getDbConnection();

    // ////////////////////////////////
    // 담당자 목록 조회 (2012Member 테이블)
    // ////////////////////////////////
    $sql = "
        SELECT 
            `num`,
            `name`
        FROM `2012Member`
        WHERE `name` IS NOT NULL 
          AND `name` != ''
        ORDER BY `name` ASC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $rows,
    ], JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

