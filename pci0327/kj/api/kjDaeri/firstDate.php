<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// POST 데이터 가져오기
$dNum = isset($_POST['dNum']) ? trim($_POST['dNum']) : ''; 
$firstDate = isset($_POST['firstDate']) ? trim($_POST['firstDate']) : ''; 

// 응답 배열 초기화
$response = array(
    "success" => false,
    "error" => ""
);

// 필수 파라미터 검증
if(empty($dNum)) {
    $response["error"] = "대리운전회사 번호(dNum)가 필요합니다.";
    echo json_encode($response);
    exit;
}

if(empty($firstDate)) {
    $response["error"] = "시작 날짜(firstDate)가 필요합니다.";
    echo json_encode($response);
    exit;
}

// 날짜 형식 검증 (YYYY-MM-DD)
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $firstDate)) {
    $response["error"] = "날짜 형식이 잘못되었습니다. YYYY-MM-DD 형식이어야 합니다.";
    echo json_encode($response);
    exit;
}

// 날짜 형식 분리 (YYYY-MM-DD)
$m = explode("-", $firstDate);

try {
    // 데이터베이스 업데이트 쿼리 (prepared statement 사용)
    $sql = "UPDATE `2012DaeriCompany` 
            SET FirstStartDay = :firstStartDay, 
                FirstStart = :firstStart 
            WHERE num = :dNum";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstStartDay', $m[2], PDO::PARAM_STR);
    $stmt->bindParam(':firstStart', $firstDate, PDO::PARAM_STR);
    $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    
    // 쿼리 실행
    $result = $stmt->execute();
    
    // 결과 확인
    if($result) {
        $response["success"] = true;
    } else {
        $response["error"] = "데이터베이스 업데이트 실패";
    }
    
} catch(PDOException $e) {
    $response["error"] = "데이터베이스 오류: " . $e->getMessage();
}

// JSON 변환 후 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 PHP가 스크립트 종료 시 자동으로 닫음
?>