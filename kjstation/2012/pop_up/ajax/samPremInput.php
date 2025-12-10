<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$sPrem=iconv("utf-8","euc-kr",$_GET['sPrem']);
	$driverNum=iconv("utf-8","euc-kr",$_GET['driverNum']);


include '../../../dbcon.php';



		$update="UPDATE 2012DaeriMember  SET sPrem='$sPrem' WHERE num='$driverNum'";
		mysql_query($update,$connect);
		$message='보험료  입력 완료!!';


	

  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		//echo "<msg3>".$sangtae."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>