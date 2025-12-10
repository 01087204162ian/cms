<?php
// 에러 로깅 설정
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');  // 절대 경로로 변경

header('Content-Type: application/json; charset=utf-8');
include '../cors.php';

// OPTIONS 요청 시 종료
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

try {
    // API 파라미터 설정
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 15;
    $search_school = isset($_GET['search_school']) ? urlencode($_GET['search_school']) : '';
    $search_mode = isset($_GET['search_mode']) ? intval($_GET['search_mode']) : 2;

    // 전체 개수를 먼저 가져오기
    $count_url = "https://lincinsu.kr/2025/api/question/get_total_count.php";
    if (!empty($search_school)) {
        $count_url .= "?search_school={$search_school}&search_mode={$search_mode}";
    }

    error_log("Requesting total count from: " . $count_url);

    $ch_count = curl_init();
    curl_setopt($ch_count, CURLOPT_URL, $count_url);
    curl_setopt($ch_count, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_count, CURLOPT_SSL_VERIFYPEER, false);
    
    $count_response = curl_exec($ch_count);
    
    if (curl_errno($ch_count)) {
        throw new Exception('Count API Curl error: ' . curl_error($ch_count));
    }
    
    curl_close($ch_count);
    
    $count_data = json_decode($count_response, true);
    error_log("Total count response: " . print_r($count_data, true));
    
    $total_count = isset($count_data['total']) ? intval($count_data['total']) : 0;

    // 데이터 가져오는 API URL 구성
    $api_url = "https://lincinsu.kr/2025/api/question/fetch_questionnaire.php?";
    $api_url .= "page={$page}&limit={$limit}";
    
    if (!empty($search_school)) {
        $api_url .= "&search_school={$search_school}&search_mode={$search_mode}";
    }

    error_log("Requesting data from: " . $api_url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        throw new Exception('Data API Curl error: ' . curl_error($ch));
    }
    
    curl_close($ch);

    // JSON 응답 파싱
    $data = json_decode($response, true);
    error_log("Data response: " . print_r($data, true));

    // 데이터가 없는 경우 빈 배열로 초기화
    if (!isset($data['data']) || !is_array($data['data'])) {
        $data['data'] = [];
    }

    // total_count가 0이면 data 배열의 길이를 사용
    if ($total_count === 0 && !empty($data['data'])) {
        $total_count = count($data['data']);
    }

    // 응답 데이터 구성
    $response_data = [
        'data' => $data['data'],
        'total' => $data['total'],
        'page' => $page,
        'limit' => $limit,
        'pages' => ceil($data['total'] / $limit)
    ];

    error_log("Final response with total count: " . $data['total']);

    // 응답 전달
    http_response_code($httpCode);
    echo json_encode($response_data);

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'data' => [],
        'total' => 0,
        'page' => 1,
        'limit' => 15,
        'pages' => 0
    ]);
}
?> 