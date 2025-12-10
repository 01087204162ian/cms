<?

//echo "unab_state $bunab_state <br>";

/*if($userid=='kibs0327'){
				$our_phone='02-558-7120';
		   }else{
			  $our_phone='02-558-7384';
		   }*/
		$our_phone="070-7841-5962";
		list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);//보내는 사람의 전화 번호
		list($hphone1,$hphone2,$hphone3)=explode("-",$con_phone);//받는사람의 전화번호
		
			$msg_2="님의 동부화재 대리운전보험 ";
			$msg_3="회차 보험료 수납이 완료 되었습니다.감사합니다";
		
			
			$msg=$oun_name.$msg_2.$bunab_state.$msg_3;
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,now())";
					
			$insert_result = mysql_query($insert_sql,$connect);

			}

			//echo "insert_sql $insert_sql <Br>";
		



?>