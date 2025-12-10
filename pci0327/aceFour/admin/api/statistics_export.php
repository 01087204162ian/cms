<?php
/**
 * 통계 데이터 엑셀 내보내기 API
 * PCI Korea 홀인원보험 관리 시스템
 */

session_start();
require_once '../../../api/config/db_config.php';

// 응답 함수
function sendResponse($success, $message, $data = null, $errorCode = null) {
    header('Content-Type: application/json; charset=utf-8');
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
        exportDailyStatistics($pdo, $client_id, $year, $month);
    } elseif ($type === 'monthly') {
        exportMonthlyStatistics($pdo, $client_id, $year);
    } else {
        sendResponse(false, '잘못된 요청 타입입니다.', null, 'INVALID_TYPE');
    }
    
} catch (Exception $e) {
    error_log('Statistics Export Error: ' . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    sendResponse(false, '파일 생성에 실패했습니다: ' . $e->getMessage(), null, 'EXPORT_ERROR');
}

/**
 * 일일 통계 엑셀 내보내기
 */
function exportDailyStatistics($pdo, $client_id, $year, $month) {
    // 데이터 시작일: 2025-08-11
    $dataStartDate = '2025-08-11';
    
    // 해당 월의 첫날과 마지막날 계산
    $monthFirstDay = sprintf('%04d-%02d-01', $year, $month);
    $lastDay = date('t', strtotime($monthFirstDay));
    $monthLastDay = sprintf('%04d-%02d-%02d', $year, $month, $lastDay);
    
    // 실제 조회 시작일과 종료일 결정
    $startDate = $monthFirstDay;
    $endDate = $monthLastDay;
    
    // 2025년 8월인 경우 11일부터 시작
    if ($year == 2025 && $month == 8) {
        $startDate = $dataStartDate;
    }
    
    // 일별 데이터 조회
    $sql = "SELECT 
                DAY(created_at) as day,
                COUNT(*) as count
            FROM holeinone_applications 
            WHERE client_id = ? 
                AND DATE(created_at) >= ? 
                AND DATE(created_at) <= ?
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
    
    // 데이터를 배열로 재구성 (모든 날짜 포함)
    $dataMap = [];
    
    // 조회된 데이터를 맵으로 변환
    foreach ($dailyData as $row) {
        $dataMap[$row['day']] = (int)$row['count'];
    }
    
    // 전체 통계
    $totalCount = array_sum($dataMap);
    $maxCount = $dataMap ? max($dataMap) : 0;
    $activeDays = count(array_filter($dataMap));
    $dailyAverage = $lastDay > 0 ? $totalCount / $lastDay : 0;
    
    // 요일 배열
    $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
    
    // 헤더 설정
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="일일현황_' . $year . '년' . $month . '월.xlsx"');
    header('Cache-Control: max-age=0');
    
    // CSV 형태로 출력 (Excel에서 읽을 수 있도록)
    $output = fopen('php://output', 'w');
    
    // BOM for UTF-8
    fwrite($output, "\xEF\xBB\xBF");
    
    // 제목
    fputcsv($output, [$year . '년 ' . $month . '월 일일 신청 현황'], ',');
    fputcsv($output, [''], ','); // 빈 줄
    
    // 요약 정보
    fputcsv($output, ['요약 정보'], ',');
    fputcsv($output, ['총 신청건수', number_format($totalCount) . '건'], ',');
    fputcsv($output, ['일평균', number_format($dailyAverage, 1) . '건'], ',');
    fputcsv($output, ['최대 신청일', number_format($maxCount) . '건'], ',');
    fputcsv($output, ['신청 있는 날', $activeDays . '일'], ',');
    fputcsv($output, [''], ','); // 빈 줄
    
    // 테이블 헤더
    fputcsv($output, ['일자', '요일', '신청건수', '비율(%)'], ',');
    
    // 각 날짜별 데이터
    for ($day = 1; $day <= $lastDay; $day++) {
        $date = new DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
        $dayOfWeek = $weekdays[(int)$date->format('w')];
        $count = $dataMap[$day] ?? 0;
        $percentage = $totalCount > 0 ? ($count / $totalCount * 100) : 0;
        
        // 2025년 8월 11일 이전은 데이터 없음 표시
        $isBeforeData = false;
        if ($year == 2025 && $month == 8 && $day < 11) {
            $isBeforeData = true;
        } else if ($year < 2025 || ($year == 2025 && $month < 8)) {
            $isBeforeData = true;
        }
        
        fputcsv($output, [
            $day . '일',
            $dayOfWeek,
            $isBeforeData ? '-' : $count,
            $isBeforeData ? '-' : number_format($percentage, 1)
        ], ',');
    }
    
    // 안내 메시지 추가
    if ($year == 2025 && $month == 8) {
        fputcsv($output, [''], ','); // 빈 줄
        fputcsv($output, ['※ 시스템 데이터는 2025년 8월 11일부터 수집 시작'], ',');
    } else if ($year < 2025 || ($year == 2025 && $month < 8)) {
        fputcsv($output, [''], ','); // 빈 줄
        fputcsv($output, ['※ 해당 기간에는 데이터가 없습니다 (시스템 데이터는 2025년 8월부터)'], ',');
    }
    
    fclose($output);
}

/**
 * 월별 통계 엑셀 내보내기
 */
function exportMonthlyStatistics($pdo, $client_id, $year) {
    // 월별 데이터 조회 (2025년 8월부터)
    $sql = "SELECT 
                MONTH(created_at) as month,
                COUNT(*) as count
            FROM holeinone_applications 
            WHERE client_id = ? 
                AND YEAR(created_at) = ?
                AND DATE(created_at) >= '2025-08-11'
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
    
    // 데이터를 배열로 재구성
    $dataMap = [];
    foreach ($monthlyData as $row) {
        $dataMap[$row['month']] = (int)$row['count'];
    }
    
    // 전체 통계
    $totalCount = array_sum($dataMap);
    $maxCount = $dataMap ? max($dataMap) : 0;
    $activeMonths = count(array_filter($dataMap));
    $monthlyAverage = $totalCount / 12;
    
    // 월 이름 배열
    $monthNames = ['1월', '2월', '3월', '4월', '5월', '6월', 
                   '7월', '8월', '9월', '10월', '11월', '12월'];
    
    // 헤더 설정
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="월별현황_' . $year . '년.xlsx"');
    header('Cache-Control: max-age=0');
    
    // CSV 형태로 출력
    $output = fopen('php://output', 'w');
    
    // BOM for UTF-8
    fwrite($output, "\xEF\xBB\xBF");
    
    // 제목
    fputcsv($output, [$year . '년 월별 신청 현황'], ',');
    fputcsv($output, [''], ','); // 빈 줄
    
    // 요약 정보
    fputcsv($output, ['요약 정보'], ',');
    fputcsv($output, ['연간 총 신청', number_format($totalCount) . '건'], ',');
    fputcsv($output, ['월평균', number_format($monthlyAverage, 1) . '건'], ',');
    fputcsv($output, ['최대 신청월', number_format($maxCount) . '건'], ',');
    fputcsv($output, ['신청 있는 월', $activeMonths . '개월'], ',');
    fputcsv($output, [''], ','); // 빈 줄
    
    // 테이블 헤더
    fputcsv($output, ['월', '신청건수', '비율(%)', '전월대비'], ',');
    
    // 각 월별 데이터
    $previousCount = 0;
    for ($month = 1; $month <= 12; $month++) {
        $count = $dataMap[$month] ?? 0;
        $percentage = $totalCount > 0 ? ($count / $totalCount * 100) : 0;
        
        // 2025년 8월 이전은 데이터 없음
        $isBeforeData = false;
        if ($year == 2025 && $month < 8) {
            $isBeforeData = true;
        } else if ($year < 2025) {
            $isBeforeData = true;
        }
        
        // 전월 대비 계산
        $compareText = '-';
        if (!$isBeforeData && $previousCount > 0) {
            $diff = $count - $previousCount;
            $diffPercentage = ($diff / $previousCount * 100);
            if ($diff > 0) {
                $compareText = '+' . number_format($diff) . ' (+' . number_format($diffPercentage, 1) . '%)';
            } elseif ($diff < 0) {
                $compareText = number_format($diff) . ' (' . number_format($diffPercentage, 1) . '%)';
            } else {
                $compareText = '변화없음';
            }
        } elseif (!$isBeforeData && $count > 0 && ($month == 8 && $year == 2025)) {
            $compareText = '시작월';
        } elseif (!$isBeforeData && $previousCount === 0 && $count > 0) {
            $compareText = '신규';
        }
        
        fputcsv($output, [
            $monthNames[$month - 1],
            $isBeforeData ? '-' : $count,
            $isBeforeData ? '-' : number_format($percentage, 1),
            $isBeforeData ? '-' : $compareText
        ], ',');
        
        if (!$isBeforeData) {
            $previousCount = $count;
        }
    }
    
    // 안내 메시지 추가
    if ($year == 2025) {
        fputcsv($output, [''], ','); // 빈 줄
        fputcsv($output, ['※ 시스템 데이터는 2025년 8월부터 수집 시작'], ',');
    } else if ($year < 2025) {
        fputcsv($output, [''], ','); // 빈 줄
        fputcsv($output, ['※ 해당 연도에는 데이터가 없습니다 (시스템 데이터는 2025년부터)'], ',');
    }
    
    fclose($output);
}
?>