<?php
/**
 * client_contacts.php 등록 또는 수정하는 api
 * 
-- 거래처 테이블
CREATE TABLE `menu_clients_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_client_id` int(11) DEFAULT NULL COMMENT 'menu_clients 테이블 ID',
  `client_name` varchar(200) NOT NULL COMMENT '거래처명',
  `business_number` varchar(20) DEFAULT NULL COMMENT '사업자번호',
  `corporate_number` varchar(20) DEFAULT NULL COMMENT '법인번호',
  `description` text DEFAULT NULL COMMENT '설명',
  `our_manager` varchar(100) DEFAULT NULL COMMENT '당사 담당자',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '활성화 여부',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_number` (`business_number`),
  UNIQUE KEY `corporate_number` (`corporate_number`),
  KEY `fk_menu_clients_info_menu_client_id` (`menu_client_id`),
  INDEX `idx_client_name` (`client_name`),
  INDEX `idx_our_manager` (`our_manager`),
  CONSTRAINT `fk_menu_clients_info_menu_client_id` FOREIGN KEY (`menu_client_id`) REFERENCES `menu_clients` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 정보 테이블';
-- 거래처 담당자 테이블
CREATE TABLE `menu_client_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL COMMENT '거래처 ID',
  `contact_name` varchar(100) NOT NULL COMMENT '담당자 성명',
  `phone` varchar(20) DEFAULT NULL COMMENT '연락처',
  `email` varchar(100) DEFAULT NULL COMMENT '이메일',
  `description` text DEFAULT NULL COMMENT '설명',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '활성화 여부',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_menu_client_contacts_client_id` (`client_id`),
  INDEX `idx_contact_name` (`contact_name`),
  INDEX `idx_email` (`email`),
  CONSTRAINT `fk_menu_client_contacts_client_id` FOREIGN KEY (`client_id`) REFERENCES `menu_clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='거래처 담당자 테이블';
 * 
 * 경로: /api/manual/client_contacts.php
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
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    // 데이터 검증
    if (!$data || !isset($data['menu_client_id']) || !isset($data['client_name'])) {
        throw new Exception('필수 데이터가 누락되었습니다.');
    }
    
    // 변수 정의
    $id = isset($data['client_info_id']) && !empty($data['client_info_id']) ? (int)$data['client_info_id'] : null;
    $menu_client_id = $data['menu_client_id'];
    $client_name = $data['client_name'];
    $business_number = !empty($data['business_number']) ? $data['business_number'] : null;
    $corporate_number = !empty($data['corporate_number']) ? $data['corporate_number'] : null;
    $description = !empty($data['description']) ? $data['description'] : null;
    $our_manager = !empty($data['our_manager']) ? $data['our_manager'] : null;
    $is_active = isset($data['is_active']) ? (int)$data['is_active'] : 1;
    $contacts = isset($data['contacts']) ? $data['contacts'] : [];
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
   
    $is_insert = true; // 기본값: 신규 등록
    
    // id 값이 있으면 업데이트, 없으면 신규 등록
    if ($id) {
        // 기존 거래처 정보 확인 (id로 확인)
        $check_sql = "SELECT id FROM menu_clients_info WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$id]);
        $existing_client = $check_stmt->fetch();
        
        if ($existing_client) {
            // 기존 거래처 정보 업데이트
            $update_sql = "UPDATE menu_clients_info SET 
                           client_name = ?, 
                           business_number = ?, 
                           corporate_number = ?, 
                           description = ?, 
                           our_manager = ?, 
                           is_active = ?, 
                           updated_at = NOW() 
                           WHERE id = ?";
            
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                $client_name,
                $business_number,
                $corporate_number,
                $description,
                $our_manager,
                $is_active,
                $id
            ]);
            
            $client_info_id = $id;
            $is_insert = false; // 업데이트 작업
            
            // 기존 담당자 정보 삭제 (새로 추가할 예정)
            $delete_contacts_sql = "DELETE FROM menu_client_contacts WHERE client_id = ?";
            $delete_contacts_stmt = $pdo->prepare($delete_contacts_sql);
            $delete_contacts_stmt->execute([$client_info_id]);
        } else {
            // id가 있지만 해당 레코드가 없으면 신규 등록
            $insert_sql = "INSERT INTO menu_clients_info 
                           (menu_client_id, client_name, business_number, corporate_number, description, our_manager, is_active) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([
                $menu_client_id,
                $client_name,
                $business_number,
                $corporate_number,
                $description,
                $our_manager,
                $is_active
            ]);
            
            $client_info_id = $pdo->lastInsertId();
            $is_insert = true; // 신규 등록
        }
    } else {
        // id가 없으면 무조건 신규 등록
        $insert_sql = "INSERT INTO menu_clients_info 
                       (menu_client_id, client_name, business_number, corporate_number, description, our_manager, is_active) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([
            $menu_client_id,
            $client_name,
            $business_number,
            $corporate_number,
            $description,
            $our_manager,
            $is_active
        ]);
        
        $client_info_id = $pdo->lastInsertId();
        $is_insert = true; // 신규 등록
    }
    
    // 담당자 정보 등록
    if (!empty($contacts) && is_array($contacts)) {
        $contact_sql = "INSERT INTO menu_client_contacts 
                        (client_id, contact_name, phone, email, description, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        
        $contact_stmt = $pdo->prepare($contact_sql);
        
        foreach ($contacts as $contact) {
            // 담당자 데이터 검증
            if (empty($contact['contact_name'])) {
                continue; // 담당자 이름이 없으면 건너뜀
            }
            
            $contact_name = $contact['contact_name'];
            $phone = !empty($contact['phone']) ? $contact['phone'] : null;
            $email = !empty($contact['email']) ? $contact['email'] : null;
            $contact_description = !empty($contact['description']) ? $contact['description'] : null;
            $contact_is_active = isset($contact['is_active']) ? (int)$contact['is_active'] : 1;
            
            $contact_stmt->execute([
                $client_info_id,
                $contact_name,
                $phone,
                $email,
                $contact_description,
                $contact_is_active
            ]);
        }
    }
    
    // 트랜잭션 커밋
    $pdo->commit();
    
    // 성공 메시지 동적 설정
    $success_message = $is_insert ? '거래처 정보가 성공적으로 저장되었습니다.' : '거래처 정보가 성공적으로 수정되었습니다.';
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'message' => $success_message,
        'client_info_id' => $client_info_id,
        'id' => $client_info_id,
        'is_insert' => $is_insert // 디버깅용 (필요하면 제거)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 트랜잭션 롤백
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 데이터베이스 오류 처리
    if (strpos($e->getMessage(), 'business_number') !== false) {
        $error_message = '사업자번호가 이미 존재합니다.';
    } elseif (strpos($e->getMessage(), 'corporate_number') !== false) {
        $error_message = '법인번호가 이미 존재합니다.';
    } else {
        $error_message = '데이터베이스 오류가 발생했습니다.';
    }
    
    echo json_encode([
        'success' => false,
        'message' => $error_message
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 트랜잭션 롤백
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 일반 오류 처리
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>