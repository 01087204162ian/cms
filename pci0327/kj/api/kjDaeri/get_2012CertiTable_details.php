<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// GET 파라미터 가져오기
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  

$data = array();
$main_info = array();

if (!empty($cNum)) {
    try {
        // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
        $conn = getDbConnection();
        
        // 첫 번째 쿼리: 최상위 정보 가져오기
        $sql_main = "
            SELECT 
                dc.company,
                ct.policyNum
            FROM 2012CertiTable ct
            INNER JOIN 2012DaeriCompany dc ON ct.2012DaeriCompanyNum = dc.num
            WHERE ct.num = :cNum
        ";
        
        $stmt_main = $conn->prepare($sql_main);
        $stmt_main->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt_main->execute();
        
        if ($stmt_main->rowCount() > 0) {
            $main_info = $stmt_main->fetch(PDO::FETCH_ASSOC);
            
            
        }
        
        // 두 번째 쿼리: data 배열에 들어갈 정보 가져오기
        $sql_data = "SELECT * FROM kj_premium_data WHERE cNum = :cNum";
        $stmt_data = $conn->prepare($sql_data);
        $stmt_data->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt_data->execute();
        
        if ($stmt_data->rowCount() > 0) {
            while ($row = $stmt_data->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        
    } catch (PDOException $e) {
        // 오류 처리
        $response = array(
            "success" => false,
            "message" => "데이터베이스 오류가 발생했습니다: " . $e->getMessage()
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true
);

// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// 배열 데이터를 추가
$response["data"] = $data;

// JSON 변환 후 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 연결 종료 (PDO에서는 자동으로 닫힙니다)
$conn = null;
?>