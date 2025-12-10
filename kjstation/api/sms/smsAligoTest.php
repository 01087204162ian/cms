<?php
// Aligo SMS 전송 함수 (PHP 4.0 + 한글 인코딩 처리)
function sendAligoSms($data) {
    // cURL 사용 가능 여부 확인
    if (!function_exists('curl_init')) {
        return array(
            'error' => 'cURL이 설치되지 않았습니다.',
            'receive' => '',
            'sendD' => $data
        );
    }
    
    // cURL 초기화
    $ch = curl_init();
    
    if (!$ch) {
        return array(
            'error' => 'cURL 초기화 실패',
            'receive' => '',
            'sendD' => $data
        );
    }
    
    // 한글 메시지를 UTF-8로 변환 (EUC-KR에서 UTF-8로)
    $msg_utf8 = iconv('EUC-KR', 'UTF-8//IGNORE', $data['msg']);
    if (!$msg_utf8) {
        $msg_utf8 = $data['msg']; // 변환 실패시 원본 사용
    }
    
    // JSON 수동 생성 (UTF-8 인코딩)
    $json_data = '{';
    $json_data .= '"receiver":"' . $data['receiver'] . '",';
    $json_data .= '"msg":"' . addslashes($msg_utf8) . '",';
    $json_data .= '"testmode_yn":"' . $data['testmode_yn'] . '"';
    $json_data .= '}';
    
    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_URL, 'https://0j8iqqmk2l.execute-api.ap-northeast-2.amazonaws.com/aligo-send');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Accept-Charset: utf-8',
        'Content-Length: ' . strlen($json_data)
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    
    // SSL 설정
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    
    // 요청 실행
    $response = curl_exec($ch);
    
    // 오류 확인
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    
    // cURL 세션 종료
    curl_close($ch);
    
    // 결과 반환
    $result = array(
        'receive' => $response,
        'sendD' => $data,
        'json_sent' => $json_data,
        'http_code' => $http_code,
        'msg_utf8' => $msg_utf8
    );
    
    if ($curl_error) {
        $result['curl_error'] = $curl_error;
    }
    
    return $result;
}

// 페이지 인코딩 설정
header('Content-Type: text/html; charset=utf-8');

// 데이터 설정 (EUC-KR로 입력된 경우)
$sendData = array(
    "receiver" => "01087204162",
    "msg" => "KB화재대리/렌트오성준님 기준일[2025-05-01][2024-7974158]월90,000배90,000 ",
    "testmode_yn" => "N"
);

// 함수 호출
//$result = sendAligoSms($sendData);

/*// 결과 출력
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
echo "<h3>SMS 전송 결과</h3>";
echo "전송 번호: " . $sendData['receiver'] . "<br>";
echo "원본 메시지: " . htmlspecialchars($sendData['msg']) . "<br>";
echo "UTF-8 변환된 메시지: " . htmlspecialchars($result['msg_utf8']) . "<br>";
echo "테스트 모드: " . $sendData['testmode_yn'] . "<br><br>";

echo "<h3>HTTP 상태 코드:</h3>";
echo $result['http_code'] . "<br><br>";

echo "<h3>서버 응답:</h3>";
echo "<pre>" . htmlspecialchars($result['receive']) . "</pre>";*/
?>