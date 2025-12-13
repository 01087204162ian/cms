<?php
/**
 * KJ 대리운전 증권별 대리기사 리스트 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-certi-member-list.php
 * 호출: GET /api/insurance/kj-certi/member-list?certiTableNum={certiTableNum}
 * - 특정 증권에 등록된 대리기사 리스트 조회 (push='4')
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
    // 1. 입력 파라미터
    // ////////////////////////////////
    $certiTableNum = isset($_GET['certiTableNum']) ? trim($_GET['certiTableNum']) : '';

    if (empty($certiTableNum)) {
        throw new Exception('증권 번호가 필요합니다.');
    }

    // ////////////////////////////////
    // 2. 대리기사 리스트 조회 (2012DaeriMember)
    // ////////////////////////////////
    $memberSql = "
        SELECT 
            num,
            Name,
            nai,
            Jumin,
            Hphone,
            push,
            cancel,
            etag,
            `2012DaeriCompanyNum`,
            CertiTableNum,
            dongbuCerti,
            InsuranceCompany
        FROM `2012DaeriMember`
        WHERE `CertiTableNum` = :certiTableNum
          AND `push` = '4'
        ORDER BY `num` ASC
    ";
    
    $memberStmt = $pdo->prepare($memberSql);
    $memberStmt->execute([':certiTableNum' => $certiTableNum]);
    $memberRows = $memberStmt->fetchAll(PDO::FETCH_ASSOC);

    // ////////////////////////////////
    // 3. 응답 데이터 구성
    // ////////////////////////////////
    $response = [
        'success' => true,
        'certiTableNum' => $certiTableNum,
        'data' => $memberRows,
        'count' => count($memberRows)
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

