<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$sigi=iconv("utf-8","euc-kr",$_GET['sigi']);

	for($_u_=0;$_u_<6;$_u_++){
		
		$giPrem[$_u_]=iconv("utf-8","euc-kr",$_GET['b'.$_u_]);
		$ayP=explode(",",$giPrem[$_u_]);
		$giPrem[$_u_]=$ayP[0].$ayP[1].$ayP[2];

		$gi2Prem[$_u_]=iconv("utf-8","euc-kr",$_GET['a2a'.$_u_]);
		$ayP=explode(",",$gi2Prem[$_u_]);
		$gi2Prem[$_u_]=$ayP[0].$ayP[1].$ayP[2];
	}


	//$Pcompany =iconv("utf-8","euc-kr",$_GET['Pcompany']);


	


    $byP=explode("-",$sigi);


	//include "../../insuranceGubun.php";
	
		$update="UPDATE 2012CertiTable  SET ";
		$update.= "startyDay='$sigi',";
		$update.= "preminum1='$giPrem[0]',preminumE1='$gi2Prem[0]', ";
		$update.= "preminum2='$giPrem[1]',preminumE2='$gi2Prem[1]', ";
		$update.= "preminum3='$giPrem[2]',preminumE3='$gi2Prem[2]', ";
		$update.= "preminum4='$giPrem[3]',preminumE4='$gi2Prem[3]', ";
		$update.= "preminum5='$giPrem[4]',preminumE5='$gi2Prem[4]', ";
		$update.= "preminum6='$giPrem[5]',preminumE6='$gi2Prem[5]'";
		//$update.= "FirstStartDay='$byP[2]',FirstStart='$sigi' ";
		//$update.= "MonthStartDay='$byP[2]' ";
		$update.= "WHERE num='$CertiTableNum'";


	//echo "sql $update <bR>";
		$rs=mysql_query($update,$connect);
//최초 거래 시작일 12개월로 받는 경우에 시작일을 찾아야 한다 없으면 입력하라고 하고 있으면 날자로 찾아 주여야 겠지

  include "./CoDongbuMonth.php";//dongbuAllSerch.php 공통사용 2012-01-01
	
		if($rs){

			$message='입력완료';

		}

		echo"<data>\n";
			echo "<thisDay>".$thisDay."</thisDay>\n";
			include "./CoDongbuMonth2.php";//dongbuAllSerch.php 공통사용 2012-01-01
			echo "<message>".$message."</message>\n";
			
		echo"</data>";



	?>