<?php
header('Content-Type: application/json; charset=utf-8');

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
        21 => 'chubb',
        22 => '기타'
    ];
    
    return isset($companies[$code]) ? $companies[$code] : '알 수 없음';
}



try {
    // 데이터베이스 연결
    include '../db.php';
    
    // POST 요청만 허용
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('POST 요청만 허용됩니다.');
    }
    
    // JSON 데이터 받기
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // JSON 파싱 에러 체크
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('잘못된 JSON 형식입니다.');
    }
    
    // 필수 파라미터 검증
    if (!isset($data['num']) || !isset($data['insuCompany'])) {
        throw new Exception('num과 insuCompany 파라미터가 필요합니다.');
    }
    
    $num = intval($data['num']);
    $insuCompany = $data['insuCompany'];
    
    // 데이터 유효성 검증
    if ($num <= 0) {
        throw new Exception('num은 양수여야 합니다.');
    }
    
    // 보험회사 코드 유효성 검증 (문자열로 받은 경우 정수로 변환)
    $insuCompanyCode = intval($insuCompany);
    if (!getInsuranceCompanyName($insuCompanyCode)) {
        throw new Exception('유효하지 않은 보험회사 코드입니다.');
    }
    
    // 데이터베이스 업데이트 쿼리
    $sql = "UPDATE 2015cord SET insuCompany = ? WHERE num = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$insuCompanyCode, $num]);
    
    if (!$result) {
        throw new Exception('데이터베이스 업데이트에 실패했습니다.');
    }
    
    // 영향받은 행 수 확인
    $affectedRows = $stmt->rowCount();
    
    if ($affectedRows === 0) {
        throw new Exception('해당 num에 대한 레코드를 찾을 수 없습니다.');
    }
    
    // 성공 응답
    $response = [
        'success' => true,
        'message' => '보험회사 정보가 성공적으로 업데이트되었습니다.',
        'data' => [
            'num' => $num,
            'insuCompany' => $insuCompanyCode,
            'insuCompanyName' => getInsuranceCompanyName($insuCompanyCode),
            'affectedRows' => $affectedRows
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 에러 응답
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // 데이터베이스 에러 응답
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => '데이터베이스 오류가 발생했습니다.',
        'error' => $e->getMessage()
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>