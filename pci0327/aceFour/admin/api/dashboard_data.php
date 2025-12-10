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
    $limit = max(5, min(50, intval($_GET['limit'] ?? 20))); // 기본 20개, 최대 50개
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
    
    // *** 쿠폰 기준 총 신청 건수 (used_count의 합계) ***
    $stmt = $pdo->prepare("
        SELECT SUM(used_count) as total_applications 
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs IN ('1', '3')
    ");
    $stmt->execute([$client_id]);
    $coupon_based_applications = $stmt->fetch()['total_applications'] ?? 0;
    
    // === 오늘 신청 건수 계산 ===
    $today = date('Y-m-d');
    
    // 오늘 가입신청 건수 (리얼 데이터만)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as today_applications
        FROM holeinone_applications ha
        INNER JOIN holeinone_coupons hc ON ha.coupon_number = hc.coupon_number
        WHERE hc.client_id = ? AND hc.testIs IN ('1', '3')
        AND DATE(ha.created_at) = ?
    ");
    $stmt->execute([$client_id, $today]);
    $today_applications = $stmt->fetch()['today_applications'] ?? 0;
    
    // 오늘 홀인원 보상신청 건수 (현재는 0)
    $today_claims = 0;
    
    // === 차수별 통계 (1차/2차 구분) ===
    // === 차수별 상세 통계 (1차/2차 구분) ===
	$stmt = $pdo->prepare("
		SELECT 
			testIs,
			COUNT(*) as total,
			COUNT(CASE WHEN used_count = 0 THEN 1 END) as unused,
			COUNT(CASE WHEN used_count = 1 THEN 1 END) as once,
			COUNT(CASE WHEN used_count = 2 THEN 1 END) as full,
			SUM(used_count) as applications
		FROM holeinone_coupons 
		WHERE client_id = ? AND testIs IN ('1', '3')
		GROUP BY testIs
	");
	$stmt->execute([$client_id]);
	$phase_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$phase_stats = [
		'phase1' => ['coupons' => 0, 'unused' => 0, 'once' => 0, 'full' => 0, 'applications' => 0],
		'phase2' => ['coupons' => 0, 'unused' => 0, 'once' => 0, 'full' => 0, 'applications' => 0]
	];

	foreach ($phase_data as $row) {
		if ($row['testIs'] === '1') {
			$phase_stats['phase1'] = [
				'coupons' => (int)$row['total'],
				'unused' => (int)$row['unused'],
				'once' => (int)$row['once'],
				'full' => (int)$row['full'],
				'applications' => (int)$row['applications']
			];
		} elseif ($row['testIs'] === '3') {
			$phase_stats['phase2'] = [
				'coupons' => (int)$row['total'],
				'unused' => (int)$row['unused'],
				'once' => (int)$row['once'],
				'full' => (int)$row['full'],
				'applications' => (int)$row['applications']
			];
		}
	}
    
    // 통계 계산 (쿠폰 기준)
    $stats = [
        'total_coupons' => (int)$total_coupons,
        'unused_coupons' => (int)$unused_coupons,
        'used_coupons' => (int)$used_coupons,
        'once_used_coupons' => (int)$once_used_coupons,
        'fully_used_coupons' => (int)$fully_used_coupons,
        'total_applications' => (int)$coupon_based_applications,
        'today_applications' => (int)$today_applications,
        'today_claims' => (int)$today_claims,
        'phase1_coupons' => $phase_stats['phase1']['coupons'],
		'phase1_unused' => $phase_stats['phase1']['unused'],    // 추가
		'phase1_once' => $phase_stats['phase1']['once'],        // 추가
		'phase1_full' => $phase_stats['phase1']['full'],        // 추가
        'phase2_coupons' => $phase_stats['phase2']['coupons'],
		 'phase2_unused' => $phase_stats['phase2']['unused'],    // 추가
		'phase2_once' => $phase_stats['phase2']['once'],        // 추가
		'phase2_full' => $phase_stats['phase2']['full'],        // 추가
        'phase1_applications' => $phase_stats['phase1']['applications'],
        'phase2_applications' => $phase_stats['phase2']['applications']
    ];
    
    // 사용률 계산
    $stats['usage_rate'] = $total_coupons > 0 
        ? round(($used_coupons / $total_coupons) * 100, 1) 
        : 0;
    
    $max_possible_applications = $total_coupons * 2;
    $stats['full_usage_rate'] = $max_possible_applications > 0 
        ? round(($coupon_based_applications / $max_possible_applications) * 100, 1) 
        : 0;
    
    $stats['available_uses'] = ($unused_coupons * 2) + $once_used_coupons;
    
    // 테스트 환경용 통계
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_test_coupons,
            COUNT(CASE WHEN is_used = 1 THEN 1 END) as used_test_coupons,
            SUM(used_count) as total_test_applications
        FROM holeinone_coupons 
        WHERE client_id = ? AND testIs = '2'
    ");
    $stmt->execute([$client_id]);
    $test_result = $stmt->fetch();
    
    $test_stats = [
        'total_test_coupons' => (int)($test_result['total_test_coupons'] ?? 0),
        'used_test_coupons' => (int)($test_result['used_test_coupons'] ?? 0),
        'total_test_applications' => (int)($test_result['total_test_applications'] ?? 0)
    ];
    
    // === 실제 신청서 리스트 조회 (holeinone_applications) ===
    
    // 총 신청서 개수 (실제 테이블 기준)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as actual_total_applications 
        FROM holeinone_applications 
        WHERE client_id = ?
    ");
    $stmt->execute([$client_id]);
    $actual_total_applications = $stmt->fetch()['actual_total_applications'] ?? 0;
    
    // 페이징을 위한 총 페이지 수 계산 (실제 신청서 기준)
    $total_pages = ceil($actual_total_applications / $limit);
    
    // 가입신청 내역 조회 (holeinone_applications에서 실제 데이터)
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
            hc.testIs,
            COALESCE((SELECT COUNT(*) FROM holeinone_companions WHERE application_id = ha.id), 0) as companion_count
        FROM holeinone_applications ha
        LEFT JOIN holeinone_coupons hc ON ha.coupon_number = hc.coupon_number
        WHERE ha.client_id = ? 
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
        
        // summary 상태별 수정 가능 여부
        $canEdit = ($app['summary'] == '1');
        $summaryStatus = ($app['summary'] == '1') ? '정상' : (($app['summary'] == '2') ? '취소' : '알 수 없음');
        
        $applications[] = [
            'id' => $app['id'],
            'signupId' => 'ACE' . str_pad($app['id'], 7, '0', STR_PAD_LEFT),
            'couponNumber' => $app['coupon_number'],
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
            'summaryDamdanga' => $app['summary_damdanga'],
            'canEdit' => $canEdit,
            'status' => $app['status'],
            'testIs' => $app['testIs'] ?? '1',
            'companionCount' => (int)($app['companion_count'] ?? 0),
            'hasCompanions' => ((int)($app['companion_count'] ?? 0)) > 0
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
    
    // 페이징 정보 구성 (실제 신청서 기준)
    $pagination = [
        'currentPage' => $page,
        'totalPages' => $total_pages,
        'totalItems' => $actual_total_applications,
        'itemsPerPage' => $limit,
        'hasNext' => $page < $total_pages,
        'hasPrev' => $page > 1,
        'startItem' => ($page - 1) * $limit + 1,
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
        'test_stats' => $test_stats,
        'recent_applications' => $applications,
        'pagination' => $pagination,'recent_logins' => $recent_logins,
        'last_updated' => date('Y-m-d H:i:s'),
        'environment' => [
            'real_data_count' => $stats['total_coupons'],
            'test_data_count' => $test_stats['total_test_coupons']
        ],
        'meta' => [
            'coupon_based_applications' => $stats['total_applications'],
            'actual_applications_count' => $actual_total_applications,
            'display_applications' => count($applications),
            'data_note' => '통계는 쿠폰 기준, 리스트는 실제 신청서 기준'
        ]
    ];
    
    sendResponse(true, '데이터 조회 성료', $response_data);
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Dashboard API Error: " . $e->getMessage());
    sendResponse(false, '데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage());
}
?>