<?php
// 에러 로깅 활성화
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
error_log("=== Proxy Request Started ===");

// CORS 헤더 설정
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// URL 파라미터 확인
if (!isset($_GET['url'])) {
    http_response_code(400);
    die('URL parameter is required');
}

$url = urldecode($_GET['url']);
error_log("Target URL: " . $url);

// cURL 초기화
$ch = curl_init();

// cURL 기본 옵션 설정
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_VERBOSE => true
]);

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    
    // POST 데이터 읽기
    $rawData = file_get_contents('php://input');
    error_log("Raw POST data: " . $rawData);
    
    if (!empty($rawData)) {
        // Content-Type 확인
        $contentType = $_SERVER['CONTENT_TYPE'] ?? 'application/x-www-form-urlencoded';
        error_log("Content-Type: " . $contentType);
        
        // 헤더 설정
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: ' . $contentType
        ]);
        
        // POST 데이터 설정
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    }
}

// Verbose 출력을 캡처하기 위한 설정
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

// 요청 실행
$response = curl_exec($ch);

// Verbose 로그 캡처
rewind($verbose);
$verboseLog = stream_get_contents($verbose);
error_log("Verbose cURL log: " . $verboseLog);
fclose($verbose);

// 에러 체크
if (curl_errno($ch)) {
    $errorMsg = curl_error($ch);
    $errorNum = curl_errno($ch);
    error_log("cURL Error ($errorNum): $errorMsg");
    http_response_code(500);
    echo json_encode([
        'error' => $errorMsg,
        'code' => $errorNum,
        'verbose' => $verboseLog
    ]);
    curl_close($ch);
    exit;
}

// HTTP 상태 코드와 응답 정보 가져오기
$info = curl_getinfo($ch);
error_log("cURL Info: " . print_r($info, true));
error_log("Response Body: " . $response);

curl_close($ch);

// 응답 헤더 설정
http_response_code($info['http_code']);
header('Content-Type: application/json');

// 응답 처리
if ($response === false || trim($response) === '') {
    echo json_encode([
        'error' => 'Empty response from server',
        'info' => $info,
        'verbose' => $verboseLog
    ]);
} else {
    // JSON 응답 확인
    $decoded = json_decode($response);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo $response;
    } else {
        echo json_encode([
            'error' => 'Invalid JSON response',
            'response' => $response,
            'info' => $info,
            'verbose' => $verboseLog
        ]);
    }
}

error_log("=== Proxy Request Completed ===");
?> 