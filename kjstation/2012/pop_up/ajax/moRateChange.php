<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$moRate=iconv("utf-8","euc-kr",$_GET['moRate']);// 1모계약 2모계약아님

			
	  
			$update="UPDATE 2012CertiTable  SET moRate='$moRate'  ";
			$update.="WHERE num='$CertiTableNum'";
			mysql_query($update,$connect);
			$message="완료!!";


			$monthsP=$preminum;//월별보험료

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			
		echo"</data>";



	?>