<?php
/*결제 기간 계산 로직:
1. 일반적인 경우: "이전 달 결제일 다음날부터 현재 달 결제일까지"
   (예: 결제일이 5일인 경우 3월 6일 ~ 4월 5일)

2. 특별한 경우(결제일이 해당 달의 마지막 날인 경우): "현재 달 1일부터 현재 달 결제일까지"
   (예: 결제일이 30일인 경우 4월 1일 ~ 4월 30일)
   (예: 결제일이 31일인 경우 1월 1일 ~ 1월 31일)

주의: 결제일이 30일, 31일인 경우 해당 달에 그 날짜가 없으면 그 달의 마지막 날을 기준으로 정산함
(예: 2월의 경우 28일 또는 29일이 마지막 날이 됨)
*/
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';  
include "./php/dNum_search.php";  // 대리업체 정기결제일 $dueDay

// 현재 날짜 정보 가져오기
$currentDate = date('Y-m-d');
$currentYear = date('Y');
$currentMonth = date('m');

// 현재 달의 마지막 날짜 구하기
$currentMonthLastDay = date('t', strtotime("$currentYear-$currentMonth-01"));

// 현재 달의 정기결제일 계산
// 결제일이 현재 달의 마지막 날보다 크면 마지막 날을 결제일로 사용
if ($dueDay > $currentMonthLastDay) {
    $thisMonthDueDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-$currentMonthLastDay"));
} else {
    $thisMonthDueDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-$dueDay"));
}

// 이전 달 및 해당 달의 마지막 날짜 구하기
$lastMonth = date('Y-m', strtotime("-1 month", strtotime("$currentYear-$currentMonth-01")));
$lastMonthYear = date('Y', strtotime($lastMonth));
$lastMonthMonth = date('m', strtotime($lastMonth));
$lastMonthLastDay = date('t', strtotime("$lastMonth-01"));

// 이전 달의 정기결제일 계산
// 결제일이 이전 달의 마지막 날보다 크면 마지막 날을 결제일로 사용
if ($dueDay > $lastMonthLastDay) {
    $lastMonthDueDate = date('Y-m-d', strtotime("$lastMonth-$lastMonthLastDay"));
} else {
    $lastMonthDueDate = date('Y-m-d', strtotime("$lastMonth-$dueDay"));
}

// 특별 케이스 확인: 결제일이 해당 달의 마지막 날과 같거나 큰 경우
$isLastDayOfMonth = ($dueDay >= $currentMonthLastDay);

// 결제 시작일 계산
if ($isLastDayOfMonth) {
    // 특별한 경우: 현재 달의 1일로 설정
    $paymentStartDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-01"));
} else {
    // 일반적인 경우: 이전 달 결제일 다음날
    $paymentStartDate = date('Y-m-d', strtotime("+1 day", strtotime($lastMonthDueDate)));
}

$company = $Drow['company'];
$company = iconv("EUC-KR", "UTF-8", $company);

$response = array(
    "success" => true,
    "company" => $company,
    "dueDay" => $dueDay,
	"jumin"=>$Drow['jumin'],
	'cNumber'=>$Drow['cNumber'],
    "thisMonthDueDate" => $thisMonthDueDate,
    "lastMonthDueDate" => $paymentStartDate,
    "paymentStartDate" => $paymentStartDate,
    "paymentPeriod" => "$paymentStartDate ~ $thisMonthDueDate",
    "currentDate" => $currentDate
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>