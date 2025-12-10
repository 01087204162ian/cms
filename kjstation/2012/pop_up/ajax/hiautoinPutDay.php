<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$Did =iconv("utf-8","euc-kr",$_GET['Did']);//가상계좌

		
			$update="UPDATE 2012hiauto  SET  ";
			$update.="inputDay ='$Did' ";
			
				$update.="WHERE num='$num'";

			mysql_query($update,$connect);


	$message='계약일 입력완료!!';
	//설계번호 보험ㅅ회사등을 조회하기 위해 

	
echo"<data>\n";
//설계번호 보험ㅅ회사등을 조회하기 위해 
	include "./php/hiautoInsSerch.php";//보험회사 정보 조회 
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>