<?php
/**
 * KJ 대리운전 업체 상세 정보 API (JSON, UTF-8)
 * 경로: /pci0327/api/insurance/kj-company-detail.php
 * 호출: GET /api/insurance/kj-company/{companyNum}
 * - 업체 기본 정보, 담당자 정보, 증권 정보 조회
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

    // ////////////////////////////////
    // 1. 입력 파라미터
    // ////////////////////////////////
    $dNum = isset($_GET['dNum']) ? trim($_GET['dNum']) : '';

    if (empty($dNum)) {
        throw new Exception('업체 번호가 필요합니다.');
    }

    $main_info = [];
    $data = [];

    // ////////////////////////////////
    // 2. 업체 기본 정보 조회
    // ////////////////////////////////
    $companySql = "SELECT * FROM `2012DaeriCompany` WHERE `num` = :dNum";
    $companyStmt = $pdo->prepare($companySql);
    $companyStmt->execute([':dNum' => $dNum]);
    $company_info = $companyStmt->fetch(PDO::FETCH_ASSOC);

    if (!$company_info) {
        throw new Exception('업체 정보를 찾을 수 없습니다.');
    }

    // 업체 정보를 main_info에 복사
    foreach ($company_info as $key => $value) {
        $main_info[$key] = $value;
    }

    // ////////////////////////////////
    // 3. 담당자 정보 조회 (2012Member)
    // ////////////////////////////////
    if (!empty($company_info['MemberNum'])) {
        $memberSql = "SELECT `name` FROM `2012Member` WHERE `num` = :memberNum";
        $memberStmt = $pdo->prepare($memberSql);
        $memberStmt->execute([':memberNum' => $company_info['MemberNum']]);
        $member_info = $memberStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($member_info) {
            $main_info['name'] = $member_info['name'] ?? '';
        }
    }

    // ////////////////////////////////
    // 4. 고객 정보 조회 (2012Costomer)
    // ////////////////////////////////
    $customerSql = "SELECT `mem_id`, `permit` FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = :dNum";
    $customerStmt = $pdo->prepare($customerSql);
    $customerStmt->execute([':dNum' => $dNum]);
    $customer_info = $customerStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($customer_info) {
        $main_info['mem_id'] = $customer_info['mem_id'] ?? '';
        $main_info['permit'] = $customer_info['permit'] ?? '';
    }

    // ////////////////////////////////
    // 5. 증권 정보 조회 (최근 1년)
    //    - 2012CertiTable에서 조회
    //    - 각 증권별 인원 수 계산 (2012DaeriMember에서 push='4')
    // ////////////////////////////////
    $certiSql = "
        SELECT * 
        FROM `2012CertiTable` 
        WHERE `2012DaeriCompanyNum` = :dNum 
          AND `startyDay` BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()
        ORDER BY `startyDay` DESC
    ";
    $certiStmt = $pdo->prepare($certiSql);
    $certiStmt->execute([':dNum' => $dNum]);
    $certiRows = $certiStmt->fetchAll(PDO::FETCH_ASSOC);

    // 각 증권별 인원 수 계산
    $inwonStmt = $pdo->prepare("
        SELECT COUNT(*) as cnt 
        FROM `2012DaeriMember` 
        WHERE `CertiTableNum` = :certiTableNum 
          AND `push` = '4'
    ");

    foreach ($certiRows as $certiRow) {
        $certiTableNum = $certiRow['num'];
        
        $inwonStmt->execute([':certiTableNum' => $certiTableNum]);
        $inwonRow = $inwonStmt->fetch(PDO::FETCH_ASSOC);
        $certiRow['inwon'] = (int)($inwonRow['cnt'] ?? 0);
        
        $data[] = $certiRow;
    }

    // ////////////////////////////////
    // 6. 응답 데이터 구성
    // ////////////////////////////////
    $response = ['success' => true];
    
    // 최상위 데이터를 추가
    $response = array_merge($response, $main_info);
    
    // 배열 데이터를 추가
    $response['data'] = $data;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

