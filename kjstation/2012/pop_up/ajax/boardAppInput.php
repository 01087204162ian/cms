<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$appNumber=iconv("utf-8","euc-kr",$_GET['appNumber']);
	$driverNum=iconv("utf-8","euc-kr",$_GET['driverNum']);

$m=explode("-",$appNumber);
if($m[1]=='0000'){$appNumber='';}
include '../../../dbcon.php';





		$update="UPDATE 2012DaeriMember  SET dongbuSelNumber='$appNumber' WHERE num='$driverNum'";
		mysql_query($update,$connect);
		$message='설계번호 입력 완료!!';


	

  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		//echo "<msg3>".$sangtae."</msg3>\n";
		echo "<message>".$message.$daeriCompanyNum.$sql."</message>\n";
		
	
 echo"</data>";
?>