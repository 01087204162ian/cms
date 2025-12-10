<?php
header('Content-Type: application/json; charset=UTF-8');
require_once "../db.php"; // DB 연결 파일
session_start();
session_regenerate_id(true);

try {
    // Pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // Search parameters
    $search_mode = isset($_GET['search_mode']) ? (int)$_GET['search_mode'] : 1;
    $search_school = isset($_GET['search_school']) ? $_GET['search_school'] : '';
    $level = isset($_GET['level']) ? $_GET['level'] : '';

    // Base query
    $sql = "SELECT * FROM users WHERE 1=1";
    
    // Parameters array for prepared statement
    $params = array();

    // Add search conditions based on search_mode
    if (!empty($search_school)) {
        switch ($search_mode) {
            case 1: // 성명
                $sql .= " AND name LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
            case 2: // 아이디
                $sql .= " AND userid LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
            case 3: // 전화번호
                $sql .= " AND phone LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
            case 4: // 담당자
                $sql .= " AND mam LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
            case 5: // 팀
                $sql .= " AND team LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
            case 6: // role
                $sql .= " AND role LIKE :search";
                $params[':search'] = "%{$search_school}%";
                break;
        }
    }

    if (!empty($level)) {
        $sql .= " AND level = :level";
        $params[':level'] = $level;
    }

    // Get total count for pagination with same conditions
    $countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
    $countSql = preg_replace('/ORDER BY.*$/i', '', $countSql);
    
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalCount = $countStmt->fetch()['total'];

    // Add sorting and pagination
    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;

    // Execute main query
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    foreach ($params as $key => &$value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();
    $users = $stmt->fetchAll();

    // Prepare response
    $response = array(
        'status' => 'success',
        'total_count' => $totalCount,
        'total_pages' => ceil($totalCount / $limit),
        'current_page' => $page,
        'limit' => $limit,
        'data' => $users
    );

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    $response = array(
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    );
    http_response_code(500);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $response = array(
        'status' => 'error',
        'message' => 'General error: ' . $e->getMessage()
    );
    http_response_code(500);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}