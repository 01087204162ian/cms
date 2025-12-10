<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	//차일드 계약의 모계약을 설정하는것임
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$moNum=iconv("utf-8","euc-kr",$_GET['moNum']);// 모계약의 num
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
			
	 
			$update="UPDATE 2012CertiTable  SET moValue='2', moNum='$moNum' ";
			$update.="WHERE num='$CertiTableNum'";
			mysql_query($update,$connect);
			$message="완료!!";


			

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";



	?>