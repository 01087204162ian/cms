<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
for($i=0;$i<15;$i++){
	$a2b[$i] =iconv("utf-8","euc-kr",$_GET['a2b'.$i]);	//성명
	$a3b[$i] =iconv("utf-8","euc-kr",$_GET['a3b'.$i]);	//주민번호

	//include "../php/nai.php";
	include "../php/manNai.php";
	$a4b[$i] =iconv("utf-8","euc-kr",$_GET['a4b'.$i]);	//핸드폰번호
	$a5b[$i] =iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  //탁송여부 1 은 일반,2은탁송



	$a7b[$i] =$_GET['a6b'.$i];  //통신사 1sk 2kt 3lig 4sk알뜰폰	5kt알뜰폰 6lig알뜰폰
	$a8b[$i] =$_GET['a7b'.$i];  //명의자 1 본인 2 타인
	$a9b[$i] =iconv("utf-8","euc-kr",$_GET['a8b'.$i]);  //주소
	////////////////////////////////////////
	//2013-08-03
	//탁송인 경우 탁송 증권으로 탁송이 아닌 경우 탁송 아닌 증권으로 
	/////////////////////////////////////////////////////////

	//include "./php/isTasong.php";
		
}	
$a2bLength=sizeof($a2b);

//배서기준일,배서번호를 설정하고 

include "../php/BoardEndorseNumSerch.php";

//
$e_count=0;
for($i=0;$i<$a2bLength;$i++){

		if(!$a3b[$i]) continue;

		$insertSql="INSERT INTO 2021DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum, ";
		$insertSql.="a6b,a7b,a8b)";
		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum','$a2b[$i]', ";
		$insertSql.="'$a3b[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b[$i]','$endorseDay','$endorse_num', ";
		$insertSql.="'$a7b[$i]','$a8b[$i]','$a9b[$i]')";

		
		mysql_query($insertSql);

		$e_count++;


		$message='가입 신청 되었습니다!!';
}
include "../php/boardEndorseNumStore.php";
echo"<data>\n";
	echo "<ak>".$ak.$endorse_day.$old_endorse_day."</ak>\n";
	echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>