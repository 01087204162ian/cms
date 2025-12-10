<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$num=iconv("utf-8","euc-kr",$_GET['num']);//CertiTableNum
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);//
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	$startDay=iconv("utf-8","euc-kr",$_GET['startDay']);//매월받는달

	$SigiDay=explode("-",$startDay);

//대리운전회사 최초거래일을 update 하자
	//$update="UPDATE 2012DaeriCompany SET FirstStartDay='$SigiDay[2]',FirstStart='$startDay' WHERE num='$DaeriCompanyNum'";

	//mysql_query($update,$connect);

	$p26p=iconv("utf-8","euc-kr",$_GET['p26p']);
	$ayP=explode(",",$p26p);
	$p26p=$ayP[0].$ayP[1].$ayP[2];//


	$p35p=iconv("utf-8","euc-kr",$_GET['p35p']);
	$ayP=explode(",",$p35p);
	$p35p=$ayP[0].$ayP[1].$ayP[2];//


	$p48p=iconv("utf-8","euc-kr",$_GET['p48p']);
	$ayP=explode(",",$p48p);
	$p48p=$ayP[0].$ayP[1].$ayP[2];//

	$p55p=iconv("utf-8","euc-kr",$_GET['p55p']);
	$ayP=explode(",",$p55p);
	$p55p=$ayP[0].$ayP[1].$ayP[2];//
	
	
	$p50p=$ayP[0].$ayP[1].$ayP[2];//



	

	switch($InsuraneCompany){
		case 1 :
			include "./php/ssMonthP.php";//흥국화재 데리 운전회사로 부터 받는 보험료 입력을 위해 월별보험료 저장
			
			break;
		case 2 :


			break;
		case 3 :
			include "./php/ligMonthP.php";//Lig화재 데리 운전회사로 부터 받는 보험료 입력을 위해 월별보험료 저장

			break;
		case 4 :

			include "./php/hyunMonthP.php";//현대화재 데리 운전회사로 부터 받는 보험료 입력을 위해 월별보험료 저장
			break;
	}
    
	
	
	include "./php/hyuncoPreminum.php";//sameP.php ;perminumSerch.php 에서 사용



	?>