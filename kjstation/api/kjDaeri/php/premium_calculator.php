<?php
/**
 * 보험료 계산 모듈
 * 
 * 월 보험료 및 엔도스 보험료 계산 함수
 */

/**
 * 일할 계산된 월 보험료 계산
 * 
 * @param float $monthly_premium1 기본 월 보험료
 * @param float $monthly_premium2 특약 월 보험료
 * @param float $monthly_premium_total 총 월 보험료
 * @param int $insuranceCompany 보험회사 코드
 * @param string $dueDay 정기결제일
 * @param string $eDay 엔도스일(일)
 * @param string $eMonth 엔도스일(월)
 * @param string $eYear 엔도스일(년)
 * @param float $personRate2 개인 할인/할증률
 * @param int $etag 보험 유형 (1: 대리, 2: 탁송, 3: 대리렌트, 4: 탁송렌트)
 * @return array 일할 계산된 보험료 정보
 */
function calculateProRatedFee($monthly_premium1, $monthly_premium2, $monthly_premium_total, $insuranceCompany, $dueDay, $eDay, $eMonth, $eYear, $personRate2, $etag) {
    // 입력값이 비어있는 경우 기본값 설정
    $monthly_premium1 = !empty($monthly_premium1) ? $monthly_premium1 : 0;
    $monthly_premium2 = !empty($monthly_premium2) ? $monthly_premium2 : 0;
    $monthly_premium_total = !empty($monthly_premium_total) ? $monthly_premium_total : ($monthly_premium1 + $monthly_premium2);
    
    // 개인 할인/할증 적용
    $fee1 = $monthly_premium1 * $personRate2;
    $fee2 = $monthly_premium2 * $personRate2;
    $fee3 = $monthly_premium_total * $personRate2;
    
    // 보험 유형(etag)에 따른 추가 계산 (필요시 구현)
    
    // 해당 월의 마지막 일자
    $lastDayOfMonth = date('t', mktime(0, 0, 0, $eMonth, 1, $eYear));
    
    // 다음 정기결제일 계산
    $nextDueDay = $dueDay;
    if ($eDay < $dueDay) {
        // 엔도스일이 정기결제일보다 이전이면 같은 달의 정기결제일
        $nextDueDay = $dueDay;
    } else {
        // 엔도스일이 정기결제일 이후면 다음 달의 정기결제일
        $nextMonth = $eMonth + 1;
        $nextYear = $eYear;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }
        $lastDayOfNextMonth = date('t', mktime(0, 0, 0, $nextMonth, 1, $nextYear));
        $nextDueDay = min($dueDay, $lastDayOfNextMonth);
    }
    
    // 일수 계산
    $totalDays = $lastDayOfMonth;
    $daysLeft = 0;
    
    if ($eDay <= $dueDay) {
        // 엔도스일이 당월 정기결제일보다 이전이거나 같은 경우
        $daysLeft = ($dueDay - $eDay);
    } else {
        // 엔도스일이 당월 정기결제일 이후인 경우, 다음 달 정기결제일까지 계산
        $daysLeft = ($lastDayOfMonth - $eDay) + $nextDueDay;
    }
    
    // 일할 계산
    $dailyFee = $fee3 / $totalDays;
    $proRatedFee = round($dailyFee * $daysLeft);
    
    // 결과 반환
    return array(
        'monthlyFee1' => $fee1,
        'monthlyFee2' => $fee2,
        'monthlyFee3' => $fee3,
        'insurance' => $insuranceCompany,
        'dueDay' => $dueDay,
        'startDay' => $eDay,
        'month' => $eMonth,
        'year' => $eYear,
        'proRatedFee' => $proRatedFee,
        'totalDays' => $totalDays,
        'lastDayOfMonth' => $lastDayOfMonth,
        'nextDueDay' => $nextDueDay,
        'daysLeft' => $daysLeft,
        'dailyFee' => $dailyFee,
        'rate' => $personRate2
    );
}

/**
 * 엔도스 보험료 계산
 * 
 * @param string $startDay 보험 시작일
 * @param string $endorse_day 엔도스일
 * @param int $nabang 납입 횟수
 * @param int $nabang_1 납입 회차
 * @param float $payment10_premium1 납입 기본 보험료
 * @param float $payment10_premium2 납입 특약 보험료
 * @param float $payment10_premium_total 납입 총 보험료
 * @param int $insuranceCompany 보험회사 코드
 * @param float $personRate2 개인 할인/할증률
 * @param int $etag 보험 유형
 * @return array 엔도스 보험료 정보
 */
function calculateEndorsePremium($startDay, $endorse_day, $nabang, $nabang_1, $payment10_premium1, $payment10_premium2, $payment10_premium_total, $insuranceCompany, $personRate2, $etag) {
    // 입력값이 비어있는 경우 기본값 설정
    $nabang = !empty($nabang) ? $nabang : 10;
    $nabang_1 = !empty($nabang_1) ? $nabang_1 : 1;
    $payment10_premium1 = !empty($payment10_premium1) ? $payment10_premium1 : 0;
    $payment10_premium2 = !empty($payment10_premium2) ? $payment10_premium2 : 0;
    $payment10_premium_total = !empty($payment10_premium_total) ? $payment10_premium_total : ($payment10_premium1 + $payment10_premium2);
    
    // 개인 할인/할증 적용
    $payment10_premium1 = $payment10_premium1 * $personRate2;
    $payment10_premium2 = $payment10_premium2 * $personRate2;
    $payment10_premium_total = $payment10_premium_total * $personRate2;
    
    // 연간 보험료 계산
    $yearPremium = $payment10_premium_total * $nabang;
    
    // 하루 보험료 계산
    $oneDayPremium = round($yearPremium / 365);
    
    // 시작일과 엔도스일 사이의 일수 계산
    $start_timestamp = strtotime($startDay);
    $endorse_timestamp = strtotime($endorse_day);
    $diff_days = round(($endorse_timestamp - $start_timestamp) / (60 * 60 * 24));
    
    // 경과된 보험료 계산
    $expired_period_premium = $oneDayPremium * $diff_days;
    
    // 남은 기간 (365일 - 경과일)
    $unexpired_period = 365 - $diff_days;
    
    // 미경과 보험료 계산
    $unexpired_period_premium = $yearPremium - $expired_period_premium;
    
    // 다음 회차 보험료 계산
    $daum_premium = $payment10_premium_total * (1 + $nabang_1);
    
    // 최종 엔도스 보험료 계산
    $i_endorese_premium = $unexpired_period_premium - $daum_premium;
    if ($i_endorese_premium < 0) {
        $i_endorese_premium = 0;
    }
    
    // 결과 반환
    return array(
        'payment10_premium1' => $payment10_premium1,
        'payment10_premium2' => $payment10_premium2,
        'payment10_premium_total' => $payment10_premium_total,
        'endorse_day' => $endorse_day,
        'nabang' => $nabang,
        'nabang_1' => $nabang_1,
        'yearPremium' => $yearPremium,
        'oneDayPremium' => $oneDayPremium,
        'unexpired_period_premium' => $unexpired_period_premium,
        'unexpired_period' => $unexpired_period,
        'daum_premium' => $daum_premium,
        'i_endorese_premium' => $i_endorese_premium,
        'InsuraneCompany' => $insuranceCompany,
        'personRate2' => $personRate2,
        'etag' => $etag
    );
}
?>