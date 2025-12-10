<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 응답 함수
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 로그인 체크
if (!isset($_SESSION['client_id'])) {
    sendResponse(false, '로그인이 필요합니다.', null);
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 세션 정보
    $client_id = $_SESSION['client_id'];
    $client_name = $_SESSION['client_name'] ?? '';
    $manager_name = $_SESSION['manager_name'] ?? '';
    $total_quota = $_SESSION['total_quota'] ?? 0;
    
    // GET 파라미터로 페이징 지원
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(5, min(50, intval($_GET['limit'] ?? 20)));
    $offset = ($page - 1) * $limit;
    
    // === 쿠폰 기준 통계 (holeinone_coupons) ===
    
    // 총 쿠폰 수 (testIs IN ('1', '3') 리얼 데이터만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_coupons 
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '3')
    ");
    $stmt->execute([$client_id]);
    $total_coupons = $stmt->fetch()['total_coupons'] ?? 0;
    
    // 1회 사용 쿠폰 수 (used_count = 1)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as once_used_coupons 
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '3') AND used_count = 1
    ");
    $stmt->execute([$client_id]);
    $once_used_coupons = $stmt->fetch()['once_used_coupons'] ?? 0;
    
    // 2회 사용 쿠폰 수 (used_count = 2)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as fully_used_coupons 
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '3') AND used_count = 2
    ");
    $stmt->execute([$client_id]);
    $fully_used_coupons = $stmt->fetch()['fully_used_coupons'] ?? 0;
    
    // 미사용 쿠폰 수 계산
    $unused_coupons = $total_coupons - $once_used_coupons - $fully_used_coupons;
    
    // 전체 사용된 쿠폰 수 (is_used = 1)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as used_coupons 
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '3') AND is_used = 1
    ");
    $stmt->execute([$client_id]);
    $used_coupons = $stmt->fetch()['used_coupons'] ?? 0;
    
    // === ✅ 총 신청 건수 (정상만, 신청서 기준) ===
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_applications
        FROM holeinone_applications
        WHERE client_id = ? 
        AND testIs IN ('1', '3')
        AND summary = '1'
    ");
    $stmt->execute([$client_id]);
    $total_applications = $stmt->fetch()['total_applications'] ?? 0;
    
    // === 오늘 신청 건수 계산 ===
    $today = date('Y-m-d');
    
    // 오늘 가입신청 건수 (정상만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as today_applications
        FROM holeinone_applications
        WHERE client_id = ? 
        AND testIs IN ('1', '3')
        AND summary = '1'
        AND DATE(created_at) = ?
    ");
    $stmt->execute([$client_id, $today]);
    $today_applications = $stmt->fetch()['today_applications'] ?? 0;
    
    // 오늘 홀인원 보상신청 건수
    $today_claims = 0;
    
    // === ✅ 1차 쿠폰 통계 (리얼, 정상만) ===
    
    $stmt = $pdo->prepare("
        SELECT 
            -- 쿠폰 개수
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '1') as coupons,
            
            -- 미사용 쿠폰
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '1' AND used_count = 0) as unused,
            
            -- 1회 사용 쿠폰
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '1' AND used_count = 1) as once,
            
            -- 2회 사용 쿠폰
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '1' AND used_count = 2) as full,
            
            -- ✅ 정상 신청서만 (summary='1')
            (SELECT COUNT(*) 
             FROM holeinone_applications 
             WHERE client_id = ? AND testIs = '1' AND summary = '1') as applications
    ");
    $stmt->execute([$client_id, $client_id, $client_id, $client_id, $client_id]);
    $phase1_stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 1차 쿠폰 가입자 수 (본인만, 동반자 없음)
    $phase1_applicants = $phase1_stats['applications'];
    $phase1_companions = 0;
    $phase1_total_persons = $phase1_applicants;
    
    // 1차 쿠폰 분포 (모두 본인만)
    $phase1_person_stats = [
        'applicants' => $phase1_applicants,
        'companions' => 0,
        'total_persons' => $phase1_total_persons,
        'solo' => $phase1_applicants,
        'with_one' => 0,
        'with_two' => 0,
        'with_three' => 0
    ];
    
    // === ✅ 2차 쿠폰 통계 (리얼, 정상만) ===
    
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '3') as coupons,
            
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '3' AND used_count = 0) as unused,
            
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '3' AND used_count = 1) as once,
            
            (SELECT COUNT(*) 
             FROM holeinone_coupons 
             WHERE client_id = ? AND testIs = '3' AND used_count = 2) as full,
            
            -- ✅ 정상 신청서만 (summary='1')
            (SELECT COUNT(*) 
             FROM holeinone_applications 
             WHERE client_id = ? AND testIs = '3' AND summary = '1') as applications
    ");
    $stmt->execute([$client_id, $client_id, $client_id, $client_id, $client_id]);
    $phase2_stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 2차 쿠폰 본인 수 (정상만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as phase2_applicants
        FROM holeinone_applications ha
        WHERE ha.client_id = ? 
        AND ha.testIs = '3'
        AND ha.summary = '1'
    ");
    $stmt->execute([$client_id]);
    $phase2_applicants = $stmt->fetch()['phase2_applicants'] ?? 0;
    
    // 2차 쿠폰 동반자 수 (정상만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as phase2_companions
        FROM holeinone_companions hcomp
        INNER JOIN holeinone_applications ha ON hcomp.application_id = ha.id
        WHERE ha.client_id = ? 
        AND ha.testIs = '3'
        AND ha.summary = '1'
    ");
    $stmt->execute([$client_id]);
    $phase2_companions = $stmt->fetch()['phase2_companions'] ?? 0;
    
    $phase2_total_persons = $phase2_applicants + $phase2_companions;
    
    // 2차 쿠폰 동반자 분포 (정상만)
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE((SELECT COUNT(*) FROM holeinone_companions WHERE application_id = ha.id), 0) as companion_count,
            COUNT(*) as count
        FROM holeinone_applications ha
        WHERE ha.client_id = ? 
        AND ha.testIs = '3'
        AND ha.summary = '1'
        GROUP BY companion_count
        ORDER BY companion_count
    ");
    $stmt->execute([$client_id]);
    $phase2_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 2차 분포 데이터 정리
    $phase2_person_stats = [
        'applicants' => $phase2_applicants,
        'companions' => $phase2_companions,
        'total_persons' => $phase2_total_persons,
        'solo' => 0,
        'with_one' => 0,
        'with_two' => 0,
        'with_three' => 0
    ];
    
    foreach ($phase2_distribution as $row) {
        $companion_count = (int)$row['companion_count'];
        $count = (int)$row['count'];
        
        switch($companion_count) {
            case 0:
                $phase2_person_stats['solo'] = $count;
                break;
            case 1:
                $phase2_person_stats['with_one'] = $count;
                break;
            case 2:
                $phase2_person_stats['with_two'] = $count;
                break;
            case 3:
                $phase2_person_stats['with_three'] = $count;
                break;
        }
    }
    
    // 통계 계산
    $stats = [
        'total_coupons' => (int)$total_coupons,
        'unused_coupons' => (int)$unused_coupons,
        'used_coupons' => (int)$used_coupons,
        'once_used_coupons' => (int)$once_used_coupons,
        'fully_used_coupons' => (int)$fully_used_coupons,
        'total_applications' => (int)$total_applications,
        'today_applications' => (int)$today_applications,
        'today_claims' => (int)$today_claims,
        
        // 1차 쿠폰 통계
        'phase1_coupons' => (int)$phase1_stats['coupons'],
        'phase1_unused' => (int)$phase1_stats['unused'],
        'phase1_once' => (int)$phase1_stats['once'],
        'phase1_full' => (int)$phase1_stats['full'],
        'phase1_applications' => (int)$phase1_stats['applications'],
        
        // 1차 쿠폰 가입자 통계
        'phase1_applicants' => $phase1_person_stats['applicants'],
        'phase1_companions' => $phase1_person_stats['companions'],
        'phase1_total_persons' => $phase1_person_stats['total_persons'],
        'phase1_solo' => $phase1_person_stats['solo'],
        'phase1_with_one' => $phase1_person_stats['with_one'],
        'phase1_with_two' => $phase1_person_stats['with_two'],
        'phase1_with_three' => $phase1_person_stats['with_three'],
        
        // 2차 쿠폰 통계
        'phase2_coupons' => (int)$phase2_stats['coupons'],
        'phase2_unused' => (int)$phase2_stats['unused'],
        'phase2_once' => (int)$phase2_stats['once'],
        'phase2_full' => (int)$phase2_stats['full'],
        'phase2_applications' => (int)$phase2_stats['applications'],
        
        // 2차 쿠폰 가입자 통계
        'phase2_applicants' => $phase2_person_stats['applicants'],
        'phase2_companions' => $phase2_person_stats['companions'],
        'phase2_total_persons' => $phase2_person_stats['total_persons'],
        'phase2_solo' => $phase2_person_stats['solo'],
        'phase2_with_one' => $phase2_person_stats['with_one'],
        'phase2_with_two' => $phase2_person_stats['with_two'],
        'phase2_with_three' => $phase2_person_stats['with_three']
    ];
    
    // 사용률 계산
    $stats['usage_rate'] = $total_coupons > 0 
        ? round(($used_coupons / $total_coupons) * 100, 1) 
        : 0;
    
    $max_possible_applications = $total_coupons * 2;
    $stats['full_usage_rate'] = $max_possible_applications > 0 
        ? round(($total_applications / $max_possible_applications) * 100, 1) 
        : 0;
    
    $stats['available_uses'] = ($unused_coupons * 2) + $once_used_coupons;
    
    // === 실제 신청서 리스트 조회 (정상만) ===
    
    // 총 신청서 개수 (정상만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as actual_total_applications 
        FROM holeinone_applications 
        WHERE client_id = ? AND summary = '1'
    ");
    $stmt->execute([$client_id]);
    $actual_total_applications = $stmt->fetch()['actual_total_applications'] ?? 0;
    
    // 페이징을 위한 총 페이지 수 계산
    $total_pages = $actual_total_applications > 0 ? ceil($actual_total_applications / $limit) : 1;
    
    // 가입신청 내역 조회 (정상만)
    $stmt = $pdo->prepare("
        SELECT 
            ha.id,
            ha.coupon_number,
            ha.applicant_name, 
            ha.applicant_phone,
            ha.golf_course, 
            ha.tee_time, 
            ha.created_at,
            ha.summary,
            ha.summary_time,
            ha.summary_damdanga,
            ha.status,
            ha.testIs,
            COALESCE((SELECT COUNT(*) FROM holeinone_companions WHERE application_id = ha.id), 0) as companion_count
        FROM holeinone_applications ha
        WHERE ha.client_id = ? 
        AND ha.summary = '1'
        ORDER BY ha.created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$client_id, $limit, $offset]);
    $applications_raw = $stmt->fetchAll();
    
    // 암호화된 개인정보 복호화
    $applications = [];
    foreach ($applications_raw as $app) {
        $decrypted_name = decryptData($app['applicant_name']);
        $decrypted_phone = decryptData($app['applicant_phone']);
        
        $canEdit = ($app['summary'] == '1');
        $summaryStatus = '정상';
        
        $testIs = $app['testIs'] ?? '1';
        $companion_count = (int)($app['companion_count'] ?? 0);
        
        $applications[] = [
            'id' => $app['id'],
            'signupId' => 'ACE' . str_pad($app['id'], 7, '0', STR_PAD_LEFT),
            'couponNumber' => $app['coupon_number'],
            'coupon_number' => $app['coupon_number'],
            'applicant_name' => $decrypted_name ?: '복호화 실패',
            'applicantName' => $decrypted_name ?: '복호화 실패',
            'applicant_phone' => $decrypted_phone ?: '복호화 실패',
            'applicantPhone' => $decrypted_phone ?: '복호화 실패',
            'golf_course' => $app['golf_course'],
            'golfCourse' => $app['golf_course'],
            'tee_time' => $app['tee_time'],
            'teeTime' => $app['tee_time'],
            'created_at' => $app['created_at'],
            'createdAt' => $app['created_at'],
            'summary' => $app['summary'],
            'summaryStatus' => $summaryStatus,
            'summaryTime' => $app['summary_time'],
            'summary_time' => $app['summary_time'],
            'summaryDamdanga' => $app['summary_damdanga'],
            'summary_damdanga' => $app['summary_damdanga'],
            'canEdit' => $canEdit,
            'status' => $app['status'],
            'testIs' => $testIs,
            'test_is' => $testIs,
            'companionCount' => $companion_count,
            'companion_count' => $companion_count,
            'hasCompanions' => $companion_count > 0
        ];
    }
    
    // 최근 로그인 기록 (5개)
    $stmt = $pdo->prepare("
        SELECT login_ip, login_time 
        FROM holeinone_login_logs 
        WHERE client_id = ? 
        ORDER BY login_time DESC 
        LIMIT 5
    ");
    $stmt->execute([$client_id]);
    $recent_logins = $stmt->fetchAll();
    
    // 페이징 정보 구성
    $pagination = [
        'currentPage' => $page,
        'totalPages' => $total_pages,
        'totalItems' => $actual_total_applications,
        'totalCount' => $actual_total_applications,
        'itemsPerPage' => $limit,
        'hasNext' => $page < $total_pages,
        'hasPrev' => $page > 1,
        'startItem' => $actual_total_applications > 0 ? ($page - 1) * $limit + 1 : 0,
        'endItem' => min($page * $limit, $actual_total_applications)
    ];
    
    // 응답 데이터 구성
    $response_data = [
        'user' => [
            'client_id' => $client_id,
            'client_name' => $client_name,
            'manager_name' => $manager_name,
            'total_quota' => $total_quota
        ],
        'stats' => $stats,
        'recent_applications' => $applications,
        'pagination' => $pagination,
        'recent_logins' => $recent_logins,
        'last_updated' => date('Y-m-d H:i:s')
    ];
    
    sendResponse(true, '데이터 조회 성공', $response_data);
    
} catch (Exception $e) {
    error_log("Dashboard API Error: " . $e->getMessage());
    sendResponse(false, '데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage());
}
?>
