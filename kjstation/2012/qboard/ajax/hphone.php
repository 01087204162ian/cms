<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	$memberNum	    =iconv("utf-8","euc-kr",$_GET['memberNum']);
	


	$sql="UPDATE 2021DaeriMember SET Hphone='$hphone' WHERE num='$memberNum'";

	mysql_query($sql,$connect);

	$message="핸드폰 번호 입력 완료";

echo"<data>\n";
	echo "<memberNum>".$sql.$memberNum."</memberNum>\n";
	echo "<hphone>".$hphone."</hphone>\n";
	echo "<message>".$message."</message>\n";
	

echo"</data>";

	?>