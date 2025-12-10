<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
for($i=0;$i<15;$i++){
	$a2b[$i] =iconv("utf-8","euc-kr",$_GET['a2b'.$i]);	//성명
	$a3b[$i] =iconv("utf-8","euc-kr",$_GET['a3b'.$i]);	//주민번호
$jumin1=$a3b[$i];
	include "../php/nai.php";

$a6b[$i]=$age;
	$a4b[$i] =iconv("utf-8","euc-kr",$_GET['a4b'.$i]);	//핸드폰번호
	$a5b[$i] =iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  //탁송여부 1 은 일반,2은탁송	
	$a7b[$i] =iconv("utf-8","euc-kr",$_GET['a6b'.$i]);  //개별증권번호	
}	
$a2bLength=sizeof($a2b);



for($i=0;$i<$a2bLength;$i++){

		if(!$a3b[$i]) continue;

		$insertSql="INSERT INTO 2021DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,Hphone,InPutDay,dongbuCerti )";

		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
		$insertSql.="'$a2b[$i]','$a3b[$i]','$a6b[$i]','4','$a5b[$i]','$a4b[$i]',now(),'$a7b[$i]')";

		mysql_query($insertSql);
	$message='저장 되었습니다!!';
}
/*	
for($_u_=1;$_u_<15;$_u_++){
		$a[$_u_]=$a5b[$_u_];//
		
    }*/
echo"<data>\n";
	echo "<num>".$insertSql."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>