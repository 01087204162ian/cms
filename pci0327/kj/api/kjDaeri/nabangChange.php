<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기
$nabang = isset($_GET['nabang']) ? $_GET['nabang'] : ''; 
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  

$success = false;
$errorMessage = null;

try {
    if (!empty($cNum) && !empty($nabang)) {
        // PDO를 사용한 UPDATE 쿼리 준비 및 실행
        $sql = "UPDATE 2012CertiTable SET nabang_1 = :nabang WHERE num = :cNum";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nabang', $nabang, PDO::PARAM_STR);
        $stmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt->execute();
        
        $success = true;
    } else {
        $errorMessage = "Required parameters are missing";
    }
} catch (PDOException $e) {
    // 오류 처리
    $errorMessage = $e->getMessage();
}

// 응답 데이터 구성
$response = [
    "success" => $success
];

// 오류가 있는 경우 오류 메시지 추가
if ($errorMessage !== null) {
    $response["error"] = $errorMessage;
}

// JSON으로 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결 해제 (자동으로 해제되므로 명시적으로 할 필요는 없음)
$pdo = null;
?>