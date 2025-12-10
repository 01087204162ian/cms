<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$DariMemberNum=iconv("utf-8","euc-kr",$_GET['DariMemberNum']);	
	$etag =iconv("utf-8","euc-kr",$_GET['etag']);	


		$insertSql="UPDATE 2012DaeriMember SET etag='$etag' ";
		$insertSql.="WHERE num='$DariMemberNum'";
		

		
		mysql_query($insertSql,$connect);

		//$e_count++;


		$message='변경  되었습니다!!';
//}

echo"<data>\n";
	echo "<enday>".$insertSql."</enday>\n";
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>