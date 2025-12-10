<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$DariMemberNum=iconv("utf-8","euc-kr",$_GET['driver_num']);	
	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
	$val=iconv("utf-8","euc-kr",$_GET['sunbun']);//계약자

	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);//위에서 부터 행
		$updateSql="UPDATE 2012DaeriMember SET p_buho ='$val' ";
		$updateSql.="WHERE num='$DariMemberNum'";


		mysql_query($updateSql,$connect);


		
		$message='처리완료!!';

        
		
//include "../php/endorseNumStore.php";
echo"<data>\n";
	//echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	echo "<sunso>".$sunso."</sunso>\n";
	echo "<SSsigi>".$SSjigi."</SSsigi>\n";
	echo "<state>".$stRow[state]."</state>\n";
	//for($_u_=1;$_u_<15;$_u_++){
	//	echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
   // }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>