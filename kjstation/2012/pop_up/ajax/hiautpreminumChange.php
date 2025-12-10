<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$preminum =iconv("utf-8","euc-kr",$_GET['preminum']);//설계번호


	$pr=explode(',',$preminum);

	$preminum=$pr[0].$pr[1];

			
			$update="UPDATE 2012hiauto  SET  ";
			$update.="preminum='$preminum' ";
			
			$update.="WHERE num='$num'";

			mysql_query($update,$connect);


	$message='보험료 입력완료!!';

echo"<data>\n";
	//설계번호 보험ㅅ회사등을 조회하기 위해 
	include "./php/hiautoInsSerch.php";//보험회사 정보 조회 
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>