<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);//2012DaeriCompanyNum
	//$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	//$eNum =iconv("utf-8","euc-kr",$_GET['eNum']);
	

if($num){//

		
	$sql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$a[1]=$row[company];
	$a[2]=$row[Pname];
	$a[3]=$row[jumin];
	$a[4]=$row[hphone];
	$a[5]=$row[cphone];

	//담당자를 찾기위해 

		$dSql="SELECT name FROM 2012Member WHERE num='$row[MemberNum]'";
		$drs=mysql_query($dSql,$connect);

		$dRow=mysql_fetch_array($drs);


	$a[6]=$dRow[name];
	//$a[6]=$row[damdanga];
	$a[7]=$row[divi];
/*	$a[8]=$row[fax];
	$a[9]=$row[cNumber];
	$a[10]=$row[lNumber];
	$a[11]=$row[a11];
	$a[12]=$row[a12];
	$a[13]=$row[a13];
	$a[14]=$row[a14];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];*/

	

	mysql_query($sql,$connect);
	
	$message='조회 되었습니다!!';


	//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' ";
	$rs=mysql_query($certiSql,$connect);
	$cNum=mysql_num_rows($rs);

	$certiSql2="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' ";
	$rs2=mysql_query($certiSql2,$connect);
	$cNum2=mysql_num_rows($rs2);


	

	
	
}
//배서 총 보험료 
$totalPreminum=0;
$haejiInwon=0;
$chugaInwon=0;
echo"<data>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	
	

	echo "<Rnum2>".$cNum2."</Rnum2>\n";

	$daeriDriverInwon='';
	$preminumTotal='';
	for($_m=0;$_m<$cNum;$_m++)
	{
		$rCertiRow=mysql_fetch_array($rs);
		
	echo "<start".$rCertiRow[InsuraneCompany].">".$rCertiRow[InsuraneCompany]."</start".$rCertiRow[InsuraneCompany].">\n";//
	
		switch($rCertiRow[InsuraneCompany]){

			case 1 : //흥국
				
				$ss=1;
				$iCompany='흥국';
				if($rCertiRow[startyDay ]>='$yearbefore'){
				include "./php/ssDcount.php";
				}
			break;
			case 2 : //동부
				$do=2;
				$iCompany='동부';
				include "./php/doDcount.php";
			break;
			case 3 : //lig
				$lig=3;
				if($rCertiRow[startyDay ]>='$yearbefore'){
				$iCompany='Lig';
				include "./php/ligDcount.php";
				}
			break;
			case 4 : //현대
				$hyun=4;
				//if($rCertiRow[startyDay ]>='$yearbefore'){
				$iCompany='현대';
				include "./php/ssDcount.php";
				//}
			break;

			
		}

		$daeriDriverInwon+=$inwonTotal[$rCertiRow[InsuraneCompany]];
		$preminumTotal+=$tmPreminumTotal[$rCertiRow[InsuraneCompany]];
		
	echo "<certiNum".$rCertiRow[InsuraneCompany].">".$rCertiRow[num]."</certiNum".$rCertiRow[InsuraneCompany].">\n";//

	}
	echo "<daeriDriverInwon>".$daeriDriverInwon."</daeriDriverInwon>\n";//전체 인원
	echo "<preminumTotal>".$preminumTotal."</preminumTotal>\n";//모든 보험회사 합계
	echo "<ss>".$ss."</ss>\n";
	echo "<do>".$do."</do>\n";
	echo "<lig>".$lig."</lig>\n";
	echo "<hyun>".$hyun."</hyun>\n";
	
	$smsContent="[".$daeriDriverInwon."명 보험료 ".number_format($preminumTotal)."]";

		echo "<smsContent>".$smsContent."</smsContent>\n";
		echo "<endorse_day>".$endorse_day."</endorse_day>\n";
		echo "<message>".$message."</message>\n";

	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>