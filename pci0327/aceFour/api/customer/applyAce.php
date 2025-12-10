<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";
require_once '../../../kj/api/smsApi/smsAligo.php';
// 디버깅을 위한 오류 표시 활성화
ini_set('display_errors', 1);
// 응답 배열 초기화
$response = array(
    "success" => false,
    "message" => "",
    "data" => []
);

try {
    // POST 데이터 가져오기
    $couponNumber = isset($_POST['couponNumber']) ? trim($_POST['couponNumber']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $golfCourse = isset($_POST['golfCourse']) ? trim($_POST['golfCourse']) : '';
    $teeTime = isset($_POST['teeTime']) ? trim($_POST['teeTime']) : '';
    $termsAgreed = isset($_POST['termsAgreed']) ? filter_var($_POST['termsAgreed'], FILTER_VALIDATE_BOOLEAN) : false;
    $companions = isset($_POST['companions']) ? json_decode($_POST['companions'], true) : [];
    
    // 필수 입력값 검증
    if (empty($couponNumber) || empty($name) || empty($phone) || empty($golfCourse) || empty($teeTime) || !$termsAgreed) {
        $response["message"] = "필수 입력값이 누락되었습니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 쿠폰 번호 유효성 검증 (예: VIP-2025-로 시작하는지)
    if (!preg_match('/^VIP-2025-\d{4}$/', $couponNumber)) {
        $response["message"] = "유효하지 않은 쿠폰 번호입니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 핸드폰 번호 포맷 정리 (하이픈 제거)
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    // 트랜잭션 시작
    $conn->beginTransaction();

    // 해시 생성 - 고유 식별자로 사용
    $uniqueHash = generateUniqueHash($couponNumber, $name, $phone);
    
    // 개인정보 암호화
    $encryptedName = encryptData($name);
    $encryptedPhone = encryptData($phone);
    
    // 메인 신청 정보 삽입
    $stmt = $conn->prepare("
        INSERT INTO holeinone_applications (
            coupon_number, 
            applicant_name, 
            applicant_phone, 
            golf_course, 
            tee_time, 
            terms_agreed, 
            unique_hash,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $couponNumber,
        $encryptedName,
        $encryptedPhone,
        $golfCourse,
        $teeTime,
        $termsAgreed ? 1 : 0,
        $uniqueHash
    ]);
    
    $applicationId = $conn->lastInsertId();
    
    // 동반자 정보 삽입
    if (!empty($companions)) {
        $companionStmt = $conn->prepare("
            INSERT INTO holeinone_companions (
                application_id, 
                companion_name, 
                companion_phone, 
                created_at
            ) VALUES (?, ?, ?, NOW())
        ");
        
        foreach ($companions as $companion) {
            $companionName = trim($companion['name']);
            $companionPhone = preg_replace('/[^0-9]/', '', $companion['phone']);
            
            // 동반자 정보 암호화
            $encryptedCompanionName = encryptData($companionName);
            $encryptedCompanionPhone = encryptData($companionPhone);
            
            $companionStmt->execute([
                $applicationId,
                $encryptedCompanionName,
                $encryptedCompanionPhone
            ]);
        }
    }
    
			// 트랜잭션 완료
			$conn->commit();

			// 메인 신청자에게 문자 발송
			$mainMsg = "[홀인원보험] {$name}님, 홀인원 보험 가입이 완료되었습니다. 골프장: {$golfCourse}, 시간: ".date('Y-m-d H:i', strtotime($teeTime))." 즐거운 라운딩 되세요!";
			$sendData = [
				"receiver" => $phone,  // 이미 하이픈이 제거된 번호
				"msg" => $mainMsg,
				"testmode_yn" => "N"  // 실제 발송은 "N", 테스트는 "Y"
			];
			sendAligoSms($sendData);

			// 동반자들에게 문자 발송
			if (!empty($companions)) {
				foreach ($companions as $companion) {
					$companionName = trim($companion['name']);
					$companionPhone = preg_replace('/[^0-9]/', '', $companion['phone']);
					
					$companionMsg = "[홀인원보험] {$companionName}님, {$name}님과 함께하는 홀인원 보험 가입이 완료되었습니다. 골프장: {$golfCourse}, 시간: ".date('Y-m-d H:i', strtotime($teeTime))." 즐거운 라운딩 되세요!";
					$sendData = [
						"receiver" => $companionPhone,
						"msg" => $companionMsg,
						"testmode_yn" => "N"  // 실제 발송은 "N", 테스트는 "Y"
					];
					sendAligoSms($sendData);
				}
			}

			// 성공 응답
			$response["success"] = true;
			$response["message"] = "홀인원 보험 가입이 완료되었습니다.";
			$response["data"] = [
				"applicationId" => $applicationId,
				"uniqueHash" => $uniqueHash
			];
    
} catch (PDOException $e) {
    // 트랜잭션 롤백
    if (isset($conn)) {
        $conn->rollBack();
    }
    
    $response["message"] = "데이터베이스 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보험 신청 오류: " . $e->getMessage());
} catch (Exception $e) {
    $response["message"] = "처리 중 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보험 신청 처리 오류: " . $e->getMessage());
} finally {
    // 연결 닫기
    $conn = null;
}

// 해시 생성 함수
function generateUniqueHash($couponNumber, $name, $phone) {
    $data = $couponNumber . $name . $phone . time();
    return hash('sha256', $data);
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>