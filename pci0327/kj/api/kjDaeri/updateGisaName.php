<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기
    $pdo = getDbConnection();
    
    // POST 데이터 받기
    $num = isset($_POST['num']) ? $_POST['num'] : ''; 
    $Name = isset($_POST['Name']) ? $_POST['Name'] : ''; 
    
   
    $response = array();
    
    if (!empty($Name) && !empty($num)) {
        // Prepared statement 사용하여 SQL 인젝션 방지
        $sql = "UPDATE 2012DaeriMember SET Name = :name WHERE num = :num";
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindParam(':name', $Name, PDO::PARAM_STR);
        $stmt->bindParam(':num', $num, PDO::PARAM_STR);
        
        // 실행
        $result = $stmt->execute();
        
        if ($result) {
            $response = array(
                "success" => true,
                "message" => "업데이트 성공"
            );
        } else {
            $response = array(
                "success" => false,
                "message" => "업데이트 실패"
            );
        }
    } else {
        $response = array(
            "success" => false,
            "message" => "필수 파라미터 누락"
        );
    }
    
} catch (PDOException $e) {
    $response = array(
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    );
} catch (Exception $e) {
    $response = array(
        "success" => false,
        "message" => "일반 오류: " . $e->getMessage()
    );
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO는 자동으로 연결을 닫으므로 별도의 close() 불필요
?>