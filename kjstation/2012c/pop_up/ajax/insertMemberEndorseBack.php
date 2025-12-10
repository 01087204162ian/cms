<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


include '../../../api/kjDaeri/php/encryption.php';
	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
for($i=0;$i<15;$i++){
	$a2b[$i] =iconv("utf-8","euc-kr",$_GET['a2b'.$i]);	//성명
	$a3b[$i] =iconv("utf-8","euc-kr",$_GET['a3b'.$i]);	//주민번호
	
	$a3b_encryp_jumin[$i] = encryptData($a3b[$i]);
$jumin1=$a3b[$i];

	include "../php/nai.php";
$a6b[$i]=$age;
	$a4b[$i] =iconv("utf-8","euc-kr",$_GET['a4b'.$i]);	//핸드폰번호
	$a4b_encryp_jumin[$i] = encryptData($a4b[$i]);
	$a5b[$i] =iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  //탁송여부 1 은 일반,2은탁송
	//$a7b[$i] =iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  //탁송여부 1 은 일반,2은탁송
}	
$a2bLength=sizeof($a2b);

//배서기준일,배서번호를 설정하고 

include "../php/endorseNumSerch.php";

//
$e_count=0;
for($i=0;$i<$a2bLength;$i++){

		if(!$a3b[$i]) continue;

		$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum,wdate,endorse_day )";
		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
		$insertSql.="'$a2b[$i]','$a3b[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b[$i]','$endorseDay','$endorse_num',now(),'$endorse_day')";

		//echo $insertSql;
		mysql_query($insertSql);
		
		
		
		$insertSql="INSERT INTO DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum,wdate,endorse_day )";
		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
		$insertSql.="'$a2b[$i]','$a3b_encryp_jumin[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b_encryp_jumin[$i]','$endorseDay','$endorse_num',now(),'$endorse_day')";

		//echo $insertSql;
		mysql_query($insertSql);
		
		
		
		
		

		$e_count++;


		$message='배서 신청 되었습니다!!';
}
include "../php/endorseNumStore.php";
echo"<data>\n";
	echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>