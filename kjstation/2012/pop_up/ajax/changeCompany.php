<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$com =iconv("utf-8","euc-kr",$_GET['com']);//보험회사

/*	$mSql="SELECT * FROM 2013dajoong  WHERE num='$num'";
	$mRs=mysql_query($mSql,$connect);
	$mRow=mysql_fetch_array($mRs);



/*	$Jumin=$mRow[Jumin];
	$Serial=$mRow[serial];

	$dSql="SELECT * FROM 2013dajoong_certi   WHERE dajongNum='$num'";
	$dRs=mysql_query($dSql,$connect);

	$dRow=mysql_fetch_array($dRs);

	if($dRow[num]){
			
			$update="UPDATE 2013dajoong_certi SET inCompany='$com', ";
			$update.="sulNum='',BankName='',bankNum='',preminum='',PolicyNum='',ch=1,wdate=now() ";
			
			$update.="WHERE dajongNum='$num'";

			mysql_query($update,$connect);

	}else{
			$insert="INSERT into 2013dajoong_certi  (dajongNum,Jumin,Serial,inCompany,sulNum,BankName,bankNum,preminum,PolicyNum,ch,wdate)";
			$insert.="values ('$num','$Jumin','$Serial','$com','','','','','','',now())";

			mysql_query($insert,$connect);

	}
*/
		
			$update="UPDATE 2013dajoong  SET inCompany='$com', ";
			$update.="sulNum='',BankName='',bankNum='',PolicyNum='' ";
			$update.="WHERE num='$num'";
			mysql_query($update,$connect);


$message='보험회사 정함';
	

echo"<data>\n";
	echo "<sql>".$update."</sql>\n";//보험회사
	//설계번호 보험ㅅ회사등을 조회하기 위해 
	include "./php/dajoongInsSerch.php";//보험회사 정보 조회 

	echo "<message>".$message."</message>\n";


	
echo"</data>";

	?>