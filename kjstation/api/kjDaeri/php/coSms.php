<?
	// "2012DaeriCompany Table  hphone 과 2012Costomer Table 의 연락처 일치 여부 확인하는 작업"; echo "<BR>";
	// "/2012/pop_up/ajax/php/coSms.php 에서   읽기전용이 아닌 아이디 개수  2개 이상인 경우는  2012Costomer Table 의 연락처로 문자 통보하고 "; echo "<BR>";
	// "1개인 경우는 2012DaeriCompany Table  연락처로 문자 발송으로 2025-03-27 수정함 ";echo "<BR>";
	if($divi==1){ //정상분납인 경우
		
		$endorsePreminum='';
	}else{
		
		$endorsePreminum=$row2['Endorsement_insurance_premium'];
	}

	$c_premiun=$row2['Endorsement_insurance_company_premium'];
//2022-10-10 
//케이드라이브처럼 관리자가 다수인 경우 각 관리자에게 문자가 전송될 수 있도록 수정함.
//그 경우 1명만 배서리스트에 표기될 수 있도록  SMSData 

$id_sql="SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum='$dNum' and readIs!='1' "; //읽기전용이 아닌 아이디 개수 

//echo $id_sql;

$id_rs=mysql_query($id_sql,$conn);

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
				$InsuraneCompany=10;

			}

	//echo $msg;			
			
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					//문자발송 고객
					
					//$msg=$comment;
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
					$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
					$insert_sql .= "preminum,preminum2,c_preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom,qboard,dagun) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
					$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$InsuraneCompany','$cNum','$pNum','$userId', ";
					$insert_sql.= "'$endorsePreminum','$endorsePreminum2','$c_premiun',";
					$insert_sql.= "'$num','$dNum','$policyNum','$endorse_day','$row[MemberNum]','$push_2','$inSuranceCom','$qboard','$dagun')";
					//echo "pre  $preminum <br>";
					//echo "  $insert_sql <Br> ";echo "// ";
					 $insert_result = mysql_query($insert_sql,$conn);
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
			$dagun=1;
				
			
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					//문자발송 고객
					
					//$msg=$comment;
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
					$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
					$insert_sql .= "preminum,preminum2,c_preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom,qboard,dagun) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
					$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$InsuraneCompany','$cNum','$pNum','$userId', ";
					$insert_sql.= "'$endorsePreminum','$endorsePreminum2','$c_premiun',";
					$insert_sql.= "'$num','$dNum','$policyNum','$endorse_day','$row[MemberNum]','$push_2','$InsuraneCompany','$qboard','$dagun')";
					//echo "pre  $preminum <br>";
					//echo " ans1 $insert_sql <Br> ";
					 $insert_result = mysql_query($insert_sql,$conn);
				 if( $insert_result){
					 $message='문자 발송 완료';
				 }
				}
			$a[4]=$con_phone1;



}
/*$row2['idCount']=$idCount;
$row2['sms']=$id_sql;

$row2['insert_sql']=$insert_sql;
//echo " ans1 $insert_sql <Br> ";*/
?>