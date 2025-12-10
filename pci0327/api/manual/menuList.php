<?php
/**
 * 메뉴 구조 조회 API (menu_clients 테이블 + 다중 담당자 지원)
 * - 기본: 중복되지 않은 menu_1st 목록 조회
 * - structure_type=hierarchical: 계층적 구조 조회
 * - structure_type=hierarchical&menu_1st=값: 특정 1차 메뉴의 하위 구조 조회
 * 
 * 경로: /api/manual/menuList.php
 */

// 세션 시작 (로그인 확인 등을 위해)
session_start();

// 필요한 파일 포함
require_once '../config/db_config.php';

// JSON 형태로 응답하도록 헤더 설정
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리 (CORS 프리플라이트)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // GET 파라미터 받기
    $structure_type = isset($_GET['structure_type']) ? trim($_GET['structure_type']) : 'flat';
    $menu_1st = isset($_GET['menu_1st']) ? trim($_GET['menu_1st']) : '';
    $menu_2nd = isset($_GET['menu_2nd']) ? trim($_GET['menu_2nd']) : '';
    $manual_writer = isset($_GET['manual_writer']) ? trim($_GET['manual_writer']) : '';  // 당사 직원
    $manager_name = isset($_GET['manager_name']) ? trim($_GET['manager_name']) : '';  // 고객사 담당자
    $company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : null;
    $is_active = isset($_GET['is_active']) ? filter_var($_GET['is_active'], FILTER_VALIDATE_BOOLEAN) : true;
    
    // 구조 타입에 따른 처리
    if ($structure_type === 'hierarchical') {
        // 계층적 구조 조회
        if (!empty($menu_1st)) {
            // 특정 1차 메뉴의 하위 구조 조회
            $hierarchicalData = getSpecificMenuHierarchy($pdo, $menu_1st, $manual_writer, $manager_name, $company_id, $is_active);
        } else {
            // 전체 계층적 구조 조회
            $hierarchicalData = getFullHierarchy($pdo, $manual_writer, $manager_name, $company_id, $is_active);
        }
        
        $response = [
            'success' => true,
            'message' => '계층적 메뉴 구조를 성공적으로 조회했습니다.',
            'data' => [
                'structure_type' => 'hierarchical',
                'categories' => $hierarchicalData,
                'total_categories' => count($hierarchicalData)
            ],
            'filters' => [
                'menu_1st' => $menu_1st,
                'menu_2nd' => $menu_2nd,
                'manual_writer' => $manual_writer,
                'manager_name' => $manager_name,
                'company_id' => $company_id,
                'is_active' => $is_active
            ]
        ];
    } else {
        // 기본: 중복되지 않은 menu_1st 조회
        $sql = "SELECT DISTINCT mc.menu_1st,
                       COUNT(DISTINCT mc.id) as total_clients,
                       COUNT(DISTINCT m.id) as total_managers
                FROM menu_clients mc
                LEFT JOIN managers m ON mc.id = m.client_id AND m.is_active = 1
                WHERE mc.menu_1st IS NOT NULL 
                AND mc.menu_1st != ''";
        
        $params = [];
        
        // 필터 조건 추가
        if (!empty($manual_writer)) {
            $sql .= " AND mc.manual_writer LIKE :manual_writer";
            $params[':manual_writer'] = "%{$manual_writer}%";
        }
        
        if (!empty($manager_name)) {
            $sql .= " AND EXISTS (
                        SELECT 1 FROM managers m2 
                        WHERE m2.client_id = mc.id 
                        AND m2.is_active = 1 
                        AND m2.name LIKE :manager_name
                      )";
            $params[':manager_name'] = "%{$manager_name}%";
        }
        
        if ($company_id !== null) {
            $sql .= " AND mc.company_id = :company_id";
            $params[':company_id'] = $company_id;
        }
        
        // is_active 조건 추가
        $sql .= " AND mc.is_active = :is_active";
        $params[':is_active'] = $is_active ? 1 : 0;
        
        $sql .= " GROUP BY mc.menu_1st ORDER BY mc.menu_1st ASC";
        
        // 쿼리 실행
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 성공 응답
        $response = [
            'success' => true,
            'message' => '중복되지 않은 1차 메뉴 목록을 성공적으로 조회했습니다.',
            'data' => $results,
            'total' => count($results),
            'filters' => [
                'manual_writer' => $manual_writer,
                'manager_name' => $manager_name,
                'company_id' => $company_id,
                'is_active' => $is_active
            ]
        ];
    }
    
    http_response_code(200);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    // 데이터베이스 오류 처리
    error_log("Database Error in menuList.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.',
        'error' => '내부 서버 오류'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 기타 오류 처리
    error_log("General Error in menuList.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '서버 오류가 발생했습니다.',
        'error' => '내부 서버 오류'
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * 특정 1차 메뉴의 계층적 구조 조회 (다중 담당자 포함)
 */
function getSpecificMenuHierarchy($pdo, $menu1st, $manual_writer = '', $manager_name = '', $company_id = null, $is_active = true) {
    $sql = "SELECT 
                mc.id,
                mc.folder,
                mc.menu_1st,
                mc.menu_2nd,
                mc.menu_3rd,
                mc.manual_writer,
                mc.company_id,
                mc.description,
                mc.is_active,
                mc.created_at,
                mc.updated_at,
                GROUP_CONCAT(
                    CASE WHEN m.is_primary = 1 THEN m.name END 
                    ORDER BY m.is_primary DESC, m.created_at ASC 
                    SEPARATOR ''
                ) as primary_manager,
                GROUP_CONCAT(
                    DISTINCT m.name 
                    ORDER BY m.is_primary DESC, m.created_at ASC 
                    SEPARATOR ', '
                ) as all_managers,
                COUNT(DISTINCT m.id) as manager_count
            FROM menu_clients mc
            LEFT JOIN managers m ON mc.id = m.client_id AND m.is_active = 1
            WHERE mc.menu_1st = :menu_1st";
    
    $params = [':menu_1st' => $menu1st];
    
    // 필터 조건 추가
    if (!empty($manual_writer)) {
        $sql .= " AND mc.manual_writer LIKE :manual_writer";
        $params[':manual_writer'] = "%{$manual_writer}%";
    }
    
    if (!empty($manager_name)) {
        $sql .= " AND EXISTS (
                    SELECT 1 FROM managers m2 
                    WHERE m2.client_id = mc.id 
                    AND m2.is_active = 1 
                    AND m2.name LIKE :manager_name
                  )";
        $params[':manager_name'] = "%{$manager_name}%";
    }
    
    if ($company_id !== null) {
        $sql .= " AND mc.company_id = :company_id";
        $params[':company_id'] = $company_id;
    }
    
    // is_active 조건 추가
    $sql .= " AND mc.is_active = :is_active";
    $params[':is_active'] = $is_active ? 1 : 0;
    
    $sql .= " GROUP BY mc.id 
              ORDER BY mc.menu_2nd ASC, mc.menu_3rd ASC, mc.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 각 클라이언트의 상세 담당자 정보 추가
    foreach ($results as &$client) {
        $client['managers'] = getManagersByClientId($pdo, $client['id']);
    }
    
    // 계층적 구조로 변환
    return buildHierarchicalStructure($results);
}

/**
 * 전체 계층적 구조 조회 (다중 담당자 포함)
 */
function getFullHierarchy($pdo, $manual_writer = '', $manager_name = '', $company_id = null, $is_active = true) {
    $sql = "SELECT 
                mc.id,
                mc.folder,
                mc.menu_1st,
                mc.menu_2nd,
                mc.menu_3rd,
                mc.manual_writer,
                mc.company_id,
                mc.description,
                mc.is_active,
                mc.created_at,
                mc.updated_at,
                GROUP_CONCAT(
                    CASE WHEN m.is_primary = 1 THEN m.name END 
                    ORDER BY m.is_primary DESC, m.created_at ASC 
                    SEPARATOR ''
                ) as primary_manager,
                GROUP_CONCAT(
                    DISTINCT m.name 
                    ORDER BY m.is_primary DESC, m.created_at ASC 
                    SEPARATOR ', '
                ) as all_managers,
                COUNT(DISTINCT m.id) as manager_count
            FROM menu_clients mc
            LEFT JOIN managers m ON mc.id = m.client_id AND m.is_active = 1
            WHERE 1=1";
    
    $params = [];
    
    // 필터 조건 추가
    if (!empty($manual_writer)) {
        $sql .= " AND mc.manual_writer LIKE :manual_writer";
        $params[':manual_writer'] = "%{$manual_writer}%";
    }
    
    if (!empty($manager_name)) {
        $sql .= " AND EXISTS (
                    SELECT 1 FROM managers m2 
                    WHERE m2.client_id = mc.id 
                    AND m2.is_active = 1 
                    AND m2.name LIKE :manager_name
                  )";
        $params[':manager_name'] = "%{$manager_name}%";
    }
    
    if ($company_id !== null) {
        $sql .= " AND mc.company_id = :company_id";
        $params[':company_id'] = $company_id;
    }
    
    // is_active 조건 추가
    $sql .= " AND mc.is_active = :is_active";
    $params[':is_active'] = $is_active ? 1 : 0;
    
    $sql .= " GROUP BY mc.id 
              ORDER BY mc.menu_1st ASC, mc.menu_2nd ASC, mc.menu_3rd ASC, mc.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 각 클라이언트의 상세 담당자 정보 추가
    foreach ($results as &$client) {
        $client['managers'] = getManagersByClientId($pdo, $client['id']);
    }
    
    // 계층적 구조로 변환
    return buildHierarchicalStructure($results);
}

/**
 * 특정 클라이언트의 담당자 목록 조회
 */
function getManagersByClientId($pdo, $client_id) {
    $sql = "SELECT 
                id,
                name,
                department,
                position,
                phone,
                email,
                is_primary,
                notes,
                created_at,
                updated_at
            FROM managers 
            WHERE client_id = :client_id AND is_active = 1 
            ORDER BY is_primary DESC, created_at ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':client_id' => $client_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 평면적 데이터를 계층적 구조로 변환하는 함수 (다중 담당자 지원)
 */
function buildHierarchicalStructure($data) {
    $hierarchical = [];
    
    foreach ($data as $item) {
        $menu1st = $item['menu_1st'] ?: '미분류';
        $menu2nd = $item['menu_2nd'] ?: '기타';
        
        // 1차 메뉴가 존재하지 않으면 생성
        if (!isset($hierarchical[$menu1st])) {
            $hierarchical[$menu1st] = [
                'category_name' => $menu1st,
                'total_items' => 0,
                'total_managers' => 0,
                'subcategories' => [],
                'items' => []
            ];
        }
        
        // 2차 메뉴가 존재하지 않으면 생성
        if (!isset($hierarchical[$menu1st]['subcategories'][$menu2nd])) {
            $hierarchical[$menu1st]['subcategories'][$menu2nd] = [
                'subcategory_name' => $menu2nd,
                'total_items' => 0,
                'total_managers' => 0,
                'items' => []
            ];
        }
        
        // 아이템을 해당 카테고리에 추가
        $hierarchical[$menu1st]['subcategories'][$menu2nd]['items'][] = $item;
        $hierarchical[$menu1st]['subcategories'][$menu2nd]['total_items']++;
        $hierarchical[$menu1st]['subcategories'][$menu2nd]['total_managers'] += intval($item['manager_count'] ?? 0);
        $hierarchical[$menu1st]['total_items']++;
        $hierarchical[$menu1st]['total_managers'] += intval($item['manager_count'] ?? 0);
        
        // 3차 메뉴가 없는 경우 1차 메뉴 직하위에도 추가 (옵션)
        if (empty($item['menu_3rd']) && empty($item['menu_2nd'])) {
            $hierarchical[$menu1st]['items'][] = $item;
        }
    }
    
    // 배열을 인덱스 기반으로 변환하고 정렬
    $result = [];
    foreach ($hierarchical as $categoryName => $categoryData) {
        $subcategories = [];
        foreach ($categoryData['subcategories'] as $subName => $subData) {
            $subcategories[] = $subData;
        }
        
        // 서브카테고리 정렬
        usort($subcategories, function($a, $b) {
            return strcmp($a['subcategory_name'], $b['subcategory_name']);
        });
        
        $categoryData['subcategories'] = $subcategories;
        $result[] = $categoryData;
    }
    
    // 카테고리 정렬
    usort($result, function($a, $b) {
        return strcmp($a['category_name'], $b['category_name']);
    });
    
    return $result;
}

/**
 * 특정 1차 메뉴의 2차 메뉴 목록만 반환하는 함수 (다중 담당자 지원)
 */
function getSubcategoriesByMainCategory($pdo, $menu1st, $filters = []) {
    $sql = "SELECT 
                mc.menu_2nd, 
                COUNT(DISTINCT mc.id) as item_count,
                COUNT(DISTINCT m.id) as manager_count,
                GROUP_CONCAT(DISTINCT m.name ORDER BY m.name ASC SEPARATOR ', ') as managers,
                GROUP_CONCAT(DISTINCT mc.folder ORDER BY mc.folder ASC SEPARATOR ', ') as folders,
                GROUP_CONCAT(DISTINCT mc.manual_writer ORDER BY mc.manual_writer ASC SEPARATOR ', ') as manual_writers
            FROM menu_clients mc
            LEFT JOIN managers m ON mc.id = m.client_id AND m.is_active = 1
            WHERE mc.menu_1st = :menu_1st AND mc.is_active = 1";
    
    $params = [':menu_1st' => $menu1st];
    
    // 추가 필터 적용 가능
    if (!empty($filters['manual_writer'])) {
        $sql .= " AND mc.manual_writer LIKE :manual_writer";
        $params[':manual_writer'] = "%{$filters['manual_writer']}%";
    }
    
    if (!empty($filters['manager_name'])) {
        $sql .= " AND EXISTS (
                    SELECT 1 FROM managers m2 
                    WHERE m2.client_id = mc.id 
                    AND m2.is_active = 1 
                    AND m2.name LIKE :manager_name
                  )";
        $params[':manager_name'] = "%{$filters['manager_name']}%";
    }
    
    if (!empty($filters['company_id'])) {
        $sql .= " AND mc.company_id = :company_id";
        $params[':company_id'] = $filters['company_id'];
    }
    
    $sql .= " GROUP BY mc.menu_2nd ORDER BY mc.menu_2nd ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 담당자별 통계 정보 조회 (당사 직원과 고객사 담당자 분리)
 */
function getManagerStatistics($pdo, $filters = []) {
    $sql = "SELECT 
                m.name as manager_name,
                m.department,
                m.position,
                COUNT(DISTINCT mc.id) as client_count,
                GROUP_CONCAT(DISTINCT mc.menu_1st ORDER BY mc.menu_1st ASC SEPARATOR ', ') as categories,
                SUM(CASE WHEN m.is_primary = 1 THEN 1 ELSE 0 END) as primary_count,
                SUM(CASE WHEN m.is_primary = 0 THEN 1 ELSE 0 END) as secondary_count,
                'customer' as manager_type
            FROM managers m
            INNER JOIN menu_clients mc ON m.client_id = mc.id
            WHERE m.is_active = 1 AND mc.is_active = 1";
    
    $params = [];
    
    // 필터 조건 추가
    if (!empty($filters['manager_name'])) {
        $sql .= " AND m.name LIKE :manager_name";
        $params[':manager_name'] = "%{$filters['manager_name']}%";
    }
    
    if (!empty($filters['department'])) {
        $sql .= " AND m.department LIKE :department";
        $params[':department'] = "%{$filters['department']}%";
    }
    
    if (!empty($filters['company_id'])) {
        $sql .= " AND mc.company_id = :company_id";
        $params[':company_id'] = $filters['company_id'];
    }
    
    $sql .= " GROUP BY m.name, m.department, m.position
              
              UNION ALL
              
              SELECT 
                mc.manual_writer as manager_name,
                NULL as department,
                NULL as position,
                COUNT(DISTINCT mc.id) as client_count,
                GROUP_CONCAT(DISTINCT mc.menu_1st ORDER BY mc.menu_1st ASC SEPARATOR ', ') as categories,
                COUNT(DISTINCT mc.id) as primary_count,
                0 as secondary_count,
                'internal' as manager_type
              FROM menu_clients mc
              WHERE mc.is_active = 1 
              AND mc.manual_writer IS NOT NULL 
              AND mc.manual_writer != ''";
    
    // manual_writer 필터 추가
    if (!empty($filters['manual_writer'])) {
        $sql .= " AND mc.manual_writer LIKE :manual_writer";
        $params[':manual_writer'] = "%{$filters['manual_writer']}%";
    }
    
    if (!empty($filters['company_id'])) {
        $sql .= " AND mc.company_id = :company_id2";
        $params[':company_id2'] = $filters['company_id'];
    }
    
    $sql .= " GROUP BY mc.manual_writer
              ORDER BY manager_type ASC, client_count DESC, manager_name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * 데이터 마이그레이션 함수 (기존 menu_structure → menu_clients)
 * 주의: 실제 운영환경에서는 신중하게 사용해야 함
 * manual_writer는 menu_clients에 그대로 유지
 */
function migrateFromMenuStructure($pdo) {
    try {
        $pdo->beginTransaction();
        
        // menu_structure 데이터를 menu_clients로 복사 (manual_writer 포함)
        $sql = "INSERT INTO menu_clients (menu_1st, menu_2nd, menu_3rd, manual_writer, company_id, description, folder, is_active, created_at, updated_at)
                SELECT menu_1st, menu_2nd, menu_3rd, manual_writer, company_id, description, folder, is_active, created_at, updated_at
                FROM menu_structure";
        $pdo->exec($sql);
        
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
?>