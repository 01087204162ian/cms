<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

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
    
    // 핸드폰 번호 포맷 정리 (하이픈 제거)
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    // 트랜잭션 시작
    $conn->beginTransaction();

    // 쿠폰 번호 유효성 검증
    $couponStmt = $conn->prepare("SELECT id, client_id, is_used, used_count, testIs FROM holeinone_coupons WHERE coupon_number = ?");
    $couponStmt->execute([$couponNumber]);
    $couponData = $couponStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$couponData) {
        $conn->rollBack();
        $response["message"] = "존재하지 않는 쿠폰 번호입니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // client_id 저장
    $clientId = $couponData['client_id'];
    $currentUsedCount = $couponData['used_count'];
	$testIs = $couponData['testIs'];
    
    // 동반자 수 검증
	$companionCount = count($companions);

	// 1차 쿠폰 (testIs = '1' 또는 '2'): 동반자 불가
	if (($testIs === '1' || $testIs === '2') && $companionCount > 0) {
		$conn->rollBack();
		$response["message"] = "1차 쿠폰은 본인만 가입 가능합니다. 동반자는 추가할 수 없습니다.";
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 2차 쿠폰 (testIs = '3' 또는 '4'): 최대 3명까지
	if (($testIs === '3' || $testIs === '4') && $companionCount > 3) {
		$conn->rollBack();
		$response["message"] = "2차 쿠폰은 본인 포함 최대 4명(동반자 3명)까지 가입 가능합니다.";
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 개선된 중복 검증 로직 실행
	$duplicateResult = handleDuplicateCheck($conn, $couponNumber, $golfCourse, $teeTime, $name, $phone, $currentUsedCount);
    
    if ($duplicateResult['action'] === 'reject') {
        $conn->rollBack();
        $response["message"] = $duplicateResult['message'];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 해시 생성 - 고유 식별자로 사용
    $uniqueHash = generateUniqueHash($couponNumber, $name, $phone, $golfCourse, $teeTime);
    
    // 개인정보 암호화
    $encryptedName = encryptData($name);
    $encryptedPhone = encryptData($phone);
    
    // 검색용 해시 생성
    $nameHash = generateNameHash($name);
    $phoneHash = generatePhoneHash($phone);
    $namePhoneHash = generateNamePhoneHash($name, $phone);
    
    // 메인 신청 정보 삽입
    $stmt = $conn->prepare("
        INSERT INTO holeinone_applications (
            client_id,
            coupon_number, 
			testIs,
            applicant_name, 
            applicant_phone, 
            golf_course, 
            tee_time, 
            terms_agreed, 
            unique_hash,
            name_hash,
            phone_hash,
            name_phone_hash,
            created_at
        ) VALUES (?, ?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
/*  오정현 추가 */
    /* 쿼리 로그테이블 적재 (약식이며, 오류 발생시 기록 확인하기위함. 테이블 적재 실패가 발생한다면 실패기간동안의 쿼리를 확인하여 applications 테이블에 추가할 수 있도록 한다)*/
$conn->prepare("INSERT INTO holeinone_application_query_log (rewQeury, createdYMD) VALUES (?, CURDATE())")
    ->execute([sprintf(
        "INSERT INTO holeinone_applications (client_id, coupon_number, testIs, applicant_name, applicant_phone, golf_course, tee_time, terms_agreed, unique_hash, name_hash, phone_hash, name_phone_hash, created_at) VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', '%s', '%s', NOW())",
        $clientId,
        $couponNumber,
        $testIs,
        $encryptedName,
        $encryptedPhone,
        $golfCourse,
        $teeTime,
        $termsAgreed ? 1 : 0,
        $uniqueHash,
        $nameHash,
        $phoneHash,
        $namePhoneHash
    )]);

    $stmt->execute([
        $clientId,
        $couponNumber,
		$testIs,
        $encryptedName,
        $encryptedPhone,
        $golfCourse,
        $teeTime,
        $termsAgreed ? 1 : 0,
        $uniqueHash,
        $nameHash,
        $phoneHash,
        $namePhoneHash
    ]);
    
    $applicationId = $conn->lastInsertId();
    

			// ========================================
		// 동반자 정보 삽입 (companion_phone_hash 추가)
		// ========================================
		if (!empty($companions)) {
			$companionStmt = $conn->prepare("
				INSERT INTO holeinone_companions (
					application_id, 
					companion_name, 
					companion_phone,
					companion_phone_hash,
					created_at
				) VALUES (?, ?, ?, ?, NOW())
			");
			
			foreach ($companions as $companion) {
				$companionName = trim($companion['name']);
				$companionPhone = preg_replace('/[^0-9]/', '', $companion['phone']);
				
				// 동반자 정보 암호화
				$encryptedCompanionName = encryptData($companionName);
				$encryptedCompanionPhone = encryptData($companionPhone);
				
				// ✅ 동반자 전화번호 해시 생성 (SHA-256)
				$companionPhoneHash = hash('sha256', $companionPhone);
				
				// 동반자 정보 저장
				$companionStmt->execute([
					$applicationId,
					$encryptedCompanionName,
					$encryptedCompanionPhone,
					$companionPhoneHash  // ✅ 해시 추가
				]);
				
				// ✅ 동반자 쿠리 로그 (해시 포함)
				$conn->prepare("INSERT INTO holeinone_companion_query_log (rewQuery) VALUES (?)")
					->execute([sprintf(
						"INSERT INTO holeinone_companions (application_id, companion_name, companion_phone, companion_phone_hash, created_at) VALUES (%d, '%s', '%s', '%s', NOW())",
						$applicationId,
						$encryptedCompanionName,
						$encryptedCompanionPhone,
						$companionPhoneHash  // ✅ 해시 추가
					)]);
			}
		}
    
    // 쿠폰 사용 횟수 업데이트 - replace 액션인 경우 증가량이 0일 수 있음
    $newUsedCount = $duplicateResult['action'] === 'replace' ? $currentUsedCount : $currentUsedCount + 1;
    $isFullyUsed = ($newUsedCount >= 2) ? 1 : 0;
    
    $updateCouponStmt = $conn->prepare("
        UPDATE holeinone_coupons 
        SET used_count = ?, 
            used_at = NOW(),
            is_used = ?
        WHERE coupon_number = ?
    ");
    $updateCouponStmt->execute([$newUsedCount, $isFullyUsed, $couponNumber]);
    
    // 트랜잭션 완료
    $conn->commit();

    // 성공 응답 메시지 생성
    $successMessage = $duplicateResult['message'];
    if ($newUsedCount == 1) {
        $successMessage .= " (이 쿠폰으로 1회 더 가입 가능합니다)";
    } else if ($newUsedCount == 2) {
        $successMessage .= " (이 쿠폰의 사용이 완료되었습니다)";
    }

    // 성공 응답
    $response["success"] = true;
    $response["message"] = $successMessage;
    $response["data"] = [
        "applicationId" => $applicationId,
        "uniqueHash" => $uniqueHash,
        "signupId" => 'ACE' . str_pad($applicationId, 7, '0', STR_PAD_LEFT),
        "usageCount" => $newUsedCount,
        "remainingUsage" => 2 - $newUsedCount,
        "actionTaken" => $duplicateResult['action']
    ];
    
} catch (PDOException $e) {
    // 트랜잭션 롤백
    if (isset($conn)) {
        $conn->rollBack();
    }
    
    $response["message"] = "데이터베이스 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보험 신청 오류: " . $e->getMessage());
} catch (Exception $e) {
    // 트랜잭션 롤백
    if (isset($conn)) {
        $conn->rollBack();
    }
    
    $response["message"] = "처리 중 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보험 신청 처리 오류: " . $e->getMessage());
} finally {
    // 연결 닫기
    $conn = null;
}

/**
 * 개선된 중복 검증 로직
 * 쿠폰번호+골프장+티업시간 기준으로 중복 체크
 * 이름은 같고 핸드폰만 다른 경우 핸드폰 오입력으로 판단하여 기존 삭제 후 새로 생성
 */
function handleDuplicateCheck($conn, $couponNumber, $golfCourse, $teeTime, $name, $phone, $currentUsedCount) {
    // 쿠폰 사용 완료 상태 확인 (used_count = 2이면 더 이상 사용 불가)
    if ($currentUsedCount >= 2) {
        // 동일 조건 기존 신청 조회하여 핸드폰 오입력 재신청인지 확인
        $existingStmt = $conn->prepare("
            SELECT id, applicant_name, applicant_phone
            FROM holeinone_applications 
            WHERE coupon_number = ? AND golf_course = ? AND tee_time = ?
            ORDER BY created_at ASC
        ");
        $existingStmt->execute([$couponNumber, $golfCourse, $teeTime]);
        $existingApplications = $existingStmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($existingApplications as $existing) {
            $existingName = decryptData($existing['applicant_name']);
            $existingPhone = decryptData($existing['applicant_phone']);
            
            if ($existingName === $name) {
                if ($existingPhone === $phone) {
                    return ['action' => 'reject', 'message' => '이미 동일한 조건으로 신청이 완료되었습니다.'];
                } else {
                    // 핸드폰 오입력 재신청 - 기존 삭제 후 새로 생성
                    deleteExistingApplication($conn, $existing['id']);
                    decreaseCouponUsageCount($conn, $couponNumber);
                    return ['action' => 'replace', 'message' => '홀인원 보험 가입이 완료되었습니다. (핸드폰 번호가 수정되어 기존 신청을 업데이트했습니다)'];
                }
            }
        }
        
        return ['action' => 'reject', 'message' => '이미 최대 사용 횟수(2회)에 도달한 쿠폰입니다.'];
    }
    
    // 동일 조건 기존 신청 조회
    $existingStmt = $conn->prepare("
        SELECT id, applicant_name, applicant_phone
        FROM holeinone_applications 
        WHERE coupon_number = ? AND golf_course = ? AND tee_time = ?
        ORDER BY created_at ASC
    ");
    $existingStmt->execute([$couponNumber, $golfCourse, $teeTime]);
    $existingApplications = $existingStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($existingApplications as $existing) {
        $existingName = decryptData($existing['applicant_name']);
        $existingPhone = decryptData($existing['applicant_phone']);
        
        if ($existingName === $name) {
            if ($existingPhone === $phone) {
                // 완전히 동일한 신청
                return ['action' => 'reject', 'message' => '이미 동일한 조건으로 신청이 완료되었습니다.'];
            } else {
                // 이름은 같지만 핸드폰이 다름 → 핸드폰 오입력으로 판단
                // 기존 신청 삭제 후 새로 생성
                deleteExistingApplication($conn, $existing['id']);
                decreaseCouponUsageCount($conn, $couponNumber);
                return ['action' => 'replace', 'message' => '홀인원 보험 가입이 완료되었습니다. (핸드폰 번호가 수정되어 기존 신청을 업데이트했습니다)'];
            }
        }
    }
    
    // 동일 이름의 기존 신청이 없음 → 정상 진행
    return ['action' => 'proceed', 'message' => '홀인원 보험 가입이 완료되었습니다.'];
}

/**
 * 기존 신청 삭제 (동반자 포함)
 */
function deleteExistingApplication($conn, $applicationId) {
    // 동반자 정보 삭제
    $deleteCompanionsStmt = $conn->prepare("DELETE FROM holeinone_companions WHERE application_id = ?");
    $deleteCompanionsStmt->execute([$applicationId]);
    
    // 신청 정보 삭제
    $deleteApplicationStmt = $conn->prepare("DELETE FROM holeinone_applications WHERE id = ?");
    $deleteApplicationStmt->execute([$applicationId]);
}

/**
 * 쿠폰 사용 횟수 감소
 */
function decreaseCouponUsageCount($conn, $couponNumber) {
    $updateStmt = $conn->prepare("
        UPDATE holeinone_coupons 
        SET used_count = used_count - 1,
            is_used = CASE WHEN used_count - 1 < 2 THEN 0 ELSE 1 END
        WHERE coupon_number = ?
    ");
    $updateStmt->execute([$couponNumber]);
}

// 해시 생성 함수들
function generateUniqueHash($couponNumber, $name, $phone, $golfCourse, $teeTime) {
    $data = $couponNumber . $name . $phone . $golfCourse . $teeTime . time();
    return hash('sha256', $data);
}

function generateNameHash($name) {
    return hash('sha256', trim($name));
}

function generatePhoneHash($phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', $cleanPhone);
}

function generateNamePhoneHash($name, $phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', trim($name) . $cleanPhone);
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>