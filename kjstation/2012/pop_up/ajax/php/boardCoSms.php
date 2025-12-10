<?


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
				$insert_sql .= "preminum,preminum2,c_preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom,qboard) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
				$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$inSuranceCom','$CertiTableNum','$eNum','$userId', ";
				$insert_sql.= "'$endorsePreminum','$endorsePreminum2','$c_preminum','$memberNum','$DaeriCompanyNum','$cRow[policyNum]','$DendorseDay','$dRow[MemberNum]','$push','$inSuranceCom','2')";
				//echo "pre  $preminum <br>";
				//echo " ans1 $insert_sql <Br> ";
			 $insert_result = mysql_query($insert_sql,$connect);
			 if( $insert_result){
				 $message='문자 발송 완료';
			 }
			}
		$a[4]=$con_phone1;


?>