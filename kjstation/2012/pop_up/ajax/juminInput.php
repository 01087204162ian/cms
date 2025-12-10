<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$jumin=iconv("utf-8","euc-kr",$_GET['jumin']);
	$driverNum=iconv("utf-8","euc-kr",$_GET['driverNum']);


include '../../../dbcon.php';



		$update="UPDATE 2012DaeriMember  SET Jumin='$jumin' WHERE num='$driverNum'";
		mysql_query($update,$connect);
		$message='주민번호  입력 완료!!';


	

  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		//echo "<msg3>".$sangtae."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>