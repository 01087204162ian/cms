<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// GET 파라미터 가져오기
$gita = isset($_GET['gita']) ? $_GET['gita'] : ''; 
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';

// 응답 초기화
$response = array(
    "success" => false
);

if (!empty($cNum) && !empty($gita)) {
    try {
        // Prepared statement를 사용하여 UPDATE 실행
        $sql_main = "UPDATE `2012CertiTable` SET gita = :gita WHERE num = :cNum";
        $stmt = $pdo->prepare($sql_main);
        $stmt->bindParam(':gita', $gita, PDO::PARAM_STR);
        $stmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        
        // 쿼리 실행
        if ($stmt->execute()) {
            $response["success"] = true;
        }
        
    } catch (PDOException $e) {
        // 에러 처리
        $response["success"] = false;
        $response["error"] = "데이터베이스 오류: " . $e->getMessage();
    }
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>