<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// GET 파라미터 가져오기
$dNum = $_GET['dNum'] ?? '';
$main_info = [];

if (!empty($dNum)) {
    try {
        // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
        $conn = getDbConnection();
        
        // 대리점 회사 정보 가져오기
        $stmt_company = $conn->prepare("SELECT company FROM `2012DaeriCompany` WHERE num = :dNum");
        $stmt_company->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt_company->execute();
        
        if ($company_info = $stmt_company->fetch()) {
            // 대리점 회사 정보를 main_info에 복사
            foreach ($company_info as $key => $value) {
                $main_info[$key] = $value;
            }
        }
        
        // 응답 데이터 구성
        $response = ["success" => true];
        
        // 최상위 데이터를 추가
        if (!empty($main_info)) {
            $response = array_merge($response, $main_info);
        }
        
        // JSON 출력
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '데이터베이스 쿼리 오류: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// PDO 연결 닫기
$conn = null;
?>