<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$pnum=iconv("utf-8","euc-kr",$_GET['endorse_num']);//d
	$enNum=iconv("utf-8","euc-kr",$_GET['enNum']);//lig 배서 번호


include '../../../dbcon.php';


		$update="UPDATE 2012EndorseList   SET enNum='$enNum' WHERE pnum='$pnum' and CertiTableNum='$CertiTableNum' ";
		mysql_query($update,$connect);
		$message='번호 입력 완료!!';
  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		//echo "<msg3>".$sangtae."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>