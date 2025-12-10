<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();



// POST 데이터 받기
$Jumin = $_POST['Jumin'] ?? ''; 
$rate = $_POST['rate'] ?? ''; 
$policyNum = $_POST['policyNum'] ?? ''; 

$message = '';

if (!empty($Jumin) && !empty($rate) && !empty($policyNum)) {
    try {
        // 첫 번째 쿼리: 기존 데이터 확인
        $sql = "SELECT num FROM 2019rate WHERE policy = :policyNum AND jumin = :Jumin";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':policyNum' => $policyNum,
            ':Jumin' => $Jumin
        ]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && isset($row['num'])) {
            // UPDATE 실행
            $update = "UPDATE 2019rate SET rate = :rate WHERE num = :num";
            $stmt = $pdo->prepare($update);
            $stmt->execute([
                ':rate' => $rate,
                ':num' => $row['num']
            ]);
        } else {
            // INSERT 실행
            $insert = "INSERT INTO 2019rate (policy, jumin, rate) VALUES (:policyNum, :Jumin, :rate)";
            $stmt = $pdo->prepare($insert);
            $stmt->execute([
                ':policyNum' => $policyNum,
                ':Jumin' => $Jumin,
                ':rate' => $rate
            ]);
        }
        
        $message = '처리완료 !!';
    } catch (PDOException $e) {
        $message = '오류 발생: ' . $e->getMessage();
    }
}

// 응답 데이터 구성
$response = [
    "success" => true,
    "Jumin" => $Jumin,
    "rate" => $rate,
    "policyNum" => $policyNum,
    "message" => $message
];

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 자동으로 종료됨
?>