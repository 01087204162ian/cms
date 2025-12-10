<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();



// POST 데이터 받기
$num = $_POST['num'] ?? ''; 
$progress = $_POST['progress'] ?? ''; 
$manager = $_POST['userName'] ?? '';

$message = '';

if (!empty($num) && !empty($progress) && !empty($manager)) {
    try {
        // Prepared statements 사용
        $update = "UPDATE 2012DaeriMember SET progress = :progress, manager = :manager WHERE num = :num";
        $stmt = $pdo->prepare($update);
        $stmt->execute([
            ':progress' => $progress,
            ':manager' => $manager,
            ':num' => $num
        ]);
        
        $update2 = "UPDATE DaeriMember SET progress = :progress, manager = :manager WHERE num = :num";
        $stmt2 = $pdo->prepare($update2);
        $stmt2->execute([
            ':progress' => $progress,
            ':manager' => $manager,
            ':num' => $num
        ]);
        
        $message = '처리완료 !!';
    } catch (PDOException $e) {
        $message = '오류 발생: ' . $e->getMessage();
    }
}

// 응답 데이터 구성
$response = [
    "success" => true,
    "message" => $message,
    "manager" => $manager
];

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 자동으로 종료됨
?>