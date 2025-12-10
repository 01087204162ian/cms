<?php
// cURL 세션 초기화
$curl = curl_init();

// cURL 옵션 설정
curl_setopt_array($curl, array(
  // 요청을 보낼 URL 설정
  CURLOPT_URL => 'https://rest.surem.com/messages/v2/sms',
  // 실행 결과를 문자열로 반환받도록 설정
  CURLOPT_RETURNTRANSFER => true,
  // 자동으로 적절한 인코딩 헤더를 전송하도록 설정
  CURLOPT_ENCODING => '',
  // 최대 리디렉션 횟수 설정
  CURLOPT_MAXREDIRS => 10,
  // 타임아웃 시간 설정 (무제한)
  CURLOPT_TIMEOUT => 0,
  // 리디렉션을 따라가도록 설정
  CURLOPT_FOLLOWLOCATION => true,
  // HTTP 버전 설정
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  // 요청 방식을 POST로 설정
  CURLOPT_CUSTOMREQUEST => 'POST',
  // POST 요청에 담을 JSON 데이터 설정
  CURLOPT_POSTFIELDS => '{
  "usercode": "simg",             // 사용자 코드
  "deptcode": "MT-EZZ-WW",        // 부서 코드
  "to": "01087204162",            // 수신자 전화번호
  "text": "메시지 내용",           // 전송할 메시지 내용
  "reqphone": "07078415962"       // 발신자 전화번호
}',
  // HTTP 헤더 설정 (JSON 형식의 콘텐츠 타입 지정)
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

// cURL 요청 실행 및 응답 받기
$response = curl_exec($curl);

// cURL 세션 종료
curl_close($curl);

// 응답 결과 출력
echo $response;
?>