<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$Certi=iconv("utf-8","euc-kr",$_GET['Certi']);// 
		$aSql="SELECT * FROM 2012Certi WHERE certi='$Certi'";

	//echo "aSql $aSql ";
		$sRs=mysql_query($aSql,$connect);
		$aRow=mysql_fetch_array($sRs);
	 

	


	   $a[1]=$aRow[company];
	   $a[2]=$aRow[name];
	   $a[3]=$aRow[jumin];
	   $a[4]=$aRow[sigi];
	   if($a[4]=="0000-00-00"){
		 $a[4]='';
	   }
	   $a[5]=$aRow[nab];
	  /* $a[6]=$aRow[cord];
	   $a[7]=$aRow[cordPass];
	   $a[8]=$aRow[cordCerti];
	   $a[9]=$aRow[phone];
	   $a[10]=$aRow[fax];*/


	   $a[11]=$aRow[yearRate];
	   
	   $a[12]=$aRow[harinRate];
	   $a[13]=$aRow[preminun25];
	   $a[14]=$aRow[preminun44];
	   $a[15]=$aRow[preminun49];
	   $a[16]=$aRow[preminun50];
	   $a[17]=$aRow[preminun60];
	   $a[18]=$aRow[preminun66];
	   $a[19]=$aRow[preminun61];

	  // echo $a[18];
	   

	   //전체 인원을 구한기 위해 

		$sql="SELECT  *  FROM 2012DaeriMember Where dongbuCerti='$Certi' and push='4'";
		$rs=mysql_query($sql,$connect);

		$rRow=mysql_fetch_array($rs);

		 //$insCompany=$rRow[InsuranceCompany];
		 $insCompany=$aRow[insurance];
		$rNum=mysql_num_rows($rs);



	   //
	/*	 $sql="SELECT distinct(MemberNum) FROM 2012DaeriCompany  ";
		 $SrS=mysql_query($sql,$connect);
		 $Snum=mysql_num_rows($SrS);*/
		
	
if($insCompany==3){ // lig 화재		

		echo"<data>\n";
		   echo "<m1>".$Certi."</m1>\n";
		   echo "<m2>".$rNum."</m2>\n";
		  /* if($a[4]<"2016-01-01"){
					$q_num=4;
				}else if($a[4]>="2016-01-01" &&  $a[4]<"2016-11-01" ){

					$q_num=4;
				}else if($a[4]>="2016-11-01"){

					$q_num=5;

				}*/
				if($a[4]<"2023-11-01"){

					$q_num=6;
					 
				}else{
					 $a[18]=$aRow[preminun61];
					 $a[19]=$aRow[preminun66];
					$q_num=7;
				}
			 echo "<q_num>".$q_num."</q_num>\n";
		   for($_p=1;$_p<5;$_p++){
				 $mSql="SELECT * FROM 2012Member where num='$_p'";
				 $mRs=mysql_query($mSql,$connect);
				 $mRow=mysql_fetch_array($mRs);
			echo "<m3>".$mRow[name]."</m3>\n"; //담당자
				
			//년령별 4번 돌기 위해
			
			
				for($_k=0;$_k<$q_num;$_k++){
					$_q=$_k+1;
					echo "<m4>".$_q."</m4>\n";

					
				if($a[4]<"2023-11-01"){  //

							switch($_k){
								case 0:
									$m5="19세~28세";
									$where="(nai>='19' and nai<='28'  ) and";
									$preminum=$a[13];
									break;
								case 1:
									$m5="29세~40세";
									$where="(nai>='29' and nai<='40' ) and";
									$preminum=$a[14];

									break;
								case 2:
									$m5="41세~49세";
									$where="(nai>='41' and nai<='49') and";
									$preminum=$a[15];
									break;
								case 3:
									$m5="50세~55세";
									$where="(nai>='50' and nai<='55') and";
									$preminum=$a[16];
									break;
								case 4:
									$m5="56세~65세";
									$where="(nai>='56' and nai<='65') and";
									$preminum=$a[17];
									break;
								case 5:
									$m5="66세이상";
									$where="(nai>='66') and";
									$preminum=$a[18];
									break;

							}
				} else{


						switch($_k){
								case 0:
									$m5="19세~28세";
									$where="(nai>='19' and nai<='28'  ) and";
									$preminum=$a[13];
									break;
								case 1:
									$m5="29세~40세";
									$where="(nai>='29' and nai<='40' ) and";
									$preminum=$a[14];

									break;
								case 2:
									$m5="41세~49세";
									$where="(nai>='41' and nai<='49') and";
									$preminum=$a[15];
									break;
								case 3:
									$m5="50세~55세";
									$where="(nai>='50' and nai<='55') and";
									$preminum=$a[16];
									break;
								case 4:
									$m5="56세~60세";
									$where="(nai>='56' and nai<='60') and";
									$preminum=$a[17];
									break;
								case 5:
									$m5="61세~65세";
									$where="(nai>='61' and nai<='65') and";
									$preminum=$a[18];
									break;
								case 6:
									$m5="66세이상";
									$where="(nai>='66') and";
									$preminum=$a[19];
									break;

							}





				}

					echo "<m5>".$m5."</m5>\n";
					//인원를 구하기 
					$tsql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
					$tsql.="ON a.2012DaeriCompanyNum = b.num WHERE $where a.dongbuCerti='$Certi' AND a.push='4'  and ";
					$tsql.="b.MemberNum='$_p' ";



					//echo  $tsql;
					$trs=mysql_query($tsql,$connect);

					$tNum=mysql_num_rows($trs);//담당자 별 연령대 인원


					//echo $tNum;
					for($_i=0;$_i<$tNum;$_i++){
					
						$tRow=mysql_fetch_array($trs);
						//연령별 대리운전회사로부터 받는 보험료 계산을 한다 
						//보험료을 구하기 위해 
								$pSql="SELECT * FROM 2012CertiTable WHERE num='$tRow[CertiTableNum]'";
								$pRs=mysql_query($pSql,$connect);
								$pRow=mysql_fetch_array($pRs);

						//보험료을 구하기 위해 

						//대리운전회사 찾기 위해 
						$daeriComNum=$sRow['2012DaeriCompanyNum'];
						$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriComNum'";
						$iRs=mysql_query($iSql,$connect);
						$iRow=mysql_fetch_array($iRs);

						//개인별 할인할증을 구하기 위해 2019-10-20

						$rSql_="SELECT * FROM 2019rate WHERE jumin='$tRow[Jumin]' and policy='$tRow[dongbuCerti]'";

						//echo $rSql_; 
						$rRs_ =mysql_query($rSql_,$connect);
						$rRow_=mysql_fetch_array($rRs_);

						switch($rRow_[rate]){

							case 1 :
								$personRate[$_i]=1;
								$personRate2[$_i]=1;//업체로 부터 받는 경우에 단일하게 
								break;
								case 2 :
								$personRate[$_i]=0.9;
								$personRate2[$_i]=0.9;
								break;
							case 3 :
								$personRate[$_i]=0.925;
								$personRate2[$_i]=0.925;
								break;
							case 4 :
								$personRate[$_i]=0.898;
								$personRate2[$_i]=0.898;
								break;
							case 5 :
								$personRate[$_i]=0.889;
								$personRate2[$_i]=0.889;
								break;
							case 6 :
								$personRate[$_i]=1.074;
								$personRate2[$_i]=1.074;
								break;
							case 7 :
								$personRate[$_i]=1.085;
								$personRate2[$_i]=1.085;
								break;
							case 8 :
								$personRate[$_i]=1.242;
								$personRate2[$_i]=1.242;
								break;
							case 9 :
								$personRate[$_i]=1.253;
								$personRate2[$_i]=1.253;
								break;
							case 10 :
								$personRate[$_i]=1.314;
								$personRate2[$_i]=1.314;
								break;
							case 11 :
								$personRate[$_i]=1.428;
								$personRate2[$_i]=1.428;
								break;
							case 12 :
								$personRate[$_i]=1.435;
								$personRate2[$_i]=1.435;
								break;

							case 13 :
								$personRate[$_i]=1.447;
								$personRate2[$_i]=1.447;
								break;
							case 14 :
								$personRate[$_i]=1.459;
								$personRate2[$_i]=1.459;
								break;
							
							default:
								$personRate[$_i]=1;	
								$personRate2[$_i]=1;
								break;
						}
						$e[50]=$tRow['2012DaeriCompanyNum'];
						$d_sql_="SELECT * FROM 2012DaeriCompany WHERE num='$e[50]'"; 

						//echo $d_sql_;
						$d_rs_=mysql_query($d_sql_,$connect);
						$d_row_=mysql_fetch_array($d_rs_);

						//echo $d_row[union_"]; echo "//";
						if($d_row_[union_]==1){
							$personRate2[$_i]=1;
						}



			
						//echo $personRate[$_i];
						   $psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$tRow[nai]' and sPreminum<='$tRow[nai]' ";
							$psql.="and CertiTableNum='$tRow[CertiTableNum]'";
							$pRs=mysql_query($psql,$connect);
							$qRow=mysql_fetch_array($pRs);
							//$companyPrimun[$_k]+=$qRow[mPreminum];
							$companyPrimun[$_k]+=($qRow[mPreminum]*$personRate2[$_i]);

							$tpreminum[$_k]+=$preminum*$personRate[$_i];

							$mTpreminum[$_k]=($tpreminum[$_k]*10)/12;

					}
					 
					
					
					//담당자 별 인원 합을 구하기 위해
					//$tpreminum=$tNum*$preminum;
					//$mTpreminum=($tpreminum*10)/12;
					$dInWon[$_p]+=$tNum;
					$tTotal[$_p]+=$tpreminum[$_k];// 담당자 받은보험료 합
					$mTotal[$_p]+=$mTpreminum[$_k];//10%소계


					$cTotl[$_p]+=$companyPrimun[$_k];//업체로 부터 받은 담당자별 합
					echo "<m16>".number_format($companyPrimun[$_k])."</m16>\n"; //업체로 부터 받는 보험료
					
					echo "<m6>".$tNum."</m6>\n";//연령별인원
				    echo "<m9>".number_format($preminum)."</m9>\n";//연령별보험료
					echo "<m10>".number_format($tpreminum[$_k])."</m10>\n";//인원X연령보험료
					echo "<m13>".number_format($mTpreminum[$_k])."</m13>\n";//10%
					
					$companyPrimun[$_k]='';//초기화
					$mTpreminum[$_k]='';
					$tpreminum[$_k]='';
					$preminum='';

				}
					$totalInwon+=$dInWon[$_p];
					$totlaPrminum+=$tTotal[$_p];
					$mTotalPrminum+=$mTotal[$_p];
					$cTotalPrminum+=$cTotl[$_p];

					$tNum='';
				echo "<m7>".$dInWon[$_p]."</m7>\n";//소계인원
				echo "<m14>".number_format($mTotal[$_p])."</m14>\n";//10% 소계
				echo "<m11>".number_format($tTotal[$_p])."</m11>\n";//소계보험료
				echo "<m17>".number_format($cTotl[$_p])."</m17>\n"; //담당자별 업체로부터 받은 보험료 소계
				

				
		   }	
		       echo "<m8>".$totalInwon."</m8>\n"; //합계인원
			   echo "<m12>".number_format($totlaPrminum)."</m12>\n"; //합계보험료
			   echo "<m15>".number_format($mTotalPrminum)."</m15>\n";//10% 합
			   echo "<m18>".number_format($cTotalPrminum)."</m18>\n"; //담당자별 업체로부터 받은 보험료 소계



	/*			
		$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  a.dongbuCerti='$Certi' AND a,push='4'  and";
		$sql.="b. ";*/
		   echo "<sql>".$tsql."</sql>\n";
		   echo "<num>".$aRow[num]."</num>\n";
		   echo "<a1>".$a[1]."</a1>\n";
		   echo "<a2>".$a[2]."</a2>\n";
		   echo "<a3>".$a[3]."</a3>\n";
		   echo "<a4>".$a[4]."</a4>\n";
		   echo "<a5>".$a[5]."</a5>\n";
		   echo "<a6>".$a[6]."</a6>\n";
		   echo "<a7>".$a[7]."</a7>\n";
		   echo "<a8>".$a[8]."</a8>\n";
		   echo "<a9>".$a[9]."</a9>\n";
		   echo "<a10>".$num."</a10>\n";
		   echo "<a11>".$a[11]."</a11>\n";
		   echo "<a12>".$a[12]."</a12>\n";
		   echo "<a13>".number_format($a[13])."</a13>\n";
		   echo "<a14>".number_format($a[14])."</a14>\n";
		   echo "<a15>".number_format($a[15])."</a15>\n";
		   echo "<a16>".number_format($a[16])."</a16>\n";
		   echo "<a17>".number_format($a[17])."</a17>\n";
		   echo "<a18>".number_format($a[18])."</a18>\n";
		   echo "<a19>".number_format($a[19])."</a19>\n";




			
			echo "<CertiTableNum>".$Certi."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";
}else if($insCompany==4){ // 현대화재		{ 

	 

		echo"<data>\n";
		   echo "<m1>".$Certi."</m1>\n";
		   echo "<m2>".$rNum."</m2>\n";
		   
				if($a[4]<"2022-01-01"){

					$q_num=5;

				}else{

					$q_num=6;
				}
			 echo "<q_num>".$q_num."</q_num>\n";  //연령구분

			
			
		   for($_p=1;$_p<4;$_p++){
				 $mSql="SELECT * FROM 2012Member where num='$_p'";
				 $mRs=mysql_query($mSql,$connect);
				 $mRow=mysql_fetch_array($mRs);
			echo "<m3>".$mRow[name]."</m3>\n"; //담당자
				
			//년령별 4번 돌기 위해 
				for($_k=0;$_k<$q_num;$_k++){
					$_q=$_k+1;
					echo "<m4>".$_q."</m4>\n";


					
						switch($_k){
									case 0:
										$m5="19세~28세";
										$where="(nai>='19' and nai<='28'  ) and";
										$preminum=$a[13];
										break;
									case 1:
										$m5="29세~40세";
										$where="(nai>='29' and nai<='40' ) and";
										$preminum=$a[14];

										break;
									case 2:
										$m5="41세~49세";
										$where="(nai>='41' and nai<='49') and";
										$preminum=$a[15];
										break;
									case 3:
										$m5="50세~55세";
										$where="(nai>='50' and nai<='55') and";
										$preminum=$a[16];
										break;
									case 4:
										$m5="56세~65세";
										$where="(nai>='56' and nai<='65') and";
										$preminum=$a[17];
										break;
									case 5:
										$m5="66세이상";
										$where="(nai>='66') and";
										$preminum=$a[18];
										break;

								}
					
					echo "<m5>".$m5."</m5>\n";//연령레이블
					//인원를 구하기 
					$tsql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
					$tsql.="ON a.2012DaeriCompanyNum = b.num WHERE $where a.dongbuCerti='$Certi' AND a.push='4'  and ";
					$tsql.="b.MemberNum='$_p' ";

					$trs=mysql_query($tsql,$connect);

					$tNum=mysql_num_rows($trs);//담당자 별 연령대 인원

					//echo $tNum;
					for($_i=0;$_i<$tNum;$_i++){
					
						$tRow=mysql_fetch_array($trs);
						//연령별 대리운전회사로부터 받는 보험료 계산을 한다 
						//보험료을 구하기 위해 
								$pSql="SELECT * FROM 2012CertiTable WHERE num='$tRow[CertiTableNum]'";
								$pRs=mysql_query($pSql,$connect);
								$pRow=mysql_fetch_array($pRs);

						//보험료을 구하기 위해 

						//대리운전회사 찾기 위해 
						$daeriComNum=$sRow['2012DaeriCompanyNum'];
						$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriComNum'";
						$iRs=mysql_query($iSql,$connect);
						$iRow=mysql_fetch_array($iRs);

						//개인별 할인할증을 구하기 위해 2019-10-20

						$rSql_="SELECT * FROM 2019rate WHERE jumin='$tRow[Jumin]' and policy='$tRow[dongbuCerti]'";

						//echo $rSql_; 
						$rRs_ =mysql_query($rSql_,$connect);
						$rRow_=mysql_fetch_array($rRs_);

						switch($rRow_[rate]){

							case 1 :
								$personRate[$_i]=1;
								$personRate2[$_i]=1;//업체로 부터 받는 경우에 단일하게 
								break;
							case 2 :
								$personRate[$_i]=0.9;
								$personRate2[$_i]=0.9;
								break;
							case 3 :
								$personRate[$_i]=0.925;
								$personRate2[$_i]=0.925;
								break;
							case 4 :
								$personRate[$_i]=0.898;
								$personRate2[$_i]=0.898;
								break;
							case 5 :
								$personRate[$_i]=0.889;
								$personRate2[$_i]=0.889;
								break;
							case 6 :
								$personRate[$_i]=1.074;
								$personRate2[$_i]=1.074;
								break;
							case 7 :
								$personRate[$_i]=1.085;
								$personRate2[$_i]=1.085;
								break;
							case 8 :
								$personRate[$_i]=1.242;
								$personRate2[$_i]=1.242;
								break;
							case 9 :
								$personRate[$_i]=1.253;
								$personRate2[$_i]=1.253;
								break;
							case 10 :
								$personRate[$_i]=1.314;
								$personRate2[$_i]=1.314;
								break;
							case 11 :
								$personRate[$_i]=1.428;
								$personRate2[$_i]=1.428;
								break;
							case 12 :
								$personRate[$_i]=1.435;
								$personRate2[$_i]=1.435;
								break;

							case 13 :
								$personRate[$_i]=1.447;
								$personRate2[$_i]=1.447;
								break;
							case 14 :
								$personRate[$_i]=1.459;
								$personRate2[$_i]=1.459;
								break;
							
							default:
								$personRate[$_i]=1;	
								$personRate2[$_i]=1;
								break;
						}
						   $psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$tRow[nai]' and sPreminum<='$tRow[nai]' ";
							$psql.="and CertiTableNum='$tRow[CertiTableNum]'";
							//echo $psql;echo '<br>';
							$pRs=mysql_query($psql,$connect);
							$qRow=mysql_fetch_array($pRs);
							//echo ''; echo $qRow[mPreminum]; echo '<br>';
							
							$companyPrimun[$_k]+=($qRow[mPreminum]*$personRate2[$_i]);
							$tpreminum[$_k]+=$preminum*$personRate[$_i];

							$mTpreminum[$_k]=($tpreminum[$_k]*10)/12;
					}
					 
					//담당자 별 인원 합을 구하기 위해
					//$tpreminum=$tNum*$preminum;
					//$mTpreminum=($tpreminum*10)/12;
					$dInWon[$_p]+=$tNum;
					$tTotal[$_p]+=$tpreminum[$_k];// 담당자 받은보험료 합
					$mTotal[$_p]+=$mTpreminum[$_k];//10%소계
					
					//담당자 별 인원 합을 구하기 위해
					/*$tpreminum=$tNum*$preminum;
					$mTpreminum=($tpreminum*10)/12;
					$dInWon[$_p]+=$tNum;
					$tTotal[$_p]+=$tpreminum;// 담당자 받은보험료 합
					$mTotal[$_p]+=$mTpreminum;//10%소계*/


$cTotl[$_p]+=$companyPrimun[$_k];//업체로 부터 받은 담당자별 합
					echo "<m16>".number_format($companyPrimun[$_k])."</m16>\n"; //업체로 부터 받는 보험료
					
					echo "<m6>".$tNum."</m6>\n";//연령별인원
				    echo "<m9>".number_format($preminum)."</m9>\n";//연령별보험료
					echo "<m10>".number_format($tpreminum[$_k])."</m10>\n";//인원X연령보험료
					echo "<m13>".number_format($mTpreminum[$_k])."</m13>\n";//10%
					
					$companyPrimun[$_k]='';//초기화
					$mTpreminum[$_k]='';
					$tpreminum[$_k]='';
					$preminum='';
				/*	$cTotl[$_p]+=$companyPrimun[$_k];//업체로 부터 받은 담당자별 합
					echo "<m16>".number_format($companyPrimun[$_k])."</m16>\n"; //업체로 부터 받는 보험료
					
					echo "<m6>".$tNum."</m6>\n";//연령별인원
				    echo "<m9>".number_format($preminum)."</m9>\n";//연령별보험료
					echo "<m10>".number_format($tpreminum)."</m10>\n";//인원X연령보험료
					echo "<m13>".number_format($mTpreminum)."</m13>\n";//10%
					
					$companyPrimun[$_k]='';//초기화 
					$preminum='';*/

				}



					$totalInwon+=$dInWon[$_p];
					$totlaPrminum+=$tTotal[$_p];
					$mTotalPrminum+=$mTotal[$_p];
					$cTotalPrminum+=$cTotl[$_p];

					$tNum='';
				echo "<m7>".$dInWon[$_p]."</m7>\n";//소계인원
				echo "<m14>".number_format($mTotal[$_p])."</m14>\n";//10% 소계
				echo "<m11>".number_format($tTotal[$_p])."</m11>\n";//소계보험료
				echo "<m17>".number_format($cTotl[$_p])."</m17>\n"; //담당자별 업체로부터 받은 보험료 소계
				

				
		   }	
		       echo "<m8>".$totalInwon."</m8>\n"; //합계인원
			   echo "<m12>".number_format($totlaPrminum)."</m12>\n"; //합계보험료
			   echo "<m15>".number_format($mTotalPrminum)."</m15>\n";//10% 합
			   echo "<m18>".number_format($cTotalPrminum)."</m18>\n"; //담당자별 업체로부터 받은 보험료 소계



	/*			
		$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  a.dongbuCerti='$Certi' AND a,push='4'  and";
		$sql.="b. ";*/
		   echo "<sql>".$tsql."</sql>\n";
		   echo "<num>".$aRow[num]."</num>\n";
		   echo "<a1>".$a[1]."</a1>\n";
		   echo "<a2>".$a[2]."</a2>\n";
		   echo "<a3>".$a[3]."</a3>\n";
		   echo "<a4>".$a[4]."</a4>\n";
		   echo "<a5>".$a[5]."</a5>\n";
		   echo "<a6>".$a[6]."</a6>\n";
		   echo "<a7>".$a[7]."</a7>\n";
		   echo "<a8>".$a[8]."</a8>\n";
		   echo "<a9>".$a[9]."</a9>\n";
		   echo "<a10>".$num."</a10>\n";
		   echo "<a11>".$a[11]."</a11>\n";
		   echo "<a12>".$a[12]."</a12>\n";
		   echo "<a13>".number_format($a[13])."</a13>\n";
		   echo "<a14>".number_format($a[14])."</a14>\n";
		   echo "<a15>".number_format($a[15])."</a15>\n";
		   echo "<a16>".number_format($a[16])."</a16>\n";
		   echo "<a17>".number_format($a[17])."</a17>\n";
		   echo "<a18>".number_format($a[18])."</a18>\n";
            echo "<a19>".number_format($a[19])."</a19>\n";




			
			echo "<CertiTableNum>".$Certi."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";






}else{  //롯데

	echo"<data>\n";
		   echo "<m1>".$Certi."</m1>\n";
		   echo "<m2>".$rNum."</m2>\n";
		   
			/*	if($a[4]<"2022-01-01"){

					$q_num=5;

				}else{

					$q_num=6;
				}*/
$q_num=3;


			 echo "<q_num>".$q_num."</q_num>\n";

			
			
		   for($_p=1;$_p<4;$_p++){
				 $mSql="SELECT * FROM 2012Member where num='$_p'";
				 $mRs=mysql_query($mSql,$connect);
				 $mRow=mysql_fetch_array($mRs);
			echo "<m3>".$mRow[name]."</m3>\n"; //담당자
				
			//년령별 4번 돌기 위해 
				for($_k=0;$_k<$q_num;$_k++){
					$_q=$_k+1;
					echo "<m4>".$_q."</m4>\n";


					

							switch($_k){
								case 0:
									$m5="26세~36세";
									$where="(nai>='26' and nai<='36'  ) and";
									$preminum=$a[13];
									break;
								case 1:
									$m5="37세~58세";
									$where="(nai>='37' and nai<='58' ) and";
									$preminum=$a[14];

									break;
								
								
								case 2:
									$m5="59세이상";
									$where="(nai>='59') and";
									$preminum=$a[15];
									break;

							}
					
					echo "<m5>".$m5."</m5>\n";
					//인원를 구하기 
					$tsql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
					$tsql.="ON a.2012DaeriCompanyNum = b.num WHERE $where a.dongbuCerti='$Certi' AND a.push='4'  and ";
					$tsql.="b.MemberNum='$_p' ";

					$trs=mysql_query($tsql,$connect);

					$tNum=mysql_num_rows($trs);//담당자 별 연령대 인원

					//echo $tNum;
					for($_i=0;$_i<$tNum;$_i++){
					
						$tRow=mysql_fetch_array($trs);
						//연령별 대리운전회사로부터 받는 보험료 계산을 한다 
						//보험료을 구하기 위해 
								$pSql="SELECT * FROM 2012CertiTable WHERE num='$tRow[CertiTableNum]'";
								$pRs=mysql_query($pSql,$connect);
								$pRow=mysql_fetch_array($pRs);

						//보험료을 구하기 위해 

						//대리운전회사 찾기 위해 
						$daeriComNum=$sRow['2012DaeriCompanyNum'];
						$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriComNum'";
						$iRs=mysql_query($iSql,$connect);
						$iRow=mysql_fetch_array($iRs);

						//
						   $psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$tRow[nai]' and sPreminum<='$tRow[nai]' ";
							$psql.="and CertiTableNum='$tRow[CertiTableNum]'";
							//echo $psql;echo '<br>';
							$pRs=mysql_query($psql,$connect);
							$qRow=mysql_fetch_array($pRs);
							//echo ''; echo $qRow[mPreminum]; echo '<br>';
							$companyPrimun[$_k]+=$qRow[mPreminum];
					}
					 
					
					
					//담당자 별 인원 합을 구하기 위해
					$tpreminum=$tNum*$preminum;
					$mTpreminum=($tpreminum*10)/12;
					$dInWon[$_p]+=$tNum;
					$tTotal[$_p]+=$tpreminum;// 담당자 받은보험료 합
					$mTotal[$_p]+=$mTpreminum;//10%소계

					$cTotl[$_p]+=$companyPrimun[$_k];//업체로 부터 받은 담당자별 합
					echo "<m16>".number_format($companyPrimun[$_k])."</m16>\n"; //업체로 부터 받는 보험료
					
					echo "<m6>".$tNum."</m6>\n";//연령별인원
				    echo "<m9>".number_format($preminum)."</m9>\n";//연령별보험료
					echo "<m10>".number_format($tpreminum)."</m10>\n";//인원X연령보험료
					echo "<m13>".number_format($mTpreminum)."</m13>\n";//10%
					
					$companyPrimun[$_k]='';//초기화 
					$preminum='';

				}
					$totalInwon+=$dInWon[$_p];
					$totlaPrminum+=$tTotal[$_p];
					$mTotalPrminum+=$mTotal[$_p];
					$cTotalPrminum+=$cTotl[$_p];

					$tNum='';
				echo "<m7>".$dInWon[$_p]."</m7>\n";//소계인원
				echo "<m14>".number_format($mTotal[$_p])."</m14>\n";//10% 소계
				echo "<m11>".number_format($tTotal[$_p])."</m11>\n";//소계보험료
				echo "<m17>".number_format($cTotl[$_p])."</m17>\n"; //담당자별 업체로부터 받은 보험료 소계
				

				
		   }	
		       echo "<m8>".$totalInwon."</m8>\n"; //합계인원
			   echo "<m12>".number_format($totlaPrminum)."</m12>\n"; //합계보험료
			   echo "<m15>".number_format($mTotalPrminum)."</m15>\n";//10% 합
			   echo "<m18>".number_format($cTotalPrminum)."</m18>\n"; //담당자별 업체로부터 받은 보험료 소계



	/*			
		$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  a.dongbuCerti='$Certi' AND a,push='4'  and";
		$sql.="b. ";*/
		   echo "<sql>".$tsql."</sql>\n";
		   echo "<num>".$aRow[num]."</num>\n";
		   echo "<a1>".$a[1]."</a1>\n";
		   echo "<a2>".$a[2]."</a2>\n";
		   echo "<a3>".$a[3]."</a3>\n";
		   echo "<a4>".$a[4]."</a4>\n";
		   echo "<a5>".$a[5]."</a5>\n";
		   echo "<a6>".$a[6]."</a6>\n";
		   echo "<a7>".$a[7]."</a7>\n";
		   echo "<a8>".$a[8]."</a8>\n";
		   echo "<a9>".$a[9]."</a9>\n";
		   echo "<a10>".$num."</a10>\n";
		   echo "<a11>".$a[11]."</a11>\n";
		   echo "<a12>".$a[12]."</a12>\n";
		   echo "<a13>".number_format($a[13])."</a13>\n";
		   echo "<a14>".number_format($a[14])."</a14>\n";
		   echo "<a15>".number_format($a[15])."</a15>\n";
		   echo "<a16>".number_format($a[16])."</a16>\n";
		   echo "<a17>".number_format($a[17])."</a17>\n";
		   echo "<a18>".number_format($a[18])."</a18>\n";
            echo "<a19>".number_format($a[19])."</a19>\n";
			echo "<CertiTableNum>".$Certi."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";


}


	?>