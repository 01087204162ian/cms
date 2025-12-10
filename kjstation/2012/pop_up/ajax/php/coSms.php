<?


//2025-06-25     surem     문자를 사용할 수 없음
// 알리고 변경
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
$currentTime = date('YmdHis');
//2022-10-10 
//케이드라이브처럼 관리자가 다수인 경우 각 관리자에게 문자가 전송될 수 있도록 수정함.
//그 경우 1명만 배서리스트에 표기될 수 있도록  SMSData 

$id_sql="SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and readIs!='1'"; //읽기전용이 아닌 아이디 개수 

//echo $id_sql;

$id_rs=mysql_query($id_sql,$connect);

$idCount=mysql_num_rows($id_rs);

//echo "아이디 개수 "; echo $idCount;


if($idCount>=2){
	//echo $idCount;
	for($m_=0;$m_<$idCount;$m_++){

		//echo $m_; echo "<br>";
		if($m_>=1){

			$dagun=2;  // 배서 보험료 카운트할 때는 dagun 1 만 적용하고, 2는 적용하지 않음 
		}else{

			$dagun=1;
		}
		$id_rows=mysql_fetch_array($id_rs);

		list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
	    list($hphone1,$hphone2,$hphone3)=explode("-",$id_rows['hphone']);
			if($endorseCh==1){

				$send_name='drive';
				$company='';

			}else{

				$dong_db="preminum";
				$send_name='preminum';
				$inSuranceCom=10;

			}

	//echo $msg;			
			
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					//문자발송 고객
					
					//$msg=$comment;
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
					$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
					$insert_sql .= "preminum,preminum2,c_preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom,qboard,dagun,LastTime) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
					$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$inSuranceCom','$CertiTableNum','$eNum','$userId', ";
					$insert_sql.= "'$endorsePreminum','$endorsePreminum2','$c_preminum','$memberNum','$DaeriCompanyNum','$cRow[policyNum]','$DendorseDay','$dRow[MemberNum]','$push','$inSuranceCom','$qboard','$dagun','$currentTime')";
					//echo "pre  $preminum <br>";
					//echo "  $insert_sql <Br> ";
				 $insert_result = mysql_query($insert_sql,$connect);

				// 페이지 인코딩 설정
						//header('Content-Type: text/html; charset=utf-8');

						// 데이터 설정 (EUC-KR로 입력된 경우)
						$sendData = array(
							"receiver" => $hphone1.$hphone2.$hphone3,
							"msg" => $msg,
							"testmode_yn" => "N"
						);
						sleep(1);
						sendAligoSms($sendData);
				 if( $insert_result){
					 $message='문자 발송 완료';
				 }
				}
			$a[4]=$id_rows['hphone'];



	}
	

}else{
	

	
	list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
	list($hphone1,$hphone2,$hphone3)=explode("-",$con_phone1);
			if($endorseCh==1){

				$send_name='drive';
				$company='';

			}else{

				$dong_db="preminum";
				$send_name='preminum';
				$inSuranceCom=10;

			}

				
			
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					//문자발송 고객
					
					//$msg=$comment;
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
					$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
					$insert_sql .= "preminum,preminum2,c_preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom,qboard,LastTime) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
					$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$inSuranceCom','$CertiTableNum','$eNum','$userId', ";
					$insert_sql.= "'$endorsePreminum','$endorsePreminum2','$c_preminum','$memberNum','$DaeriCompanyNum','$cRow[policyNum]','$DendorseDay','$dRow[MemberNum]','$push','$inSuranceCom','$qboard','$currentTime')";
					//echo "pre  $preminum <br>";
					//echo " ans1 $insert_sql <Br> ";
				 $insert_result = mysql_query($insert_sql,$connect);

					 header('Content-Type: text/html; charset=utf-8');

							// 데이터 설정 (EUC-KR로 입력된 경우)
							$sendData = array(
								"receiver" => $hphone1.$hphone2.$hphone3,
								"msg" => $msg,
								"testmode_yn" => "N"
							);
							sleep(1);
							sendAligoSms($sendData);
				 if( $insert_result){
					 $message='문자 발송 완료';
				 }
				}
			$a[4]=$con_phone1;



}



?>