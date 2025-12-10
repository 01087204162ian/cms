<?php
include '../../dbcon.php';
$mstart=$_GET['mstart'];
$estart=$_GET['estart'];
$daeriCompanyNum=$_GET['num'];
$normalT =$_GET['normalT']; //2022-03-05 값이 1이면 정상 분납 보험료가 표시됨


$sigi=$_GET['sigi'];
$sql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriCompanyNum'";
//echo $sql;
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);


//memo값을 찾기 위해 // 
$sqlm="SELECT * FROM ssang_c_memo WHERE c_number='$row[jumin]' and memokind='2'";
$rsm=$rs=mysql_query($sqlm,$connect);
$rowM=mysql_fetch_array($rsm);

$damdanga=$row[MemberNum];

		$dsSql="SELECT * FROM  2012Member WHERE num='$damdanga'";
		$dsrs=mysql_query($dsSql,$connect);
		$dsrow=mysql_fetch_array($dsrs);


		//$damName=$dsrow[name];
$output_file_name = $row['company'].$sigi;

$gugan_=0;
$gugan1=0;
$gugan2=0;
$gugan3=0;

//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
  //print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=euc-kr\">");

	if($daeriCompanyNum){

		$where1="and 2012DaeriCompanyNum='$daeriCompanyNum' and etag='1'";//대리컴퍼니Num

	}

	if($InsuraneCompany==99 || $InsuraneCompany==''){
		
	}else{
		$where3="and InsuranceCompany='$InsuraneCompany'";
		if($CertiTableNum){
		$where2="and CertiTableNum='$CertiTableNum'";
		}
	}


$sSql="SELECT * FROM 2012DaeriMember WHERE  push='4' $where1  $where2 $where3 order by Jumin asc";

//echo "sSql $sSql <Br>";
$srs=mysql_query($sSql,$connect);
$sNUM=mysql_num_rows($srs);
$rowM=
// Add some data
$EXCEL_STR = "  
<table border='1'> 
<tr style=\"text-align:center;mso-number-format:'\@';\">  
 <tr>  
   <td>구분</td>  
   <td>성명</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>탁/일</td>
   <td>기타</td>
   <td>cnmp보험료</td>
   <td>simg보험료</td>
</tr>";
for ($i = 0; $i <$sNUM; $i++) {
	
	
	$sRow=mysql_fetch_array($srs);


	$j=$i+1;


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
		// 보험료 계산을 위해 

			$c_sql="SELECT * FROM 2012Cpreminum WHERE ePreminum>='$sRow[nai]' AND sPreminum<='$sRow[nai]' ";
			$c_sql.=" AND  CertiTableNum='$sRow[CertiTableNum]'";

		//echo $c_sql;
			$c_rs=mysql_query($c_sql,$connect);

			$c_row=mysql_fetch_array($c_rs);




			$pSql="SELECT * FROM 2012CertiTable WHERE num='$sRow[CertiTableNum]'";
				//echo "pSql $pSql ";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);


				//2018-11-30	//모계약의 보험료 산출을 위해 

				//echo $pRow[moValue];
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


				
				// 고고대리운전처럼 동부화재 단체와 개인 가입자를 구분
				// certiTable의 policyNum 값으로 구분한다 2018-01-26
				//  
				$policy[$j]=$pRow[policyNum];
			
			$dongbuCerti[$j]=$sRow[dongbuCerti];
				$e[12]=$dongbuCerti[$j];//증권번호
				$e[5]=$sRow['Jumin'];//주민번호

				$jumin=explode("-",$sRow['Jumin']);
				$e[30]=$sRow[InsuranceCompany]; //보험회사
				$e[50]=$sRow['2012DaeriCompanyNum'];//대리운전회사
				//2019-10-19
				//개인별 손해율 
				include "./ajax/php/personRate.php";
			
			switch($sRow[InsuranceCompany]){
			case 1 :
				$InsuranceCompany='흥국';
			
				break;
			case 2 :
				$InsuranceCompany='DB';
			

				//$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				$sRow[dongbuCerti]=$sRow[dongbuCerti]."-000";
				//$sigi_[$j]=$sRow[dongbusigi];

				$sigi_[$j]=$sRow[InPutDay]; // 동부화재 가입일 찾기위해 2017-08-08 sj

				//echo "$p[$j] qsql $qsql <br>";
				break;
			case 3 :
				$InsuranceCompany='KB';

				
			
				$Ysql="SELECT * FROM 2012Certi WHERE certi='$sRow[dongbuCerti]'";

				//echo $Ysql;
				$yrs=mysql_query($Ysql,$connect);
				$yRow=mysql_fetch_array($yrs);

		

if($yRow[sigi]>'2019-07-31'){  // 2018 년 08월01일부터 나이구분이 변경됨

					 // 정상납인경우만 
					//if($pRow[divi]!=2){  // 정상납인경우만 
					 	 if($sRow[nai]<29){   
							 $yPreminum[$j]=round($yRow[preminun25]*10/12,-1);
							 $mY[$j]=$yRow[preminun25]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=29 && $sRow[nai]<=40){  
							 $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); 
							 $mY[$j]=$yRow[preminun44]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=41 && $sRow[nai]<=49){ 
							 $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); 
							 $mY[$j]=$yRow[preminun49]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=50 && $sRow[nai]<=55){   
							 $yPreminum[$j]=round($yRow[preminun50]*10/12,-1);
							 $mY[$j]=round($yRow[preminun50],-1); //보험회사 정규 보험료
						}
						if($sRow[nai]>=56 && $sRow[nai]<=65){   
							 $yPreminum[$j]=round($yRow[preminun60]*10/12,-1);
							 $mY[$j]=$yRow[preminun60]; //보험회사 정규 보험료
						}
						if($sRow[nai]>=66){

							 $yPreminum[$j]=round($yRow[preminun66]*10/12,-1);
							 $mY[$j]=$yRow[preminun66]; //보험회사 정규 보험료

						}

				//	}


				}else{

					//if($pRow[divi]!=2){  // 정상납인경우만 
					 	 if($sRow[nai]<26){   
							 $yPreminum[$j]=round($yRow[preminun25]*10/12,-1);
							 $mY[$j]=$yRow[preminun25]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=26 && $sRow[nai]<=40){  
							 $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); 
							 $mY[$j]=$yRow[preminun44]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=41 && $sRow[nai]<=49){ 
							 $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); 
							 $mY[$j]=$yRow[preminun49]; //보험회사 정규 보험료
						 }
						 if($sRow[nai]>=50 && $sRow[nai]<=55){   
							 $yPreminum[$j]=round($yRow[preminun50]*10/12,-1);
							 $mY[$j]=$yRow[preminun50]; //보험회사 정규 보험료
						}
						if($sRow[nai]>=56){   
								//echo "11";echo "//";
							 $yPreminum[$j]=round($yRow[preminun60]*10/12,-1);
							 $mY[$j]=$yRow[preminun60]; //보험회사 정규 보험료

							 //echo $mY[$j]; echo "//";
						}

					//}



				}
					
					// 실질적으로 보험회사에 1/12로 내는 보험료 산출을 위해서



				break;
			case 4 :
				$InsuranceCompany='현대';


				$Ysql="SELECT * FROM 2012Certi WHERE certi='$sRow[dongbuCerti]'";

				//echo $Ysql;
				$yrs=mysql_query($Ysql,$connect);
				$yRow=mysql_fetch_array($yrs);

		



					 // 정상납인경우만 
					if($yRow[sigi]>'2022-03-01'){
							 if($sRow[nai]<29){   
								 $yPreminum[$j]=round($yRow[preminun25]*10/12,-1);
								 $mY[$j]=$yRow[preminun25]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=29 && $sRow[nai]<=40){  
								 $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); 
								 $mY[$j]=$yRow[preminun44]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=41 && $sRow[nai]<=49){ 
								 $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); 
								 $mY[$j]=$yRow[preminun49]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=50 && $sRow[nai]<=55){   
								 $yPreminum[$j]=round($yRow[preminun50]*10/12,-1);
								 $mY[$j]=round($yRow[preminun50],-1); //보험회사 정규 보험료
							}
							if($sRow[nai]>=56 && $sRow[nai]<=65){   
								 $yPreminum[$j]=round($yRow[preminun60]*10/12,-1);
								 $mY[$j]=$yRow[preminun60]; //보험회사 정규 보험료
							}
							if($sRow[nai]>=66){

								 $yPreminum[$j]=round($yRow[preminun66]*10/12,-1);
								 $mY[$j]=$yRow[preminun66]; //보험회사 정규 보험료

							}
					}else{

						 if($sRow[nai]<=34){   
								 $yPreminum[$j]=round($yRow[preminun25]*10/12,-1);
								 $mY[$j]=$yRow[preminun25]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=35 && $sRow[nai]<=44){  
								 $yPreminum[$j]=round($yRow[preminun44]*10/12,-1); 
								 $mY[$j]=$yRow[preminun44]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=45 && $sRow[nai]<=47){ 
								 $yPreminum[$j]=round($yRow[preminun49]*10/12,-1); 
								 $mY[$j]=$yRow[preminun49]; //보험회사 정규 보험료
							 }
							 if($sRow[nai]>=48 && $sRow[nai]<=54){   
								 $yPreminum[$j]=round($yRow[preminun50]*10/12,-1);
								 $mY[$j]=round($yRow[preminun50],-1); //보험회사 정규 보험료
							}
							if($sRow[nai]>=55){   
								 $yPreminum[$j]=round($yRow[preminun60]*10/12,-1);
								 $mY[$j]=$yRow[preminun60]; //보험회사 정규 보험료
							}
							


					}
			
				break;
			case 5 :
				$InsuranceCompany='한화';
			
				break;
			case 6 :
				$InsuranceCompany='더케이';
			
				break;
			case 7 :
				$InsuranceCompany='MG';
			
				break;
			case 8 :
				$InsuranceCompany='삼성';
			

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
			case 9 :
				$Ysql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
				$Ysql.="and CertiTableNum='$sRow[CertiTableNum]' AND InsuraneCompany='$sRow[InsuranceCompany]';";

				//echo $Ysql;

							$yrs=mysql_query($Ysql,$connect);
							$yRow=mysql_fetch_array($yrs);

							
							$mY[$j]=round($yRow[yPreminum]/11,-1); //연간보험료의 1/11

							//echo $yPreminum[$j]; echo "<br>";
				$InsuranceCompany='메리츠';

				if($sRow[InPutDay]>=$pRow[startyDay]){

					$sigi_[$j]=$sRow[InPutDay]; // 메리츠화재인 경우
				}else{

					$sigi_[$j]=$pRow[startyDay];
				}




			break;



		}

//	if($sRow[InsuranceCompany]==3){ //kb화재만 적용 
		if($pRow[divi]==2){ // 1/12 납인 경우에만
			   $p[$j]=round($c_row[mPreminum]*$personRate2,-1);
			   $p_2[$j]=round($p_2[$j]*$personRate2,-1);

			   $gita[$j]="월납";
			   $q[$j]=round($yPreminum[$j]*$personRate2,-1);//보험사 내는 1/12기준 보험료
		}else{
			//$p[$j]= $mY[$j];//보험회사에 내는 보험료 10%
			$gita[$j]="정상";
			if($normalT==1){  //2022-03-05 표시를 할 경우 
				$p[$j]= round($mY[$j]*$personRate2,-1);
				$q[$j]=round($mY[$j]*$personRate2,-1);//보험사 내는 1/12기준 보험료

				$p_day[$j]=$c_row[dPreminum];
			}else{
				$p[$j]='';
				$q[$j]='';
			}
			
		}
$p_day[$j]=$c_row[dPreminum];
if($sRow['nai']>=50 && $sRow['nai']<56){
	$p_day2[$j]=$p_day[$j]+1;
	$gugan2++;
}else if($sRow['nai']>=56){
   $p_day2[$j]=$p_day[$j]+4;
	$gugan3++;
}else{
	$p_day2[$j]=$p_day[$j];
	$gugan1++;
}
/*	}else{

		$personRate2=1;

		if($pRow[divi]==2){ // 1/12 납인 경우에만
			   $p[$j]=round($c_row[mPreminum]*$personRate2,-1);
		}else{
			//$p[$j]= $mY[$j];//보험회사에 내는 보험료 10%
			$p[$j]= round($mY[$j]*$personRate2,-1);
		}

	}*/
			
			$totlaDailyPrice2+=$p_day2[$j];
			$totlaDailyPrice+=$p_day[$j];

			
		//
	$EXCEL_STR .= "  
   <tr >  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['Name']."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$jumin[0]."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['nai']."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$InsuranceCompany."</td>
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['dongbuCerti']."</td>
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$etage[$j]."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$gita[$j]."</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($p_day[$j])."</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($p_day2[$j])."</td> 
	   
	  
	  
   </tr>  
   ";  

   $m2='';
	
  
   
}  
  $gugan_=$gugan1+$gugan2+$gugan3;
  $chai=$totlaDailyPrice2-$totlaDailyPrice;
$EXCEL_STR .= "</table>";  
 
$EXCEL_STR .= "  
<table border='1'>  
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\">일일 보험료 소계</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice)."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice2)."</td>
   
   <td style=\"text-align:right;mso-number-format:'\@';\">".$gugan1."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".$gugan2."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".$gugan3."</td>

   <td style=\"text-align:right;mso-number-format:'\@';\">".$gugan_."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($chai)."</td>
 </tr>";

$EXCEL_STR .= "</table>";  
 



//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

echo $EXCEL_STR2;  



?>




