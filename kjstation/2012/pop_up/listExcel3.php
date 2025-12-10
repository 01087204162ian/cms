<?php
include '../../dbcon.php';
$mstart=$_GET['mstart'];
$estart=$_GET['estart'];
$daeriCompanyNum=$_GET['num'];
$sigi=$_GET['sigi'];
$sql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriCompanyNum'";
//echo $sql;
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);




$damdanga=$row[MemberNum];

		$dsSql="SELECT * FROM  2012Member WHERE num='$damdanga'";
		$dsrs=mysql_query($dsSql,$connect);
		$dsrow=mysql_fetch_array($dsrs);


		//$damName=$dsrow[name];
$output_file_name = $row['company'].$sigi;



//echo $output_file_name;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );
  //print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=euc-kr\">");

	if($daeriCompanyNum){

		$where1="and 2012DaeriCompanyNum='$daeriCompanyNum'";//대리컴퍼니Num

	}

	if($InsuraneCompany==99 || $InsuraneCompany==''){
		
	}else{
		$where3="and InsuranceCompany='$InsuraneCompany'";
		if($CertiTableNum){
		$where2="and CertiTableNum='$CertiTableNum'";
		}
	}


$sSql="SELECT * FROM 2021DaeriMember WHERE  push='4' $where1  $where2 $where3 order by CertiTableNum asc,Jumin asc";

//echo "sSql $sSql <Br>";
$srs=mysql_query($sSql,$connect);
$sNUM=mysql_num_rows($srs);

// Add some data
$EXCEL_STR = "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>구분</td>  
   <td>성명</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>탁/일</td>
   <td>기타</td>
   <td>보험료</td>
   <td>보험회사에 내는 월보험료</td>
    <td>담당자</td>
	<td>이파리가 내는  월보험료</td>
   <td>단체구분</td>
   <td>사고유무</td>
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

			$c_sql="SELECT * FROM 2021Cpreminum WHERE ePreminum>='$sRow[nai]' AND sPreminum<='$sRow[nai]' ";
			$c_sql.=" AND  CertiTableNum='$sRow[CertiTableNum]'";

		//echo $c_sql;
			$c_rs=mysql_query($c_sql,$connect);

			$c_row=mysql_fetch_array($c_rs);




			$pSql="SELECT * FROM 2021CertiTable WHERE num='$sRow[CertiTableNum]'";
				//echo "pSql $pSql ";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);


				//2018-11-30	//모계약의 보험료 산출을 위해 

				//echo $pRow[moValue];
		if($pRow[moValue]==2){

			$m__sql="SELECT * FROM 2021CertiTable WHERE policyNum='$pRow[policyNum]' AND moValue='1' ";
			//echo $m__sql;
			$m_rs=mysql_query($m__sql,$connect);
			$m_row=mysql_fetch_array($m_rs);
			
			$qsql2_="SELECT * FROM 2021Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
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
				$e[30]=$sRow[InsuranceCompany]; //보험회사
				$e[50]=$sRow['2012DaeriCompanyNum'];//대리운전회사
				//2019-10-19
				//개인별 손해율 
				include "./ajax/php/boardPersonRate.php";
			
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
				$Ysql="SELECT * FROM 2021Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
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
		}else{
			//$p[$j]= $mY[$j];//보험회사에 내는 보험료 10%
			$p[$j]= round($mY[$j]*$personRate2,-1);
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
			$q[$j]=round($yPreminum[$j]*$personRate3,-1);//보험사 내는 1/12기준 보험료
			$totlaDailyPrice2+=$q[$j];
			$totlaDailyPrice+=$p[$j];

			 $totlaDailyPrice3+=$p_2[$j]; //우리가 지사로 부터 받는 보험료
		//
	$EXCEL_STR .= "  
   <tr >  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['Name']."</td>  
       <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['Jumin']."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['nai']."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$InsuranceCompany."</td>
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$sRow['dongbuCerti']."</td>
	   
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$etage[$j]."</td>
	   <td>&nbsp;</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($p[$j])."</td>
	   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($q[$j])."</td> 
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$dsrow[name]."</td>
	    <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($p_2[$j])."</td>
	   <td style=\"text-align:center;mso-number-format:'\@';\">".$policy[$j]."</td>
	    <td style=\"text-align:center;mso-number-format:'\@';\">".$personRate2."</td>
	  
	  
   </tr>  
   ";  

   $m2='';
	
  
   
}  
  
$EXCEL_STR .= "</table>";  
 
$EXCEL_STR .= "  
<table border='1'>  
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\">월 보험료 소계</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice)."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice2)."</td>
   <td>&nbsp;</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totlaDailyPrice3)."</td>
   
 </tr>";
  $EXCEL_STR2 = "  
<table border='1'>  
<tr>  
   <td colspan='9' style=\"text-align:center;mso-number-format:'\@';\">배서리스트".$sigi."</td></tr>";
$EXCEL_STR2 .= "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>구분</td>  
   <td>배서일자</td>  
   <td>성명</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>일/탁</td>
    <td>배서종류</td>
	<td>배서보험료</td>
	<td>증권성격</td>
	<td>&nbsp;</td>
	<td>지사로부터 받는 배서보험료</td>
</tr>";




				
			
//증권별로  배서 기록을 찾아서 추가자 인원수 찾기
			$e_sql="SELECT  *  FROM SMSData a left join 2012DaeriCompany  b ";
			$e_sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.num='$num' and push>='1' ";
			$e_sql.="and (endorse_day>='$mstart' and endorse_day<='$estart')  and qboard='2' order by SeqNo desc";

				//echo $j;echo $e_sql;
				
				$e_rs=mysql_query($e_sql,$connect);

				$e_num=mysql_num_rows($e_rs);
				

				
				for($k=0;$k<$e_num;$k++){
					$j_=$k+1;


				
					$e_row=mysql_fetch_array($e_rs);
				//	if($e_row['qboard']==2){ //킥보드는 제외하기 위해
				//		continue;
				//	}
					//echo $e_row['SeqNo'];
					$a[50]=$e_row[preminum2];
					
					$daeriMemberNum=$e_row['2012DaeriMemberNum'];
							$e2_sql="SELECT * FROM 2021DaeriMember WHERE num='$daeriMemberNum'";
							$e2_rs=mysql_query($e2_sql,$connect);
							$e2_row=mysql_fetch_array($e2_rs);

							//echo $e2_sql;
					switch($e_row['push']){
						case 2 :
							$pushName='해지';
							$e_row[preminum]=-$e_row[preminum];
							$a[50]=-$a[50];
							break;
						case 4 :
							$pushName='추가';
							$e_row[preminum]=$e_row[preminum];
							$a[50]=$a[50];
							break;
					}

					switch($e2_row[etag]){
						case 1 : 
							$metat="일반";

							break;
						case 2 :

							$metat="탁송";
							break;
						default:
							$metat="전탁송";
							break;
					}
					
			switch($e_row[insuranceCom]){
			case 1 :
				$inName='흥국';
			
				break;
			case 2 :
				$inName='DB';
			

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$inName='KB';
			
				break;
			case 4 :
				$inName='현대';
			
				break;
			case 5 :
				$inName='한화';
			
				break;
			case 6 :
				$inName='더케이';
			
				break;
			case 7 :
				$inName='MG';
			
				break;
			case 8 :
				$inName='삼성';
			
				break;
			case 9 :
				$inName='메리츠';
			
				break;


		}
		$pSql="SELECT * FROM 2021CertiTable WHERE num='$e2_row[CertiTableNum]'";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);


			$policy[$j]=$pRow[policyNum];
			$divi[$j]=$pRow[divi];

			switch($divi[$j]){
				case 1 :
					$divi_name[$j]='직접';
					
					break;
				case 2 :
					$divi_name[$j]='1/12';
					
					break;
				default:
					$divi_name[$j]='직접';
				    
					break;
			}
			
					$ju__=explode('-',$e_row[endorse_day]);
					if($row[FirstStartDay]==$ju__[2]){
					
						$e_row[preminum]=0;
						$a[50]=0;
					}


					$totalPrice+=$e_row[preminum];//배서보험료 소계
					$totalPrice2+=$a[50];//배서보험료 소계(지사로 부터 받은 금액)
			//배서일과 받는일이 같은 경우 배서보험료 0
				$EXCEL_STR2 .= "  
				   <tr>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$j_."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e_row[endorse_day]."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e2_row['Name']."</td> 
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e2_row['nai']."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$inName."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$e2_row['dongbuCerti']."</td>
					  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$metat."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$pushName."</td>
					   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($e_row[preminum])."</td>
					    <td style=\"text-align:center;mso-number-format:'\@';\">".$divi_name[$j]."</td>
						<td style=\"text-align:center;mso-number-format:'\@';\">".$policy[$j]."</td>
						<td style=\"text-align:right;mso-number-format:'\@';\">".number_format($a[50])."</td>
				   </tr>  
				   ";  
				}




			

 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\">배서 보험료 소계".$sigi."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totalPrice)."</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totalPrice2)."</td>
</tr>";
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='8'style=\"text-align:center;mso-number-format:'\@';\">입금 하실 보험료=월 보험료 소계+배서 보험료 소계 </td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totalPrice+$totlaDailyPrice)."</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
    <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($totalPrice2+$totlaDailyPrice3)."</td>
</tr>";
$EXCEL_STR2 .= "</table>";  


//$totalPrice
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

echo $EXCEL_STR2;  



?>




