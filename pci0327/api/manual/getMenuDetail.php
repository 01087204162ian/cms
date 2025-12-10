<?php
/**
 * 메뉴 상세 정보 조회 API
 * 
 * 경로: /api/manual/getMenuDetail.php
 * 메서드: GET
 * 파라미터: id (메뉴 ID)
 */

// 세션 시작 (로그인 확인 등을 위해)
session_start();

// 필요한 파일 포함
require_once '../config/db_config.php';

// JSON 형태로 응답하도록 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// GET 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'GET 요청만 허용됩니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // ID 파라미터 검증
    if (!isset($_GET['id']) || trim($_GET['id']) === '') {
        throw new Exception('메뉴 ID가 필요합니다.');
    }
    
    $menu_id = (int)$_GET['id'];
    
    if ($menu_id <= 0) {
        throw new Exception('유효하지 않은 메뉴 ID입니다.');
    }
    
    // 메뉴 상세 정보 조회
    $sql = "SELECT 
                id,
                menu_1st,
                menu_2nd,
                menu_3rd,
                manual_writer,
                company_id,
                description,
                folder,
                is_active,
                created_at,
                updated_at
            FROM menu_clients 
            WHERE id = ? AND is_active = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$menu_id]);
    
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$menu) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => '해당 ID의 메뉴를 찾을 수 없습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // 회사 정보가 있는 경우 회사명도 조회
    $company_name = null;
    if ($menu['company_id']) {
        $company_sql = "SELECT company_name FROM companies WHERE company_id = ?";
        $company_stmt = $pdo->prepare($company_sql);
        $company_stmt->execute([$menu['company_id']]);
        $company_result = $company_stmt->fetch(PDO::FETCH_ASSOC);
        if ($company_result) {
            $company_name = $company_result['company_name'];
        }
    }
    
    // 응답 데이터 구성
    $response_data = [
        'id' => (int)$menu['id'],
        'menu_1st' => $menu['menu_1st'],
        'menu_2nd' => $menu['menu_2nd'],
        'menu_3rd' => $menu['menu_3rd'],
        'manual_writer' => $menu['manual_writer'],
        'company_id' => $menu['company_id'] ? (int)$menu['company_id'] : null,
        'company_name' => $company_name,
        'description' => $menu['description'],
        'folder' => $menu['folder'],
        'is_active' => (bool)$menu['is_active'],
        'created_at' => $menu['created_at'],
        'updated_at' => $menu['updated_at']
    ];
    
    echo json_encode([
        'success' => true,
        'message' => '메뉴 정보를 성공적으로 조회했습니다.',
        'data' => $response_data
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>