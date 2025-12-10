<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num=iconv("utf-8","euc-kr",$_GET['num']);
	$FirstartDay=iconv("utf-8","euc-kr",$_GET['FirstartDay']);


include '../../../dbcon.php';

$m=explode("-",$FirstartDay);


		$update="UPDATE 2012DaeriCompany   SET FirstStartDay='$m[2]' ,FirstStart='$FirstartDay' WHERE num='$num'";
		mysql_query($update,$connect);
		$message='보험료 받는 날 입력 완료!!';


	

  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		//echo "<msg3>".$sangtae."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>