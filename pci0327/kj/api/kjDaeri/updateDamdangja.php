<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : ''; 
$memberNum = isset($_POST['memberNum']) ? $_POST['memberNum'] : '';

// 응답 초기화
$response = array(
    "success" => false,
    'memberNum' => $memberNum,
    'dNum' => $dNum
);

if (!empty($memberNum) && !empty($dNum)) {
    try {
        // Prepared statement를 사용하여 UPDATE 실행
        $sql = "UPDATE `2012DaeriCompany` SET MemberNum = :memberNum WHERE num = :dNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':memberNum', $memberNum, PDO::PARAM_STR);
        $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        
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