<?php
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
    
    // 쿠폰 존재 여부 확인 (대소문자 구분 없이 검색)
    $stmt = $conn->prepare("
        SELECT id, client_id, coupon_number, is_used, used_count, used_at, created_at 
        FROM holeinone_coupons 
        WHERE LOWER(coupon_number) = LOWER(?) 
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
    
    // 쿠폰 사용 여부 확인
    if ($coupon['is_used']) {
        echo json_encode([
            'success' => false,
            'errorCode' => 'COUPON_ALREADY_USED',
            'message' => '이미 사용된 쿠폰입니다.',
            'data' => [
                'usedAt' => $coupon['used_at'],
                'usedCount' => (int)$coupon['used_count']
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 검증 성공
    echo json_encode([
        'success' => true,
        'message' => '쿠폰 검증 성공',
        'data' => [
            'couponNumber' => $coupon['coupon_number'], // 실제 DB에 저장된 형식 반환
            'inputCoupon' => $originalCoupon, // 사용자가 입력한 원본
            'clientId' => (int)$coupon['client_id'],
            'customerInfo' => [],
            'expiryDate' => null,
            'isVipCustomer' => false,
            'availableSlots' => 1,
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
?>