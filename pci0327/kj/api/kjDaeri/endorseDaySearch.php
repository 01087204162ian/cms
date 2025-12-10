<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기
    $pdo = getDbConnection();
    
    // 입력값 검증 (예시 - 실제 상황에 맞게 수정 필요)
    $dNum = $_POST['dNum'] ?? $_GET['dNum'] ?? null;
    
    if (empty($dNum)) {
        throw new Exception('대리운전 회사 번호가 필요합니다.');
    }
    
    // 대리운전 회사 정보 조회
    $sql = "SELECT company, FirstStart, MemberNum, jumin, hphone, cNumber 
            FROM 2012DaeriCompany 
            WHERE num = :dNum";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dNum', $dNum, PDO::PARAM_STR);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        throw new Exception('대리운전 회사 정보를 찾을 수 없습니다.');
    }
    
    // 정기결제일 분리
    if (!empty($row['FirstStart'])) {
        [$duYear, $duMonth, $dueDay] = explode('-', $row['FirstStart'], 3);
        $dueDay = (int)$dueDay;
    } else {
        throw new Exception('정기결제일 정보가 없습니다.');
    }
    
    // 현재 날짜 정보
    $currentDate = new DateTime();
    $currentYear = $currentDate->format('Y');
    $currentMonth = $currentDate->format('m');
    
    // 현재 달의 마지막 날짜
    $currentMonthLastDay = (int)$currentDate->format('t');
    
    // 현재 달의 정기결제일 계산
    $thisMonthDueDay = min($dueDay, $currentMonthLastDay);
    $thisMonthDueDate = new DateTime("$currentYear-$currentMonth-$thisMonthDueDay");
    
    // 이전 달 정보
    $lastMonthDate = clone $currentDate;
    $lastMonthDate->modify('-1 month');
    $lastMonthYear = $lastMonthDate->format('Y');
    $lastMonthMonth = $lastMonthDate->format('m');
    $lastMonthLastDay = (int)$lastMonthDate->format('t');
    
    // 이전 달의 정기결제일 계산
    $lastMonthDueDay = min($dueDay, $lastMonthLastDay);
    $lastMonthDueDate = new DateTime("$lastMonthYear-$lastMonthMonth-$lastMonthDueDay");
    
    // 특별 케이스 확인: 결제일이 해당 달의 마지막 날과 같거나 큰 경우
    $isLastDayOfMonth = ($dueDay >= $currentMonthLastDay);
    
    // 결제 시작일 계산
    if ($isLastDayOfMonth) {
        // 특별한 경우: 현재 달의 1일로 설정
        $paymentStartDate = new DateTime("$currentYear-$currentMonth-01");
    } else {
        // 일반적인 경우: 이전 달 결제일 다음날
        $paymentStartDate = clone $lastMonthDueDate;
        $paymentStartDate->modify('+1 day');
    }
    
    // 응답 데이터 구성
    $response = [
        'success' => true,
        'company' => $row['company'],
        'dueDay' => $dueDay,
        'jumin' => $row['jumin'],
        'cNumber' => $row['cNumber'],
        'thisMonthDueDate' => $thisMonthDueDate->format('Y-m-d'),
        'lastMonthDueDate' => $paymentStartDate->format('Y-m-d'),
        'paymentStartDate' => $paymentStartDate->format('Y-m-d'),
        'paymentPeriod' => $paymentStartDate->format('Y-m-d') . ' ~ ' . $thisMonthDueDate->format('Y-m-d'),
        'currentDate' => $currentDate->format('Y-m-d')
    ];
    
    // JSON 응답 출력
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 에러 응답
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

// PDO는 자동으로 연결이 닫히므로 명시적으로 닫을 필요 없음
?>