<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기




	$num=iconv("utf-8","euc-kr",$_GET['num']);	
	$selNum =iconv("utf-8","euc-kr",$_GET['selNum']);//설계번호
	$inSNum =iconv("utf-8","euc-kr",$_GET['inSNum']);//보험회사

	if($inSNum==4){

		$update="UPDATE 2013dajoong SET  ";
			$update.="sulNum='$selNum',policyNum='$selNum' ";
			$update.="WHERE num='$num'";
			mysql_query($update,$connect);

	}else{

			
			$update="UPDATE 2013dajoong SET  ";
			$update.="sulNum='$selNum' ";
			$update.="WHERE num='$num'";
			mysql_query($update,$connect);
	}
	$message='설계번호 입력완료!!';

echo"<data>\n";
	//echo "<sql>".$update."</sql>\n";
	include "./php/dajoongInsSerch.php";//보험회사 정보 조회 

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>