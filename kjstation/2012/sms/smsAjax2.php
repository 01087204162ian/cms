<?include '../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$comment	    =iconv("utf-8","euc-kr",$_GET['comment']);
	$our_phone	    ="070-7841-5962";
	$checkPhone		=iconv("utf-8","euc-kr",$_GET['checkPhone']);
	
	list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);
	list($hphone1,$hphone2,$hphone3)=explode("-",$checkPhone);
	$dong_db="preminum";
	$send_name='preminum';
	$company=10;
	echo"<data>\n";
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
				
				//문자발송 고객
				$msg=$comment;
				$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
				$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
				$insert_sql .= "preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
				$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$inSuranceCom','$CertiTableNum','$eNum','$userId', ";
				$insert_sql.= "'$endorsePreminum','$memberNum','$DaeriCompanyNum','','$DendorseDay','$dRow[MemberNum]','$push')";
				
				//echo "<pnum>".$insert_sql."</pnum>\n";
			 $insert_result = mysql_query($insert_sql,$connect);
			}//
	
	$message="문자 발송 완료 하였습니다!!";
	echo "<message>".$message."</message>\n";
	echo "<care>".$care."</care>\n";
	echo"</data>";
	?>