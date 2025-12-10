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
	$FirstStartDay=$row[FirstStartDay];
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
	$a[7]=$row[FirstStart];
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

	//echo " certiSql $certiSql <bR>";
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
		case 5 :
			$a[8]='한화';
			break;
		case 6 :
			$a[8]='더케이';
			break;
	}
	$a[9]=$rCertiRow[policyNum];
	$a[10]=$rCertiRow[startyDay];
	
	$endE=date("Y-m-d ", strtotime("$a[10] + 1 year"));

	$sigiS=explode("-",$a[10]);

	$endS=explode("-",$endE);

	$a[10]=$sigiS[0].".".$sigiS[1].".".$sigiS[2];
	$end=$endS[0].".".$endS[1].".".$endS[2];
	$a[10]=$a[10]."~".$end;
	$a[11]=$rCertiRow[nabang]."회중".$rCertiRow[nabang_1]."회 납입";
	$a[12]=$rCertiRow[nabang_1];

	//$a[13]=		$rCertiRow[divi];

	switch($rCertiRow[divi])
		{
				case 1 :
					$a[13]='정상';

					break;
				case 2:
					$a[13]='1/12씩';
					break;
				default:
					$rCertiRow[divi]=1;
					$a[13]='정상';
					break;
		}

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
//배서기준일 기준으로 보험료 산출하기 위해 2012-10-16
//echo "dd $endorse_day <br>";

$em=explode("-",$endorse_day);
 
//echo "today $today ";

$today=$em[2];

//
		include "./php/preminumNaiEndorse.php";
	
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


    //동부화재 인 경우 회차를 구하기 위해 

	include "./php/nab.php";


    if(!$DRow[dongbusigi]){//시기가 배서기준일

		$DRow[dongbusigi]=$endorse_day;

		//$DR=explode("
		$DRow[dongbujeongi]=$endE;
	}
	//echo "<member2Num".$_m.">"."[".$Dsangte."]".$aj."</member2Num".$_m.">\n";//
	echo "<memberNum".$_m.">".$DRow[num]."</memberNum".$_m.">\n";//
	echo "<Name".$_m.">".$DRow[Name]."[".$DRow[nai]."]"."</Name".$_m.">\n";//
	echo "<Jumin".$_m.">".$DRow[Jumin]."</Jumin".$_m.">\n";//
	echo "<push".$_m.">".$DRow[push]."</push".$_m.">\n";//


	if($DRow[cancel]==13 || $DRow[cancel]==12){//가입한 다른보험회사가 있나 찾는다

			$Csql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' ";
			$Csql.="and InsuraneCompany!='$a[14]' and startyDay>='$yearbefore'";
			
			$Crs=mysql_query($Csql,$connect);
			$Cnum=mysql_num_rows($Crs);

				$C2num=$Cnum+1;//개수보다 하나 많게 하는 이유은 ?
				echo "<Cnum".$_m.">".$C2num."</Cnum".$_m.">\n";
				
			for($_p=0;$_p<$C2num;$_p++){

				$Crow=mysql_fetch_array($Crs);

				$Ccnum[$_p]=$Crow[num];
				$Cinsum[$_p]=$Crow[InsuraneCompany];

				//$Snum[$_p]=$Crow[changeCom];
				if($_p==$Cnum){// 앞부분에 선택을 만들기 위해 
					$Ccnum[$_p]='99999999';
					$Cinsum[$_p]='99';
				}
				echo "<Ctnum".$_m.$_p.">".$Ccnum[$_p]."</Ctnum".$_m.$_p.">\n";//2012CertiTablenum
				echo "<inum".$_m.$_p.">".$Cinsum[$_p]."</inum".$_m.$_p.">\n";
				
			}


		}

	echo "<Qnum".$_m.">".$DRow[changeCom]."</Qnum".$_m.">\n";////변경한 보험회사 증권 2012CertiTablenum  이며 
	//이것을 통해 보험횟를 찾는다

if($DRow[changeCom]){
	$C2sql="SELECT * FROM 2012CertiTable WHERE num='$DRow[changeCom]'";
	//echo "C  $C2sql <br>";
	$C2rs=mysql_query($Csql,$connect);
	$C2row=mysql_fetch_array($C2rs);

	switch($C2row[InsuraneCompany]){
		case 1 :
				$chCom='흥국';
			break;
	    case 2 :
				$chCom='동부';
			break;
		case 3 :
				$chCom='LiG';
			break;
	    case 4 :
				$chCom='현대';
			break;
		case 5 :
				$chCom='한화';
			break;
	}
}
	echo "<chCom".$_m.">".$chCom."</chCom".$_m.">\n";//

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
	echo "<nabang_1".$_m.">".$cha."</nabang_1".$_m.">\n";//
	}
	

	$smsContent="추가".$chugaInwon."명 해지".$haejiInwon."명 보험료 ".number_format($totalPreminum);

		echo "<smsContent>".$smsContent."</smsContent>\n";
		echo "<endorse_day>".$endorse_day."</endorse_day>\n";
		echo "<message>".$message."</message>\n";

	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>