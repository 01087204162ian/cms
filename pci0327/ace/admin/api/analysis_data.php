<?php
/**
 * 데이터 분석 API
 * PCI Korea 홀인원보험 관리 시스템
 * 
 * 2회 사용 쿠폰 정리 기준:
 * 1. 완전 동일 조건 (골프장+시간+신청자): 나중 신청 유지, 이전 것 삭제 대상
 * 2. 전화번호만 다름: 나중 신청 유지 (오타 수정으로 간주)
 * 3. 같은 골프장 + 같은 날 + 시간 조정: 나중 신청 유지 (변경 의도)
 * 4. 다른 날짜: 별도 이용으로 정상 인정
 * 5. 다른 골프장: 별도 이용으로 정상 인정
 * 
 * 핵심 원칙: 신청 시간 우선 - 나중 신청이 신청자의 최종 의도
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// 세션 검증
if (!isset($_SESSION['client_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// 데이터베이스 연결 및 암호화 설정
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 응답 함수
function sendResponse($success, $message, $data = null, $errorCode = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'errorCode' => $errorCode
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $type = $_GET['type'] ?? '';
    $clientId = $_SESSION['client_id'];
    
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    switch ($type) {
        case 'past_tee_time':
            $result = getPastTeeTimeData($pdo, $clientId);
            break;
            
        case 'golf_course_stats':
            $result = getGolfCourseStats($pdo, $clientId);
            break;
            
        case 'full_used_coupons':
            $result = getFullUsedCoupons($pdo, $clientId);
            break;
            
        default:
            throw new Exception('잘못된 분석 타입입니다.');
    }
    
    sendResponse(true, '데이터 조회 성공', $result);
    
} catch (Exception $e) {
    error_log("Analysis data error: " . $e->getMessage());
    sendResponse(false, $e->getMessage(), null, 'SERVER_ERROR');
}

/**
 * 지난 티업시간 데이터 조회
 */
function getPastTeeTimeData($pdo, $clientId) {
    // 현재 시간
    $now = date('Y-m-d H:i:s');
    
    // 지난 티업시간 건수
    $pastQuery = "
        SELECT COUNT(*) as count
        FROM holeinone_applications 
        WHERE client_id = ? AND tee_time < ?
    ";
    $pastStmt = $pdo->prepare($pastQuery);
    $pastStmt->execute([$clientId, $now]);
    $pastCount = $pastStmt->fetchColumn();
    
    // 미래 티업시간 건수
    $futureQuery = "
        SELECT COUNT(*) as count
        FROM holeinone_applications 
        WHERE client_id = ? AND tee_time >= ?
    ";
    $futureStmt = $pdo->prepare($futureQuery);
    $futureStmt->execute([$clientId, $now]);
    $futureCount = $futureStmt->fetchColumn();
    
    // 총 건수
    $totalCount = $pastCount + $futureCount;
    
    // 완료율 계산
    $completionRate = $totalCount > 0 ? round(($pastCount / $totalCount) * 100, 1) : 0;
    
    // 평균 경과일 계산
    $avgDaysQuery = "
        SELECT AVG(DATEDIFF(?, tee_time)) as avg_days
        FROM holeinone_applications 
        WHERE client_id = ? AND tee_time < ?
    ";
    $avgDaysStmt = $pdo->prepare($avgDaysQuery);
    $avgDaysStmt->execute([$now, $clientId, $now]);
    $avgDaysAfter = round($avgDaysStmt->fetchColumn() ?? 0);
    
    // 지난 티업시간 상세 목록 (최근 100건) - 암호화된 데이터 복호화
    $applicationsQuery = "
        SELECT 
            applicant_name,
            golf_course,
            tee_time,
            created_at,
            DATEDIFF(?, tee_time) as days_after
        FROM holeinone_applications 
        WHERE client_id = ? AND tee_time < ?
        ORDER BY tee_time DESC
        LIMIT 100
    ";
    $applicationsStmt = $pdo->prepare($applicationsQuery);
    $applicationsStmt->execute([$now, $clientId, $now]);
    $applications = $applicationsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 암호화된 데이터 복호화
    foreach ($applications as &$app) {
        try {
            $app['applicant_name'] = decryptApplicationData($app['applicant_name']);
        } catch (Exception $e) {
            $app['applicant_name'] = '복호화 오류';
            error_log("Decryption error for applicant_name: " . $e->getMessage());
        }
    }
    unset($app); // 참조 해제
    
    return [
        'summary' => [
            'past_count' => $pastCount,
            'future_count' => $futureCount,
            'completion_rate' => $completionRate,
            'avg_days_after' => $avgDaysAfter
        ],
        'applications' => $applications
    ];
}

/**
 * 골프장별 통계 조회
 */
function getGolfCourseStats($pdo, $clientId) {
    // 총 신청 건수
    $totalQuery = "SELECT COUNT(*) FROM holeinone_applications WHERE client_id = ?";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->execute([$clientId]);
    $totalApplications = $totalStmt->fetchColumn();
    
    // 골프장별 신청 건수 (golf_course는 암호화되지 않은 필드)
    $coursesQuery = "
        SELECT 
            golf_course,
            COUNT(*) as count,
            ROUND((COUNT(*) * 100.0 / ?), 1) as percentage
        FROM holeinone_applications 
        WHERE client_id = ?
        GROUP BY golf_course
        ORDER BY count DESC
    ";
    $coursesStmt = $pdo->prepare($coursesQuery);
    $coursesStmt->execute([$totalApplications, $clientId]);
    $courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 총 골프장 수
    $totalCourses = count($courses);
    
    // 최대 신청 건수
    $maxCount = $totalCourses > 0 ? $courses[0]['count'] : 0;
    
    // 골프장당 평균 신청 건수
    $avgPerCourse = $totalCourses > 0 ? round($totalApplications / $totalCourses, 1) : 0;
    
    // 상위 5개 골프장 비율
    $top5Count = 0;
    for ($i = 0; $i < min(5, count($courses)); $i++) {
        $top5Count += $courses[$i]['count'];
    }
    $top5Percentage = $totalApplications > 0 ? round(($top5Count / $totalApplications) * 100, 1) : 0;
    
    return [
        'summary' => [
            'total_courses' => $totalCourses,
            'max_count' => $maxCount,
            'avg_per_course' => $avgPerCourse,
            'top5_percentage' => $top5Percentage
        ],
        'courses' => $courses
    ];
}


/**
 * 2회 사용 완료 쿠폰의 개별 신청 내역 조회
 * 의심 패턴이 있는 케이스만 반환
 */
function getFullUsedCoupons($pdo, $clientId) {
    // 2회 사용된 쿠폰번호 먼저 조회 (summary 조건 추가)
    $fullUsedCouponsQuery = "
        SELECT coupon_number
        FROM holeinone_applications 
        WHERE client_id = ? AND summary = '1'
        GROUP BY coupon_number
        HAVING COUNT(*) = 2
    ";
    $fullUsedCouponsStmt = $pdo->prepare($fullUsedCouponsQuery);
    $fullUsedCouponsStmt->execute([$clientId]);
    $fullUsedCouponNumbers = $fullUsedCouponsStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($fullUsedCouponNumbers)) {
        return [
            'summary' => [
                'total_full_used_coupons' => 0,
                'suspicious_count' => 0,
                'normal_count' => 0
            ],
            'applications' => [],
            'suspicious_patterns' => []
        ];
    }
    
    // 2회 사용된 쿠폰의 모든 신청 내역 조회 (summary 필드 추가)
    $placeholders = str_repeat('?,', count($fullUsedCouponNumbers) - 1) . '?';
    $applicationsQuery = "
        SELECT 
            id,
            coupon_number,
            applicant_name,
            applicant_phone,
            golf_course,
            tee_time,
            created_at,
            updated_at,
            summary,
            summary_time,
            summary_damdanga
        FROM holeinone_applications 
        WHERE client_id = ? AND coupon_number IN ($placeholders) AND summary = '1'
        ORDER BY coupon_number ASC, created_at ASC
    ";
    
    $params = array_merge([$clientId], $fullUsedCouponNumbers);
    $applicationsStmt = $pdo->prepare($applicationsQuery);
    $applicationsStmt->execute($params);
    $applicationsRaw = $applicationsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 신청 내역 처리 및 복호화
    $applications = [];
    $couponUsageMap = []; // 쿠폰별 사용 횟수 추적
    
    foreach ($applicationsRaw as $app) {
        try {
            // 개인정보 복호화
            $decrypted_name = decryptApplicationData($app['applicant_name']);
            $decrypted_phone = decryptApplicationData($app['applicant_phone']);
            
            // 복호화 실패 시 처리
            if ($decrypted_name === false || $decrypted_name === null || $decrypted_name === '복호화 실패') {
                $decrypted_name = '복호화 실패';
            }
            
            if ($decrypted_phone === false || $decrypted_phone === null || $decrypted_phone === '복호화 실패') {
                $decrypted_phone = '복호화 실패';
            }
            
            // 전화번호 포맷팅
            if ($decrypted_phone !== '복호화 실패') {
                $decrypted_phone = formatPhoneNumber($decrypted_phone);
            }
            
            // 쿠폰 사용 순서 계산
            if (!isset($couponUsageMap[$app['coupon_number']])) {
                $couponUsageMap[$app['coupon_number']] = 0;
            }
            $couponUsageMap[$app['coupon_number']]++;
            $usageOrder = $couponUsageMap[$app['coupon_number']];
            
            // summary 상태 텍스트 변환
            $summaryText = '';
            switch ($app['summary']) {
                case '1':
                    $summaryText = '정상';
                    break;
                case '2':
                    $summaryText = '취소';
                    break;
                default:
                    $summaryText = '미정';
                    break;
            }
            
            $applications[] = [
                'id' => $app['id'],
                'coupon_number' => $app['coupon_number'],
                'usage_order' => $usageOrder,
                'usage_label' => $usageOrder === 1 ? '1차 사용' : '2차 사용',
                'applicant_name' => $decrypted_name,
                'applicant_phone' => $decrypted_phone,
                'golf_course' => $app['golf_course'],
                'tee_time' => $app['tee_time'],
                'created_at' => $app['created_at'],
                'updated_at' => $app['updated_at'],
                'summary' => $app['summary'],
                'summary_text' => $summaryText,
                'summary_time' => $app['summary_time'],
                'summary_damdanga' => $app['summary_damdanga'],
                'suspicious_pattern' => null,
                'validation_status' => 'pending' // 기본값
            ];
            
        } catch (Exception $e) {
            error_log("Record decryption error (ID: {$app['id']}): " . $e->getMessage());
            
            // 기본값으로 처리
            if (!isset($couponUsageMap[$app['coupon_number']])) {
                $couponUsageMap[$app['coupon_number']] = 0;
            }
            $couponUsageMap[$app['coupon_number']]++;
            
            // summary 상태 텍스트 변환 (오류 케이스)
            $summaryText = '';
            switch ($app['summary']) {
                case '1':
                    $summaryText = '정상';
                    break;
                case '2':
                    $summaryText = '취소';
                    break;
                default:
                    $summaryText = '미정';
                    break;
            }
            
            $applications[] = [
                'id' => $app['id'],
                'coupon_number' => $app['coupon_number'],
                'usage_order' => $couponUsageMap[$app['coupon_number']],
                'usage_label' => $couponUsageMap[$app['coupon_number']] === 1 ? '1차 사용' : '2차 사용',
                'applicant_name' => '복호화 오류',
                'applicant_phone' => '복호화 오류',
                'golf_course' => $app['golf_course'],
                'tee_time' => $app['tee_time'],
                'created_at' => $app['created_at'],
                'updated_at' => $app['updated_at'],
                'summary' => $app['summary'],
                'summary_text' => $summaryText,
                'summary_time' => $app['summary_time'],
                'summary_damdanga' => $app['summary_damdanga'],
                'suspicious_pattern' => null,
                'validation_status' => 'pending'
            ];
        }
    }
    
    // 의심 패턴 감지
    $suspiciousPatterns = detectSuspiciousPatterns($applications);
    
    // 의심 패턴 정보를 신청 데이터에 추가
    foreach ($applications as &$app) {
        foreach ($suspiciousPatterns as $pattern) {
            if (in_array($app['id'], $pattern['applications'])) {
                $app['suspicious_pattern'] = $pattern;
                break;
            }
        }
    }
    unset($app);
    
    // *** 핵심 변경: 의심 패턴이 있는 케이스만 필터링 ***
    $filteredApplications = [];
    foreach ($applications as $app) {
        if ($app['suspicious_pattern'] !== null) {
            $filteredApplications[] = $app;
        }
    }
    
    return [
        'summary' => [
            'total_full_used_coupons' => count($applications) / 2,
            'suspicious_count' => count($filteredApplications),
            'normal_count' => count($applications) - count($filteredApplications)
        ],
        'applications' => $filteredApplications, // 의심 케이스만 반환
        'suspicious_patterns' => $suspiciousPatterns
    ];
}

/**
 * 의심 패턴 감지 함수
 * 정의된 판단 기준에 따라 의심스러운 케이스 감지
 */
function detectSuspiciousPatterns($applications) {
    $suspicious = [];
    $couponGroups = [];
    
    // 쿠폰별로 그룹화
    foreach ($applications as $app) {
        $couponGroups[$app['coupon_number']][] = $app;
    }
    
    foreach ($couponGroups as $couponNumber => $group) {
        if (count($group) === 2) {
            $first = $group[0];
            $second = $group[1];
            
            // 완전 동일 조건 체크 (심각)
            if (isSameCondition($first, $second)) {
                $suspicious[] = [
                    'applications' => [$first['id'], $second['id']],
                    'pattern' => 'identical_condition',
                    'severity' => 'critical',
                    'description' => '완전 동일 조건 (삭제 대상)',
                    'action' => 'delete_first'
                ];
            }
            // 전화번호만 다른 경우 (의심)
            else if (isSimilarPhone($first['applicant_phone'], $second['applicant_phone']) &&
                     $first['golf_course'] === $second['golf_course'] &&
                     $first['tee_time'] === $second['tee_time'] &&
                     $first['applicant_name'] === $second['applicant_name']) {
                $suspicious[] = [
                    'applications' => [$first['id'], $second['id']],
                    'pattern' => 'phone_correction',
                    'severity' => 'suspicious',
                    'description' => '전화번호 오타 수정 (삭제 대상)',
                    'action' => 'delete_first'
                ];
            }
            // 같은 골프장 + 같은 날 + 시간 다름 (의심)
            else if ($first['golf_course'] === $second['golf_course'] &&
                     date('Y-m-d', strtotime($first['tee_time'])) === date('Y-m-d', strtotime($second['tee_time'])) &&
                     $first['tee_time'] !== $second['tee_time']) {
                $suspicious[] = [
                    'applications' => [$first['id'], $second['id']],
                    'pattern' => 'time_adjustment',
                    'severity' => 'suspicious',
                    'description' => '같은 날 시간 조정 (삭제 대상)',
                    'action' => 'delete_first'
                ];
            }
            // 비정상 시간대 체크 (검토)
            else if (hasAbnormalTime($first) || hasAbnormalTime($second)) {
                $apps = [];
                if (hasAbnormalTime($first)) $apps[] = $first['id'];
                if (hasAbnormalTime($second)) $apps[] = $second['id'];
                
                $suspicious[] = [
                    'applications' => $apps,
                    'pattern' => 'abnormal_time',
                    'severity' => 'review',
                    'description' => '비정상 티업시간',
                    'action' => 'review'
                ];
            }
        }
    }
    
    return $suspicious;
}

/**
 * 완전 동일 조건 체크
 */
function isSameCondition($app1, $app2) {
    return $app1['golf_course'] === $app2['golf_course'] && 
           $app1['tee_time'] === $app2['tee_time'] &&
           $app1['applicant_name'] === $app2['applicant_name'] &&
           $app1['applicant_phone'] === $app2['applicant_phone'];
}

/**
 * 전화번호 유사성 체크 (1-2자리 차이)
 */
function isSimilarPhone($phone1, $phone2) {
    if (!$phone1 || !$phone2 || $phone1 === '복호화 실패' || $phone2 === '복호화 실패') {
        return false;
    }
    
    $clean1 = preg_replace('/[^0-9]/', '', $phone1);
    $clean2 = preg_replace('/[^0-9]/', '', $phone2);
    
    if (strlen($clean1) !== strlen($clean2) || strlen($clean1) < 10) {
        return false;
    }
    
    $diffCount = 0;
    for ($i = 0; $i < strlen($clean1); $i++) {
        if ($clean1[$i] !== $clean2[$i]) $diffCount++;
    }
    
    return $diffCount >= 1 && $diffCount <= 2;
}

/**
 * 비정상 시간대 체크
 */
function hasAbnormalTime($app) {
    $teeTime = new DateTime($app['tee_time']);
    $hour = (int)$teeTime->format('H');
    $minute = (int)$teeTime->format('i');
    
    // 새벽 1시~5시 또는 밤 10시~11시 59분
    return ($hour >= 1 && $hour < 5) || 
           ($hour >= 22) ||
           ($hour === 0 && $minute < 30);
}

/**
 * 암호화된 데이터 복호화 함수
 * encryption.php의 decryptData 함수와 동일한 방식 사용
 */
function decryptApplicationData($encryptedData) {
    if (empty($encryptedData)) {
        return '';
    }
    
    try {
        // encryption.php에서 정의된 상수들 확인
        if (!defined('ENCRYPT_KEY') || !defined('CIPHER_ALGO')) {
            error_log("Encryption constants not defined");
            return '암호화 설정 오류';
        }
        
        // 키 해싱 (암호화와 동일한 방식)
        $key = hash('sha256', ENCRYPT_KEY, true);
        
        // base64 디코딩
        $decoded = base64_decode($encryptedData);
        if ($decoded === false) {
            return '복호화 실패 - base64 디코딩 오류';
        }
        
        // IV 크기 계산
        $iv_length = openssl_cipher_iv_length(CIPHER_ALGO);
        if ($iv_length === false) {
            return '복호화 실패 - 잘못된 암호화 알고리즘';
        }
        
        // 데이터 길이 확인
        if (strlen($decoded) < $iv_length) {
            return '복호화 실패 - 데이터 길이 부족';
        }
        
        // IV 및 암호화된 데이터 추출
        $iv = substr($decoded, 0, $iv_length);
        $encrypted_data = substr($decoded, $iv_length);
        
        // 복호화 수행
        $decrypted = openssl_decrypt(
            $encrypted_data,
            CIPHER_ALGO,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($decrypted === false) {
            return '복호화 실패 - openssl_decrypt 오류';
        }
        
        return $decrypted;
        
    } catch (Exception $e) {
        error_log("Decryption error: " . $e->getMessage());
        return '복호화 오류: ' . $e->getMessage();
    }
}

/**
 * 전화번호 포맷팅 함수
 */
function formatPhoneNumber($phone) {
    if (!$phone) return '';
    
    // 숫자만 추출
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    // 11자리 휴대폰 번호 포맷팅
    if (strlen($cleanPhone) === 11 && substr($cleanPhone, 0, 2) === '01') {
        return substr($cleanPhone, 0, 3) . '-' . substr($cleanPhone, 3, 4) . '-' . substr($cleanPhone, 7, 4);
    }
    
    // 10자리 전화번호 포맷팅 (지역번호)
    if (strlen($cleanPhone) === 10) {
        return substr($cleanPhone, 0, 3) . '-' . substr($cleanPhone, 3, 3) . '-' . substr($cleanPhone, 6, 4);
    }
    
    // 기타 경우는 원본 반환
    return $cleanPhone;
}
?>