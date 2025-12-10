<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 보험회사 코드 매핑
function getInsuranceCompanyName($code) {
    $companies = [
        1 => '흥국',
        2 => 'DB',
        3 => 'KB',
        4 => '현대',
        5 => '한화',
        6 => '메리츠',
        7 => 'MG',
        8 => '삼성',
        9 => '하나',
		10 => '신한',
        21 => 'chubb',
        22 => '기타'
    ];
    
    return isset($companies[$code]) ? $companies[$code] : '알 수 없음';
}

try {
    // 데이터베이스 연결
    include '../db.php';
    
    // num 파라미터 확인
    if (!isset($_GET['num']) || empty($_GET['num'])) {
        throw new Exception('num 파라미터가 필요합니다.');
    }
    
    $num = intval($_GET['num']);
    
    // 상세정보 조회 쿼리
    $sql = "SELECT * FROM `2015cord` WHERE num = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$num]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        throw new Exception('해당 데이터를 찾을 수 없습니다.');
    }
    
    // 보험회사 코드가 있다면 회사명으로 변환
    if (isset($result['insurance_code'])) {
        $result['insurance_company'] = getInsuranceCompanyName($result['insurance_code']);
    }
    
    // 날짜 형식 변환 (필요한 경우)
    if (isset($result['reg_date'])) {
        $result['formatted_reg_date'] = date('Y-m-d H:i:s', strtotime($result['reg_date']));
    }
    
    // 성공 응답
    echo json_encode([
        'success' => true,
        'message' => '상세정보를 성공적으로 조회했습니다.',
        'data' => $result
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 오류 응답
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.',
        'data' => null
    ], JSON_UNESCAPED_UNICODE);
}
?>