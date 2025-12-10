<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // PHP 4에서 JSON 변환을 위한 함수 포함
include '../dbcon.php'; // DB 연결 정보 포함
// 데이터베이스 쿼리 실행
$query = "SELECT * FROM `2012DaeriMember` WHERE push = '4' LIMIT 500"; // 데이터 양 제한
$result = mysql_query($query, $conn);
$data = array();
// 여기서 결과를 $data 배열에 추가해야 합니다
while ($row = mysql_fetch_assoc($result)) {
    foreach ($row as $key => $value) {
        if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
            $converted = @iconv("EUC-KR", "UTF-8", $value);
            $row[$key] = ($converted !== false) ? $converted : $value;
        }
    }
    $data[] = $row;
}


// API URL 설정

/*
$url = "https://pcikorea.com/RestApi/daeriMember.php"; // 원래 대상 URL
$headers = array(
    'X-API-SECRET: 300301CA-4D8E-4B00-BE90-AFB37208DD70',
    'Content-Type: application/json;charset=UTF-8',
    'Accept: application/json;charset=UTF-8',
    'Access-Control-Allow-Origin: ' . $_SERVER['HTTP_HOST']
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); // $url 변수 사용
curl_setopt($ch, CURLOPT_HEADER, false); // 헤더 정보는 응답에 포함하지 않음
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode_php4($response)); // $response 전송 (중요!)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 인증서 검증 비활성화
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 호스트 검증 비활성화
// curl 실행 (한 번만)
$server_output = curl_exec($ch);
$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
// 결과 표시
$result_message = array(
    "local_success" => true,
    "api_response_code" => $http_code,
    "api_response" => $server_output,
    "error" => $curl_error,
    "url" => $url // 실제 사용한 URL 표시
);*/
echo json_encode_php4($data);
mysql_close($conn);
?>