<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	//$push			=iconv("utf-8","euc-kr",$_GET['push']);
	$driver_num	    =iconv("utf-8","euc-kr",$_GET['driver_num']);
	//$new_name		=iconv("utf-8","euc-kr",$_GET['new_name']);
	//$new_jumin_a	=iconv("utf-8","euc-kr",$_GET['new_jumin_a']);
	//$new_jumin_b	=iconv("utf-8","euc-kr",$_GET['new_jumin_b']);
	//$ssang_c_num	=iconv("utf-8","euc-kr",$_GET['ssangCnum']);
	$userid	=iconv("utf-8","euc-kr",$_GET['useruid']);
	$endorse_day	=iconv("utf-8","euc-kr",$_GET['endorse_day']);
	//$bank	=iconv("utf-8","euc-kr",$_GET['bank']);
	$sunbun	=iconv("utf-8","euc-kr",$_GET['sunbun']);//회차
	
	

	$update ="UPDATE 2012DaeriMember SET ch='$sunbun' WHERE num='$driver_num'";
	mysql_query($update,$connect);




	$message='처리완료 !!';
	
		


	

		
echo"<data>\n";
	echo "<num>".$endorse_num."</num>\n";
	echo "<care>".$push."</care>\n";
	echo "<message>".$message."</message>\n";
	echo "<cMsg>".$cMsag."</cMsg>\n";
	echo "<comP>".$comP."</comP>\n";
	echo "<sunbun>".$sunbun."</sunbun>\n";
echo"</data>";


?>
