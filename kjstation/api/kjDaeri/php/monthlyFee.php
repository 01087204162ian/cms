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
 * @param $InsuraneCompany (보험회사 3 kb, 4 현대 ...)
 * @param $personRate2 (할증계수) 
 * @param $etag (1대리,2탁송,3 대리/렌트 ,4 대리/탁송)
 * @return float 배서 시 납입해야 할 보험료
 */
function calculateEndorsePremium($startDay, $endorse_day, $nabang, $nabang_1,$payment10_premium1, $payment10_premium2, $payment10_premium_total,$InsuraneCompany,$personRate2, $etag) {
    // 보험 종기 계산 (시작일로부터 1년)
    $endDay = date("Y-m-d", strtotime("$startDay +1 year -1 day"));
    
    // 경과기간과 미경과기간 계산 (일 단위)
    $start_timestamp = strtotime($startDay);
    $endorse_timestamp = strtotime($endorse_day);
    $end_timestamp = strtotime($endDay);
   
    $elapsed_period = ($endorse_timestamp - $start_timestamp) / 86400; // 초를 일로 변환
    
    // 미경과기간 계산 - PHP 4.4 호환 방식
    // 날짜를 년, 월, 일로 분리
    $endorse_parts = explode("-", $endorse_day);
    $end_parts = explode("-", $endDay);
    
    // mktime을 사용하여 타임스탬프 생성
    $endorse_mk = mktime(0, 0, 0, $endorse_parts[1], $endorse_parts[2], $endorse_parts[0]);
    $end_mk = mktime(0, 0, 0, $end_parts[1], $end_parts[2], $end_parts[0]);
    
    // 일수 계산 (초 단위를 일로 변환) 및 당일 포함
    $unexpired_period = round(($end_mk - $endorse_mk) / 86400) + 1;
    
    // 년 보험료 계산
    $gibonYearPremium = $payment10_premium1 * 10; //1/10 보험료 10배 (기본보험료)
    $rentYearPremium = $payment10_premium2 * 10; //1/10 보험료 10배 (특약보험료)
    
    if($InsuraneCompany == 3){ //kb손보인 경우
        //kb 손보 렌트특약을 가입한 경우
        // 적용보험료= 기본보험료*렌트할인할증계수 + 렌트특약보험료
        if($etag == 3 || $etag == 4){
            $yearPremium = $gibonYearPremium * $personRate2 + $rentYearPremium;
        }else{
            $yearPremium = ($payment10_premium_total * 10) * $personRate2; // 10개월치 보험료를 연간으로 환산
        }
    }else{
        $yearPremium = ($payment10_premium_total * 10) * $personRate2; // 10개월치 보험료를 연간으로 환산
    }
    
    // 1일 보험료 계산
    $oneDayPremium = $yearPremium / 365;
    
    // 미경과 보험료 계산
    $unexpired_period_premium = $unexpired_period * $oneDayPremium;
    
    // 다음에 낼 보험료 계산 (분납 회차에 따라)
    $daum_premium = 0;
    if ($nabang == 10) { // 10분납인 경우
        if ($nabang_1 == 1) {
            $daum_premium = $yearPremium * 0.8; // 1회차까지 냈으면, 80% 남음
        } else if ($nabang_1 == 2) {
            $daum_premium = $yearPremium * 0.7; // 2회차까지 냈으면, 70% 남음
        } else if ($nabang_1 == 3) {
            $daum_premium = $yearPremium * 0.6; // 3회차까지 냈으면, 60% 남음
        } else if ($nabang_1 == 4) {
            $daum_premium = $yearPremium * 0.5; // 4회차까지 냈으면, 50% 남음
        } else if ($nabang_1 == 5) {
            $daum_premium = $yearPremium * 0.4; // 5회차까지 냈으면, 40% 남음
        } else if ($nabang_1 == 6) {
            $daum_premium = $yearPremium * 0.3; // 6회차까지 냈으면, 30% 남음
        } else if ($nabang_1 == 7) {
            $daum_premium = $yearPremium * 0.2; // 7회차까지 냈으면, 20% 남음
        } else if ($nabang_1 == 8) {
            $daum_premium = $yearPremium * 0.1; // 8회차까지 냈으면, 10% 남음
        } else if ($nabang_1 == 9) {
            $daum_premium = $yearPremium * 0.05; // 9회차까지 냈으면, 5% 남음
        } else if ($nabang_1 == 10) {
            $daum_premium = 0; // 10회차까지 다 냈으면, 0% 남음
        }
    } else {
        // 다른 분납 방식에 대한 로직이 필요하면 여기에 추가
    }
    
    // 이번에 납입할 보험료 계산 = 미경과보험료 - 다음에 낼 보험료
    $i_endorese_premium = round($unexpired_period_premium - $daum_premium, -1);
    
    $insurance = "";  // 정의되지 않은 변수 오류 방지
    
    return array(
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
    );
}
/**
 * 일할 계산으로 보험료를 산출하는 함수
 * PHP 4.4 버전 호환
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
function calculateProRatedFee($monthlyFee1, $monthlyFee2, $monthlyFee3, $insurance, $dueDay, $startDay, $month, $year, $rate, $etag) {
    // 해당 월의 마지막 날짜 계산
    $lastDayOfMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
    
    // 납부일이 31일이거나 "말일"로 지정된 경우 해당 월의 마지막 날짜로 설정
    $thisDueDay = $dueDay;
    if ($thisDueDay > $lastDayOfMonth || $thisDueDay == 31 || $thisDueDay == 0) {
        $thisDueDay = $lastDayOfMonth;
    }
    
    // 다음달 정보 계산
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $lastDayOfNextMonth = date("t", mktime(0, 0, 0, $nextMonth, 1, $nextYear));
    
    // 다음달 납부일 계산 (다음달의 마지막 날짜보다 크면 마지막 날짜로 조정)
    $nextDueDay = $dueDay;
    if ($nextDueDay > $lastDayOfNextMonth || $nextDueDay == 31 || $nextDueDay == 0) {
        $nextDueDay = $lastDayOfNextMonth;
    }
    
    // 전월 정보 계산
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear = $month == 1 ? $year - 1 : $year;
    $lastDayOfPrevMonth = date("t", mktime(0, 0, 0, $prevMonth, 1, $prevYear));
    
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
        $proRatedFee = $monthlyFee3; // 전체 보험료 적용
        
        if ($insurance == 3) { // KB손보인 경우
            if ($etag == 3 || $etag == 4) {
                $proRatedFee = $monthlyFee1 * $rate + $monthlyFee2;
            }
        } else {
            $proRatedFee = $monthlyFee3 * $rate;
        }
        
        $proRatedFee = round($proRatedFee, -1); // 10원 단위 반올림
    } else {
        // 일반 케이스: 일할 계산 수행
        $totalDays = 0;
        $daysLeft = 0;
        
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
        
        if ($insurance == 3) { // KB손보인 경우
            // KB 손보 렌트특약을 가입한 경우
            // 적용보험료 = 기본보험료*렌트할인할증계수 + 렌트특약보험료
            if ($etag == 3 || $etag == 4) {
                $monthlyFee3 = $monthlyFee1 * $rate + $monthlyFee2;
                $proRatedFee = round(($monthlyFee3 / $totalDays) * $daysLeft, -1);
            } else {
                $proRatedFee = round(($monthlyFee3 / $totalDays) * $daysLeft, -1);
            }
        } else {
            $proRatedFee = round(($monthlyFee3 / $totalDays) * $daysLeft * $rate, -1);
        }
    }
    
    return array(
        'monthlyFee1' => $monthlyFee1, 
        'monthlyFee2' => $monthlyFee2,
        'monthlyFee3' => $monthlyFee3,
        'insurance' => $insurance,
        'dueDay' => $thisDueDay,
        'startDay' => $startDay,
        'month' => $month,
        'year' => $year,
        'proRatedFee' => $proRatedFee,
        'totalDays' => $totalDays,
        'lastDayOfMonth' => $lastDayOfMonth,
        'lastDayOfPrevMonth' => $lastDayOfPrevMonth,
        'prevDueDay' => $prevDueDay,
        'nextDueDay' => $nextDueDay,
        'daysLeft' => $daysLeft,
        'dailyFee' => isset($dailyFee) ? $dailyFee : 0,
        'rate' => $rate,
        'etag' => $etag
    );
}
// 주민번호 처리 및 만나이 계산 함수
function calculateAge($jumin1, $jumin2, $baseDate) {

	
    // 주민번호 앞 6자리에서 년,월,일 추출
    $_year = substr($jumin1, 0, 2);
    $_month = substr($jumin1, 2, 2);
    $_day = substr($jumin1, 4, 2);
    
    // 주민번호 뒷자리 첫번째 숫자로 세기 판단
    $sex = substr($jumin2, 0, 1);
    
    // 성별코드에 따라 세기 판단
    if ($sex == '1' || $sex == '2' || $sex == '3' || $sex == '4') {
        $a = '19';
    } else if ($sex == '5' || $sex == '6' || $sex == '7' || $sex == '8') {
        $a = '20';
    } else {
        // 기본값 - 오류 시 1900년대로 가정
        $a = '19';
    }
    
    // 완전한 생년월일 생성
    $__year = $a . $_year;
    $birthday = $__year . $_month . $_day;
    
    // 기준일 형식 맞추기 (YYYYMMDD)
    $to_day = $baseDate[0] . $baseDate[1] . $baseDate[2];
    
    // 만나이 계산
    $cal_age = $to_day - $birthday;
    
    // 결과 반환
    $age = floor(substr($cal_age, 0, 2));
    
    return array(
        'age' => $age,
        'sex' => $sex,
        'birthday' => $birthday,
        'to_day' => $to_day
    );
}
/*
// 예시 1: 매월 말일에 60,000원 보험료, 4월 20일 가입
$monthlyFee = 60000;   // 월 보험료
$startDay = 20;        // 가입일
$dueDay = 0;           // 납부일 (0은 말일을 의미)
$month = 4;            // 가입 월 (4월)
$year = 2025;          // 가입 연도

$fee = calculateProRatedFee($monthlyFee, $dueDay, $startDay, $month, $year);

echo "월 보험료: " . number_format($monthlyFee) . "원\n";
echo "가입일: {$year}년 {$month}월 {$startDay}일\n";
echo "납부일: 매월 말일\n";
echo "일할 계산 보험료: " . number_format($fee) . "원\n\n";

// 2월 예시 실행
$fee2 = calculateProRatedFee($monthlyFee, $dueDay,$startDay,  $month, $year);

// 2월의 마지막 날짜 확인
$lastDayOfFeb = date("t", mktime(0, 0, 0, 2, 1, $year));

echo "월 보험료: " . number_format($monthlyFee) . "원\n";
echo "가입일: {$year}년 {$month}월 {$startDay}일\n";
echo "납부일: 매월 말일 (2월의 경우 {$lastDayOfFeb}일)\n";
echo "일할 계산 보험료: " . number_format($fee2) . "원\n";

// 예시 2: 매월 말일에 60,000원 보험료, 2월 15일 가입 (2월은 28일까지)
$monthlyFee = 60000;   // 월 보험료
$startDay = 15;        // 가입일
$dueDay = 0;           // 납부일 (0은 말일을 의미)
$month = 2;            // 가입 월 (2월)
$year = 2025;          // 가입 연도

$fee = calculateProRatedFee($monthlyFee,$dueDay, $startDay,  $month, $year);

echo "월 보험료: " . number_format($monthlyFee) . "원\n";
echo "가입일: {$year}년 {$month}월 {$startDay}일\n";
echo "납부일: 매월 말일\n";
echo "일할 계산 보험료: " . number_format($fee) . "원\n";*/
?>