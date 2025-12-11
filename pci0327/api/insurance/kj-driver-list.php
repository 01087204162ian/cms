<?php
/**
 * KJ 대리운전 기사 목록 API
 * 경로: /pci0327/api/insurance/kj-driver-list.php
 * 호출: GET /api/insurance/kj-driver/list?page=&limit=&name=&jumin=&status=
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

    // 입력 파라미터
    $page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit  = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
    $name   = isset($_GET['name']) ? trim($_GET['name']) : '';
    $jumin  = isset($_GET['jumin']) ? trim($_GET['jumin']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';

    $offset = ($page - 1) * $limit;

    $where  = [];
    $params = [];

    if ($name !== '') {
        $where[] = '`Name` LIKE :name';
        $params[':name'] = '%' . $name . '%';
    }

    if ($jumin !== '') {
        // 주민번호 평문 컬럼 기준 부분 검색
        $where[] = '`Jumin` LIKE :jumin';
        $params[':jumin'] = '%' . $jumin . '%';
    }

    if ($status !== '' && $status !== '전체') {
        // "정상"만 필터링: ch = '1'
        $where[] = '`ch` = :ch';
        $params[':ch'] = '1';
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // 총 개수
    $countSql = "SELECT COUNT(*) FROM `DaeriMember` {$whereSql}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // 데이터 조회
    $dataSql = "
        SELECT 
            `num`,
            `Name`,
            `Hphone`,
            `2012DaeriCompanyNum` AS companyNum,
            `ch` AS status,
            `progress`,
            `InputDay`
        FROM `DaeriMember`
        {$whereSql}
        ORDER BY `num` DESC
        LIMIT :limit OFFSET :offset
    ";
    $dataStmt = $pdo->prepare($dataSql);
    foreach ($params as $key => $value) {
        $dataStmt->bindValue($key, $value);
    }
    $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $dataStmt->execute();

    $rows = $dataStmt->fetchAll();

    // 응답 포맷
    echo json_encode([
        'success' => true,
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
        ],
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

