<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$num=iconv("utf-8","euc-kr",$_GET['num']);//CertiTableNum
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);//
	//$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
	
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	 $dSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
	 $drs=mysql_query($dSql,$connect);
	 $dRow=mysql_fetch_array($drs);

	$startDay=$dRow[FirstStart];

	switch($InsuraneCompany){
		case 1 :
			include "./php/ssP.php";
		    include "./php/coPreminum.php";//sameP.php ;perminumSerch.php 에서 사용
			break;
		case 2 :
			

			break;
		case 3 :

			include "./php/LigP.php";
			include "./php/ligcoPreminum.php";//sameP.php ;perminumSerch.php 에서 사용
			break;
		case 4 :

			include "./php/hyundaiP.php";
			include "./php/hyuncoPreminum.php";//sameP.php ;perminumSerch.php 에서 사용
			break;
	}
    
	
	
	



	?>