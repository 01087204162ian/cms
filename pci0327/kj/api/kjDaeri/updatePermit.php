<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 파라미터 받기
$permit = isset($_POST['permit']) ? $_POST['permit'] : ''; 
$idNum = isset($_POST['idNum']) ? $_POST['idNum'] : '';

// 응답 초기화
$response = array("success" => false);

if (!empty($idNum) && !empty($permit)) {
    try {
        // 디버깅: 받은 파라미터 확인
        error_log("받은 파라미터 - permit: " . $permit . ", idNum: " . $idNum);
        
        // 먼저 해당 레코드가 존재하는지 확인
        $checkSql = "SELECT COUNT(*) as count FROM `2012Costomer` WHERE num = :idNum";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':idNum', $idNum, PDO::PARAM_STR);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("레코드 존재 여부: " . $result['count']);
        
        if ($result['count'] == 0) {
            $response["success"] = false;
            $response["error"] = "idNum {$idNum}에 해당하는 레코드가 존재하지 않습니다.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 허용여부 업데이트 쿼리
        $sql = "UPDATE `2012Costomer` SET 
                    permit = :permit
                WHERE num = :idNum";
        
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindParam(':permit', $permit, PDO::PARAM_STR);
        $stmt->bindParam(':idNum', $idNum, PDO::PARAM_STR);
        
        // 쿼리 실행
        $stmt->execute();
        
        // 디버깅: 실행된 쿼리와 영향받은 행 수 확인
        error_log("실행된 쿼리: UPDATE 2012Costomer SET permit = '{$permit}' WHERE num = '{$idNum}'");
        error_log("영향받은 행 수: " . $stmt->rowCount());
        
        // 영향받은 행 수 확인
        if ($stmt->rowCount() > 0) {
            $response["success"] = true;
            $response["message"] = "허용여부가 성공적으로 업데이트되었습니다.";
            $response["debug"] = array(
                "updated_rows" => $stmt->rowCount(),
                "idNum" => $idNum,
                "new_permit" => $permit
            );
        } else {
            $response["success"] = false;
            $response["message"] = "업데이트할 레코드를 찾을 수 없거나 동일한 값으로 업데이트 시도";
            $response["debug"] = array(
                "idNum" => $idNum,
                "permit" => $permit,
                "query" => "UPDATE 2012Costomer SET permit = ? WHERE num = ?"
            );
        }
        
    } catch (PDOException $e) {
        // 에러 처리
        $response["success"] = false;
        $response["error"] = "데이터베이스 오류: " . $e->getMessage();
        error_log("PDO 오류: " . $e->getMessage());
    }
} else {
    $response["success"] = false;
    $response["error"] = "필수 파라미터(permit, idNum)가 누락되었습니다.";
    $response["debug"] = array(
        "received_permit" => $permit,
        "received_idNum" => $idNum
    );
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>