<?php
// Aligo SMS 전송 함수
// 현재 발송 번호 1877-3006 
function sendAligoSms($data) {
    // cURL 초기화
    $ch = curl_init();
    
    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_URL, 'https://j7rqfprgb5.execute-api.ap-northeast-2.amazonaws.com/default/aligo-5962');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    
    // 요청 실행 및 응답 받기
    $response = curl_exec($ch);
    
    // cURL 세션 종료
    curl_close($ch);
    
    // 응답 처리
    $responseBody = json_decode($response, true);
    
  
    
    return [
        'receive' => $responseBody,
        'sendD' => $data
    ];
}

// 데이터 설정
$sendData = [
    "receiver" => "01087204162",
    "msg" => "KB화재대리/렌트오성준님 기준일[2025-05-01][2024-7974158]월90,000배90,000 ",
    "testmode_yn" => "N"
];

// 함수 호출
$result = sendAligoSms($sendData);


echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>