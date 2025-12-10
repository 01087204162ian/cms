<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기

	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$certi =iconv("utf-8","euc-kr",$_GET['certi']);//증권번호

		
			$update="UPDATE 2012hiauto  SET  ";
			$update.="policyNum='$certi' ";
			
			$update.="WHERE num='$num'";

			mysql_query($update,$connect);


	$message='증권번호  입력완료!!';
	
echo"<data>\n";
	echo "<sql>".$update."</sql>\n";//보험회사
	//설계번호 보험ㅅ회사등을 조회하기 위해 
	include "./php/hiautoInsSerch.php";//보험회사 정보 조회 
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>