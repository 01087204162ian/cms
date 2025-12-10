<?php
/**
 * 특정 메뉴 클라이언트 정보 조회 API
 * - idGubun 1: 거래처 신규 등록용 - menu_clients 테이블에서 메뉴 정보 조회
 * - idGubun 2: 거래처 조회용 - menu_clients_info 테이블 id로 거래처 상세 정보 조회
 * 
 * 테이블 관계:
 * - menu_clients_info.menu_client_id = menu_clients.id
 * - menu_client_contacts.client_id = menu_clients_info.id
 * 
 * 경로: /api/manual/menu_client_serarch.php
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

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'POST 요청만 허용됩니다.',
        'error' => 'Method Not Allowed'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // POST 파라미터 받기
    $itemId = isset($_POST['itemId']) ? intval($_POST['itemId']) : null;
    $idGubun = isset($_POST['idGubun']) ? intval($_POST['idGubun']) : null;
    
    // 파라미터 유효성 검증
    if (!$itemId || $itemId <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => '유효한 itemId가 필요합니다.',
            'error' => 'Invalid itemId parameter'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    if (!$idGubun || !in_array($idGubun, [1, 2])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => '유효한 idGubun이 필요합니다. (1: 신규등록, 2: 조회)',
            'error' => 'Invalid idGubun parameter'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    $responseData = null;
    
    if ($idGubun == 1) {
        // 구분 1: 거래처 신규 등록용 - menu_clients 테이블에서 메뉴 정보 조회
        $responseData = getMenuClientById($pdo, $itemId);
        
        if ($responseData) {
            $response = [
                'success' => true,
                'message' => '메뉴 클라이언트 정보를 성공적으로 조회했습니다.',
                'data' => $responseData,
                'action' => 'new_registration'
            ];
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => '해당 ID의 메뉴 클라이언트를 찾을 수 없습니다.',
                'error' => 'Menu client not found',
                'requested_id' => $itemId
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
    } else if ($idGubun == 2) {
        // 구분 2: 거래처 조회용 - menu_clients_info 테이블 id로 거래처 상세 정보 조회
        $responseData = getClientDetailInfo($pdo, $itemId);
        
        if ($responseData) {
            $response = [
                'success' => true,
                'message' => '거래처 상세 정보를 성공적으로 조회했습니다.',
                'data' => $responseData,
                'action' => 'view_detail'
            ];
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => '해당 ID의 거래처 정보를 찾을 수 없습니다.',
                'error' => 'Client info not found',
                'requested_id' => $itemId
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
    
    // 성공 응답
    http_response_code(200);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    // 데이터베이스 오류 처리
    error_log("Database Error in menu_client_serarch.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.',
        'error' => '내부 서버 오류'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 기타 오류 처리
    error_log("General Error in menu_client_serarch.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '서버 오류가 발생했습니다.',
        'error' => '내부 서버 오류'
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * ID로 특정 메뉴 클라이언트 정보 조회 (idGubun = 1용)
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $itemId 조회할 메뉴 클라이언트 ID
 * @return array|false 조회된 데이터 또는 false
 */
function getMenuClientById($pdo, $itemId) {
    try {
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
                WHERE id = :itemId AND is_active = 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // 데이터 타입 변환 및 정리
            $result['id'] = intval($result['id']);
            $result['company_id'] = $result['company_id'] ? intval($result['company_id']) : null;
            $result['is_active'] = intval($result['is_active']);
            
            // 빈 문자열을 null로 변환
            foreach (['menu_1st', 'menu_2nd', 'menu_3rd', 'manual_writer', 'description', 'folder'] as $field) {
                if (isset($result[$field]) && trim($result[$field]) === '') {
                    $result[$field] = null;
                }
            }
        }
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("Error in getMenuClientById: " . $e->getMessage());
        throw $e;
    }
}

/**
 * 거래처 상세 정보 조회 (idGubun = 2용)
 * menu_clients_info 테이블의 ID로 거래처와 담당자 정보를 함께 조회
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $clientInfoId menu_clients_info 테이블의 ID
 * @return array|false 조회된 데이터 또는 false
 */
function getClientDetailInfo($pdo, $clientInfoId) {
    try {
        // 거래처 기본 정보 + 메뉴 정보 조회
        $sql = "SELECT 
                    ci.id as client_info_id,
                    ci.menu_client_id,
                    ci.client_name,
                    ci.business_number,
                    ci.corporate_number,
                    ci.description as client_description,
                    ci.our_manager,
                    ci.is_active as client_active,
                    ci.created_at as client_created_at,
                    ci.updated_at as client_updated_at,
                    mc.menu_1st,
                    mc.menu_2nd,
                    mc.menu_3rd,
                    mc.manual_writer,
                    mc.company_id,
                    mc.description as menu_description,
                    mc.folder
                FROM menu_clients_info ci
                LEFT JOIN menu_clients mc ON ci.menu_client_id = mc.id
                WHERE ci.id = :clientInfoId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':clientInfoId', $clientInfoId, PDO::PARAM_INT);
        $stmt->execute();
        
        $clientInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$clientInfo) {
            return false;
        }
        
        // 데이터 타입 변환
        $clientInfo['client_info_id'] = intval($clientInfo['client_info_id']);
        $clientInfo['menu_client_id'] = $clientInfo['menu_client_id'] ? intval($clientInfo['menu_client_id']) : null;
        $clientInfo['company_id'] = $clientInfo['company_id'] ? intval($clientInfo['company_id']) : null;
        $clientInfo['client_active'] = intval($clientInfo['client_active']);
        
        // 담당자 정보 조회
        $contactsSql = "SELECT 
                            id,
                            contact_name,
                            phone,
                            email,
                            description,
                            is_active,
                            created_at,
                            updated_at
                        FROM menu_client_contacts 
                        WHERE client_id = :clientInfoId
                        ORDER BY created_at ASC";
        
        $contactsStmt = $pdo->prepare($contactsSql);
        $contactsStmt->bindParam(':clientInfoId', $clientInfoId, PDO::PARAM_INT);
        $contactsStmt->execute();
        
        $contacts = $contactsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 담당자 데이터 타입 변환
        foreach ($contacts as &$contact) {
            $contact['id'] = intval($contact['id']);
            $contact['is_active'] = intval($contact['is_active']);
        }
        
        // 결과 구성
        $result = [
            'client_info' => $clientInfo,
            'contacts' => $contacts,
            'total_contacts' => count($contacts)
        ];
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("Error in getClientDetailInfo: " . $e->getMessage());
        throw $e;
    }
}

/**
 * 메뉴 클라이언트의 기본 정보만 조회 (빠른 조회용)
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $itemId 조회할 메뉴 클라이언트 ID
 * @return array|false 기본 정보 또는 false
 */
function getMenuClientBasicInfo($pdo, $itemId) {
    try {
        $sql = "SELECT 
                    id,
                    menu_1st,
                    menu_2nd,
                    menu_3rd,
                    is_active
                FROM menu_clients 
                WHERE id = :itemId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $result['id'] = intval($result['id']);
            $result['is_active'] = intval($result['is_active']);
        }
        
        return $result;
        
    } catch (PDOException $e) {
        error_log("Error in getMenuClientBasicInfo: " . $e->getMessage());
        throw $e;
    }
}

/**
 * 활성화된 메뉴 클라이언트인지 확인
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $itemId 확인할 메뉴 클라이언트 ID
 * @return bool 활성화 여부
 */
function isMenuClientActive($pdo, $itemId) {
    try {
        $sql = "SELECT is_active FROM menu_clients WHERE id = :itemId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? intval($result['is_active']) === 1 : false;
        
    } catch (PDOException $e) {
        error_log("Error in isMenuClientActive: " . $e->getMessage());
        return false;
    }
}

/**
 * 메뉴 클라이언트 존재 여부 확인
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $itemId 확인할 메뉴 클라이언트 ID
 * @return bool 존재 여부
 */
function menuClientExists($pdo, $itemId) {
    try {
        $sql = "SELECT COUNT(*) as count FROM menu_clients WHERE id = :itemId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return intval($result['count']) > 0;
        
    } catch (PDOException $e) {
        error_log("Error in menuClientExists: " . $e->getMessage());
        return false;
    }
}

/**
 * 거래처 정보 존재 여부 확인
 * 
 * @param PDO $pdo 데이터베이스 연결 객체
 * @param int $clientInfoId 확인할 거래처 정보 ID
 * @return bool 존재 여부
 */
function clientInfoExists($pdo, $clientInfoId) {
    try {
        $sql = "SELECT COUNT(*) as count FROM menu_clients_info WHERE id = :clientInfoId";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':clientInfoId', $clientInfoId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return intval($result['count']) > 0;
        
    } catch (PDOException $e) {
        error_log("Error in clientInfoExists: " . $e->getMessage());
        return false;
    }
}

/**
 * 로깅용 함수 (선택사항)
 */
function logMenuClientAccess($itemId, $idGubun, $userInfo = null) {
    $action = $idGubun == 1 ? 'new_registration_form' : 'view_client_detail';
    
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action,
        'item_id' => $itemId,
        'id_gubun' => $idGubun,
        'user_info' => $userInfo,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    error_log("Menu Client Access: " . json_encode($logData));
}
?>