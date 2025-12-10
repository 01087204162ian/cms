<?php
include '../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//car 2승용 3RV //제조사 2현대,3기아,4쉐보레,5르노삼성
	//////////////////////////////////////////////////////////////////
	$car	    =iconv("utf-8","euc-kr",$_GET['car']);
	$maker	    =iconv("utf-8","euc-kr",$_GET['maker']);
	

	


echo"<data>";
	echo"<car>".$car."</car>\n";
	echo"<maker>".$maker."</maker>\n";
echo"</data>";

	?>