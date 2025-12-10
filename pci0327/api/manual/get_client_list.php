<?php
/**
 * get_client_list.php - 거래처 리스트 조회 API
 * 
 * 경로: /api/manual/get_client_list.php
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
try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // GET 파라미터에서 menu_client_id 가져오기
    $menuClientId = isset($_GET['menu_client_id']) ? (int)$_GET['menu_client_id'] : 0;
    
    if ($menuClientId <= 0) {
        throw new Exception('유효하지 않은 메뉴 ID입니다.');
    }
    
    // 메뉴 정보 조회
    $menuQuery = "SELECT menu_1st, menu_2nd, menu_3rd FROM menu_clients WHERE id = :menu_client_id AND is_active = 1";
    $menuStmt = $pdo->prepare($menuQuery);
    $menuStmt->bindParam(':menu_client_id', $menuClientId, PDO::PARAM_INT);
    $menuStmt->execute();
    $menuInfo = $menuStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$menuInfo) {
        throw new Exception('메뉴 정보를 찾을 수 없습니다.');
    }
    
    // 거래처 리스트 조회 (기본 정보)
    $clientQuery = "
        SELECT 
            id,
            client_name,
            business_number,
            corporate_number,
            description,
            our_manager,
            created_at,
            updated_at
        FROM menu_clients_info 
        WHERE menu_client_id = :menu_client_id AND is_active = 1
        ORDER BY client_name ASC
    ";
    
    $clientStmt = $pdo->prepare($clientQuery);
    $clientStmt->bindParam(':menu_client_id', $menuClientId, PDO::PARAM_INT);
    $clientStmt->execute();
    $clients = $clientStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 각 거래처에 대한 담당자 정보 조회
    $contactQuery = "
        SELECT 
            id,
            contact_name,
            phone,
            email,
            description,
            is_active,
            created_at,
            updated_at
        FROM menu_client_contacts 
        WHERE client_id = :client_id AND is_active = 1
        ORDER BY contact_name ASC
    ";
    
    $contactStmt = $pdo->prepare($contactQuery);
    
    // 각 거래처에 담당자 정보 추가
    foreach ($clients as &$client) {
        $contactStmt->bindParam(':client_id', $client['id'], PDO::PARAM_INT);
        $contactStmt->execute();
        $contacts = $contactStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 담당자 정보를 거래처 정보에 추가
        $client['contacts'] = $contacts;
        $client['contact_count'] = count($contacts);
        
        // 담당자 요약 정보 (기존 방식과 호환성 유지)
        $contactSummary = [];
        foreach ($contacts as $contact) {
            $contactSummary[] = $contact['contact_name'] . ' (' . $contact['phone'] . ')';
        }
        $client['contact_summary'] = implode(', ', $contactSummary);
    }
    
    // 응답 데이터 구성
    $response = [
        'success' => true,
        'data' => [
            'menu_info' => $menuInfo,
            'clients' => $clients,
            'total_count' => count($clients)
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    
    http_response_code(500);
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>