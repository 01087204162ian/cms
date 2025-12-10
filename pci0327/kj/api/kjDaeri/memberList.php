<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 파라미터 가져오기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$data = array();
$main_info = array();

// dNum 값이 존재하는 경우에만 처리
if (!empty($dNum)) {
    try {
        // 회사 정보 조회
        $sql_main = "SELECT company FROM `2012DaeriCompany` WHERE num = :dNum";
        $stmt_main = $pdo->prepare($sql_main);
        $stmt_main->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt_main->execute();
        
        // 회사 정보가 존재하면 처리
        if ($stmt_main->rowCount() > 0) {
            $main_info = $stmt_main->fetch(PDO::FETCH_ASSOC);
            
            // 문자열 인코딩 변환 (EUC-KR → UTF-8)
            
        }
        
        // 회원 정보 조회
        $sql = "SELECT num, mem_id, name FROM `2012Member` WHERE level = '1'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // 회원 정보가 존재하면 처리
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $data[] = $row;
            }
        }
    } catch (PDOException $e) {
        // 에러 처리
        $response = array(
            "success" => false,
            "error" => "데이터베이스 오류: " . $e->getMessage()
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "dNum" => $dNum,
    "data" => $data
);

// 회사 정보가 있으면 응답에 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>