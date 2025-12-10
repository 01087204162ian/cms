<?include '../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

//	phpinfo();
//2025-06-25     surem     문자를 사용할 수 없음
// 알리고 변경
// Aligo SMS 전송 함수 (PHP 4.0 + 한글 인코딩 처리)
// 개선된 Aligo SMS 전송 함수 (PHP 4.4.9 호환)
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
    
    // JSON 안전하게 생성 (특수문자 이스케이프)
    $json_data = '{';
    $json_data .= '"receiver":"' . $data['receiver'] . '",';
    $json_data .= '"msg":"' . json_escape($msg_utf8) . '",';
    $json_data .= '"testmode_yn":"' . $data['testmode_yn'] . '"';
    $json_data .= '}';
    
    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_URL, 'https://j7rqfprgb5.execute-api.ap-northeast-2.amazonaws.com/default/aligo-5962');
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

// JSON 이스케이프 함수 (PHP 4.4.9 호환)
function json_escape($str) {
    $search = array('\\', '"', '/', "\b", "\f", "\n", "\r", "\t");
    $replace = array('\\\\', '\\"', '\\/', '\\b', '\\f', '\\n', '\\r', '\\t');
    return str_replace($search, $replace, $str);
}

// JSON 함수들 (PHP 4.4.9용)
if (!function_exists('json_decode')) {
    function json_decode($content, $assoc=false) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/_db/libs/JSON.php';
        
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
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

$currentTime = date('YmdHis');
	//계약자 정보

	$comment	    =iconv("utf-8","euc-kr",$_GET['comment']);
	$our_phone	    =iconv("utf-8","euc-kr",$_GET['our_phone']);
	$bun[1]	    =iconv("utf-8","euc-kr",$_GET['bun1']);
	$bun[2]	    =iconv("utf-8","euc-kr",$_GET['bun2']);
	$bun[3]	    =iconv("utf-8","euc-kr",$_GET['bun3']);
	$bun[4]	    =iconv("utf-8","euc-kr",$_GET['bun4']);
	$bun[5]	    =iconv("utf-8","euc-kr",$_GET['bun5']);
	$bun[6]	    =iconv("utf-8","euc-kr",$_GET['bun6']);
	$bun[7]	    =iconv("utf-8","euc-kr",$_GET['bun7']);
	$bun[8]	    =iconv("utf-8","euc-kr",$_GET['bun8']);
	$bun[9]	    =iconv("utf-8","euc-kr",$_GET['bun9']);
	$bun[10]	=iconv("utf-8","euc-kr",$_GET['bun10']);

	$bunlength=sizeof($bun);
	list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);
	echo"<data>\n";
	for($k=1;$k<$bunlength;$k++){
			if($bun[$k]==''){
				continue;
			}
			list($hphone1,$hphone2,$hphone3)=explode("-",$bun[$k]);
			
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
				
				//문자발송 고객
				$msg=$comment;
				$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,LastTime) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,'$currentTime')";
				//echo "<pnum>".$insert_sql."</pnum>\n";
			 $insert_result = mysql_query($insert_sql,$connect);

			 // 페이지 인코딩 설정
						

						// 데이터 설정 (EUC-KR로 입력된 경우)
						$sendData = array(
							"receiver" => $hphone1.$hphone2.$hphone3,
							"msg" => $msg,
							"testmode_yn" => "N"
						);

						$result=sendAligoSms($sendData);

						
			}//


	}

	$json_result = json_encode($result); // 결과를 변수에 저장
	/*echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . $result['receive'] . "\n";
echo "cURL Error: " . (isset($result['curl_error']) ? $result['curl_error'] : 'None') . "\n";
echo "</debug>";*/
	$message="문자 발송 완료 하였습니다!!";
	echo "<message>".$message."</message>\n";
	echo "<message2>".$json_result."</message2>\n";
	echo "<care>".$care."</care>\n";
	echo"</data>";
	?>