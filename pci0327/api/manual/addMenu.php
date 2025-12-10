<?php
/**
 * menu_clients 등록 또는 수정하는 api
 * 
CREATE TABLE menu_structure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_1st VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    menu_2nd VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    menu_3rd VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    manual_writer VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    company_id INT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    folder VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_menu_1st (menu_1st),
    INDEX idx_manual_writer (manual_writer),
    INDEX idx_company_id (company_id),
    INDEX idx_created_at (created_at),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 * 
 * 경로: /api/manual/addMenu.php
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
    
    // 필수 필드 검증 (menu_1st만 필수)
    if (!isset($input['menu_1st']) || trim($input['menu_1st']) === '') {
        throw new Exception("1차 메뉴명은 필수입니다.");
    }
    
    // 데이터 정리
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
    
    // 중복 체크를 위한 조건 생성
    $duplicate_conditions = ["menu_1st = ?"];
    $duplicate_params = [$menu_1st];
    
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
    
    // 중복 체크 - 같은 메뉴 구조가 이미 존재하는지 확인
    $check_sql = "SELECT id FROM menu_clients WHERE " . implode(" AND ", $duplicate_conditions);
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute($duplicate_params);
    
    if ($existing_record = $check_stmt->fetch()) {
        // 이미 존재하는 경우 - 업데이트 처리
        $update_sql = "UPDATE menu_clients 
                       SET description = ?, company_id = ?, updated_at = CURRENT_TIMESTAMP
                       WHERE id = ?";
        
        $update_stmt = $pdo->prepare($update_sql);
        $result = $update_stmt->execute([
            $description, 
            $company_id, 
            $existing_record['id']
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => '기존 메뉴가 성공적으로 수정되었습니다.',
                'action' => 'updated',
                'id' => $existing_record['id']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('메뉴 수정에 실패했습니다.');
        }
    } else {
        // 새로 등록
        $insert_sql = "INSERT INTO menu_clients 
                       (menu_1st, menu_2nd, menu_3rd, manual_writer, company_id, description, folder) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $insert_stmt = $pdo->prepare($insert_sql);
        $result = $insert_stmt->execute([
            $menu_1st,
            $menu_2nd,
            $menu_3rd,
            $manual_writer,
            $company_id,
            $description,
            $folder
        ]);
        
        if ($result) {
            $new_id = $pdo->lastInsertId();
            echo json_encode([
                'success' => true,
                'message' => '새 메뉴가 성공적으로 등록되었습니다.',
                'id' => $new_id,
                'action' => 'created',
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
            throw new Exception('메뉴 등록에 실패했습니다.');
        }
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