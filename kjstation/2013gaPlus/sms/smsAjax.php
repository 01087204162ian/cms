<?include '../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

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
				$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'gaplus', 'CS', '$msg','', '', '', '0', 's',0, 0, 0)";
				//echo "<pnum>".$insert_sql."</pnum>\n";
			 $insert_result = mysql_query($insert_sql,$connect);
			}//
	}
	$message="문자 발송 완료 하였습니다!!";
	echo "<message>".$message."</message>\n";
	echo "<care>".$care."</care>\n";
	echo"</data>";
	?>