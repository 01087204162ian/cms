<?php
/**
 * 대리운전 기사 연령별 구간별 보험료 산출 통계
 * 
 * 이 스크립트는 대리운전 기사의 연령별, 구간별 보험료 통계를 산출하고 표시합니다.
 * 연령별, 담당자별 보험료 계산 및 할인할증 적용을 처리합니다.
 */

// 헤더 설정 및 필요한 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';      // CORS 헤더 설정
include '../json4version.php'; // PHP 4용 JSON 변환 함수
include '../dbcon.php';     // 데이터베이스 연결 설정
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";
// 입력 파라미터 가져오기 및 보안 처리
$certi = isset($_GET['certi']) ? $_GET['certi'] : '';

$certi = mysql_real_escape_string($certi); // SQL 인젝션 방지


// 특정 증권번호의 대리기사 정보 조회
$memberSql = "SELECT * FROM DaeriMember WHERE dongbuCerti='$certi' AND push='4'";
$memberRs = mysql_query($memberSql, $conn);
$memberCount = mysql_num_rows($memberRs);

// 대리기사 정보가 없는 경우
if ($memberCount <= 0) {
    $response["message"] = "해당 증권번호의 대리기사 정보가 없습니다.";
    echo json_encode_php4($response);
    mysql_close($conn);
    exit;
}

// 보험료 데이터 테이블 조회
$premiumSql = "SELECT * FROM kj_insurance_premium_data WHERE policyNum = '$certi'";
$premiumRs = mysql_query($premiumSql, $conn);
$premiumCount = mysql_num_rows($premiumRs);

if ($premiumCount <= 0) {
    $response["message"] = "해당 증권번호의 보험료 데이터가 없습니다.";
    echo json_encode_php4($response);
    mysql_close($conn);
    exit;
}

// 구간별 보험료 정보 저장 배열
$ageRangeStats = array();

// 구간별 실제 적용 보험료 계산
$rangeSql = "SELECT start_month, end_month FROM kj_insurance_premium_data 
             WHERE policyNum = '$certi' 
             GROUP BY start_month, end_month 
             ORDER BY start_month ASC";
$rangeRs = mysql_query($rangeSql, $conn);

// 각 구간별 처리
$totalDrivers = 0;
// 보험회사 납입 보험료 관련 변수
$grandTotalPremium1 = 0;
$grandTotalPremium2 = 0;
$grandTotalPremium = 0;
$grandTotalAdjustedPremium = 0;
// 월 납입 보험료 관련 변수
$grandTotalMonthPremium1 = 0;
$grandTotalMonthPremium2 = 0;
$grandTotalMonthPremium = 0;
$grandTotalMonthAdjustedPremium = 0;
// 보험회사 납입 보험료의 월 환산 관련 변수
$grandTotalPremium1Monthly = 0;
$grandTotalPremium2Monthly = 0;
$grandTotalPremiumMonthly = 0;
$grandTotalAdjustedPremiumMonthly = 0;

while ($rangeRow = mysql_fetch_array($rangeRs)) {
    $startMonth = (int)$rangeRow['start_month'];
    $endMonth = (int)$rangeRow['end_month'];
    $rangeKey = "$startMonth-$endMonth";
    


    // 보험회사 납입 보험료 - 해당 구간의 실제 보험료 데이터
    $rangePremiumSql = "SELECT payment10_premium1, payment10_premium2, payment10_premium_total 
                        FROM kj_insurance_premium_data 
                        WHERE policyNum = '$certi' 
                        AND start_month = $startMonth 
                        AND end_month = $endMonth";
    $rangePremiumRs = mysql_query($rangePremiumSql, $conn);
    $rangePremiumRow = mysql_fetch_array($rangePremiumRs);
    
    $premium1 = isset($rangePremiumRow['payment10_premium1']) ? (int)$rangePremiumRow['payment10_premium1'] : 0;
    $premium2 = isset($rangePremiumRow['payment10_premium2']) ? (int)$rangePremiumRow['payment10_premium2'] : 0;
    $premiumTotal = isset($rangePremiumRow['payment10_premium_total']) ? (int)$rangePremiumRow['payment10_premium_total'] : 0;
    
    // 이 구간에 속하는 대리기사 가져오기
    $rangeDriversSql = "SELECT * FROM DaeriMember 
                        WHERE dongbuCerti='$certi' 
                        AND push='4' 
                        AND nai >= $startMonth 
                        AND nai <= $endMonth";
    $rangeDriversRs = mysql_query($rangeDriversSql, $conn);
    $rangeDriverCount = mysql_num_rows($rangeDriversRs);
    
    // 해당 구간의 모든 대리기사에 대한 실제 적용 보험료 합계
    $totalAdjustedPremium = 0;
    $totalMonthAdjustedPremium = 0;
    
    // 구간에 속하는 각 대리기사별로 할인할증 적용 보험료 계산
    while ($driverRow = mysql_fetch_array($rangeDriversRs)) {
		include "./php/decrptJuminHphone.php"; 
        $driverJumin = isset($driverRow['Jumin']) ? $driverRow['Jumin'] : '';
        $driverNai = isset($driverRow['nai']) ? (int)$driverRow['nai'] : 0;
        $cNum=$driverRow['CertiTableNum'];


        // 할인할증률 조회
        $rateSql = "SELECT rate as r FROM 2019rate WHERE policy='$certi' AND jumin = '$driverJumin'";
        $rateRs = mysql_query($rateSql, $conn);
        $rateRow = mysql_fetch_array($rateRs);
        $discountRate = isset($rateRow['r']) ? (float)$rateRow['r'] : 1.0;
        
        // 대리기사 정보의 rate 코드로 계산
        $rateCode = isset($driverRow['rate']) ? (int)$driverRow['rate'] : 0;
        $discountRate = calculatePersonalRate($rateCode);
        
        // 대리기사별 적용 보험회사 납입하는 보험료 계산 
        $adjustedPremium = round($premiumTotal * $discountRate['rate']);
        $totalAdjustedPremium += $adjustedPremium;

        // 대리기사별 적용 월 납입하는 보험료 계산 

		// 대리운전회사가 월 납입하는 보험료 구하기 위해
		$mSql="SELECT *  FROM kj_premium_data WHERE cNum = '$cNum' AND start_month<='$driverNai' and end_month>='$driverNai' ";
			//echo $mSql;
			$mrs=mysql_query($mSql,$conn);
			$mrow=mysql_fetch_array($mrs);

			//echo $mrow['monthly_premium1'];

			$monthPremium1=$mrow['monthly_premium1']; // 월 기본보험료
			$monthPremium2=$mrow['monthly_premium2']; // 월 특약보험료
			$monthPremiumTotal=$mrow['monthly_premium_total']; // 기본+특약 합계


    


        $adjustedMonthPremium = round($monthPremiumTotal * $discountRate['rate']);
        $totalMonthAdjustedPremium += $adjustedMonthPremium;
    }
    
    // 보험회사 납입 보험료의 월 환산 계산 (보험회사 납입 보험료 * 10/12)
    $premium1Monthly = round($premium1 * 10 / 12);
    $premium2Monthly = round($premium2 * 10 / 12);
    $premiumTotalMonthly = round($premiumTotal * 10 / 12);
    $totalAdjustedPremiumMonthly = round($totalAdjustedPremium * 10 / 12);
    
    // 월 환산 보험료와 실제 월 납입 보험료의 차이 계산
    $premium1Diff = $premium1Monthly - $monthPremium1;
    $premium2Diff = $premium2Monthly - $monthPremium2;
    $premiumTotalDiff = $premiumTotalMonthly - $monthPremiumTotal;
    $totalAdjustedPremiumDiff = $totalAdjustedPremiumMonthly - $totalMonthAdjustedPremium;
    
    // 구간별 통계 정보 저장
    $ageRangeStats[$rangeKey] = array(
        'start_month' => $startMonth,
        'end_month' => $endMonth,
        'driver_count' => $rangeDriverCount,
        // 보험회사 납입 보험료
        'premium1' => $premium1,
        'premium2' => $premium2,
        'premium_total' => $premiumTotal,
        'total_adjusted_premium' => $totalAdjustedPremium,
        // 월 납입 보험료
        'monthPremium1' => $monthPremium1,
        'monthPremium2' => $monthPremium2,
        'monthPremiumTotal' => $monthPremiumTotal,
        'total_month_adjusted_premium' => $totalMonthAdjustedPremium,
        // 보험회사 납입 보험료의 월 환산
        'premium1_monthly' => $premium1Monthly,
        'premium2_monthly' => $premium2Monthly,
        'premium_total_monthly' => $premiumTotalMonthly,
        'total_adjusted_premium_monthly' => $totalAdjustedPremiumMonthly,
        // 월 환산 보험료와 실제 월 납입 보험료의 차이
        'premium1_diff' => $premium1Diff,
        'premium2_diff' => $premium2Diff,
        'premium_total_diff' => $premiumTotalDiff,
        'total_adjusted_premium_diff' => $totalAdjustedPremiumDiff,
    );
    
    // 전체 합계 계산
    $totalDrivers += $rangeDriverCount;
    
    // 보험회사 납입 보험료 합계
    $grandTotalPremium1 += ($premium1 * $rangeDriverCount);
    $grandTotalPremium2 += ($premium2 * $rangeDriverCount);
    $grandTotalPremium += ($premiumTotal * $rangeDriverCount);
    $grandTotalAdjustedPremium += $totalAdjustedPremium;
    
    // 월 납입 보험료 합계
    $grandTotalMonthPremium1 += ($monthPremium1 * $rangeDriverCount);
    $grandTotalMonthPremium2 += ($monthPremium2 * $rangeDriverCount);
    $grandTotalMonthPremium += ($monthPremiumTotal * $rangeDriverCount);
    $grandTotalMonthAdjustedPremium += $totalMonthAdjustedPremium;
    
    // 보험회사 납입 보험료의 월 환산 합계
    $grandTotalPremium1Monthly += ($premium1Monthly * $rangeDriverCount);
    $grandTotalPremium2Monthly += ($premium2Monthly * $rangeDriverCount);
    $grandTotalPremiumMonthly += ($premiumTotalMonthly * $rangeDriverCount);
    $grandTotalAdjustedPremiumMonthly += $totalAdjustedPremiumMonthly;
}

// 보험회사 납입 보험료와 월 납입 보험료의 차이 계산
$grandTotalPremium1Diff = $grandTotalPremium1Monthly - $grandTotalMonthPremium1;
$grandTotalPremium2Diff = $grandTotalPremium2Monthly - $grandTotalMonthPremium2;
$grandTotalPremiumDiff = $grandTotalPremiumMonthly - $grandTotalMonthPremium;
$grandTotalAdjustedPremiumDiff = $grandTotalAdjustedPremiumMonthly - $grandTotalMonthAdjustedPremium;

// 응답 데이터 구성
$response = array(
    "success" => true,
    "certi" => $certi,
    "member_count" => $memberCount,
    "age_range_premiums" => $ageRangeStats,
    "summary" => array(
        "total_drivers" => $totalDrivers,
        // 보험회사 납입 보험료 요약
        "total_premium1" => $grandTotalPremium1,
        "total_premium2" => $grandTotalPremium2,
        "total_premium" => $grandTotalPremium,
        "total_adjusted_premium" => $grandTotalAdjustedPremium,
        "average_premium_per_driver" => $totalDrivers > 0 ? round($grandTotalPremium / $totalDrivers) : 0,
        "average_adjusted_premium_per_driver" => $totalDrivers > 0 ? round($grandTotalAdjustedPremium / $totalDrivers) : 0,
        
        // 월 납입 보험료 요약
        "total_month_premium1" => $grandTotalMonthPremium1,
        "total_month_premium2" => $grandTotalMonthPremium2,
        "total_month_premium" => $grandTotalMonthPremium,
        "total_month_adjusted_premium" => $grandTotalMonthAdjustedPremium,
        "average_month_premium_per_driver" => $totalDrivers > 0 ? round($grandTotalMonthPremium / $totalDrivers) : 0,
        "average_month_adjusted_premium_per_driver" => $totalDrivers > 0 ? round($grandTotalMonthAdjustedPremium / $totalDrivers) : 0,
        
        // 보험회사 납입 보험료의 월 환산 요약
        "total_premium1_monthly" => $grandTotalPremium1Monthly,
        "total_premium2_monthly" => $grandTotalPremium2Monthly, 
        "total_premium_monthly" => $grandTotalPremiumMonthly,
        "total_adjusted_premium_monthly" => $grandTotalAdjustedPremiumMonthly,
        "average_premium_monthly_per_driver" => $totalDrivers > 0 ? round($grandTotalPremiumMonthly / $totalDrivers) : 0,
        "average_adjusted_premium_monthly_per_driver" => $totalDrivers > 0 ? round($grandTotalAdjustedPremiumMonthly / $totalDrivers) : 0,
        
        // 월 환산 보험료와 실제 월 납입 보험료의 차이 요약
        "total_premium1_diff" => $grandTotalPremium1Diff,
        "total_premium2_diff" => $grandTotalPremium2Diff,
        "total_premium_diff" => $grandTotalPremiumDiff,
        "total_adjusted_premium_diff" => $grandTotalAdjustedPremiumDiff,
        "diff_percentage" => $grandTotalMonthPremium > 0 ? round(($grandTotalPremiumDiff / $grandTotalMonthPremium) * 100, 2) : 0
    )
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);


?>