<?php
/**
 * 보험 배서 시 보험료 계산 함수
 * 
 * @param string $startDay 보험 시작일 (Y-m-d 형식)
 * @param string $endorse_day 배서일/가입일 (Y-m-d 형식)
 * @param int $nabang 분납 횟수
 * @param int $nabang_1 분납 회차
 * @param float $payment10_premium1  1/10 기본보험료 
 * @param float $payment10_premium2  1/10 특약보험료 
 * @param float $payment10_premium_total 1/10 합계보험료 (기본+특약 합계)
 * @param int $InsuraneCompany 보험회사 (3 kb, 4 현대 ...)
 * @param float $personRate2 할증계수 
 * @param int $etag (1대리,2탁송,3 대리/렌트 ,4 대리/탁송)
 * @return array 배서 시 납입해야 할 보험료 정보
 */
function calculateEndorsePremium(
    string $startDay, 
    string $endorse_day, 
    int $nabang, 
    int $nabang_1, 
    float $payment10_premium1, 
    float $payment10_premium2, 
    float $payment10_premium_total, 
    int $InsuraneCompany, 
    float $personRate2, 
    int $etag
): array {
    // 보험 종기 계산 (시작일로부터 1년)
    $endDay = date("Y-m-d", strtotime("$startDay +1 year -1 day"));
    
    // 경과기간과 미경과기간 계산 (일 단위)
    $start_timestamp = strtotime($startDay);
    $endorse_timestamp = strtotime($endorse_day);
    $end_timestamp = strtotime($endDay);
   
    $elapsed_period = ($endorse_timestamp - $start_timestamp) / 86400; // 초를 일로 변환
    
    // 미경과기간 계산 - PHP 8.2 호환 방식
    // DateTime 객체 사용
    $endorse_date = new DateTime($endorse_day);
    $end_date = new DateTime($endDay);
    $date_diff = $endorse_date->diff($end_date);
    
    // 일수 계산 및 당일 포함
    $unexpired_period = $date_diff->days + 1;
    
    // 년 보험료 계산
    $gibonYearPremium = $payment10_premium1 * 10; //1/10 보험료 10배 (기본보험료)
    $rentYearPremium = $payment10_premium2 * 10; //1/10 보험료 10배 (특약보험료)
    
    // 보험회사별 계산 로직
    if ($InsuraneCompany == 3) { //kb손보인 경우
        //kb 손보 렌트특약을 가입한 경우
        // 적용보험료= 기본보험료*렌트할인할증계수 + 렌트특약보험료
        if ($etag == 3 || $etag == 4) {
            $yearPremium = $gibonYearPremium * $personRate2 + $rentYearPremium;
        } else {
            $yearPremium = ($payment10_premium_total * 10) * $personRate2; // 10개월치 보험료를 연간으로 환산
        }
    } else {
        $yearPremium = ($payment10_premium_total * 10) * $personRate2; // 10개월치 보험료를 연간으로 환산
    }
    
    // 1일 보험료 계산
    $oneDayPremium = $yearPremium / 365;
    
    // 미경과 보험료 계산
    $unexpired_period_premium = $unexpired_period * $oneDayPremium;
    
    // 다음에 낼 보험료 계산 (분납 회차에 따라)
    $daum_premium = 0;
    if ($nabang == 10) { // 10분납인 경우
        // Match 식으로 간결하게 변경 (PHP 8.0 이상)
        $daum_premium = match ($nabang_1) {
            1 => $yearPremium * 0.8,  // 1회차까지 냈으면, 80% 남음
            2 => $yearPremium * 0.7,  // 2회차까지 냈으면, 70% 남음
            3 => $yearPremium * 0.6,  // 3회차까지 냈으면, 60% 남음
            4 => $yearPremium * 0.5,  // 4회차까지 냈으면, 50% 남음
            5 => $yearPremium * 0.4,  // 5회차까지 냈으면, 40% 남음
            6 => $yearPremium * 0.3,  // 6회차까지 냈으면, 30% 남음
            7 => $yearPremium * 0.2,  // 7회차까지 냈으면, 20% 남음
            8 => $yearPremium * 0.1,  // 8회차까지 냈으면, 10% 남음
            9 => $yearPremium * 0.05, // 9회차까지 냈으면, 5% 남음
            10 => 0,                  // 10회차까지 다 냈으면, 0% 남음
            default => 0,
        };
    }
    
    // 이번에 납입할 보험료 계산 = 미경과보험료 - 다음에 낼 보험료
    $i_endorese_premium = round($unexpired_period_premium - $daum_premium, -1);
    
    // 변수 초기화
    $insurance = "";  
    
    return [
        'payment10_premium1' => $payment10_premium1,
        'payment10_premium2' => $payment10_premium2,
        'payment10_premium_total' => $payment10_premium_total,
        'insurance' => $insurance,
        'nabang' => $nabang,
        'startDay' => $startDay,
        'endorse_day' => $endorse_day,
        'i_endorese_premium' => $i_endorese_premium,
        'nabang_1' => $nabang_1,
        'yearPremium' => $yearPremium,
        'oneDayPremium' => $oneDayPremium,
        'unexpired_period_premium' => $unexpired_period_premium, //미경과 보험료
        'daum_premium' => $daum_premium,  //다음번 분납부터 낼 보험료
        'unexpired_period' => $unexpired_period,
        'InsuraneCompany' => $InsuraneCompany,
        'personRate2' => $personRate2,
        'etag' => $etag
    ];
}

/**
 * 일할 계산으로 보험료를 산출하는 함수
 * PHP 8.2 버전 호환
 *
 * @param int $monthlyFee1 월 기본보험료
 * @param int $monthlyFee2 월 렌트보험료
 * @param int $monthlyFee3 월 기본+렌트=합계보험료
 * @param int $insurance 보험회사 4현대, 3kb
 * @param int $startDay 가입일 (1-31)
 * @param int $dueDay 정기 납부일 (1-31, 말일인 경우 31로 설정)
 * @param int $month 가입 월 (1-12)
 * @param int $year 가입 연도
 * @param float $rate 보험료 비율
 * @param int $etag 렌트특약 관련 태그
 * @return array 일할 계산된 보험료와 계산 관련 정보
 */
function calculateProRatedFee(
    int $monthlyFee1, 
    int $monthlyFee2, 
    int $monthlyFee3, 
    int $insurance, 
    int $dueDay, 
    int $startDay, 
    int $month, 
    int $year, 
    float $rate, 
    int $etag
): array {
    // DateTime 객체를 사용하여 해당 월의 마지막 날짜 계산
    $lastDayOfMonth = (int)(new DateTime("$year-$month-01"))->format('t');
    
    // 납부일이 31일이거나 "말일"로 지정된 경우 해당 월의 마지막 날짜로 설정
    $thisDueDay = $dueDay;
    if ($thisDueDay > $lastDayOfMonth || $thisDueDay == 31 || $thisDueDay == 0) {
        $thisDueDay = $lastDayOfMonth;
    }
    
    // 다음달 정보 계산
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $lastDayOfNextMonth = (int)(new DateTime("$nextYear-$nextMonth-01"))->format('t');
    
    // 다음달 납부일 계산 (다음달의 마지막 날짜보다 크면 마지막 날짜로 조정)
    $nextDueDay = $dueDay;
    if ($nextDueDay > $lastDayOfNextMonth || $nextDueDay == 31 || $nextDueDay == 0) {
        $nextDueDay = $lastDayOfNextMonth;
    }
    
    // 전월 정보 계산
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear = $month == 1 ? $year - 1 : $year;
    $lastDayOfPrevMonth = (int)(new DateTime("$prevYear-$prevMonth-01"))->format('t');
    
    // 전월 납부일 계산 (전월의 마지막 날짜보다 크면 마지막 날짜로 조정)
    $prevDueDay = $dueDay;
    if ($prevDueDay > $lastDayOfPrevMonth || $prevDueDay == 31 || $prevDueDay == 0) {
        $prevDueDay = $lastDayOfPrevMonth;
    }
    
    // 특수 케이스: 시작일이 납부일과 같은 경우
    if ($startDay == $thisDueDay) {
        // 납부일에 가입하는 경우 전체 보험료 적용 (새로운 주기 시작)
        $totalDays = 0; // 전체 일수는 사용하지 않음
        $daysLeft = 0;  // 남은 일수도 사용하지 않음
        
        // KB손보인 경우 특별 계산
        if ($insurance == 3 && ($etag == 3 || $etag == 4)) {
            $proRatedFee = $monthlyFee1 * $rate + $monthlyFee2;
        } else {
            $proRatedFee = $monthlyFee3 * $rate;
        }
        
        $proRatedFee = round($proRatedFee, -1); // 10원 단위 반올림
        $dailyFee = 0; // 사용하지 않음
    } else {
        // 일반 케이스: 일할 계산 수행
        if ($startDay < $thisDueDay) {
            // 시작일이 납부일보다 작은 경우 (예: 납부일 5일, 오늘 3일)
            // 전월 납부일부터 당월 납부일 전날까지의 일수
            $daysPrevMonth = $lastDayOfPrevMonth - $prevDueDay + 1; // 전월 납부일부터 전월 말일까지
            $daysThisMonth = $thisDueDay - 1; // 당월 1일부터 당월 납부일 전날까지
            $totalDays = $daysPrevMonth + $daysThisMonth;
            
            // 남은 일수: 시작일부터 당월 납부일 전날까지
            $daysLeft = $thisDueDay - 1 - $startDay + 1;
        } else {
            // 시작일이 납부일보다 큰 경우 (예: 납부일 5일, 오늘 6일)
            // 당월 납부일부터 다음달 납부일 전날까지의 일수
            $daysThisMonth = $lastDayOfMonth - $thisDueDay + 1; // 당월 납부일부터 당월 말일까지
            $daysNextMonth = $nextDueDay - 1; // 다음달 1일부터 다음달 납부일 전날까지
            $totalDays = $daysThisMonth + $daysNextMonth;
            
            // 남은 일수: 시작일부터 다음달 납부일 전날까지
            $daysLeft = ($lastDayOfMonth - $startDay + 1) + $daysNextMonth;
        }
        
        // 일할 보험료 계산
        $dailyFee = $monthlyFee3 / $totalDays;
        
        // KB손보인 경우 특별 계산
        if ($insurance == 3 && ($etag == 3 || $etag == 4)) {
            $monthlyFee3 = $monthlyFee1 * $rate + $monthlyFee2;
            $proRatedFee = round(($monthlyFee3 / $totalDays) * $daysLeft, -1);
        } else {
            $proRatedFee = round(($monthlyFee3 / $totalDays) * $daysLeft * $rate, -1);
        }
    }
    
    return [
        'monthlyFee1' => $monthlyFee1, 
        'monthlyFee2' => $monthlyFee2,
        'monthlyFee3' => $monthlyFee3,
        'insurance' => $insurance,
        'dueDay' => $thisDueDay,
        'startDay' => $startDay,
        'month' => $month,
        'year' => $year,
        'proRatedFee' => $proRatedFee,
        'totalDays' => $totalDays ?? 0,
        'lastDayOfMonth' => $lastDayOfMonth,
        'lastDayOfPrevMonth' => $lastDayOfPrevMonth,
        'prevDueDay' => $prevDueDay,
        'nextDueDay' => $nextDueDay,
        'daysLeft' => $daysLeft ?? 0,
        'dailyFee' => $dailyFee ?? 0,
        'rate' => $rate,
        'etag' => $etag
    ];
}

/**
 * 주민번호 처리 및 만나이 계산 함수 - 개선된 버전
 * 
 * @param string $jumin1 주민번호 앞 6자리
 * @param string $jumin2 주민번호 뒷 7자리
 * @param string $baseDate 기준일 (Y-m-d 형식)
 * @return array 나이 및 관련 정보
 */
function calculateAge(string $jumin1, string $jumin2, string $baseDate): array {
    // 주민번호 앞 6자리에서 년,월,일 추출
    $_year = substr($jumin1, 0, 2);
    $_month = substr($jumin1, 2, 2);
    $_day = substr($jumin1, 4, 2);
    
    // 주민번호 뒷자리 첫번째 숫자로 세기 판단
    $sex = substr($jumin2, 0, 1);
    
    // 성별코드에 따라 세기 판단
    $a = match ((int)$sex) {
        1, 2, 5, 6 => '19',  // 남성(1,5), 여성(2,6) - 1900년대
        3, 4, 7, 8 => '20',  // 남성(3,7), 여성(4,8) - 2000년대
        default => '19'      // 기본값
    };
    
    // 완전한 생년월일 생성
    $birthYear = $a . $_year;
    $birthMonth = $_month;
    $birthDay = $_day;
    
    // DateTime 객체로 변환
    $birthDate = new DateTime("$birthYear-$birthMonth-$birthDay");
    $baseDateObj = new DateTime($baseDate);
    
    // 만나이 계산
    $interval = $birthDate->diff($baseDateObj);
    $age = $interval->y;
    
    // 성별 정보
    $gender = ((int)$sex % 2 === 1) ? 'M' : 'F';
    
    // 날짜 형식 통일
    $birthday = $birthDate->format('Ymd');
    $to_day = $baseDateObj->format('Ymd');
    
    return [
        'age' => $age,
        'sex' => $gender,
        'birthday' => $birthday,
        'to_day' => $to_day
    ];
}
?>