<?php
// _db/hcurl.php 복사
/* 변수 설정
$driverCompony = "팔천대리";  // 대리운전회사
$driverName = "홍길동";       // 성명
$data = '01087204162';        // 핸드폰번호 - 암호화할 데이터
$data2 = "6603271069011";     // 주민번호
$policyNumber = "2024-124567"; // 증권번호
$validStartDay = "20241201";
$validEndDay = "202521130";
*/

// AES 암호화 클래스
class AESEncryption {
    private $key;
    private $iv;
    
    public function __construct($key, $iv) {
        $this->key = $key;
        $this->iv = $iv;
    }
    
    public function encrypt($data) {
        // OpenSSL은 자동으로 PKCS#7 패딩을 적용합니다
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);
        return base64_encode($encrypted);
    }
}

$key = 'CB1C198B747B87D03DFF8FA2CE776F1D';  // 32 바이트의 키
$iv = 'f95ef629cdc8e11a';  // 16 바이트의 IV

// AES 암호화 객체 생성
$aes = new AESEncryption($key, $iv);

// 데이터 암호화
$encryptedHNumber = $aes->encrypt($data);
$encryptedJumin = $aes->encrypt($data2);

// API 요청을 위한 데이터 준비
$body_data = [
    "siteGubun" => "KJ",
    "driverCompony" => mb_convert_encoding($driverCompony, "UTF-8", "EUC-KR"),
    "driverName" => mb_convert_encoding($driverName, "UTF-8", "EUC-KR"),
    "driverCell" => $encryptedHNumber,
    "driverJumin" => $encryptedJumin,
    "policyNumber" => $policyNumber,
    "validStartDay" => $validStartDay,
    "validEndDay" => $validEndDay
];

// 헤더 설정
$headers = [
    'X-API-SECRET: 300301CA-4D8E-4B00-BE90-AFB37208DD70',
    'Content-Type: application/json;charset=UTF-8',
    'Accept: application/json;charset=UTF-8',
    'Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_HOST'] ?? '*')
];

$url = "https://center-api.simg.kr/v1/api/simg/hyundai/in/driver";
$body = json_encode($body_data, JSON_UNESCAPED_UNICODE);

// cURL 초기화 및 옵션 설정
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADER => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 10
]);

// API 요청 실행
$response = curl_exec($ch);

if ($response === false) {
    error_log('Curl error: ' . curl_error($ch));
    // 에러 처리
    $error = curl_error($ch);
    curl_close($ch);
    throw new Exception("cURL 요청 실패: " . $error);
} else {
    // 응답에서 헤더와 본문 분리
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    // HTTP 상태 코드 확인
    if ($http_code >= 400) {
        error_log("HTTP Error {$http_code}: " . $body);
    }
    
    // JSON 응답 파싱
    try {
        $jsonResponse = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON 파싱 오류: ' . json_last_error_msg());
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

curl_close($ch);

// 디버깅용 (필요시 주석 해제)
// echo 'JSON Response: ' . $body . "\n";
// var_dump($jsonResponse);
?>