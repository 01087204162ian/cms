<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// 응답 배열 초기화
$response = [
    'success' => false,
    'error' => '',
    'dNum' => 0
];

// POST 데이터 받기 (널 병합 연산자 사용)
$dNum = $_POST['dNum'] ?? '';
$jumin = $_POST['jumin'] ?? '';
$company = $_POST['company'] ?? '';
$Pname = $_POST['Pname'] ?? '';
$hphone = $_POST['hphone'] ?? '';
$cphone = $_POST['cphone'] ?? '';
$cNumber = $_POST['cNumber'] ?? '';
$lNumber = $_POST['lNumber'] ?? '';



// 필수 필드 검증
if (empty($jumin)) {
    $response['error'] = '주민번호는 필수 입력 항목입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($company)) {
    $response['error'] = '대리운전회사명은 필수 입력 항목입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($Pname)) {
    $response['error'] = '대표자는 필수 입력 항목입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 트랜잭션 시작
    $pdo->beginTransaction();

    if (!empty($dNum)) {
        // dNum이 존재하면 해당 레코드 업데이트
        $stmt = $pdo->prepare("UPDATE `2012DaeriCompany` 
                              SET company = :company, 
                                  Pname = :Pname, 
                                  jumin = :jumin, 
                                  hphone = :hphone, 
                                  cphone = :cphone, 
                                  cNumber = :cNumber, 
                                  lNumber = :lNumber 
                              WHERE num = :dNum");
        
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':Pname', $Pname);
        $stmt->bindParam(':jumin', $jumin);
        $stmt->bindParam(':hphone', $hphone);
        $stmt->bindParam(':cphone', $cphone);
        $stmt->bindParam(':cNumber', $cNumber);
        $stmt->bindParam(':lNumber', $lNumber);
        $stmt->bindParam(':dNum', $dNum, PDO::PARAM_INT);
        
        $stmt->execute();
        
        // 성공 응답 설정
        $response['success'] = true;
        $response['dNum'] = (int)$dNum;
    } else {
        // 새 레코드 삽입
        $stmt = $pdo->prepare("INSERT INTO `2012DaeriCompany` 
                              (company, Pname, jumin, hphone, cphone, cNumber, lNumber) 
                              VALUES 
                              (:company, :Pname, :jumin, :hphone, :cphone, :cNumber, :lNumber)");
        
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':Pname', $Pname);
        $stmt->bindParam(':jumin', $jumin);
        $stmt->bindParam(':hphone', $hphone);
        $stmt->bindParam(':cphone', $cphone);
        $stmt->bindParam(':cNumber', $cNumber);
        $stmt->bindParam(':lNumber', $lNumber);
        
        $stmt->execute();
        
        // 새로 생성된 ID 가져오기
        $newId = $pdo->lastInsertId();
        
        // 성공 응답 설정
        $response['success'] = true;
        $response['dNum'] = (int)$newId;
    }
    
    // 트랜잭션 커밋
    $pdo->commit();
    
} catch (PDOException $e) {
    // 트랜잭션 롤백
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 오류 응답 설정
    $response['error'] = '데이터베이스 오류: ' . $e->getMessage();
}

// JSON 응답 반환 (한글 인코딩 처리)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>