<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$DariMemberNum=iconv("utf-8","euc-kr",$_GET['DariMemberNum']);	
	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
/*for($i=0;$i<15;$i++){
	$a2b[$i] =iconv("utf-8","euc-kr",$_GET['a2b'.$i]);	//성명
	$a3b[$i] =iconv("utf-8","euc-kr",$_GET['a3b'.$i]);	//주민번호

	include "../php/nai.php";
	$a4b[$i] =iconv("utf-8","euc-kr",$_GET['a4b'.$i]);	//핸드폰번호
		
}	
$a2bLength=sizeof($a2b);*/

//배서기준일,배서번호를 설정하고 

include "../php/boardEndorseNumSerch.php";

//
$e_count=1;
//for($i=0;$i<$a2bLength;$i++){

		//if(!$a3b[$i]) continue;

		$insertSql="UPDATE 2021DaeriMember SET sangtae='1',OutPutDay='$endorseDay',EndorsePnum='$endorse_num',cancel='42' ";
		$insertSql.="WHERE num='$DariMemberNum'";
		

		
		mysql_query($insertSql);

		//$e_count++;


		$message='해지 신청 되었습니다!!';
//}
include "../php/boardEndorseNumStore.php";
echo"<data>\n";
	echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>