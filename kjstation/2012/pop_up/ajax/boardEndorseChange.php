<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

$memberNum =iconv("utf-8","euc-kr",$_GET['memberNum']);	
//배서 처리전 sangtae 가 2인경우는 이미 처리 된 경우에 해당 하고 

// sangtae 1 인 경우 처리 한다
	$sql="SELECT * FROM 2021DaeriMember WHERE num='$memberNum'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);




	//2019-10-19 
	//개인별 손해율 조회 하기 위해
	$e[12]=$row[dongbuCerti];// 증권번호
	$e[5]=$row[Jumin]; //주민번호
	$e[30]=$row[InsuranceCompany]; //보험회사
	$e[50]=$_GET['DaeriCompanyNum'];

	if($row[sangtae]==2){
		$message='이미 처리 된 건 입니다';
		echo"<data>\n";
			echo "<message>".$message."</message>\n";
		echo"</data>";

	}else{ ///// sangtae 1 인 경우 처리 한다


				
				$eNum=iconv("utf-8","euc-kr",$_GET['eNum']);
				$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
				$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
				$val =iconv("utf-8","euc-kr",$_GET['val']);//push값 Db에 저장 된 값과 같은지 검사부터 하자
				$nn=iconv("utf-8","euc-kr",$_GET['nn']);// 배서된 자의 보험료 조정하기우해 

				$certiNum=iconv("utf-8","euc-kr",$_GET['certiNum']);//동부화재 증권번호	
				$selNum=iconv("utf-8","euc-kr",$_GET['selNum']);	//동부화재 설계번호


				$sigi=iconv("utf-8","euc-kr",$_GET['sigi']);//동부화재 보험시기
				$jeonggi=iconv("utf-8","euc-kr",$_GET['jeonggi']);//동부화재 보험종기
				$cheriii=iconv("utf-8","euc-kr",$_GET['cheriii']); //동부화재 납입회차

				$c_preminum=iconv("utf-8","euc-kr",$_GET['c_preminum']); //보험회사 보험료


				//CertiTable  보험료 산출을 위해 

				$cSql="SELECT * FROM 2021CertiTable WHERE num='$CertiTableNum'";
				$cRs=mysql_query($cSql,$connect);
				$cRow=mysql_fetch_array($cRs);


				$divi=$cRow[divi];
				
				//$insuranceCompany=iconv("utf-8","euc-kr",$_GET['insuranceCompany']); //1흥국,2동부,3LiG 4.현대

				//처음 거래일을 찾기 위해 2012DaeriCompanyNum

				$dSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
				$dRs=mysql_query($dSql,$connect);
				$dRow=mysql_fetch_array($dRs);

				$FirstStartDay=$dRow[FirstStartDay];

				$fstartyDay=$cRow[fstartyDay];

				//2015-03-19  광명7080대리운전  각 증권별로 월 받는 날이 다름 5일자 25일자 구분 하기 

				// 동일 대리운전회사가 증권별로 보험료 정산일이 다른 경우 

				if($fstartyDay){$FirstStartDay=$fstartyDay;}


				


			//개별기사 증권번호를 보내기 위해
			//2013-07-14 수정한다

			//$row[dongbuCerti]
			////////////////////////////////////////
				
				switch($val){//
					
					case 1 : //청약을 정상으로 
					 $push=4;
					 $sms=1;//문자보냄
					 $message="가입완료";


					 //우편물 발송을 위해 
						//$DaeriCompanyNum;
						


					 ///우편물 발송 
					break;

					case 2 : //청약을 취소
					 $push=1;
					 $cancel=12;
					 $sms=2;//문자안보냄
					 $message="청약취소";
					break;

					case 3 : //청약을 거절
					 $push=1;
					 $cancel=13;
					 $sms=2;//문자안보냄
					 $message="청약거절";
					break;

					case 4: //정상을 해지로 
					 $push=2;
					 $caccel=42;
					 $sms=1;//문자보냄
					 $message="해지완료";
					break;

					case 5: //해지 신청한 것을 취소 
					 $push=4;
					 $cancel=45; 
					 $sms=2;//문자안보냄
					 $message="해지취소";
					break;
				}
					//
					

					//if($push==4){
					/*if(!$cancel){
						$updateSj="UPDATE 2012DaeriMember SET push='$push',sangtae='2',dongbuCerti='$certiNum',cancel='$cancel',";	
						$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii' ";
						$updateSj.="WHERE num='$memberNum'";
					}else{*/


						if($insuranceCompany==2){

								$updateSj="UPDATE 2021DaeriMember SET push='$push',sangtae='2',dongbuCerti='$certiNum',cancel='$cancel',";	
								$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii',ch='1' ";
								$updateSj.="WHERE num='$memberNum'";
						}else{


							$updateSj="UPDATE 2021DaeriMember SET push='$push',sangtae='2',dongbuCerti='$certiNum',cancel='$cancel',ch='1'";	
								//$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii' ";
							$updateSj.="WHERE num='$memberNum'";
						}
						


					//}
					mysql_query($updateSj,$connect);
					$qSql="SELECT e_count,e_count_2,endorse_day FROM 2021EndorseList WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					$qrs=mysql_query($qSql,$connect);
					$qRow=mysql_fetch_array($qrs);//
					
					
					$e_count_2=$qRow[e_count_2]+1;//신청건수
					$updateE="UPDATE 2021EndorseList SET e_count_2='$e_count_2' WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					mysql_query($updateE,$connect);

					

					//신청건수와 배서건수가 같으면  sangtae를 2로 변경,ch 값을 10로 변경한다

					$eSql="SELECT e_count_2 FROM 2021EndorseList WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					$ers=mysql_query($eSql,$connect);

					$erow=mysql_fetch_array($ers);

					if($qRow[e_count]==$erow[e_count_2]){

						$updateQ="UPDATE 2021EndorseList SET ch=10,sangtae='2' WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
							mysql_query($updateQ,$connect);
					}

					

					//보험료 산출하자
					$pchagne=2;//preminumNaiEndorse.php 에서 사용하기 위해 2는 endorseChange.php 
			$em=explode("-",$qRow[endorse_day]);
			 
			//echo "today $today ";

			$today=$em[2];
					

			//2019-10-19
		include "./php/boardPersonRate.php";
					include "./php/boardPreminumNaiEndorse.php";

					//모계약의 보험료 기준으로 보험료 산출하여 별도로 청구하기 위하여
					if($cRow['moValue']==2){
						$m_sql="SELECT * FROM 2021CertiTable WHERE policyNum='$cRow[policyNum]' AND moValue='1'";
						//echo $m_sql; echo "<br>";
						$m_rs=mysql_query($m_sql,$connect);
						$m_row=mysql_fetch_array($m_rs);
						$CertiTableNum=$m_row[num];

						include "./php/boardPreminumNaiEndorse_2.php";


						$endorsePreminum2=$endorsePreminum2;

						

						//echo "$CertiTableNum ㅂㅐ서 $endorsePreminum";
					}
			//return false;
					// 배서 처리 후 확정 보험료를 2012EndorsePreminum 저장한다...
					//sort 2 미수 1 받음;
			//취소,거절인경우 저장 되지 않는다
			if($sms==1){
				/*	$pInsert="INSERT INTO 2012EndorsePreminum (preminum,2012DaeriMemberNum,pnum,2012DaeriCompanyNum,InsuranceCompany, ";
					$pInsert.="CertiTableNum,policyNum,endorse_day,get,damdanga,sort )";
					$pInsert.="VALUES ('$endorsePreminum','$memberNum','$eNum','$DaeriCompanyNum','$inSuranceCom', ";
					$pInsert.="'$CertiTableNum','$cRow[policyNum]','$DendorseDay','$push','$dRow[MemberNum]','2')";

					mysql_query($pInsert,$connect);*/

			//문자보냄
					// 처리 결과를 문자 보내기 위해 .....
				   $company_tel="070-7841-5962";
				   $con_phone1=$dRow[hphone];
				   $endorseCh=1;//send_name을 drive로 하기 위해 ....

						switch($inSuranceCom)
						{
							case 1 : 
								$insuranceCompany="흥국화재";
							//	$policy=explode("-",$cRow[policyNum]);
							//	$po=$policy[0]."-".$policy[1];
								$po=$certiNum;
								break;
							case 2 : 
								$insuranceCompany="DB화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po="017-".$certiNum."-000";
								$po="2-20".$certiNum."-000";
								break;
							case 3 : 
								$insuranceCompany="KB화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[0]."-".$policy[1];
								$po=$certiNum;
								break;
							case 4 : 
								$insuranceCompany="현대화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[0]."-".$policy[1];
								$po=$certiNum;
								break;
							case 5 : 
								$insuranceCompany="한화화재";
								$policy=explode("-",$cRow[policyNum]);
								$po=$policy[1]."-".$policy[2];
								$po=$certiNum;
								break;
							case 6 : 
								$insuranceCompany="더케이";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$certiNum;
								break;
							case 7 : 
								$insuranceCompany="MG";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$certiNum;
								break;
							case 8 : 
								$insuranceCompany="삼성";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$certiNum;
								break;
							case 9 : 
								$insuranceCompany="메리츠";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$certiNum;
								break;						}
						

					//$endorsePreminum=number_format($endorsePreminum);
					if($endorsePreminum==0){

						$endorsePreminum='';
					}

					if($divi!=2){  //2015-10-20  정상분납일경우 에 

						$endorsePreminum=$c_preminum;
					}

					//2019-10-19 개인요율 적용하기 위해 
					$endorsePreminum=round($endorsePreminum*$personRate2,-1);
					if($cRow['moValue']==2){
						$endorsePreminum2=round($endorsePreminum2*$personRate2,-1);
					}
					// 2020-01-25 월보험료 적용

					$PreminumMonth=round($PreminumMonth*$personRate2,-1);
				   switch($push){
						case 2 :	
							$msg=$insuranceCompany."킥보험".$row[Name]."님 해지기준일[".$DendorseDay."][".$po."]".number_format($endorsePreminum);
							break;
						case 4 :
							$msg=$insuranceCompany."킥보험".$row[Name]."님 기준일[".$DendorseDay."][".$po."]월".number_format($PreminumMonth)."배".number_format($endorsePreminum);
							break;
				   }
				   $qboard='2'; //킥보드 2 대리 1 
				   include "./php/coSms.php";//smsAjax.php에서 사용
				   include "./php/smsQuery.php";
			//2013-03-22 //흥국화재 인경우는 우편물 발행이 필요없으니 까 

			/*if($inSuranceCom!=1){
				   if($val==1){//청약을 정상으로  되면 우편물 발행을 위해 12  || 2012DaeriCompany table응 가리킴 $table_name_num=12

						$msql="SELECT * FROM print_table WHERE table_name_num='12' and table_num='$DaeriCompanyNum' and wdate='$DendorseDay'";
						$mrs=mysql_query($msql,$connect);
						$mrow=mysql_fetch_array($mrs);

						if($mrow[num]){//있으면 날자을 update 없으면 insert 한다

							$mupdate="UPDATE print_table SET table_name_num='12',table_num='$DaeriCompanyNum',wdate='$DendorseDay' ";
							$mupdate.="WHERE num='$mrow[num]'";

							mysql_query($mupdate,$connect);
						}else{
				
							$mInsert="INSERT INTO print_table (table_name_num,table_num,wdate) ";
							$mInsert.="VALUES ('12','$DaeriCompanyNum','$DendorseDay' )";

							mysql_query($mInsert,$connect);
						}
						
						

						
				   }//val==1
				}*///2013-03-22 //흥국화재 인경우는 우편물 발행이 필요없으니 까 

			}//sms
			
			switch($push){
				case 2 :
					$message="해지 처리 되었습니다";
					break;
				case 4:
					$message="가입 처리 되었습니다";
					break;
				default :
					$message="처리완료";
					break;
			}
			echo"<data>\n";
				echo "<num>".$updateSj."</num>\n";
				echo "<num2>".$pInsert."[".$endorsePreminum."</num2>\n";
				//echo "<DendorseDay>".$DendorseDay."</DendorseDay>\n";//
				echo "<EndorsePnum>".$endorsePreminum."</EndorsePnum>\n";//
				echo "<EndorsePreminum>".number_format($endorsePreminum)."</EndorsePreminum>\n";//
				echo "<nn>".$nn."</nn>\n";
			/*	for($_u_=1;$_u_<15;$_u_++){
					echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
					
				}*/
				
				
				echo "<val>".$val."</val>\n";//2또는 3인경우//청약이 취소 또는 거절인 경우 

					if($val==2 || $val==3){//가입한 다른보험회사가 있나 찾는다

						$Csql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' ";
						$Csql.="and InsuraneCompany!='$inSuranceCom' and startyDay>='$yearbefore'";
						
						$Crs=mysql_query($Csql,$connect);
						$Cnum=mysql_num_rows($Crs);

							$C2num=$Cnum+1;//개수보다 하나 많게 하는 이유은 ?
							echo "<Cnum>".$C2num."</Cnum>\n";
							
						for($_p=0;$_p<$C2num;$_p++){

							$Crow=mysql_fetch_array($Crs);

							$Ccnum[$_p]=$Crow[num];
							$Cinsum[$_p]=$Crow[InsuraneCompany];
							if($_p==$Cnum){// 앞부분에 선택을 만들기 위해 
								$Ccnum[$_p]='99999999';
								$Cinsum[$_p]='99';
							}
							echo "<Ctnum".$_p.">".$Ccnum[$_p]."</Ctnum".$_p.">\n";//2012CertiTablenum
							echo "<inum".$_p.">".$Cinsum[$_p]."</inum".$_p.">\n";

						}


					}

				echo "<message>".$message."</message>\n";
				//include "./php/smsData.php";//endorseAjaxSerch.php에서 공동으로 사용
				
			echo"</data>";

	} //// sangtae 1 인 경우 처리 한다

	?>