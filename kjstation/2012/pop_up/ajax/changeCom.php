<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

//배서 신청 후 취소 또는 거절 후 다른 보험회사 신규 가입신청을 한다고 보고

	$memberNum=iconv("utf-8","euc-kr",$_GET['memberNum']);	
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);//신규 가입신청할 증권번호num

	$mSql="SELECT * FROM 2012DaeriMember WHERE num='$memberNum'";
	$mRs=mysql_query($mSql,$connect);
	$mRow=mysql_fetch_array($mRs);

	$a2b =$mRow[Name];	//성명
	$a3b=$mRow[Jumin];	//주민번호

	$a4b =$mRow[Hphone];	//핸드폰번호
	$a5b =$mRow[etag];  //탁송여부 1 은 일반,2은탁송	
	
	
	
	
	
	
	$EndorsePnum=$mRow[EndorsePnum];

	$Esql="SELECT * FROM 2012EndorseList WHERE pnum='$EndorsePnum' and CertiTableNum='$mRow[CertiTableNum]'";
	$Ers=mysql_query($Esql,$connect);
	$Erow=mysql_fetch_array($Ers);


	$endorseDay=$Erow[endorse_day];
	
	$userId=$Erow[userid];


	$Csql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$Crs=mysql_query($Csql,$connect);
	$Crow=mysql_fetch_array($Crs);

	$insuranceNum=$Crow[InsuraneCompany];
	$policyNum=$Crow[policyNum];
    $DaeriCompanyNum=$Crow["2012DaeriCompanyNum"];
	

$jumin1=$a3b;
	include "../php/nai.php";
$a6b=$age;
//$a2bLength=sizeof($a2b);

//배서기준일,배서번호를 설정하고 

include "../php/endorseNumSerch.php";

//


		$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
		$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum )";
		$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum','$a2b', ";
		$insertSql.="'$a3b','$a6b','1','$a5b','1','$a4b','$endorseDay','$endorse_num')";

		
		mysql_query($insertSql);

	
$e_count=1;//배서건수 가 증가 되어야한다

		$message='배서 신청 되었습니다!!';

include "../php/endorseNumStore.php";


      //마지막으로 기존 member가 타회사에 신규 가입 신청 되어 있음
//changeCom
	  $update="UPDATE 2012DaeriMember SET changeCom='$CertiTableNum' WHERE num='$memberNum'";
	  mysql_query($update,$connect);
echo"<data>\n";
	echo "<enday>".$ak."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	/*for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
    }*/
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>