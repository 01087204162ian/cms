<?php
/**
 * 메뉴 수정 API
 * 
 * 경로: /api/manual/updateMenu.php
 * 메서드: POST
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

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'POST 요청만 허용됩니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // JSON 데이터 받기
    $input = json_decode(file_get_contents('php://input'), true);
    
    // JSON 파싱 오류 체크
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('잘못된 JSON 형식입니다.');
    }
    
    // 필수 필드 검증
    if (!isset($input['id']) || trim($input['id']) === '') {
        throw new Exception('메뉴 ID가 필요합니다.');
    }
    
    if (!isset($input['menu_1st']) || trim($input['menu_1st']) === '') {
        throw new Exception("1차 메뉴명은 필수입니다.");
    }
    
    // 데이터 정리
    $menu_id = (int)$input['id'];
    $menu_1st = trim($input['menu_1st']);
    $menu_2nd = isset($input['menu_2nd']) && $input['menu_2nd'] !== null ? trim($input['menu_2nd']) : null;
    $menu_3rd = isset($input['menu_3rd']) && $input['menu_3rd'] !== null ? trim($input['menu_3rd']) : null;
    $manual_writer = isset($input['manual_writer']) && $input['manual_writer'] !== null ? trim($input['manual_writer']) : null;
    $folder = isset($input['folder']) && $input['folder'] !== null ? trim($input['folder']) : null;
    $description = isset($input['description']) && $input['description'] !== null ? trim($input['description']) : null;
    $company_id = isset($input['company_id']) && $input['company_id'] !== null ? (int)$input['company_id'] : null;
    
    // 빈 문자열을 null로 처리
    if ($menu_2nd === '') $menu_2nd = null;
    if ($menu_3rd === '') $menu_3rd = null;
    if ($manual_writer === '') $manual_writer = null;
    if ($folder === '') $folder = null;
    if ($description === '') $description = null;
    
    // folder 유효성 검사 (값이 있는 경우에만)
    if ($folder !== null && !preg_match('/^[a-zA-Z0-9-]+$/', $folder)) {
        throw new Exception('폴더명은 영문, 숫자, 하이픈(-)만 사용할 수 있습니다.');
    }
    
    // 메뉴 존재 여부 확인
    $check_sql = "SELECT id FROM menu_clients WHERE id = ? AND is_active = 1";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$menu_id]);
    
    if (!$check_stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => '해당 ID의 메뉴를 찾을 수 없습니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // 다른 레코드와의 중복 체크 (자신 제외)
    $duplicate_conditions = ["menu_1st = ?", "id != ?"];
    $duplicate_params = [$menu_1st, $menu_id];
    
    // null 값 처리를 위한 조건 추가
    if ($menu_2nd === null) {
        $duplicate_conditions[] = "menu_2nd IS NULL";
    } else {
        $duplicate_conditions[] = "menu_2nd = ?";
        $duplicate_params[] = $menu_2nd;
    }
    
    if ($menu_3rd === null) {
        $duplicate_conditions[] = "menu_3rd IS NULL";
    } else {
        $duplicate_conditions[] = "menu_3rd = ?";
        $duplicate_params[] = $menu_3rd;
    }
    
    if ($manual_writer === null) {
        $duplicate_conditions[] = "manual_writer IS NULL";
    } else {
        $duplicate_conditions[] = "manual_writer = ?";
        $duplicate_params[] = $manual_writer;
    }
    
    if ($folder === null) {
        $duplicate_conditions[] = "folder IS NULL";
    } else {
        $duplicate_conditions[] = "folder = ?";
        $duplicate_params[] = $folder;
    }
    
    $duplicate_conditions[] = "is_active = 1";
    
    // 중복 체크
    $duplicate_sql = "SELECT id FROM menu_clients WHERE " . implode(" AND ", $duplicate_conditions);
    $duplicate_stmt = $pdo->prepare($duplicate_sql);
    $duplicate_stmt->execute($duplicate_params);
    
    if ($duplicate_stmt->fetch()) {
        throw new Exception('동일한 메뉴 구조가 이미 존재합니다.');
    }
    
    // 메뉴 수정
    $update_sql = "UPDATE menu_clients 
                   SET menu_1st = ?, 
                       menu_2nd = ?, 
                       menu_3rd = ?, 
                       manual_writer = ?, 
                       company_id = ?, 
                       description = ?, 
                       folder = ?, 
                       updated_at = CURRENT_TIMESTAMP
                   WHERE id = ?";
    
    $update_stmt = $pdo->prepare($update_sql);
    $result = $update_stmt->execute([
        $menu_1st,
        $menu_2nd,
        $menu_3rd,
        $manual_writer,
        $company_id,
        $description,
        $folder,
        $menu_id
    ]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => '메뉴가 성공적으로 수정되었습니다.',
            'id' => $menu_id,
            'data' => [
                'menu_1st' => $menu_1st,
                'menu_2nd' => $menu_2nd,
                'menu_3rd' => $menu_3rd,
                'manual_writer' => $manual_writer,
                'folder' => $folder,
                'description' => $description
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('메뉴 수정에 실패했습니다.');
    }
    
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