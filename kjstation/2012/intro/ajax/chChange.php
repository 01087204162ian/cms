<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


	$EndorseListNum	    =iconv("utf-8","euc-kr",$_GET['EndorseListNum']);
	$chValue	    =iconv("utf-8","euc-kr",$_GET['chValue']);


	$update="UPDATE 2012EndorseList SET ch='$chValue' WHERE num='$EndorseListNum'";

	mysql_query($update,$connect);

		switch($chValue){
				   case 1 :
					  $chValue='접수';
						break;
					case 2 :
						$chValue='프린트';
					break;
					case 3 :
						$chValue='팩스';
					break;
					case 4 :
						$chValue='배서저장';
					break;
					case 5 :
						$chValue='스캔';
					break;
					case 6 :
					    $chValue='입금예정';
						break;
					case 7 :
						$chValue='수납예정';
					break;
					case 8 :
						$chValue='문자';
					break;
					case 9 :
						$chValue='구배서';
					break;
					case 10 :
						$chValue='종결';
					break;
					case 11 :
						$chValue='선택';
					break;
					
					
				}
	$message=$chValue."처리 함";
	

echo"<data>\n";
	
	echo"<message>".$message."</message>\n";
echo"</data>";

	?>