<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

echo "<values>\n";
	
	$memberNum=iconv("utf-8","euc-kr",$_GET['memberNum']);
	$proc=iconv("utf-8","euc-kr",$_GET['proc']);
	$shp=iconv("utf-8","euc-kr",$_GET['shp']);
	$nhp=iconv("utf-8","euc-kr",$_GET['nhp']);

	$address=iconv("utf-8","euc-kr",$_GET['address']);

if($proc=='_tongSin'){

	$sql="UPDATE 2012DaeriMember SET a6b='$shp' WHERE num='$memberNum'";

	mysql_query($sql,$connect);

	$message="통신사 변경완료!!";

	echo("\t<item>\n");
		echo("\t\t<message>".$message."</message>\n");
	echo("\t</item>\n");

}else if($proc=='_tongName'){

	$sql="UPDATE 2012DaeriMember SET a7b='$nhp' WHERE num='$memberNum'";

	mysql_query($sql,$connect);

    if($nhp==3){
		$message="인증완료!!";
	}else{
		$message="명의자 변경완료!!";
	}

	echo("\t<item>\n");
		echo("\t\t<message>".$message."</message>\n");
	echo("\t</item>\n");

}else if($proc=='_tongAddress'){

	$sql="UPDATE 2012DaeriMember SET a8b='$address' WHERE num='$memberNum'";

	mysql_query($sql,$connect);

	$message="주소  변경완료!!";

	echo("\t<item>\n");
		echo("\t\t<message>".$message."</message>\n");
	echo("\t</item>\n");

}

echo "</values>";

	?>