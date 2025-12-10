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
    if (!isset($data['insuCompany'])) {
        throw new Exception('insuCompany 파라미터가 필요합니다.');
    }
    
    // 보험회사 코드 유효성 검증
    $insuCompanyCode = intval($data['insuCompany']);
    if (!getInsuranceCompanyName($insuCompanyCode)) {
        throw new Exception('유효하지 않은 보험회사 코드입니다.');
    }
    
    // 데이터 정리 및 기본값 설정
    $num = isset($data['num']) && !empty($data['num']) ? intval($data['num']) : null;
    $agent = isset($data['agent']) ? $data['agent'] : '';
    $name = isset($data['name']) ? $data['name'] : '';
    $cord = isset($data['cord']) ? $data['cord'] : '';
    $cord2 = isset($data['cord2']) ? $data['cord2'] : '';
    $verify = isset($data['verify']) ? $data['verify'] : '';
    $password = isset($data['password']) ? $data['password'] : '0000-00-00';
    $jijem = isset($data['jijem']) ? $data['jijem'] : '';
    $jijemLady = isset($data['jijemLady']) ? $data['jijemLady'] : '';
    $phone = isset($data['phone']) ? $data['phone'] : '';
    $fax = isset($data['fax']) ? $data['fax'] : '';
    $wdate = isset($data['wdate']) ? $data['wdate'] : date('Y-m-d');
    $gita = isset($data['gita']) ? $data['gita'] : '';
    
    $isUpdate = false;
    $affectedRows = 0;
    
    if ($num && $num > 0) {
        // UPDATE 처리
        // 먼저 해당 num이 존재하는지 확인
        $checkSql = "SELECT COUNT(*) FROM 2015cord WHERE num = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$num]);
        $exists = $checkStmt->fetchColumn();
        
        if ($exists > 0) {
            // 레코드가 존재하면 UPDATE
            $sql = "UPDATE 2015cord SET 
                    insuCompany = ?, 
                    agent = ?, 
                    name = ?, 
                    cord = ?, 
                    cord2 = ?, 
                    verify = ?, 
                    password = ?, 
                    jijem = ?, 
                    jijemLady = ?, 
                    phone = ?, 
                    fax = ?, 
                    wdate = ?, 
                    gita = ? 
                    WHERE num = ?";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $insuCompanyCode, $agent, $name, $cord, $cord2, $verify, 
                $password, $jijem, $jijemLady, $phone, $fax, $wdate, $gita, $num
            ]);
            
            if (!$result) {
                throw new Exception('데이터베이스 업데이트에 실패했습니다.');
            }
            
            $affectedRows = $stmt->rowCount();
            $isUpdate = true;
        } else {
            // 레코드가 존재하지 않으면 해당 num으로 INSERT
            $sql = "INSERT INTO 2015cord (
                    num, insuCompany, agent, name, cord, cord2, verify, 
                    password, jijem, jijemLady, phone, fax, wdate, gita
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $num, $insuCompanyCode, $agent, $name, $cord, $cord2, $verify, 
                $password, $jijem, $jijemLady, $phone, $fax, $wdate, $gita
            ]);
            
            if (!$result) {
                throw new Exception('데이터베이스 삽입에 실패했습니다.');
            }
            
            $affectedRows = $stmt->rowCount();
            $isUpdate = false;
        }
    } else {
        // INSERT 처리 (num이 없거나 0인 경우)
        $sql = "INSERT INTO 2015cord (
                insuCompany, agent, name, cord, cord2, verify, 
                password, jijem, jijemLady, phone, fax, wdate, gita
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $insuCompanyCode, $agent, $name, $cord, $cord2, $verify, 
            $password, $jijem, $jijemLady, $phone, $fax, $wdate, $gita
        ]);
        
        if (!$result) {
            throw new Exception('데이터베이스 삽입에 실패했습니다.');
        }
        
        $num = $pdo->lastInsertId(); // 새로 생성된 num 값
        $affectedRows = $stmt->rowCount();
        $isUpdate = false;
    }
    
    // 성공 응답
    $response = [
        'success' => true,
        'message' => $isUpdate ? '보험회사 정보가 성공적으로 업데이트되었습니다.' : '보험회사 정보가 성공적으로 등록되었습니다.',
        'operation' => $isUpdate ? 'UPDATE' : 'INSERT',
        'data' => [
            'num' => $num,
            'insuCompany' => $insuCompanyCode,
            'insuCompanyName' => getInsuranceCompanyName($insuCompanyCode),
            'agent' => $agent,
            'name' => $name,
            'cord' => $cord,
            'cord2' => $cord2,
            'verify' => $verify,
            'password' => $password,
            'jijem' => $jijem,
            'jijemLady' => $jijemLady,
            'phone' => $phone,
            'fax' => $fax,
            'wdate' => $wdate,
            'gita' => $gita,
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