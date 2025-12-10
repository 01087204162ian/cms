<?php
include '../../../dbcon.php';

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
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
for($i=0;$i<15;$i++){
	$a2b[$i] =iconv("utf-8","euc-kr",$_GET['a2b'.$i]);	//성명
	$a3b[$i] =iconv("utf-8","euc-kr",$_GET['a3b'.$i]);	//주민번호

	//include "../php/nai.php";
	include "../php/manNai.php";
	$a4b[$i] =iconv("utf-8","euc-kr",$_GET['a4b'.$i]);	//핸드폰번호
	$a5b[$i] =iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  //탁송여부 1 은 일반,2은탁송



	$a7b[$i] =$_GET['a6b'.$i];  //통신사 1sk 2kt 3lig 4sk알뜰폰	5kt알뜰폰 6lig알뜰폰
	$a8b[$i] =$_GET['a7b'.$i];  //명의자 1 본인 2 타인
	$a9b[$i] =iconv("utf-8","euc-kr",$_GET['a8b'.$i]);  //주소
	////////////////////////////////////////
	//2013-08-03
	//탁송인 경우 탁송 증권으로 탁송이 아닌 경우 탁송 아닌 증권으로 
	/////////////////////////////////////////////////////////

	//include "./php/isTasong.php";
		
}	
$a2bLength=sizeof($a2b);

//배서기준일,배서번호를 설정하고 

include "../php/endorseNumSerch.php";

//
$e_count=0;
for($i=0;$i<$a2bLength;$i++){

		if(!$a2b[$i]) continue;
	/*	if(!$a3b[$i]){

			$a3b[$i]="660327-1069017";
		}*/

		$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum,dongbuCerti, ";
		$insertSql.="a6b,a7b,a8b,wdate,endorse_day)";
		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum','$a2b[$i]', ";
		$insertSql.="'$a3b[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b[$i]','$endorseDay','$endorse_num','$policyNum',";
		$insertSql.="'$a7b[$i]','$a8b[$i]','$a9b[$i]',now(),'$endorse_day')";
		
		//echo $a3b[$i];
		mysql_query($insertSql);

		$e_count++;


		// 대리기사에게 주민번호 요청하는 문자 메세지 보내기 위해 //성명,핸드폰번호,대리운전회사,청약자// 2012DaeriMember 의 num 찾는다



		$sql__="SELECT num FROM 2012DaeriMember WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' AND Name='$a2b[$i]' AND Hphone='$a4b[$i]' AND push='1' AND sangtae='1'";
		//echo $sql__;
		$rs__=mysql_query($sql__,$connect);
		$row__=mysql_fetch_array($rs__);

		$num[$i]=$row__[num];

		if($a5b[$i]==1){
	
			$etagName="대리보험";
		}else{

			$etagName="탁송보험";
		}
		$our_phone="070-7841-5962";
		$msg="https://www.kjstation.kr/k.html?num=".$num[$i];
		$msg=$a2b[$i]."님" .$etagName. "가입에 동의 해주세요".$msg;
		list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);
		list($hphone1,$hphone2,$hphone3)=explode("-",$a4b[$i]);
			
		if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
			
			//문자발송 고객
			
			$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,LastTime) ";
			$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,'$currentTime')";

			//echo $insert_sql;
			//echo "<pnum>".$insert_sql."</pnum>\n";



		 $insert_result = mysql_query($insert_sql,$connect);


		 // 페이지 인코딩 설정
					//	header('Content-Type: text/html; charset=utf-8');

						// 데이터 설정 (EUC-KR로 입력된 경우)
						$sendData = array(
							"receiver" => $hphone1.$hphone2.$hphone3,
							"msg" => $msg,
							"testmode_yn" => "N"
						);
						sleep(1);
						sendAligoSms($sendData);
		}

}
include "../php/endorseNumStore.php";

$message='가입 신청 되었습니다!!';
echo"<data>\n";
	echo "<ak>".$ak.$endorse_day.$old_endorse_day."</ak>\n";
	echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>