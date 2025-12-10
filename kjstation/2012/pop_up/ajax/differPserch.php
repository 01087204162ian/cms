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



//2016-03-01 작업
	$mFirstStart=explode("-",$row[FirstStart]);
	$beforeMonth=date("Y-m-d ",strtotime("$now_time -1 month"));

	$beforeM=explode("-",$beforeMonth);

	$First=$beforeM[0]."-".$beforeM[1]."-".$mFirstStart[2];  //전월 받는 날을 찾기 위해
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
	$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' and startyDay>='$yearbefore' order by InsuraneCompany asc ";
	$rs=mysql_query($certiSql,$connect);
	$cNum=mysql_num_rows($rs);

	$certiSql2="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' and startyDay>='$yearbefore' order by InsuraneCompany asc";
	$rs2=mysql_query($certiSql2,$connect);
	$cNum2=mysql_num_rows($rs2);


	

	
	
}
//배서 총 보험료 
$totalPreminum=0;
$haejiInwon=0;
$chugaInwon=0;
echo"<data>\n";
	echo "<FirstStart>".$First."</FirstStart>\n"; 
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }

	

	echo "<Rnum2>".$cNum2."</Rnum2>\n";
	for($_m=0;$_m<$cNum;$_m++)
	{
		$rCertiRow=mysql_fetch_array($rs);
	echo "<a>".$rCertiRow[InsuraneCompany]."</a>\n";//보험회사
	

	switch($rCertiRow[InsuraneCompany]){

			case 1 : //흥국
				$iCompany[$_m]='흥국';	
				$_jCount=7;
				$iColor[$_m]='#AF8AC1';
			break;
			case 2 : //동부
				$iCompany[$_m]='DB';	
				$_jCount=7;
				$iColor[$_m]='#E4690C';
			break;
			case 3 : //lig
				$iCompany[$_m]='KB';
				$_jCount=7;
				$iColor[$_m]='#0A8FC1';
			break;
			case 4 : //현대			
				$iCompany[$_m]='현대';
				$_jCount=7;
				$iColor[$_m]='#FA8FC1';
			break;
			case 5 : //현대			
				$iCompany[$_m]='롯데';
				$_jCount=7;
				$iColor[$_m]='#FA8FC1';
			break;
			case 6 : //현대			
				$iCompany[$_m]='더케이';
				$_jCount=7;
				$iColor[$_m]='#FA8FC1';
			break;
			case 7 : //현대			
				$iCompany[$_m]='MG';
				$_jCount=7;
				$iColor[$_m]='#FA8FC1';
			break;
		}

		//include "./php/2012Cpreminum.php";

			$pQsQl="SELECT * FROM 2012Cpreminum WHERE CertiTableNum='$rCertiRow[num]' order by sunso asc";
			$pRs=mysql_query($pQsQl,$connect);
			$pNum=mysql_num_rows($pRs);

			for($_j=0;$_j<$pNum;$_j++){// 

				$pRow=mysql_fetch_array($pRs);
					
					
					//$pRow[sPreminum]
					$where="(nai>='$pRow[sPreminum]' and nai<='$pRow[ePreminum]')";
					$pRow[sPreminum]=$pRow[sPreminum].'세~';
					$pRow[ePreminum]=$pRow[ePreminum].'세';
					if($pRow[ePreminum]==999){$pRow[ePreminum]='';}
					$nai[$_j]=$pRow[sPreminum].$pRow[ePreminum];
					$etag[$_j]='일반';
						//$ewhere="and (etag='' or etag='1')";
					$monThP[$_j]=$pRow[mPreminum];

			/*   switch($rCertiRow[InsuraneCompany]){

					case 1 : //흥국
						include "./php/DriverCount1.php";
					break;
					case 2 : //동부
						include "./php/DriverCount2.php";
					break;
					case 3 : //lig
						include "./php/DriverCount3.php";	
					break;
					case 4 : //현대			
						include "./php/DriverCount4.php";
					break;
					case 5 : //롯데		
						include "./php/DriverCount5.php";
					break;
					case 6 : //더 케이	
						include "./php/DriverCount6.php";
					break;
				}

			*/
				if($nai[$_j]){
					$sql2="SELECT num FROM 2012DaeriMember  WHERE $where and push='4' and 2012DaeriCompanyNum='$num' and InsuranceCompany='$rCertiRow[InsuraneCompany]'  and CertiTableNum='$rCertiRow[num]' $ewhere ";

					//echo "sql $sql2 <bR>";
					$rs2=mysql_query($sql2);

					 $inwon[$_j]=mysql_num_rows($rs2);//인원

					 
				}
					// echo "$_j $inwon[$_j] <Br>";
					 $naiName[$_j]=$nai[$_j];//나이
					 $toPm[$_j]=$inwon[$_j]*$monThP[$_j];
					
					 $certiInWon[$_m]+=$inwon[$_j];
					 $certiInPtotal[$_m]+=$toPm[$_j];

				/*	 if(!$monThP[$_j]){//월보험료 
						$inwon[$_j]='';
						$monThP[$_j]='ddkddkd';
					 }*/
		//if($rCertiRow[InsuraneCompany]==2){
			echo "<b".$_m.">".$inwon[$_j]."</b".$_m.">\n";//인원
			echo "<bm".$_m.">".$inwon[$_j]."</bm".$_m.">\n";//인원//계산을 위한
			echo "<p".$_m.">".number_format($monThP[$_j])."</p".$_m.">\n";//월보험료
			echo "<t".$_m.">".number_format($toPm[$_j])."</t".$_m.">\n";//인원x월보험료
			echo "<tm".$_m.">".$toPm[$_j]."</tm".$_m.">\n";//인원x월보험료 //계산을 위한
			echo "<nai".$_m.">".$naiName[$_j]."</nai".$_m.">\n";//나이
			echo "<icom".$_m.">".$iCompany[$_m]."</icom".$_m.">\n";//회사명
			echo "<icolor".$_m.">".$iColor[$_m]."</icolor".$_m.">\n";//회사명
				  $monThP[$_j]='';$inwon[$_j]='';$toPm[$_j]='';
			}	

		
	echo "<ci>".number_format($certiInWon[$_m])."</ci>\n";//증권별인원 합계
	echo "<ct>".number_format($certiInPtotal[$_m])."</ct>\n";//증권별 보험료합계
	echo "<cim>".$certiInWon[$_m]."</cim>\n";//증권별인원 합계//계산을 위한
	echo "<ctm>".$certiInPtotal[$_m]."</ctm>\n";//증권별 보험료합계//계산을 위한
		
		$ts+=$certiInPtotal[$_m];
		$tInwon+=$certiInWon[$_m];
	
	echo "<certiNum".$rCertiRow[InsuraneCompany].">".$rCertiRow[num]."</certiNum".$rCertiRow[InsuraneCompany].">\n";//
	}
	echo "<me>".$tInwon."명".number_format($ts)."</me>\n";//보험료 총합
	$message='조회 완료';
	echo "<message>".$message."</message>\n";
/*	echo "<daeriDriverInwon>".$daeriDriverInwon."</daeriDriverInwon>\n";//전체 인원
	echo "<preminumTotal>".$preminumTotal."</preminumTotal>\n";//모든 보험회사 합계
	echo "<ss>".$ss."</ss>\n";
	echo "<do>".$do."</do>\n";
	echo "<lig>".$lig."</lig>\n";
	echo "<hyun>".$hyun."</hyun>\n";
	
	$smsContent="[".$daeriDriverInwon."명 보험료 ".number_format($preminumTotal)."]";

		echo "<smsContent>".$smsContent."</smsContent>\n";
		echo "<endorse_day>".$endorse_day."</endorse_day>\n";
		echo "<message>".$message."</message>\n";
*/
	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>