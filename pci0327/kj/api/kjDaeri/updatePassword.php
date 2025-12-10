<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 파라미터 받기
$password = isset($_POST['password']) ? $_POST['password'] : ''; 
$idNum = isset($_POST['idNum']) ? $_POST['idNum'] : '';

// 응답 초기화
$response = array("success" => false);

if (!empty($idNum) && !empty($password)) {
    try {
        // 디버깅: 받은 파라미터 확인
        error_log("받은 파라미터 - password: " . $password . ", idNum: " . $idNum);
        
        // 먼저 해당 레코드가 존재하는지 확인
        $checkSql = "SELECT COUNT(*) as count FROM `2012Costomer` WHERE num = :idNum";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':idNum', $idNum, PDO::PARAM_STR); // INT에서 STR로 변경
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("레코드 존재 여부: " . $result['count']);
        
        if ($result['count'] == 0) {
            $response["success"] = false;
            $response["error"] = "idNum {$idNum}에 해당하는 레코드가 존재하지 않습니다.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 비밀번호 암호화 (더 안전한 password_hash 사용 권장)
        $pass = md5($password);
        
        // Prepared statement 사용 - passwd 필드만 업데이트
        $sql = "UPDATE `2012Costomer` SET 
                    passwd = :pass
                WHERE num = :idNum";
        
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩 (문자열과 정수 둘 다 시도)
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':idNum', $idNum, PDO::PARAM_STR); // INT에서 STR로 변경
        
        // 쿼리 실행
        $stmt->execute();
        
        // 디버깅: 실행된 쿼리와 영향받은 행 수 확인
        error_log("실행된 쿼리: UPDATE 2012Costomer SET passwd = '{$pass}' WHERE num = '{$idNum}'");
        error_log("영향받은 행 수: " . $stmt->rowCount());
        
        // 영향받은 행 수 확인
        if ($stmt->rowCount() > 0) {
            $response["success"] = true;
            $response["message"] = "비밀번호가 성공적으로 업데이트되었습니다.";
            $response["debug"] = array(
                "updated_rows" => $stmt->rowCount(),
                "idNum" => $idNum,
                "new_password_hash" => $pass
            );
        } else {
            $response["success"] = false;
            $response["message"] = "업데이트할 레코드를 찾을 수 없거나 동일한 값으로 업데이트 시도";
            $response["debug"] = array(
                "idNum" => $idNum,
                "password_hash" => $pass,
                "query" => "UPDATE 2012Costomer SET passwd = ? WHERE num = ?"
            );
        }
        
    } catch (PDOException $e) {
        // 에러 처리
        $response["success"] = false;
        $response["error"] = "데이터베이스 오류: " . $e->getMessage();
    }
} else {
    $response["success"] = false;
    $response["error"] = "필수 파라미터(password, idNum)가 누락되었습니다.";
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>