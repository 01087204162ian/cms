<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 데이터 배열 초기화
$data = [];
$main_info = [];
$response = [
    "success" => false
];

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $conn = getDbConnection();
    
    // 연결 UTF-8 설정
    $conn->exec("SET NAMES utf8");
    
    // GET 파라미터 가져오기
    $cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';
    
    if (!empty($cNum)) {
        // 첫 번째 쿼리: 최상위 정보 가져오기
        $sql_main = "
           SELECT 
            dc.num as dNum,
            dc.company,
            ct.policyNum,
            ct.gita,
            ct.InsuraneCompany
            FROM `2012CertiTable` ct
            INNER JOIN `2012DaeriCompany` dc ON ct.`2012DaeriCompanyNum` = dc.num
            WHERE ct.num = :cNum
        ";
        
        $stmt_main = $conn->prepare($sql_main);
        $stmt_main->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt_main->execute();
        
        if ($stmt_main->rowCount() > 0) {
            $main_info = $stmt_main->fetch(PDO::FETCH_ASSOC); // 한 개의 행만 가져옴
            
           
        }
    }
    
    // 응답 데이터 구성
    $response["success"] = true;
    
    // 최상위 데이터를 추가
    if (!empty($main_info)) {
        $response = array_merge($response, $main_info);
    }
    
    // 배열 데이터를 추가
    $response["data"] = $data;
    
    // PDO 연결 닫기
    $conn = null;
    
} catch (PDOException $e) {
    // 오류 처리
    $response["success"] = false;
    $response["error"] = "데이터베이스 오류: " . $e->getMessage();
} catch (Exception $e) {
    // 일반 오류 처리
    $response["success"] = false;
    $response["error"] = "오류 발생: " . $e->getMessage();
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>