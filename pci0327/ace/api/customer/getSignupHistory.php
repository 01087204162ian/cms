<?php
/**
 * 홀인원 보험 가입내역 조회 API
 * 주신청자 조회 + 동반자 본인 조회 모두 지원
 * 
 * Version: 2.0 (동반자 검색 추가)
 * Last Updated: 2025-10-11
 */

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
    "data" => [],
    "errorCode" => ""
);

try {
    // POST 데이터 가져오기 (JSON으로 받음)
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        // JSON이 아닌 경우 POST로 받기
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    } else {
        $phone = isset($input['phone']) ? trim($input['phone']) : '';
    }
    
    // 필수 입력값 검증
    if (empty($phone)) {
        $response["message"] = "휴대폰번호를 입력해주세요.";
        $response["errorCode"] = "INVALID_PHONE";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 핸드폰 번호 포맷 정리 (하이픈 제거)
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    // 핸드폰 번호 유효성 검증
    if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 11) {
        $response["message"] = "올바른 휴대폰 번호를 입력해주세요.";
        $response["errorCode"] = "INVALID_PHONE";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    // ✅ 전화번호 해시 생성
    $phoneHash = hash('sha256', $cleanPhone);
    
    // ========================================
    // ✅ STEP 1: 주신청자로 조회
    // ========================================
    $mainApplications = searchAsMainApplicant($conn, $phoneHash);
    
    // ========================================
    // ✅ STEP 2: 동반자로 조회
    // ========================================
    $companionApplications = searchAsCompanion($conn, $phoneHash, $cleanPhone);
    
    // 결과 합치기
    $allResults = array_merge($mainApplications, $companionApplications);
    
    if (empty($allResults)) {
        $response["message"] = "해당 전화번호로 가입된 내역이 없습니다.";
        $response["errorCode"] = "DATA_NOT_FOUND";
        $response["success"] = false;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 성공 응답
    $response["success"] = true;
    $response["message"] = "가입내역 조회가 완료되었습니다. (총 " . count($allResults) . "건)";
    $response["data"] = $allResults;
    $response["searchInfo"] = [
        "resultCount" => count($allResults),
        "mainCount" => count($mainApplications),
        "companionCount" => count($companionApplications)
    ];
    
} catch (PDOException $e) {
    $response["message"] = "데이터베이스 오류가 발생했습니다.";
    $response["errorCode"] = "DATABASE_ERROR";
    error_log("가입내역 조회 DB 오류: " . $e->getMessage());
} catch (Exception $e) {
    $response["message"] = "서버 오류가 발생했습니다. 고객센터로 문의해주세요.";
    $response["errorCode"] = "SERVER_ERROR";
    error_log("가입내역 조회 처리 오류: " . $e->getMessage());
} finally {
    // 연결 닫기
    $conn = null;
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// ========================================
// ✅ 함수 1: 주신청자로 조회
// ========================================
function searchAsMainApplicant($conn, $phoneHash) {
    $stmt = $conn->prepare("
        SELECT 
            id, client_id, coupon_number, testIs,
            applicant_name, applicant_phone,
            golf_course, tee_time, status,
            created_at, updated_at
        FROM holeinone_applications 
        WHERE phone_hash = ?
        ORDER BY created_at DESC
    ");
    
    $stmt->execute([$phoneHash]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $historyData = [];
    
    foreach ($results as $row) {
        // 개인정보 복호화
        $decryptedName = decryptData($row['applicant_name']);
        $decryptedPhone = decryptData($row['applicant_phone']);
        
        if ($decryptedName === false || $decryptedPhone === false) {
            continue;
        }
        
        // ✅ 동반자 정보 조회
        $companions = getCompanions($conn, $row['id']);
        
        // 상태 결정
        $status = determineStatus($row['tee_time'], $row['status']);
        
        $historyData[] = [
            'role' => 'MAIN',  // ✅ 역할: 주신청자
            'signupId' => 'ACE' . str_pad($row['id'], 7, '0', STR_PAD_LEFT),
            'id' => $row['id'],
            'clientId' => $row['client_id'],
            'couponNumber' => $row['coupon_number'],
            'testIs' => $row['testIs'],  // ✅ testIs 추가
            'customerName' => $decryptedName,
            'name' => $decryptedName,
            'phoneNumber' => $decryptedPhone,
            'golfCourseName' => $row['golf_course'],
            'golfCourse' => $row['golf_course'],
            'teeOffTime' => $row['tee_time'],
            'teeTime' => $row['tee_time'],
            'createdAt' => $row['created_at'],
            'signupDate' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
            'status' => $status,
            'companions' => $companions  // ✅ 동반자 정보 추가
        ];
    }
    
    return $historyData;
}

// ========================================
// ✅ 함수 2: 동반자로 조회
// ========================================
function searchAsCompanion($conn, $phoneHash, $cleanPhone) {
    // companion_phone_hash로 검색
    $stmt = $conn->prepare("
        SELECT 
            c.id as companion_id,
            c.application_id,
            c.companion_name,
            c.companion_phone,
            a.id, a.client_id, a.coupon_number, a.testIs,
            a.applicant_name, a.applicant_phone,
            a.golf_course, a.tee_time, a.status,
            a.created_at, a.updated_at
        FROM holeinone_companions c
        JOIN holeinone_applications a ON c.application_id = a.id
        WHERE c.companion_phone_hash = ?
        ORDER BY a.created_at DESC
    ");
    
    $stmt->execute([$phoneHash]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $historyData = [];
    
    foreach ($results as $row) {
        // 동반자 본인 정보 복호화
        $myName = decryptData($row['companion_name']);
        $myPhone = decryptData($row['companion_phone']);
        
        if ($myName === false || $myPhone === false) {
            continue;
        }
        
        // 전화번호 재검증
        $myPhoneClean = preg_replace('/[^0-9]/', '', $myPhone);
        if ($myPhoneClean !== $cleanPhone) {
            continue;
        }
        
        // 주신청자 정보 복호화
        $mainApplicantName = decryptData($row['applicant_name']);
        $mainApplicantPhone = decryptData($row['applicant_phone']);
        
        // ✅ 마스킹 처리
        $maskedName = maskName($mainApplicantName);
        $maskedPhone = maskPhone($mainApplicantPhone);
        
        // 상태 결정
        $status = determineStatus($row['tee_time'], $row['status']);
        
        $historyData[] = [
            'role' => 'COMPANION',  // ✅ 역할: 동반자
            'signupId' => 'ACE' . str_pad($row['id'], 7, '0', STR_PAD_LEFT),
            'id' => $row['id'],
            'clientId' => $row['client_id'],
            'couponNumber' => $row['coupon_number'],
            'testIs' => $row['testIs'],
            
            // ✅ 주신청자 정보 (마스킹)
            'mainApplicant' => [
                'name' => $maskedName,
                'phone' => $maskedPhone
            ],
            
            // ✅ 본인(동반자) 정보
            'customerName' => $myName,
            'name' => $myName,
            'phoneNumber' => $myPhone,
            
            'golfCourseName' => $row['golf_course'],
            'golfCourse' => $row['golf_course'],
            'teeOffTime' => $row['tee_time'],
            'teeTime' => $row['tee_time'],
            'createdAt' => $row['created_at'],
            'signupDate' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
            'status' => $status,
            
            'companions' => null  // ✅ 다른 동반자 정보는 표시 안함
        ];
    }
    
    return $historyData;
}

// ========================================
// ✅ 함수 3: 동반자 정보 조회
// ========================================
function getCompanions($conn, $applicationId) {
    $stmt = $conn->prepare("
        SELECT companion_name, companion_phone
        FROM holeinone_companions
        WHERE application_id = ?
        ORDER BY id ASC
    ");
    
    $stmt->execute([$applicationId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $companions = [];
    foreach ($results as $row) {
        $name = decryptData($row['companion_name']);
        $phone = decryptData($row['companion_phone']);
        
        if ($name !== false && $phone !== false) {
            $companions[] = [
                'name' => $name,
                'phone' => $phone
            ];
        }
    }
    
    return $companions;
}

// ========================================
// ✅ 함수 4: 상태 결정
// ========================================
function determineStatus($teeTime, $dbStatus) {
    $teeOffTime = strtotime($teeTime);
    $currentTime = time();
    
    if ($dbStatus === 'pending' || $dbStatus === 'approved') {
        return ($teeOffTime < $currentTime) ? 'COMPLETED' : 'ACTIVE';
    } elseif ($dbStatus === 'rejected') {
        return 'CANCELLED';
    }
    
    return 'ACTIVE';
}

// ========================================
// ✅ 함수 5: 이름 마스킹
// ========================================
function maskName($name) {
    if (empty($name)) return '***';
    
    $length = mb_strlen($name, 'UTF-8');
    
    if ($length === 2) {
        // 2글자: 홍*
        return mb_substr($name, 0, 1, 'UTF-8') . '*';
    } else if ($length >= 3) {
        // 3글자 이상: 홍*동
        return mb_substr($name, 0, 1, 'UTF-8') . 
               str_repeat('*', $length - 2) . 
               mb_substr($name, -1, 1, 'UTF-8');
    }
    
    return $name;
}

// ========================================
// ✅ 함수 6: 전화번호 마스킹
// ========================================
function maskPhone($phone) {
    if (empty($phone)) return '***-****-****';
    
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    if (strlen($cleanPhone) === 11) {
        // 010-****-5678
        return substr($cleanPhone, 0, 3) . '-****-' . substr($cleanPhone, -4);
    } else if (strlen($cleanPhone) === 10) {
        // 010-***-5678
        return substr($cleanPhone, 0, 3) . '-***-' . substr($cleanPhone, -4);
    }
    
    return '***-****-****';
}
?>