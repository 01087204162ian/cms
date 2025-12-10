<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$msg	    =iconv("utf-8","euc-kr",$_GET['comment']);
	$con_phone1	    =iconv("utf-8","euc-kr",$_GET['checkPhone']);
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$eNum=iconv("utf-8","euc-kr",$_GET['eNum']);
	$preminum	    =iconv("utf-8","euc-kr",$_GET['preminum']);
	$userId	    =iconv("utf-8","euc-kr",$_GET['userId']);
	$company_tel='070-7841-5962';

	//* 대리운전회사  dnum 을 찾기 위해  2016-01-02

	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['dnum']);  //2016-01-02 

			


		include "./php/coSms.php";//endorseChange.php에서 공동사용
		include "./php/smsQuery.php";

echo"<data>\n";
	echo "<message>".$message."</message>\n";
	include "./php/smsData.php";//endorseAjaxSerch.php에서 공동으로 사용
echo"</data>";
	?>