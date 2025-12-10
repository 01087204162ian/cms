<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../dbcon.php';

//echo "num $num <br>";

$jum=explode("-",$num);

$mem=1;

	if($num){

		$where1="and 2012DaeriCompanyNum='$num'";//대리컴퍼니Num

	}

	if($InsuraneCompany==99 || $InsuraneCompany==''){
		
	}else{
		$where3="and InsuranceCompany='$InsuraneCompany'";
		if($CertiTableNum){
		$where2="and CertiTableNum='$CertiTableNum'";
		}
	}
//흥국화재 

echo "번호	운전자	주민앞	증권번호	대리운전회사	보험회사	만나이	년간보험료	탁송여부	담당자	전화번호	보험료	통신사	주소	모보험료\n ";


$sSql="SELECT * FROM 2012DaeriMember WHERE  push='4' $where1  $where2 $where3 order by Jumin asc";

//echo "sSql $sSql <Br>";
$srs=mysql_query($sSql,$connect);
$sNUM=mysql_num_rows($srs);



	//$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	//$iRs=mysql_query($iSql,$connect);
	//$iRow=mysql_fetch_array($iRs);
for($j=0;$j<$sNUM;$j++){

		$sRow=mysql_fetch_array($srs);

		//보험료을 구하기 위해 
				$pSql="SELECT * FROM 2012CertiTable WHERE num='$sRow[CertiTableNum]'";
				//echo "pSql $pSql ";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);

		//보험료을 구하기 위해 

		//대리운전회사 찾기 위해 
		$daeriComNum=$sRow['2012DaeriCompanyNum'];
		$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriComNum'";
		$iRs=mysql_query($iSql,$connect);
		$iRow=mysql_fetch_array($iRs);


		$damdanga[$j]=$iRow[MemberNum];

		$dsSql="SELECT * FROM  2012Member WHERE num='$damdanga[$j]'";
		$dsrs=mysql_query($dsSql,$connect);
		$dsrow=mysql_fetch_array($dsrs);


		$damName[$sj]=$dsrow[name];

		

		//
		$qsql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
			$qsql.="and CertiTableNum='$sRow[CertiTableNum]'";
			//echo $qsql;
			$qRs=mysql_query($qsql,$connect);
			$qRow=mysql_fetch_array($qRs);
			$p[$j]=$qRow[preminum1]=$qRow[mPreminum];


		//2018-11-30	//모계약의 보험료 산출을 위해 
		if($pRow[moValue]==2){

			$m__sql="SELECT * FROM 2012CertiTable WHERE policyNum='$pRow[policyNum]' AND moValue='1' ";
			//echo $m__sql;
			$m_rs=mysql_query($m__sql,$connect);
			$m_row=mysql_fetch_array($m_rs);
			
			$qsql2_="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
			$qsql2_.="and CertiTableNum='$m_row[num]'";
			//echo $qsql2_;
			$qRs2_=mysql_query($qsql2_,$connect);
			$qRow_=mysql_fetch_array($qRs2_);
			$p_2[$j]=$qRow_[mPreminum];
				



		}
		
		
		switch($sRow[InsuranceCompany]){
			case 1 :
				$rRow[nab]='흥국';
			
				break;
			case 2 :
				$rRow[nab]='동부';
			

				//$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				$sRow[dongbuCerti]="2-20-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$rRow[nab]='KB';
			
				$Ysql="SELECT * FROM 2012Certi WHERE certi='$sRow[dongbuCerti]'";

				//echo $Ysql;
				$yrs=mysql_query($Ysql,$connect);
				$yRow=mysql_fetch_array($yrs);


				//echo "o $pRow[startyDay]";
	
				if($yRow[sigi]<"2016-01-01"){

					 if($sRow[nai]<26){   $yPreminum[$j]=round($yRow[preminun25]*10/12,-1); }
					 if($sRow[nai]>=26 && $sRow[nai]<=44){   $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); }
					 if($sRow[nai]>=45 && $sRow[nai]<=49){   $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); }
					 if($sRow[nai]>=50){   $yPreminum[$j]=round($yRow[preminun50]*10/12,-1); }

				}else if($yRow[sigi]>="2016-01-01" &&  $yRow[sigi]<"2016-11-01"){

					 if($sRow[nai]<26){   $yPreminum[$j]=round($yRow[preminun25]*10/12,-1); }
					 if($sRow[nai]>=26 && $sRow[nai]<=40){   $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); }
					 if($sRow[nai]>=41 && $sRow[nai]<=49){   $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); }
					 if($sRow[nai]>=50){   $yPreminum[$j]=round($yRow[preminun50]*10/12,-1); }

				}else if($yRow[sigi]>="2016-11-01" &&  $yRow[sigi]<"2018-07-31"){

					 if($sRow[nai]<26){   $yPreminum[$j]=round($yRow[preminun25]*10/12,-1); }
					 if($sRow[nai]>=26 && $sRow[nai]<=40){   $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); }
					 if($sRow[nai]>=41 && $sRow[nai]<=49){   $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); }
					 if($sRow[nai]>=50 && $sRow[nai]<=59){   $yPreminum[$j]=round($yRow[preminun50]*10/12,-1); }
					 if($sRow[nai]>=60){   $yPreminum[$j]=round($yRow[preminun60]*10/12,-1); }

				}else if($yRow[sigi]>="2018-07-31"){

					 if($sRow[nai]<26){   $yPreminum[$j]=round($yRow[preminun25]*10/12,-1); }
					 if($sRow[nai]>=26 && $sRow[nai]<=40){   $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); }
					 if($sRow[nai]>=41 && $sRow[nai]<=49){   $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); }
					 if($sRow[nai]>=50 && $sRow[nai]<=55){   $yPreminum[$j]=round($yRow[preminun50]*10/12,-1); }
					 if($sRow[nai]>=56){   $yPreminum[$j]=round($yRow[preminun60]*10/12,-1); }

				}

				break;
			case 4 :
				$rRow[nab]='현대';
			
				break;
			case 5 :
				$rRow[nab]='한화';
			
				break;
			case 6 :
				$rRow[nab]='더케이';
			
				break;
			case 7 :
				$rRow[nab]='MG';
			
				break;
			case 8 :
				$rRow[nab]='삼성';
			

				if($pRow[moValue]==1){  //1이면 본계약이 모계약 ,2이면 차일드 계약
					$Ysql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
					$Ysql.="and CertiTableNum='$sRow[CertiTableNum]' AND InsuraneCompany='$sRow[InsuranceCompany]';";

				//echo "$Ysql ";

							$yrs=mysql_query($Ysql,$connect);
							$yRow=mysql_fetch_array($yrs);

							
							$yPreminum[$j]=round($yRow[yPreminum]/12,-1); //연간보험료의 1/12

				}else{ //차일드 인경우에는

					$Ysql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
					$Ysql.="and CertiTableNum='$pRow[moNum]' AND InsuraneCompany='$sRow[InsuranceCompany]';";

					//echo "$Ysql ";

							$yrs=mysql_query($Ysql,$connect);
							$yRow=mysql_fetch_array($yrs);

							
							$yPreminum[$j]=round($yRow[yPreminum]/12,-1); //연간보험료의 1/12

					




				}
				break;
			



		}
		
		switch($sRow[etag]){
			default:

				$etage[$j]="일반";
				break;
			case 2 :
				$etage[$j]="탁송";	
				break;
			case 3 :
				$etage[$j]="전탁송";	
				break;
		}




		if(!$sRow[Hphone]){
				$tSql="SELECT * FROM 2012DaeriMember WHERE Jumin='$sRow[Jumin]' order by num desc";

				$trs=mysql_query($tSql,$connect);

				$tnum=mysql_num_rows($trs);

				for($_t=0;$_t<$tnum;$_t++){

					$tRow=mysql_fetch_array($trs);


					$sRow[Hphone]=$tRow[Hphone];




					if($sRow[Hphone]){
						$updaete="UPDATE 2012DaeriMember SET Hphone='$tRow[Hphone]' WHERE num='$sRow[num]'";

						//echo " $updaete ";
						mysql_query($updaete,$connect);
						break;
					}
				}

		}
		//보험회사 내는 보험료 찾기

		switch($sRow[a7b]){
			case 1 :
				$tongsisa="sk";
				break;
			case 2 :
				$tongsisa="kt";
				break;
			case 3:
				$tongsisa="lg";
				break;
			case 4 :
				$tongsisa="sk알뜰폰";
				break;
			case 5 :
				$tongsisa="kt알뜰폰";
				break;
			case 6:
				$tongsisa="lg알뜰폰";
				break;
		}

		switch($sRow[a6b]){
			case 1 :
				$tongsisaName="본인";
				break;
			case 2 :
				$tongsisaName="타인";
				break;
			case 3 :
				$tongsisaName="인증";
				break;
		}

  echo "$mem	$sRow[Name]	$sRow[Jumin]	$sRow[dongbuCerti]	$iRow[company]	$rRow[nab]	$sRow[nai]	$p[$j]	$etage[$j]	$damName[$sj]	$sRow[Hphone]	$yPreminum[$j]	$tongsisa	$tongsisaName	$sRow[a8b]	$p_2[$j]\n ";

		$mem++;

		$tongsisa='';
		$tongsisaName='';
}















?>