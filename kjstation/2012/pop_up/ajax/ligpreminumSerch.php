<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$num=iconv("utf-8","euc-kr",$_GET['num']);//
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	//$yearP=iconv("utf-8","euc-kr",$_GET['yearP']);//
	//$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
if($emday){
	$now_time=$emday;

	$em=explode("-",$emday);
	$today=$em[2];
}//배서기준일 기준으로 보험료 산출하기 위해
	
	$Dsql="SELECT FirstStart FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";


	$Drs=mysql_query($Dsql,$connect);
	$DRow=mysql_fetch_array($Drs);

	
	$startDay=$DRow[FirstStart];

	$SigiDay=explode("-",$startDay);
	
	include "./php/ligcoPreminum.php";//sameP.php ;perminumSerch.php 에서 사용


	?>