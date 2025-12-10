<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	$cNum=iconv("utf-8","euc-kr",$_GET['cNum']);
	$joong=iconv("utf-8","euc-kr",$_GET['get']);

	//echo "num $num  <br> ch $ch <br> $kor_str"; 




	//echo "me $change ";


			$update =" UPDATE SMSData SET ";
			$update .="get='$joong' WHERE SeqNo='$cNum'";

			$rs=mysql_query($update,$connect);
		
		if($rs){

			$message='처리완료';

		}
		echo"<data>\n";
			echo "<num>".$update."</num>\n";
			echo "<ch>".$get."</ch>\n";
			echo "<message>".$message."</message>\n";
			echo "<care>".$change_1."</care>\n";
		echo"</data>";



	?>