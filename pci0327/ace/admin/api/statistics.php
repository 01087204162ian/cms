<?php
/**
 * 통계 데이터 API
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
        'errorCode' => $errorCode
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
    
    $client_id = $_SESSION['client_id'];
    $type = $_GET['type'] ?? '';
    $year = (int)($_GET['year'] ?? date('Y'));
    
    if ($type === 'daily') {
        $month = (int)($_GET['month'] ?? date('n'));
        $data = getDailyStatistics($pdo, $client_id, $year, $month);
        sendResponse(true, "일일 통계 조회 성공", $data);
    } elseif ($type === 'monthly') {
        $data = getMonthlyStatistics($pdo, $client_id, $year);
        sendResponse(true, "월별 통계 조회 성공", $data);
    } else {
        sendResponse(false, '잘못된 요청 타입입니다.', null, 'INVALID_TYPE');
    }
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Statistics API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // 개발 환경에서는 자세한 에러 메시지, 운영 환경에서는 일반적인 메시지
    $error_message = (defined('DEBUG') && DEBUG) ? 
        '통계 데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage() :
        '통계 데이터를 불러오는 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    
    sendResponse(false, $error_message, null, 'SERVER_ERROR');
}

/**
 * 일일 통계 데이터 조회
 */
function getDailyStatistics($pdo, $client_id, $year, $month) {
    // 데이터 시작일: 2025-08-11
    $dataStartDate = '2025-08-11';
    
    // 해당 월의 첫날과 마지막날 계산
    $monthFirstDay = sprintf('%04d-%02d-01', $year, $month);
    $lastDay = date('t', strtotime($monthFirstDay)); // 해당 월의 마지막 날
    $monthLastDay = sprintf('%04d-%02d-%02d', $year, $month, $lastDay);
    
    // 실제 조회 시작일과 종료일 결정
    $startDate = $monthFirstDay;
    $endDate = $monthLastDay;
    
    // 2025년 8월인 경우 11일부터 시작
    if ($year == 2025 && $month == 8) {
        $startDate = $dataStartDate;
    }
    // 2025년 8월 이전인 경우 데이터 없음
    elseif (($year < 2025) || ($year == 2025 && $month < 8)) {
        return [
            'summary' => [
                'total_count' => 0,
                'daily_average' => 0,
                'max_count' => 0,
                'active_days' => 0,
                'month_days' => $lastDay
            ],
            'daily_data' => []
        ];
    }
    
    // 일별 신청 건수 조회 (정상 처리된 건만 포함)
    $sql = "SELECT 
                DAY(created_at) as day,
                COUNT(*) as count
            FROM holeinone_applications 
            WHERE client_id = ? 
                AND DATE(created_at) >= ? 
                AND DATE(created_at) <= ?
                AND summary = '1'
            GROUP BY DAY(created_at)
            ORDER BY DAY(created_at)";
    
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        throw new Exception("일일 통계 쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
    }
    
    $result = $stmt->execute([
        $client_id,
        $startDate,
        $endDate
    ]);
    
    if (!$result) {
        throw new Exception("일일 통계 쿼리 실행 실패: " . implode(", ", $stmt->errorInfo()));
    }
    
    $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 요약 통계 계산
    $totalCount = 0;
    $maxCount = 0;
    $activeDays = 0;
    
    foreach ($dailyData as $day) {
        $count = (int)$day['count'];
        $totalCount += $count;
        if ($count > $maxCount) {
            $maxCount = $count;
        }
        if ($count > 0) {
            $activeDays++;
        }
    }
    
    $dailyAverage = $lastDay > 0 ? $totalCount / $lastDay : 0;
    
    return [
        'summary' => [
            'total_count' => $totalCount,
            'daily_average' => round($dailyAverage, 2),
            'max_count' => $maxCount,
            'active_days' => $activeDays,
            'month_days' => $lastDay
        ],
        'daily_data' => $dailyData
    ];
}

/**
 * 월별 통계 데이터 조회
 */
function getMonthlyStatistics($pdo, $client_id, $year) {
    // 2025년 이전인 경우 데이터 없음
    if ($year < 2025) {
        return [
            'summary' => [
                'total_count' => 0,
                'monthly_average' => 0,
                'max_count' => 0,
                'active_months' => 0
            ],
            'monthly_data' => []
        ];
    }
    
    // 해당 연도의 월별 신청 건수 조회 (정상 처리된 건만 포함)
    $sql = "SELECT 
                MONTH(created_at) as month,
                COUNT(*) as count
            FROM holeinone_applications 
            WHERE client_id = ? 
                AND YEAR(created_at) = ?
                AND DATE(created_at) >= '2025-08-11'
                AND summary = '1'
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at)";
    
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        throw new Exception("월별 통계 쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
    }
    
    $result = $stmt->execute([
        $client_id,
        $year
    ]);
    
    if (!$result) {
        throw new Exception("월별 통계 쿼리 실행 실패: " . implode(", ", $stmt->errorInfo()));
    }
    
    $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 요약 통계 계산
    $totalCount = 0;
    $maxCount = 0;
    $activeMonths = 0;
    
    foreach ($monthlyData as $month) {
        $count = (int)$month['count'];
        $totalCount += $count;
        if ($count > $maxCount) {
            $maxCount = $count;
        }
        if ($count > 0) {
            $activeMonths++;
        }
    }
    
    // 2025년인 경우 8월부터 계산, 다른 년도는 12개월 기준
    $monthsForAverage = ($year == 2025) ? (12 - 7) : 12; // 8월부터 12월까지 5개월
    $monthlyAverage = $monthsForAverage > 0 ? $totalCount / $monthsForAverage : 0;
    
    return [
        'summary' => [
            'total_count' => $totalCount,
            'monthly_average' => round($monthlyAverage, 2),
            'max_count' => $maxCount,
            'active_months' => $activeMonths
        ],
        'monthly_data' => $monthlyData
    ];
}
?>