<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	//자기부담금 설정하는 하면
	$jagi=iconv("utf-8","euc-kr",$_GET['jagi']);// 모계약의 num
	$moNum=iconv("utf-8","euc-kr",$_GET['moNum']);// 모계약의 num
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
			
	 
			$update="UPDATE 2012CertiTable  SET jagi='$jagi'";
			$update.="WHERE num='$moNum'";
			mysql_query($update,$connect);
			$message="완료!!";


			

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";



	?>