<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$nabsunso=iconv("utf-8","euc-kr",$_GET['nabsunso']);	
	$certiTableNum =iconv("utf-8","euc-kr",$_GET['certiTableNum']);	
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);	//몇번째인지
	
	


	//납부 회차를 upDate 한다.

		$update="UPDATE 2012CertiTable  SET gita='$nabsunso' ";
		$update.="WHERE num='$certiTableNum'";
		mysql_query($update);


   switch($nabsunso){
				   case 1 :
					  $nabsunso='일반';
						break;
					case 2 :
						$nabsunso='탁송';
					break;
					case 3 :
						$nabsunso='3회차';
					break;
					
					
					
				}
			


		///////////////////////////////////////

		$message=$nabsunso."으로 변경하였습니다";

echo"<data>\n";
	echo "<naColor>".$naColor."</naColor>\n";//
	echo "<naState>".$naState.$gigan."</naState>\n";//
	echo "<val>".$sunso."</val>\n";
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>