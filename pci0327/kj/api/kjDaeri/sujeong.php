<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : ''; 
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : ''; 
$insurance = isset($_GET['insurance']) ? $_GET['insurance'] : '';  
$startDay = isset($_GET['startDay']) ? $_GET['startDay'] : '';  
$policyNum = isset($_GET['policyNum']) ? $_GET['policyNum'] : '';  
$nabang = isset($_GET['nabang']) ? $_GET['nabang'] : '';  

$success = false;

try {
    if ($cNum == 'new') {
        // 새 레코드 삽입
        $sql = "INSERT INTO `2012CertiTable` (
                `2012DaeriCompanyNum`,
                InsuraneCompany,
                startyDay,
                policyNum,
                nabang
            ) VALUES (
                :dNum,
                :insurance,
                :startDay,
                :policyNum,
                :nabang
            )";
            
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt->bindParam(':insurance', $insurance, PDO::PARAM_STR);
        $stmt->bindParam(':startDay', $startDay, PDO::PARAM_STR);
        $stmt->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
        $stmt->bindParam(':nabang', $nabang, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // 기존 레코드 업데이트
        $sql = "UPDATE 2012CertiTable SET 
                InsuraneCompany = :insurance,
                startyDay = :startDay,
                policyNum = :policyNum,
                nabang = :nabang
                WHERE num = :cNum";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':insurance', $insurance, PDO::PARAM_STR);
        $stmt->bindParam(':startDay', $startDay, PDO::PARAM_STR);
        $stmt->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
        $stmt->bindParam(':nabang', $nabang, PDO::PARAM_STR);
        $stmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    $success = true;
} catch (PDOException $e) {
    // 오류 발생 시 처리
    $errorMessage = $e->getMessage();
}

// 응답 데이터 구성
$response = [
    "success" => $success
];

// 오류가 있는 경우 오류 메시지 추가
if (isset($errorMessage)) {
    $response["error"] = $errorMessage;
}

// JSON으로 출력 (PHP 8에서는 json_encode_php4 함수가 필요 없음)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결 해제 (자동으로 해제되므로 명시적으로 할 필요는 없음)
$pdo = null;
?>