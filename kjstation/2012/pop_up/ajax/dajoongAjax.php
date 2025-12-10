<?php
  include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	$a1	    =iconv("utf-8","euc-kr",$_GET['a1']);
	$a2	    =iconv("utf-8","euc-kr",$_GET['a2']);
	$a3	    =iconv("utf-8","euc-kr",$_GET['a3']);
	$a4	    =iconv("utf-8","euc-kr",$_GET['a4']);
	$a5	    =iconv("utf-8","euc-kr",$_GET['a5']);

	$a6	    =iconv("utf-8","euc-kr",$_GET['a6']);
	$a7	    =iconv("utf-8","euc-kr",$_GET['a7']);
	$a8	    =iconv("utf-8","euc-kr",$_GET['a8']);
	$a9	    =iconv("utf-8","euc-kr",$_GET['a9']);
	$a15	    =iconv("utf-8","euc-kr",$_GET['a15']); //객실인견우 면겆
	$a10	    =iconv("utf-8","euc-kr",$_GET['a10']);
	$a11       =iconv("utf-8","euc-kr",$_GET['a11']);


	$a16       =iconv("utf-8","euc-kr",$_GET['a16']);//업소전화번호


	$postNum=iconv("utf-8","euc-kr",$_GET['a28']);
	$address1=iconv("utf-8","euc-kr",$_GET['a29']);
	$address2=iconv("utf-8","euc-kr",$_GET['a30']);
    $num=iconv("utf-8","euc-kr",$_GET['num']);

if(!$num){

		$insert="INSERT into 2013dajoong (Name,Jumin,hphone,phone,sigi,email,businessNum,company,comKind,gi,gi2,serial,wdate, ";
		$insert.="postNum,address1,address2) ";

		$insert.="values('$a1','$a2','$a3','$a16','$a4','$a5','$a6','$a7','$a8','$a9','$a15','$a10',now(), ";
		
		$insert.="'$postNum','$address1','$address2')";

		$rs=mysql_query($insert,$connect);
		if($rs){ 
			$msg='가입 신청 완료 사업자등록증,소방방재청공문 팩스02-6442-6419 보내주세요';
		}
		$hp=explode("-",$a4);

		$company_tel="070-7841-5962";
		list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
					$send_name='drive';
					
//문자 발송
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
					
			} //문자 발송


		

}else{//22번째 줄


		$update="UPDATE  2013dajoong SET Name='$a1',Jumin='$a2',hphone='$a3',phone='$a16',sigi='$a4', ";
		$update.="email='$a5',businessNum='$a6',company='$a7',comKind='$a8',gi='$a9',gi2='$a15',serial='$a10',wdate=now(), "; 
		$update.="postNum='$postNum',address1='$address1',address2='$address2' ";
		$update.="WHERE num='$num'";

		$rs=mysql_query($update,$connect);

		if($rs){ 
			$msg='update 되었습니다 !!';
		}

}


echo"<data>";
	   echo "<insert_sql>".$insert_sql.$update."</insert_sql>\n";
	   echo "<message>".$msg."</message>\n";
	   echo "<a1a>".$a1."</a1a>\n";
	   echo "<a2a>".$a2."</a2a>\n";
	   echo "<a3a>".$a3."</a3a>\n";
	   echo "<a4a>".$a4."</a4a>\n";
	   echo "<a5a>".$a5."</a5a>\n";
	   echo "<a6a>".$a6."</a6a>\n";
	   echo "<a7a>".$a7."</a7a>\n";
	   echo "<a8a>".$a8."</a8a>\n";
	   echo "<a9a>".$a9."</a9a>\n";
	   echo "<a10a>".$a10."</a10a>\n";
	   echo "<a15a>".$a15."</a15a>\n";
	   echo "<a16a>".$a16."</a16a>\n";
	   
		
		include "./php/dajoongInsSerch.php";//보험회사 정보 조회 

	  
echo"</data>";

	?>