<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);//2012DaeriCompanyNum
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$eNum =iconv("utf-8","euc-kr",$_GET['eNum']);
	

if($num){//


	$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$CertiTableNum' and pnum='$eNum'";
	$eRs=mysql_query($eSql,$connect);

	$eRow=mysql_fetch_array($eRs);


	$endorse_day=$eRow[endorse_day];

		
	$sql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$a[1]=$row[company];
	$a[2]=$row[Pname];
	$a[3]=$row[jumin];
	$a[4]=$row[hphone];
	$a[5]=$row[cphone];
	$a[6]=$row[damdanga];
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
	$certiSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$rs=mysql_query($certiSql,$connect);
	$rCertiRow=mysql_fetch_array($rs);

	$a[14]=$rCertiRow[InsuraneCompany];//보험회사

	switch($a[14]){
		case 1 :
			$a[8]='흥국';
			break;
		case 2 :
			$a[8]='동부';
			break;
		case 3 :
			$a[8]='LiG';
			break;
		case 4 :
			$a[8]='현대';
			break;
		case 7 :
			$a[8]='MG';
			break;
		case 8 :
			$a[8]='삼성';
			break;
	}
	$a[9]=$rCertiRow[policyNum];
	$a[10]=$rCertiRow[startyDay];
	
	$end=date("Y-m-d ", strtotime("$a[10] + 1 year"));

	$sigiS=explode("-",$a[10]);

	$endS=explode("-",$end);

	$a[10]=$sigiS[0].".".$sigiS[1].".".$sigiS[2];
	$end=$endS[0].".".$endS[1].".".$endS[2];
	$a[10]=$a[10]."~".$end;
	$a[11]=$rCertiRow[nabang]."회중".$rCertiRow[nabang_1]."회 납입";
	$a[12]=$rCertiRow[nabang_1];
}
//배서 총 보험료 
$totalPreminum=0;
$haejiInwon=0;
$chugaInwon=0;
echo"<data>\n";
	echo "<sql>".$certiSql."</sql>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	
	$Dsql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum'";
	//echo "Dsql $Dsql <br>";
	$Drs=mysql_query($Dsql,$connect);
	$DNum=mysql_num_rows($Drs);

	echo "<Rnum>".$DNum."</Rnum>\n";
	for($_m=0;$_m<$DNum;$_m++)
	{
		$DRow=mysql_fetch_array($Drs);

		switch($DRow[etag]){
			case 1 :

				$etag='일반';
				break;
			case  2:
				$etag='탁송';
				break;
			default:
				$etag='일반';
				break;
		}
		

		//보험료 구하기 위해 
$pchagne=1;//preminumNaiEndorse.php 에서 사용하기 위해 2는 endorseChange.php 
		include "../../../2012/pop_up/ajax/php/preminumNaiEndorse.php";
	
	if($DRow[sangtae]==2){//배서처리후 
		switch($Dpush){
			case 1 :	
				$Dpush=3;//
				break;
			case 2 :
			    $Dpush=4;
				break;
			case 4 :

				$Dpush=1;
				break;
		}
		
	}
		switch($Dpush){
				case 1 ://청약		
					$totalPreminum+=$endorsePre[$_m];
					$chugaInwon++;
					break;
				case 4 ://해지
					$totalPreminum+=$endorsePre[$_m];
					$haejiInwon++;
					break;
			}
	//echo "<member2Num".$_m.">"."[".$Dsangte."]".$aj."</member2Num".$_m.">\n";//
	echo "<memberNum".$_m.">".$DRow[num]."</memberNum".$_m.">\n";//
	echo "<Name".$_m.">".$DRow[Name]."[".$DRow[nai]."]"."</Name".$_m.">\n";//
	echo "<Jumin".$_m.">".$DRow[Jumin]."</Jumin".$_m.">\n";//
	echo "<push".$_m.">".$DRow[push]."</push".$_m.">\n";//
	echo "<etag".$_m.">".$etag."</etag".$_m.">\n";//
	//echo "<push".$_m.">".$DRow[push]."</push".$_m.">\n";//
	echo "<cancel".$_m.">".$DRow[cancel]."</cancel".$_m.">\n";//
	echo "<sangtae".$_m.">".$DRow[sangtae]."</sangtae".$_m.">\n";//
	echo "<EndorsePnum".$_m.">".$DRow[EndorsePnum]."</EndorsePnum".$_m.">\n";//
	echo "<EndorsePreminum".$_m.">".number_format($endorsePre[$_m])."</EndorsePreminum".$_m.">\n";//
	echo "<Endorse2Preminum".$_m.">".$endorsePre[$_m]."</Endorse2Preminum".$_m.">\n";//
	echo "<dongbuCerti".$_m.">".$DRow[dongbuCerti]."</dongbuCerti".$_m.">\n";//
	echo "<dongbuSelNumber".$_m.">".$DRow[dongbuSelNumber]."</dongbuSelNumber".$_m.">\n";//
	echo "<dongbusigi".$_m.">".$DRow[dongbusigi]."</dongbusigi".$_m.">\n";//
	echo "<dongbujeongi".$_m.">".$DRow[dongbujeongi]."</dongbujeongi".$_m.">\n";//
	echo "<nabang_1".$_m.">".$DRow[nabang_1]."</nabang_1".$_m.">\n";//
	}
	

	$smsContent="추가".$chugaInwon."명 해지".$haejiInwon."명 보험료 ".number_format($totalPreminum);

		echo "<smsContent>".$smsContent."</smsContent>\n";
		echo "<endorse_day>".$endorse_day."</endorse_day>\n";
		echo "<message>".$message."</message>\n";

	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>