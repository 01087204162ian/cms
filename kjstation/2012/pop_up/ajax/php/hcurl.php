<?php 

//_db/hcurl.php 복사 

/*변수 설정
$driverCompony="팔천대리";  // 대리운전회사
$driverName="홍길동";         //성명
$data = '01087204162'; //핸드폰번호// 암호화할 데이터를 준비합니다.
$data2="6603271069011";//주민번호
$policyNumber="2024-124567";//증권번호
$validStartDay="20241201";
$validEndDay="202521130";
 */
// PKCS#7 패딩을 추가하는 함수
function pkcs7_pad($data, $block_size) {
    $pad_length = $block_size - (strlen($data) % $block_size);
    return $data . str_repeat(chr($pad_length), $pad_length);
}




$key = 'CB1C198B747B87D03DFF8FA2CE776F1D';  // 32 바이트의 키
$iv = 'f95ef629cdc8e11a';  // 16 바이트의 IV		
		
		//$proc="sago";


if (!function_exists('json_decode')) {

    function json_decode($content, $assoc=false) {

        require_once $_SERVER['DOCUMENT_ROOT'].'/_db/libs/JSON.php';

        if ($assoc) {

            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

        }

        else {

            $json = new Services_JSON;

        }

        return $json->decode($content);

    }

}



if (!function_exists('json_encode')) {

    function json_encode($content) {

        require_once $_SERVER['DOCUMENT_ROOT'].'/_db/libs/JSON.php';

        $json = new Services_JSON;

        return $json->encode($content);

    }

}


//phpinfo();








// PKCS#7 패딩을 추가하여 데이터 길이를 맞춥니다.
$padded_data = pkcs7_pad($data, 16);  // 16은 AES 블록 크기입니다.

// mcrypt를 사용하여 AES-256 CBC로 암호화합니다.
$hNumber = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $padded_data, MCRYPT_MODE_CBC, $iv);

// 암호화된 데이터를 출력합니다.
//echo "Encrypted: " . base64_encode($hNumber) . "\n";



// PKCS#7 패딩을 추가하여 데이터 길이를 맞춥니다.
$padded_data = pkcs7_pad($data2, 16);  // 16은 AES 블록 크기입니다.

// mcrypt를 사용하여 AES-256 CBC로 암호화합니다.
$jumin = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $padded_data, MCRYPT_MODE_CBC, $iv);

// 암호화된 데이터를 출력합니다.
//echo "Encrypted: " . base64_encode($jumin) . "\n";

$body_data = array("siteGubun" => "KJ",
	  "driverCompony" =>iconv("euc-kr","utf-8",$driverCompony),
	  "driverName" =>iconv("euc-kr","utf-8",$driverName),
	  "driverCell"=> base64_encode($hNumber),
	 "driverJumin" => base64_encode($jumin),
	//  "driverCell"=> "QegoHppwdivu4zhDu0R4Qg==",
	//  "driverJumin" =>  "wonsRZIZQO1bNoL/dHAXRQ==",
	  "policyNumber" => $policyNumber,
	  "validStartDay" => $validStartDay,
	  "validEndDay" => $validEndDay);

		
		echo json_encode($body_data); 


 $headers = array(
        'X-API-SECRET:300301CA-4D8E-4B00-BE90-AFB37208DD70',
        'Content-Type: application/json;charset=UTF-8',
		'accept: application/json;charset=UTF-8',
        'Access-Control-Allow-Origin:' . $_SERVER['HTTP_HOST'],
    );

$url="https://center-api.simg.kr/v1/api/simg/hyundai/in/driver";
$body = json_encode($body_data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-SECRET:300301CA-4D8E-4B00-BE90-AFB37208DD70"));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec ($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
   //echo 'Response: ' . iconv("utf-8","euc-kr",$response);
}

// 응답에서 헤더 길이 확인
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

// 응답 본문 (JSON) 추출
$body = substr($response, $header_size);

// JSON 데이터 출력
echo 'JSON Response: ' . iconv("utf-8","euc-kr",$body) . "\n";
//echo  iconv("utf-8","euc-kr",$response);
curl_close($ch);



//echo json_decode(iconv("utf-8","euc-kr",$response));

?>
	 
