<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$BankName =iconv("utf-8","euc-kr",$_GET['BankName']);//설계번호

			$update="UPDATE 2013dajoong SET  ";
			$update.="bankName ='$BankName' ";
			
			$update.="WHERE num='$num'";

			mysql_query($update,$connect);


	$message='은행명 입력완료!!';

echo"<data>\n";
	echo "<sql>".$update."</sql>\n";
	//설계번호 보험ㅅ회사등을 조회하기 위해 
	include "./php/dajoongInsSerch.php";//보험회사 정보 조회 
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>