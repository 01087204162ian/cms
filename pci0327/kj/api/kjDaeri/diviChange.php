<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// GET 파라미터 가져오기
$divi = isset($_GET['divi']) ? $_GET['divi'] : ''; 
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  

// divi 값 변환
if($divi == 1) {
    $divi = 2;
    $diviName = '월납';
} else {
    $divi = 1;
    $diviName = '정상납';
}

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

if (!empty($cNum) && !empty($divi)) {
    try {
        // PDO를 사용한 쿼리 준비 및 실행
        $sql = "UPDATE 2012CertiTable SET divi = :divi WHERE num = :cNum";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':divi', $divi, PDO::PARAM_INT);
        $stmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $stmt->execute();
        
        $success = true;
    } catch (PDOException $e) {
        // 오류 처리
        $success = false;
        $errorMessage = $e->getMessage();
    }
}

// 응답 데이터 구성
$response = [
    "success" => $success ?? true,
    "divi" => $divi,
    "diviName" => $diviName
];

// 오류가 있는 경우 오류 메시지 추가
if (isset($errorMessage)) {
    $response["error"] = $errorMessage;
}

// JSON으로 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결 해제 (자동으로 해제되므로 명시적으로 할 필요는 없음)
$pdo = null;
?>