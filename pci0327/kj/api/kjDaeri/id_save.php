<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 파라미터 받기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : ''; 
$mem_id = isset($_POST['mem_id']) ? $_POST['mem_id'] : ''; 
$phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
$password = isset($_POST['password']) ? $_POST['password'] : ''; 
$company = isset($_POST['company']) ? $_POST['company'] : '';
$user = isset($_POST['user']) ? $_POST['user'] : '';

// 응답 초기화
$response = array("success" => false);

if (!empty($dNum)) {
    try {
        // 비밀번호 암호화
        $pass = md5($password);
        
        // Prepared statement 사용
        $sql = "INSERT INTO `2012Costomer` (
                    `2012DaeriCompanyNum`, mem_id, passwd, name, jumin1, jumin2, 
                    hphone, email, level, mail_check, wdate, inclu, kind, 
                    pAssKey, pAssKeyoPen, permit, readIs, user
                ) VALUES (
                    :dNum, :mem_id, :pass, :company, '', '', 
                    :phone, '', '5', '', NOW(), '', '2', 
                    '', '', '1', '1', :user
                )";
        
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmt->bindParam(':mem_id', $mem_id, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':company', $company, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        
        // 쿼리 실행
        $stmt->execute();
        
        // 성공 응답
        $response["success"] = true;
        
    } catch (PDOException $e) {
        // 에러 처리
        $response["success"] = false;
        $response["error"] = "데이터베이스 오류: " . $e->getMessage();
    }
}

// JSON 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>