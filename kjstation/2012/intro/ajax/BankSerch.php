<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$pBankNum	    =iconv("utf-8","euc-kr",$_GET['pBankNum']);

	$Sql4="SELECT bankName,pName,bankaccount FROM bankAccount WHERE num='$pBankNum'";
	$rs4=mysql_query($Sql4,$connect);
	$row=mysql_fetch_array($rs4);


echo"<data>\n";
	echo "<message>".$row[bankName].$row[pName].$row[bankaccount]."</message>\n";
echo"</data>";

	?>