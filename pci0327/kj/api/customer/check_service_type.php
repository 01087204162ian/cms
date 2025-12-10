<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
//include "../kjDaeri/php/encryption.php";

// 데이터 배열 초기화
$data = array();
$response = array(
    "success" => false,
    "data" => []
);

// POST 요청에서 requestNum 가져오기
$num = isset($_POST['requestNum']) ? $_POST['requestNum'] : null;

// requestNum이 존재하는지 확인
if ($num) {
    try {
        // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
        $conn = getDbConnection();
        
        // SQL 쿼리 수정 - 올바른 구문으로 변경
        $sql = "SELECT etag FROM DaeriMember WHERE num = :num";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':num', $num, PDO::PARAM_STR);
        $stmt->execute();
        
        // 결과 가져오기
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response["success"] = true;
            $response["data"] = $row;
            
            // etag 값에 따라 type 지정
            $etag = $row['etag'];
            if ($etag == '1' || $etag == '3') {
                $response["type"] = "대리";
            } else if ($etag == '2' || $etag == '4') {
                $response["type"] = "탁송";
            } else {
                $response["type"] = "알 수 없음";
            }
        } else {
            $response["message"] = "해당 번호에 대한 데이터를 찾을 수 없습니다.";
        }
    } catch (PDOException $e) {
        $response["error"] = "데이터베이스 오류: " . $e->getMessage();
    } finally {
        // 연결 닫기
        $conn = null;
    }
} else {
    $response["error"] = "requestNum이 제공되지 않았습니다.";
}

// JSON 응답 출력 (PHP 8.2의 json_encode 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>