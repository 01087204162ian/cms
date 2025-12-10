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
    $couponStmt = $conn->prepare("SELECT id, client_id, is_used, used_count FROM holeinone_coupons WHERE coupon_number = ?");
    $couponStmt->execute([$couponNumber]);
    $couponData = $couponStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$couponData) {
        $conn->rollBack();
        $response["message"] = "존재하지 않는 쿠폰 번호입니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 쿠폰 사용 완료 상태 확인 (used_count = 2이면 더 이상 사용 불가)
    if ($couponData['used_count'] >= 2) {
        $conn->rollBack();
        $response["message"] = "이미 최대 사용 횟수(2회)에 도달한 쿠폰입니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // client_id 저장
    $clientId = $couponData['client_id'];
    
    // 개인정보 해시 생성 (중복 검증용)
    $namePhoneHash = generateNamePhoneHash($name, $phone);
    
    // 1. 동일 쿠폰 + 동일인의 기존 신청 내역 조회
    $existingApplicationsStmt = $conn->prepare("
        SELECT golf_course, tee_time, created_at
        FROM holeinone_applications 
        WHERE coupon_number = ? AND name_phone_hash = ?
        ORDER BY created_at DESC
    ");
    $existingApplicationsStmt->execute([$couponNumber, $namePhoneHash]);
    $existingApplications = $existingApplicationsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. 동일 쿠폰 + 동일인의 신청 횟수 확인 (최대 2회까지 허용)
    $applicationCount = count($existingApplications);
    if ($applicationCount >= 2) {
        $conn->rollBack();
        $response["message"] = "동일한 쿠폰으로는 최대 2회까지만 가입이 가능합니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 3. 동일 골프장 + 동일 티업시간 중복 검증
    foreach ($existingApplications as $existing) {
        if ($existing['golf_course'] === $golfCourse && $existing['tee_time'] === $teeTime) {
            $conn->rollBack();
            $response["message"] = "동일한 골프장, 동일한 티업시간으로 이미 가입 신청이 완료되었습니다. 다른 골프장이나 다른 시간으로 신청해주세요.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $clientId,
        $couponNumber,
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
    
    // 쿠폰 사용 횟수 업데이트 (2회 사용 시 완전 사용 완료)
    $newUsedCount = $applicationCount + 1;
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
    $successMessage = "홀인원 보험 가입이 완료되었습니다.";
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
        "remainingUsage" => 2 - $newUsedCount
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

// 해시 생성 함수들 (수정된 uniqueHash 함수)
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