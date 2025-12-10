<?php
/*
	testIs 값별 쿠폰 정보

	testIs = '1': 1차 쿠폰 40,000개, 본인만 신청 가능
	testIs = '2': 1차 쿠폰 테스트 5건, 본인만 신청 가능
	testIs = '3': 2차 쿠폰 20,000개, 본인 포함 동반자 3인까지 신청 가능
	testIs = '4': 2차 쿠폰 테스트 5건, 본인 포함 동반자 3인까지 신청 가능

*/
session_start();
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'errorCode' => 'METHOD_NOT_ALLOWED',
        'message' => 'POST 요청만 허용됩니다.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 쿠폰 번호 정규화 함수
 * 다양한 형식의 쿠폰 번호를 처리할 수 있도록 정리
 */
function normalizeCouponNumber($couponNumber) {
    // 1. 앞뒤 공백 제거
    $normalized = trim($couponNumber);
    
    // 2. 대소문자 통일 (선택사항 - 필요에 따라 주석 처리)
    // $normalized = strtoupper($normalized);
    
    // 3. 특수문자나 공백 제거 (선택사항)
    // $normalized = preg_replace('/[^A-Za-z0-9]/', '', $normalized);
    
    return $normalized;
}

/**
 * 기본적인 쿠폰 번호 유효성 검사
 * 형식 제약을 최소화하고 기본적인 검증만 수행
 */
function isValidCouponFormat($couponNumber) {
    // 1. 빈 값 체크
    if (empty($couponNumber)) {
        return false;
    }
    
    // 2. 길이 체크 (너무 짧거나 너무 긴 것만 제외)
    $length = strlen($couponNumber);
    if ($length < 3 || $length > 50) {
        return false;
    }
    
    // 3. 위험한 문자 체크 (SQL 인젝션 방지)
    if (preg_match('/[\'";\\\\]/', $couponNumber)) {
        return false;
    }
    
    // 4. 기본적인 문자 체크 (영문, 숫자, 일부 특수문자만 허용)
    if (!preg_match('/^[A-Za-z0-9\-_#@]+$/', $couponNumber)) {
        return false;
    }
    
    return true;
}

try {
    // JSON 데이터 파싱
    $rawInput = file_get_contents('php://input');
    $jsonData = json_decode($rawInput, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('잘못된 JSON 형식입니다.');
    }
    
    // 쿠폰 번호 추출 및 정규화
    $originalCoupon = isset($jsonData['couponNumber']) ? $jsonData['couponNumber'] : '';
    $couponNumber = normalizeCouponNumber($originalCoupon);
    
    if (empty($couponNumber)) {
        echo json_encode([
            'success' => false,
            'errorCode' => 'MISSING_COUPON',
            'message' => '쿠폰 번호가 필요합니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 기본적인 유효성 검사만 수행
    if (!isValidCouponFormat($couponNumber)) {
        echo json_encode([
            'success' => false,
            'errorCode' => 'INVALID_FORMAT',
            'message' => '유효하지 않은 쿠폰 번호입니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 데이터베이스 연결
    require_once '../../../api/config/db_config.php';
    $conn = getDbConnection();
    
    // 쿠폰 존재 여부 확인 및 클라이언트 정보 조인 (대소문자 구분 없이 검색)
    $stmt = $conn->prepare("
        SELECT 
            hc.id, 
            hc.client_id, 
            hc.coupon_number, 
            hc.is_used, 
            hc.used_count, 
            hc.used_at, 
            hc.created_at,
			hc.testIs,  
            c.name as client_name,
            c.manager_name,
            c.manager_phone,
            c.manager_email
        FROM holeinone_coupons hc
        LEFT JOIN clients c ON hc.client_id = c.id
        WHERE LOWER(hc.coupon_number) = LOWER(?) 
        LIMIT 1
    ");
    $stmt->execute([$couponNumber]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$coupon) {
        echo json_encode([
            'success' => false,
            'errorCode' => 'COUPON_NOT_FOUND',
            'message' => '존재하지 않는 쿠폰 번호입니다.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 쿠폰 사용 횟수 확인 (2회까지 사용 가능)
    $usedCount = (int)$coupon['used_count'];
    $maxUsageCount = 2; // 최대 사용 횟수
    
    if ($usedCount >= $maxUsageCount) {
        // 쿠폰 사용 횟수 초과 시에도 통계 정보 조회
        $statsStmt = $conn->prepare("
            SELECT 
                COUNT(*) as total_coupons,
                SUM(used_count) as total_used_count,
                COUNT(CASE WHEN used_count >= 2 THEN 1 END) as fully_used_coupons,
                COUNT(CASE WHEN used_count = 1 THEN 1 END) as partially_used_coupons,
                COUNT(CASE WHEN used_count = 0 THEN 1 END) as unused_coupons
            FROM holeinone_coupons 
            WHERE client_id = ?
        ");
        $statsStmt->execute([$coupon['client_id']]);
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => false,
            'errorCode' => 'COUPON_USAGE_EXCEEDED',
            'message' => '쿠폰 사용 횟수를 초과했습니다. (최대 ' . $maxUsageCount . '회)',
            'data' => [
                'usedAt' => $coupon['used_at'],
                'usedCount' => $usedCount,
                'maxUsageCount' => $maxUsageCount,
                'remainingUsage' => 0,
                'clientName' => $coupon['client_name'],
			   'testIs' => $coupon['testIs'],  // 이 줄 추가
                'couponStats' => [
                    'totalCoupons' => (int)$stats['total_coupons'],
                    'totalUsedCount' => (int)$stats['total_used_count'],
                    'fullyUsedCoupons' => (int)$stats['fully_used_coupons'],
                    'partiallyUsedCoupons' => (int)$stats['partially_used_coupons'],
                    'unusedCoupons' => (int)$stats['unused_coupons'],
                    'totalRemainingUsage' => ((int)$stats['total_coupons'] * 2) - (int)$stats['total_used_count']
                ]
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 해당 클라이언트의 쿠폰 통계 조회
    $statsStmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_coupons,
            SUM(used_count) as total_used_count,
            COUNT(CASE WHEN used_count >= 2 THEN 1 END) as fully_used_coupons,
            COUNT(CASE WHEN used_count = 1 THEN 1 END) as partially_used_coupons,
            COUNT(CASE WHEN used_count = 0 THEN 1 END) as unused_coupons
        FROM holeinone_coupons 
        WHERE client_id = ?
    ");
    $statsStmt->execute([$coupon['client_id']]);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    // 검증 성공
    $remainingUsage = $maxUsageCount - $usedCount;
    
    echo json_encode([
        'success' => true,
        'message' => '쿠폰 검증 성공',
        'data' => [
            'couponNumber' => $coupon['coupon_number'], // 실제 DB에 저장된 형식 반환
            'inputCoupon' => $originalCoupon, // 사용자가 입력한 원본
            'clientId' => (int)$coupon['client_id'],
            'clientName' => $coupon['client_name'], // 클라이언트 이름 추가
			'testIs' => $coupon['testIs'],  // 이 줄 추가
            'customerInfo' => [
                'managerName' => $coupon['manager_name'],
                'managerPhone' => $coupon['manager_phone'],
                'managerEmail' => $coupon['manager_email']
            ],
            'couponStats' => [
                'totalCoupons' => (int)$stats['total_coupons'], // 전체 쿠폰 수
                'totalUsedCount' => (int)$stats['total_used_count'], // 총 사용 횟수
                'fullyUsedCoupons' => (int)$stats['fully_used_coupons'], // 완전히 사용된 쿠폰 수 (2회 사용)
                'partiallyUsedCoupons' => (int)$stats['partially_used_coupons'], // 부분적으로 사용된 쿠폰 수 (1회 사용)
                'unusedCoupons' => (int)$stats['unused_coupons'], // 미사용 쿠폰 수
                'totalRemainingUsage' => ((int)$stats['total_coupons'] * 2) - (int)$stats['total_used_count'] // 전체 남은 사용 가능 횟수
            ],
            'expiryDate' => null,
            'isVipCustomer' => false,
            'availableSlots' => $remainingUsage, // 현재 쿠폰의 남은 사용 가능 횟수
            'usedCount' => $usedCount, // 현재 쿠폰의 사용 횟수
            'maxUsageCount' => $maxUsageCount, // 쿠폰당 최대 사용 가능 횟수
            'remainingUsage' => $remainingUsage, // 현재 쿠폰의 남은 사용 가능 횟수
            'restrictions' => [],
            'createdAt' => $coupon['created_at']
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 에러 로깅
    error_log("쿠폰 검증 오류: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'errorCode' => 'SERVER_ERROR',
        'message' => '서버 오류가 발생했습니다. 잠시 후 다시 시도해주세요.'
    ], JSON_UNESCAPED_UNICODE);
}



/******************************

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `total_quota` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `manager_name` varchar(100) DEFAULT NULL,
  `manager_phone` varchar(20) DEFAULT NULL,
  `manager_email` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


CREATE TABLE `holeinone_coupons` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL COMMENT '고객사 ID',
  `coupon_number` varchar(20) NOT NULL COMMENT '쿠폰 번호',
  `is_used` tinyint(1) NOT NULL DEFAULT 0 COMMENT '사용 여부',
  `used_count` int(11) NOT NULL DEFAULT 0 COMMENT '사용 횟수',
  `used_at` datetime DEFAULT NULL COMMENT '사용 시간',
  `created_at` datetime NOT NULL COMMENT '생성 시간',
  `testIs` char(1) NOT NULL DEFAULT '1' COMMENT '1리얼 2 테스트 3: 2차쿠폰'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

********************************/
?>