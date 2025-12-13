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
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;

    if (empty($certiTableNum)) {
        throw new Exception('증권 번호가 필요합니다.');
    }

    $offset = ($page - 1) * $limit;

    // ////////////////////////////////
    // 2. 총 개수 조회
    // ////////////////////////////////
    $countSql = "
        SELECT COUNT(*) as total
        FROM `2012DaeriMember`
        WHERE `CertiTableNum` = :certiTableNum
          AND `push` = '4'
    ";
    
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute([':certiTableNum' => $certiTableNum]);
    $total = (int)$countStmt->fetchColumn();
    $totalPages = $total > 0 ? (int)ceil($total / $limit) : 1;

    // ////////////////////////////////
    // 3. 대리기사 리스트 조회 (2012DaeriMember)
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
        ORDER BY `nai` DESC
        LIMIT :limit OFFSET :offset
    ";
    
    $memberStmt = $pdo->prepare($memberSql);
    $memberStmt->bindValue(':certiTableNum', $certiTableNum, PDO::PARAM_INT);
    $memberStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $memberStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $memberStmt->execute();
    $memberRows = $memberStmt->fetchAll(PDO::FETCH_ASSOC);

    // ////////////////////////////////
    // 4. 응답 데이터 구성
    // ////////////////////////////////
    $response = [
        'success' => true,
        'certiTableNum' => $certiTableNum,
        'data' => $memberRows,
        'count' => count($memberRows),
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages
        ]
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

