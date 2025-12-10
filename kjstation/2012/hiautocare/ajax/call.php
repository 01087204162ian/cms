<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//state 1 실효 제외 state 2 : 포함
	//////////////////////////////////////////////////////////////////
	$num	    =iconv("utf-8","euc-kr",$_GET['num']);
	$val	    =iconv("utf-8","euc-kr",$_GET['val']);


	$sql="SELECT * FROM 2012hiauto   WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	$hp=explode("-",$row[hphone]);


	switch($row[inCompany]){
		case 1 :
			$inCom='흥국';
			break;
		case 2 :
			$inCom='동부';
			break;
		case 3 :
			$inCom='LiG';
			break;
		case 4 :
			$inCom='현대';
			break;

	}
	switch($row[bankName]){
			case 1 :
				$bankName='국민';
					break;
			case 2 :
				$bankName='농협';
				break;
			case 3 :
				$bankName='신한';
				break;
			case 4 :
				$bankName='우리';
				break;
			case 5 :
				$bankName='경남';
				break;
			case 6 :
				$bankName='광주';
				break;
			case 7 :
				$bankName='하나';
				break;
	}
switch($val){

	case 2 :
			$msg='보내신 팩스 확인하였습니다. 감사합니디';
			$message='팩스문자발송';
		break;
	case 3 :
			$msg='하이오토케어 보험료'.number_format($row[preminum]).$bankName.$row[bankNum].'['.$inCom.']화재';
			$message='입금요청문자발송';
		break;
	case 4 :
			$msg='입금확인 되었습니다 ';//증권을'.$row[email].'로 보내겠습니다';
			$message='입금확인문자';
		break;
	case 5 :
			
			$msg=$inCom.'하이오토케어 증권번호'.$row[policyNum].'이며 증권은'.$row[email].'로 발송 되었습니다';
			$message='증권발송 ';
		break;
	case 6 :
			$msg='하이오토케어보험관련 사업자등록증,소방방재청공문 02-6442-6419 팩스 보내주세요';
			$message='팩스 재요청';
			$val=1;
		break;
	case 10 :
			
			$message='취소합니다';
			
		break;

}



	
$update="UPDATE 2012hiauto  SET dCh='$val' WHERE num='$num'";
	mysql_query($update,$connect);


if($val<=6){
	
	$company_tel="070-7841-5964";

	$hp=explode("-",$row[hphone]);
	list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
				$send_name='gaplus';

	if($hp[0]=='011' || $hp[0]=='016' || $hp[0]=='017' || $hp[0]=='018' || $hp[0]=='019' || $hp[0]=='010'){
					
					//문자발송 고객
					
					//$msg=$comment;
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, ";
					$insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2,company,ssang_c_num,endorse_num,userid, ";
					$insert_sql .= "preminum,2012DaeriMemberNum,2012DaeriCompanyNum,policyNum,endorse_day,damdanga,push,insuranceCom) ";
					$insert_sql.= "VALUES ('csdrive', '$hp[0]', '$hp[1]', '$hp[2]', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
					$insert_sql.= "'$msg','', '', '', '0', 's',0, 0, 0,'$inSuranceCom','$CertiTableNum','$eNum','$userId', ";
					$insert_sql.= "'$endorsePreminum','$memberNum','$DaeriCompanyNum','$cRow[policyNum]','$DendorseDay','$dRow[MemberNum]','$push','$inSuranceCom')";
					//echo "pre  $preminum <br>";
					//echo " ans1 $insert_sql <Br> ";
				 $insert_result = mysql_query($insert_sql,$connect);
				
		}
}
echo"<data>";	   
   echo "<a1>".$num."</a1>\n";
   echo "<a2>".$val."</a2>\n";
   echo "<message>".$message."</message>\n";
echo"</data>";

	?>