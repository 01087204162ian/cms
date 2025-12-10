<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    $pdo = getDbConnection();
    
    // POST 파라미터 수신
    $dNumCompany = $_POST['dNumCompany'] ?? '';
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 15;
    $duDay_ = $_POST['duDay_'] ?? '';
    $damdangja = $_POST['damdangja'] ?? '';
    $currentInwonElement = $_POST['currentInwonElement'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    // 기본 조건: sangtae가 10 또는 13이고 danche가 1인 경우
    $base_conditions = ["(sangtae = '10' OR sangtae = '13')", "danche = '1'"];
    $where_conditions = $base_conditions;
    $params = [];
    
    // 추가 조건절 구성
    if (!empty($dNumCompany)) {
        $where_conditions[] = "company LIKE :dNumCompany";
        $params[':dNumCompany'] = "%{$dNumCompany}%";
    } else {
        // duDay_ 조건 (start 또는 new_jeonggi 날짜와 비교)
        if (!empty($duDay_)) {
            $where_conditions[] = "(start = :duDay_ OR new_jeonggi = :duDay_)";
            $params[':duDay_'] = $duDay_;
        }
        
        // 담당자 조건 (userid 필드 사용)
        if ($damdangja !== '' && $damdangja !== 'all' && is_numeric($damdangja)) {
            $where_conditions[] = "userid = :damdangja";
            $params[':damdangja'] = $damdangja;
        }
    }
    
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
    
    // 총 개수 조회
    $count_sql = "SELECT COUNT(*) as total FROM `new_cs_dongbu` $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    foreach ($params as $key => $value) {
        $count_stmt->bindValue($key, $value);
    }
    $count_stmt->execute();
    $count_row = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $count_row['total'];
    $totalPages = ceil($totalRecords / $limit);
    
    // 본문 데이터 조회
    $sql = "SELECT * FROM `new_cs_dongbu` 
            $where_clause
            ORDER BY num ASC
            LIMIT :offset, :limit";
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 담당자 이름 처리 (userid 기반)
        if (!empty($row['userid'])) {
            $damStmt = $pdo->prepare("SELECT name FROM 2012Member WHERE num = ? LIMIT 1");
            $damStmt->execute([$row['userid']]);
            $damRow = $damStmt->fetch(PDO::FETCH_ASSOC);
            $row['damdanga'] = $damRow 
                ? mb_convert_encoding($damRow['name'], 'UTF-8', 'EUC-KR') 
                : '';
        } else {
            $row['damdanga'] = '';
        }
        
        // 인원 수 확인 (new_cs_dongbu 테이블의 동일한 company를 가진 레코드 수)
        $inwonStmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM `new_cs_dongbu` WHERE `company` = ? AND (sangtae = '10' OR sangtae = '13') AND danche = '1'");
        $inwonStmt->execute([$row['company']]);
        $inwonRow = $inwonStmt->fetch(PDO::FETCH_ASSOC);
        $row['inwon'] = $inwonRow ? (int)$inwonRow['cnt'] : 0;
        
        // 인원 조건 필터링
        if ($currentInwonElement == 1 && $row['inwon'] <= 0) {
            continue;
        }
        
        $data[] = $row;
    }
    
    // 결과 응답
    echo json_encode([
        "success" => true,
        "currentPage" => $page,
        "totalPages" => $totalPages,
        "totalRecords" => $totalRecords,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Database error: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>