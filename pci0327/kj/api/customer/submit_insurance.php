<?php

header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "../kjDaeri/php/encryption.php";

// 디버깅을 위한 오류 표시 활성화
ini_set('display_errors', 1);

// 데이터 배열 초기화
$data = array();
$response = array(
    "success" => false,
    "data" => []
);

// POST 요청에서 데이터 가져오기
$requestNum = isset($_POST['requestNum']) ? $_POST['requestNum'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$phoneNumber = isset($_POST['phone']) ? $_POST['phone'] : null;
$juminNo = isset($_POST['ssn']) ? $_POST['ssn'] : null;
$termsAgreed = isset($_POST['termsAgreed']) ? $_POST['termsAgreed'] : 0;
$marketingConsent = isset($_POST['marketingConsent']) ? $_POST['marketingConsent'] : 0;

// 필수 데이터 검증
if (!$requestNum || !$name || !$phoneNumber || !$juminNo) {
    $response["message"] = "필수 정보가 누락되었습니다.";
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

try {

	// PDO 연결 - db_config.php에서 정의된 getDbConnection() 함수 사용 가정
    $pdo = getDbConnection();
    
    // 데이터 암호화 및 해시 처리
    $encryptedJumin = encryptData($juminNo);
    $juminHash = sha1($juminNo);
    $phoneHash = sha1($phoneNumber);
    
    // 데이터베이스에 저장
		 $stmt = $pdo->prepare("
			UPDATE DaeriMember SET
				Name = :name,
				Jumin = :jumin,
				JuminHash = :juminHash,
				Hphone = :phone,
				PhoneHash = :phoneHash,
				termsAgreed = :termsAgreed,
				marketingConsent = :marketingConsent,
				wdate = NOW()
			WHERE num = :requestNum
		");
    
    $stmt->bindParam(':requestNum', $requestNum);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':jumin', $encryptedJumin);
    $stmt->bindParam(':juminHash', $juminHash);
    $stmt->bindParam(':phone', $phoneNumber);
    $stmt->bindParam(':phoneHash', $phoneHash);
    $stmt->bindParam(':termsAgreed', $termsAgreed);
    $stmt->bindParam(':marketingConsent', $marketingConsent);
    
    $result = $stmt->execute();
    
    if ($result) {
        $response["success"] = true;
        $response["message"] = "회원 정보가 성공적으로 저장되었습니다.";
    } else {
        $response["message"] = "데이터베이스 저장 중 오류가 발생했습니다.";
    }
    
} catch (PDOException $e) {
    $response["message"] = "오류: " . $e->getMessage();
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>