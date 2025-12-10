<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
// 디버깅 정보 추가
$debug = array(
    'post_data' => $_POST,
    'get_data' => $_GET,
    'request_method' => $_SERVER['REQUEST_METHOD']
);
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);
// GET과 POST 모두에서 날짜 파라미터 처리
$fromDate = '';
$toDate = '';
// POST 데이터 확인
if (isset($_POST['fromDate']) && !empty($_POST['fromDate'])) {
    $fromDate = $_POST['fromDate'];
} 
// GET 데이터 확인
elseif (isset($_GET['fromDate']) && !empty($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
}
// 기본값 설정
else {
    $fromDate = date('Y-m-d', strtotime('-1 months'));
}
// toDate도 동일한 방식으로 처리
if (isset($_POST['toDate']) && !empty($_POST['toDate'])) {
    $toDate = $_POST['toDate'];
} 
elseif (isset($_GET['toDate']) && !empty($_GET['toDate'])) {
    $toDate = $_GET['toDate'];
}
else {
    $toDate = date('Y-m-d');
}
// 날짜 유효성 검사 추가
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
    echo json_encode(array(
        'error' => '날짜 형식이 올바르지 않습니다. YYYY-MM-DD 형식이어야 합니다.',
        'debug' => $debug,
        'fromDate' => $fromDate,
        'toDate' => $toDate
    ));
    exit;
}
// 디버깅 정보 업데이트
$debug['final_fromDate'] = $fromDate;
$debug['final_toDate'] = $toDate;

// 푸시 의미 정의
$pushMeanings = array(
    '1' => 'subscription2',
    '2' => 'termination',
    '3' => 'subscriptionReject',
    '4' => 'subscription',
    '5' => 'terminationCancel',
    '6' => 'subscriptionCancel',
);

// 날짜 범위에 대한 모든 날짜를 생성하여 초기화
$dateResults = array();
$current = strtotime($fromDate);
$last = strtotime($toDate);
while ($current <= $last) {
    $dateKey = date('Y-m-d', $current);
    $dateResults[$dateKey] = array(
        'date' => $dateKey,
        'total' => 0
    );
    
    // 각 푸시 타입별로 데이터 초기화
    foreach ($pushMeanings as $pushCode => $pushMeaning) {
        $dateResults[$dateKey][$pushMeaning] = 0;
    }
    
    $current = strtotime('+1 day', $current);
}

// 하나의 쿼리로 모든 날짜와 푸시 타입에 대한 데이터를 가져옴
$sql = "SELECT endorse_day, push, COUNT(*) as count FROM SMSData 
        WHERE endorse_day BETWEEN '$fromDate' AND '$toDate' 
        AND dagun='1' 
        GROUP BY endorse_day, push";

$result = mysql_query($sql, $conn);

// 쿼리 성공 여부 확인
if (!$result) {
    $debug['query_error'] = mysql_error($conn);
    $response = array(
        "success" => false,
        "error" => "데이터베이스 쿼리 오류",
        "debug" => $debug
    );
    echo json_encode_php4($response);
    mysql_close($conn);
    exit;
}

// 결과를 날짜별로 정리
while ($row = mysql_fetch_assoc($result)) {
    $date = $row['endorse_day'];
    $push = $row['push'];
    $count = intval($row['count']);
    
    // 해당 날짜가 결과 배열에 있는지 확인
    if (isset($dateResults[$date])) {
        // 푸시 타입에 해당하는 의미가 있는지 확인
        if (isset($pushMeanings[$push])) {
            $pushMeaning = $pushMeanings[$push];
            $dateResults[$date][$pushMeaning] = $count;
            $dateResults[$date]['total'] += $count;
        }
    }
}

// 결과 배열을 날짜 순서대로 정렬된 배열로 변환하기 전에 total이 0인 항목 제거
$filteredResults = array();
foreach ($dateResults as $date => $data) {
    if ($data['total'] > 0) {
        $filteredResults[$date] = $data;
    }
}

// 필터링된 결과 배열을 날짜 순서대로 정렬된 배열로 변환
$sortedResults = array_values($filteredResults);

$response = array(
    "success" => true,
    "fromDate" => $fromDate,
    "toDate" => $toDate,
    "data" => $sortedResults,
    "pushMeanings" => $pushMeanings,
    "debug" => $debug
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>