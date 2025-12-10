<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 파라미터 가져오기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$data = array();

// dNum 값이 존재하는 경우에만 처리
if (!empty($dNum)) {
    try {
        // 회사 정보 조회
        $sql_main = "SELECT 
                    a.company,
                    dc.num, dc.mem_id, dc.hphone, dc.permit, dc.readIs, dc.user            
                    FROM `2012DaeriCompany` a
                    INNER JOIN `2012Costomer` dc ON a.num = dc.2012DaeriCompanyNum
                    WHERE a.num = :dNum";
        
        // Prepared statement 사용
        $stmt = $pdo->prepare($sql_main);
        $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt->execute();
        
        // 회사 정보가 존재하면 처리
        if ($stmt->rowCount() > 0) {
            while ($main_info = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // 문자열 인코딩 변환 (EUC-KR → UTF-8)
               
                
                // 회사 정보를 data 배열에 추가
                $data[] = $main_info;
            }
        }
        
        // 회원 정보 조회
        // 여기에 추가 회원 정보 조회 코드 작성
        
    } catch (PDOException $e) {
        // 에러 처리
        $response = array(
            "success" => false,
            "error" => "데이터베이스 오류가 발생했습니다."
        );
        echo json_encode($response);
        exit;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => (!empty($data)),
    "dNum" => $dNum,
    "data" => $data
);

// JSON 출력 (PHP 8.2는 json_encode를 지원)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>