<?php
/**
 * 홀인원 쿠폰 불일치 번호 조회 API
 * PCI Korea 홀인원보험 관리 시스템
 */

session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 응답 함수
function sendResponse($success, $message, $data = null, $errorCode = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'errorCode' => $errorCode,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 로그인 체크
if (!isset($_SESSION['client_id'])) {
    sendResponse(false, '로그인이 필요합니다.', null, 'AUTH_REQUIRED');
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 세션 정보
    $client_id = $_SESSION['client_id'];
    
    // === 불일치 쿠폰 번호 수집 ===
    $inconsistent_coupons = [];
    
    // 1. 사용됨으로 표시되었지만 신청 내역 없는 쿠폰
    $used_no_app = getUsedButNoApplicationCoupons($pdo, $client_id);
    $inconsistent_coupons['used_but_no_application'] = $used_no_app;
    
    // 2. 미사용으로 표시되었지만 신청 내역 있는 쿠폰
    $not_used_has_app = getNotUsedButHasApplicationCoupons($pdo, $client_id);
    $inconsistent_coupons['not_used_but_has_application'] = $not_used_has_app;
    
    // 3. 사용 횟수 불일치 쿠폰
    $usage_count_mismatch = getUsageCountMismatchCoupons($pdo, $client_id);
    $inconsistent_coupons['usage_count_mismatch'] = $usage_count_mismatch;
    
    // 4. 존재하지 않는 쿠폰으로 신청된 번호들
    $missing_coupons = getMissingCouponNumbers($pdo, $client_id);
    $inconsistent_coupons['missing_coupons'] = $missing_coupons;
    
    // 전체 불일치 쿠폰 번호 목록 (중복 제거)
    $all_inconsistent = array_unique(array_merge(
        $used_no_app,
        $not_used_has_app,
        $usage_count_mismatch,
        $missing_coupons
    ));
    sort($all_inconsistent);
    
    // === 결과 요약 ===
    $total_count = count($all_inconsistent);
    $status_message = $total_count === 0 ? 
        '모든 쿠폰이 정상입니다' : 
        "{$total_count}개의 불일치 쿠폰이 발견되었습니다";
    
    // === 응답 데이터 ===
    $response_data = [
        'total_count' => $total_count,
        'inconsistent_coupons' => $all_inconsistent,
        'by_type' => [
            'used_but_no_application' => [
                'count' => count($used_no_app),
                'coupons' => $used_no_app
            ],
            'not_used_but_has_application' => [
                'count' => count($not_used_has_app),
                'coupons' => $not_used_has_app
            ],
            'usage_count_mismatch' => [
                'count' => count($usage_count_mismatch),
                'coupons' => $usage_count_mismatch
            ],
            'missing_coupons' => [
                'count' => count($missing_coupons),
                'coupons' => $missing_coupons
            ]
        ]
    ];
    
    sendResponse(true, $status_message, $response_data);
    
} catch (Exception $e) {
    error_log("Inconsistent Coupons API Error: " . $e->getMessage());
    
    $error_message = (defined('DEBUG') && DEBUG) ? 
        '불일치 쿠폰 조회 중 오류가 발생했습니다: ' . $e->getMessage() :
        '불일치 쿠폰 조회 중 오류가 발생했습니다.';
    
    sendResponse(false, $error_message, null, 'SERVER_ERROR');
}

/**
 * 사용됨으로 표시되었지만 신청 내역이 없는 쿠폰 번호들
 */
function getUsedButNoApplicationCoupons($pdo, $client_id) {
    $stmt = $pdo->prepare("
        SELECT c.coupon_number
        FROM holeinone_coupons c
        LEFT JOIN holeinone_applications a ON c.coupon_number = a.coupon_number 
            AND c.client_id = a.client_id AND a.summary = '1'
        WHERE c.client_id = ? 
            AND c.testIs = '1' 
            AND c.is_used = 1 
            AND a.coupon_number IS NULL
        ORDER BY c.coupon_number
    ");
    $stmt->execute([$client_id]);
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'coupon_number');
}

/**
 * 미사용으로 표시되었지만 신청 내역이 있는 쿠폰 번호들
 */
function getNotUsedButHasApplicationCoupons($pdo, $client_id) {
    $stmt = $pdo->prepare("
        SELECT DISTINCT c.coupon_number
        FROM holeinone_coupons c
        INNER JOIN holeinone_applications a ON c.coupon_number = a.coupon_number 
            AND c.client_id = a.client_id
        WHERE c.client_id = ? 
            AND c.testIs = '1' 
            AND c.is_used = 0 
            AND a.summary = '1'
        ORDER BY c.coupon_number
    ");
    $stmt->execute([$client_id]);
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'coupon_number');
}

/**
 * 사용 횟수가 실제 신청 건수와 다른 쿠폰 번호들
 */
function getUsageCountMismatchCoupons($pdo, $client_id) {
    $stmt = $pdo->prepare("
        SELECT c.coupon_number
        FROM holeinone_coupons c
        LEFT JOIN holeinone_applications a ON c.coupon_number = a.coupon_number 
            AND c.client_id = a.client_id AND a.summary = '1'
        WHERE c.client_id = ? AND c.testIs = '1'
        GROUP BY c.coupon_number, c.client_id, c.used_count
        HAVING c.used_count != COUNT(a.id)
        ORDER BY c.coupon_number
    ");
    $stmt->execute([$client_id]);
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'coupon_number');
}

/**
 * 존재하지 않는 쿠폰 번호들
 */
function getMissingCouponNumbers($pdo, $client_id) {
    $stmt = $pdo->prepare("
        SELECT DISTINCT a.coupon_number
        FROM holeinone_applications a
        LEFT JOIN holeinone_coupons c ON a.coupon_number = c.coupon_number 
            AND a.client_id = c.client_id AND c.testIs = '1'
        WHERE a.client_id = ? 
            AND a.summary = '1' 
            AND c.coupon_number IS NULL
        ORDER BY a.coupon_number
    ");
    $stmt->execute([$client_id]);
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'coupon_number');
}
?>