<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "../kjDaeri/php/encryption.php";

// 데이터 배열 초기화
$data = array();
$response = array(
    "success" => false,
    "data" => []
);

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $conn = getDbConnection();
    
    // POST 데이터 검증 및 필터링
    $dNum = "";
    if (isset($_POST['cNum'])) {
        $dNum = trim($_POST['cNum']);
        // mysql_escape_string() 대신 PDO의 prepared statement 사용
    }
    
    // SQL 쿼리 준비 (prepared statement 사용)
    $sql_data = "SELECT num, Name, Jumin, nai, etag, Hphone, dongbuCerti, InsuranceCompany, push 
                 FROM DaeriMember 
                 WHERE sangtae='1' AND `2012DaeriCompanyNum`=:dNum 
                 ORDER BY Jumin ASC";
    
    $stmt = $conn->prepare($sql_data);
    $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    $stmt->execute();
    
    // 결과 처리
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // 인코딩 변환 (필요한 경우)
           
            
            // 주민번호와 전화번호 복호화 로직 포함
            include "../kjDaeri/php/decrptJuminHphone.php";
            
            // 데이터 배열에 추가
            $data[] = $row;
        }
        
        // 성공 응답 설정
        $response["success"] = true;
        $response["data"] = $data;
    }
    
    // PDO 연결 닫기 (명시적으로 닫을 필요 없음 - PHP가 자동으로 처리)
    $conn = null;
    
} catch (PDOException $e) {
    // 오류 처리
    $response["success"] = false;
    $response["error"] = "데이터베이스 오류: " . $e->getMessage();
}

// JSON 응답 출력 (PHP 8.2의 json_encode 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>