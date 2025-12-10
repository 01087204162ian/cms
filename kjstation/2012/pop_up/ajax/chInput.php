<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$ch =iconv("utf-8","euc-kr",$_GET['ch']);//증권번호


			$update="UPDATE 2013dajoong_certi SET  ";
			$update.="ch='$ch',wdate=now() ";
			
			$update.="WHERE dajongNum='$num'";

			mysql_query($update,$connect);

	

	$mSql="SELECT * FROM 2013dajoong  WHERE num='$num'";
	$mRs=mysql_query($mSql,$connect);
	$mRow=mysql_fetch_array($mRs);



	
	
			
			


	$message='증권번호  입력완료!!';
	//설계번호 보험ㅅ회사등을 조회하기 위해 

	$tSql="SELECT * FROM 2013dajoong_certi  WHERE dajongNum ='$mRow[num]'";
	$tRs=mysql_query($tSql,$connect);
	$tRow=mysql_fetch_array($tRs);

	$b[1]=$tRow[inCompany];//보험회사
	$b[2]=$tRow[sulNum]; //설계번호
	$b[3]=$tRow[preminum];//보험료
	$b[4]=$tRow[bankName];//은행명
	$b[5]=$tRow[bankNum];//가상계좌
	
	$b[6]=$tRow[policyNum];//증권번호
	$b[7]=$tRow[ch];//처리


	switch($b[7]){
		case 1 :
			$message='미처리';
			break;
		case 2 :
			$message='청약취소';
			break;
		case 3 :
			$message='청약거절';
			break;
		case 4 :
			$message='해지';
			break;
		case 5 :
			$message='계약';
			break;
	}


if(!$b[7]){
	
	$b[7]=1;
}
if(!$b[4]){
	
	$b[4]=0;
}




//문자 보내는 모줄이 있어야 합니다
echo"<data>\n";
	echo "<sql>".$update."</sql>\n";//보험회사
	echo "<b1b>".$b[1]."</b1b>\n";//보험회사
	echo "<b2b>".$b[2]."</b2b>\n";//설계번호
	echo "<b3b>".number_format($b[3])."</b3b>\n";//보험료
	echo "<b4b>".$b[4]."</b4b>\n";//은행명
	echo "<b5b>".$b[5]."</b5b>\n";//가상계좌
	echo "<b6b>".$b[6]."</b6b>\n";//증권번호
	echo "<b7b>".$b[7]."</b7b>\n";//처리유뮤
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>