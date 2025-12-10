<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

/*	//계약자 정보
	//$push			=iconv("utf-8","euc-kr",$_GET['push']);
	$driver_num	    =iconv("utf-8","euc-kr",$_GET['driver_num']);
	//$new_name		=iconv("utf-8","euc-kr",$_GET['new_name']);
	//$new_jumin_a	=iconv("utf-8","euc-kr",$_GET['new_jumin_a']);
	//$new_jumin_b	=iconv("utf-8","euc-kr",$_GET['new_jumin_b']);
	//$ssang_c_num	=iconv("utf-8","euc-kr",$_GET['ssangCnum']);
	$userid	=iconv("utf-8","euc-kr",$_GET['useruid']);
	$endorse_day	=iconv("utf-8","euc-kr",$_GET['endorse_day']);
	//$bank	=iconv("utf-8","euc-kr",$_GET['bank']);
	$sunbun	=iconv("utf-8","euc-kr",$_GET['sunbun']);//회차
	
	

	$update ="UPDATE 2012DaeriMember SET ch='$sunbun' WHERE num='$driver_num'";
	echo $update;
	//mysql_query($update,$connect);

*


	$message='처리완료 !!';*/



	//배서 처리를 위해 


	
$memberNum =iconv("utf-8","euc-kr",$_GET['driver_num']);	
//배서 처리전 sangtae 가 2인경우는 이미 처리 된 경우에 해당 하고 

// sangtae 1 인 경우 처리 한다
	$sql="SELECT * FROM 2012DaeriMember WHERE num='$memberNum'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);




	//2019-10-19 
	//개인별 손해율 조회 하기 위해
	$e[12]=$row[dongbuCerti];// 증권번호
	$e[5]=$row[Jumin]; //주민번호
	$e[30]=$row[InsuranceCompany]; //보험회사
	$_GET['DaeriCompanyNum']=$e[50]=$row['2012DaeriCompanyNum'];

	$_GET['CertiTableNum']=$row[CertiTableNum];
	$_GET['certiNum']=$row[dongbuCerti];

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

				$cSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				$cRs=mysql_query($cSql,$connect);
				$cRow=mysql_fetch_array($cRs);


				$divi=$cRow[divi];
				
				//$insuranceCompany=iconv("utf-8","euc-kr",$_GET['insuranceCompany']); //1흥국,2동부,3LiG 4.현대

				//처음 거래일을 찾기 위해 2012DaeriCompanyNum

				$dSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";

				//echo $dSql;
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

								$updateSj="UPDATE 2012DaeriMember SET push='$push',sangtae='2',dongbuCerti='$certiNum',cancel='$cancel',";	
								$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii',ch='1' ";
								$updateSj.="WHERE num='$memberNum'";
						}else{


							$updateSj="UPDATE 2012DaeriMember SET push='$push',sangtae='2',dongbuCerti='$certiNum',cancel='$cancel',ch='1'";	
								//$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii' ";
							$updateSj.="WHERE num='$memberNum'";
						}
						

					//echo $updateSj;
					//}
					mysql_query($updateSj,$connect);
					$qSql="SELECT e_count,e_count_2,endorse_day FROM 2012EndorseList WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					$qrs=mysql_query($qSql,$connect);
					$qRow=mysql_fetch_array($qrs);//
					
					
					$e_count_2=$qRow[e_count_2]+1;//신청건수
					$updateE="UPDATE 2012EndorseList SET e_count_2='$e_count_2' WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					mysql_query($updateE,$connect);

					

					//신청건수와 배서건수가 같으면  sangtae를 2로 변경,ch 값을 10로 변경한다

					$eSql="SELECT e_count_2 FROM 2012EndorseList WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
					$ers=mysql_query($eSql,$connect);

					$erow=mysql_fetch_array($ers);

					if($qRow[e_count]==$erow[e_count_2]){

						$updateQ="UPDATE 2012EndorseList SET ch=10,sangtae='2' WHERE pnum='$eNum' AND CertiTableNum='$CertiTableNum'";
							mysql_query($updateQ,$connect);
					}

					

					//보험료 산출하자
					$pchagne=2;//preminumNaiEndorse.php 에서 사용하기 위해 2는 endorseChange.php 
			$em=explode("-",$qRow[endorse_day]);
			 
			//echo "today $today ";

			$today=$em[2];
					

			//2019-10-19
		include "../../pop_up/ajax/php/personRate.php";
					include "../../pop_up/ajax/php/preminumNaiEndorse_3.php"; 
					//배서 보험료 결과가 같은데,
					//endorseAjaxSerch에서는 $endorsePre[$k] (개인별할인할증이 적용된) 사용하고
					//endoreseChange에서는 $endorsePreminum을 사용하고 있음 
					//정신없이 개발하였군 ....

					//하여 endoreseChange
					$fakeendorsePreminum = $endorsePreminum; 
					//$fakeEndorsePre[$k] 정상분납인 경우에도 월납 배서보험료를 계산하여 업체에게 알려주기 위함
					// $fakeEndorsePre[$k] 과 $endoreseChange는 같은 값임,
					
					//$endorsePre[$k] 계산됨  

					//


					//모계약의 보험료 기준으로 보험료 산출하여 별도로 청구하기 위하여
					if($cRow['moValue']==2){
						$m_sql="SELECT * FROM 2012CertiTable WHERE policyNum='$cRow[policyNum]' AND moValue='1'";
						//echo $m_sql; echo "<br>";
						$m_rs=mysql_query($m_sql,$connect);
						$m_row=mysql_fetch_array($m_rs);
						$CertiTableNum=$m_row[num];

						include "../../pop_up/ajax/php/preminumNaiEndorse_2.php";


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
						}
						

					//$endorsePreminum=number_format($endorsePreminum);
					if($endorsePreminum==0){

						$endorsePreminum='';
					}

					if($divi!=2){  //2015-10-20  정상분납일경우 에 

						$endorsePreminum=$c_preminum;
					}

					//2019-10-19 개인요율 적용하기 위해 
					$endorsePreminum=round($endorsePreminum*$personRate2,-1);
					$fakeendorsePreminum=round($fakeendorsePreminum*$personRate2,-1);
					if($cRow['moValue']==2){
						$endorsePreminum2=round($endorsePreminum2*$personRate2,-1);
					}
					// 2020-01-25 월보험료 적용

					$PreminumMonth=round($PreminumMonth*$personRate2,-1);

					//echo $push;  echo "sjjd";
				   switch($push){
						case 2 :	
							if($Divi==1){ //정상분납인 경우
								$msg=$insuranceCompany."대리보험".$row[Name]."님 해지기준일[".$DendorseDay."][".$po."]"."배".number_format($endorsePreminum);
							}else{
								$msg=$insuranceCompany."대리보험".$row[Name]."님 해지기준일[".$DendorseDay."][".$po."]".number_format($endorsePreminum);
							}
							break;
						case 4 :
							if($Divi==1){ //정산분납인 경우
							
								$msg=$insuranceCompany."대리보험".$row[Name]."님 기준일[".$DendorseDay."][".$po."]월".number_format($PreminumMonth)."배".number_format($endorsePreminum);
							}else{
								$msg=$insuranceCompany."대리보험".$row[Name]."님 기준일[".$DendorseDay."][".$po."]월".number_format($PreminumMonth)."배".number_format($endorsePreminum);
							}


							//2024-12-16 현대해상 체결 동의 

							if($inSuranceCom==4){
									$jumin_=explode('-',$row[Jumin]);
									$hnumber_=explode('-',$row[Hphone]);
									$driverCompony=$dRow[company];  // 대리운전회사
									$driverName=$row[Name];         //성명
									$data =$hnumber_[0].$hnumber_[1].$hnumber_[2]; //핸드폰번호// 암호화할 데이터를 준비합니다.
									$data2=$jumin_[0].$jumin_[1];//주민번호
									$po_=explode("-",$po);
									$policyNumber=$po_[0].$po_[1];//증권번호
									$startDay__=explode('-',$cRow[startyDay]);
									$validStartDay=$startDay__[0].$startDay__[1].$startDay__[2];
									$sigi__ =$cRow[startyDay];
									$endDay__=date("Y-m-d ",strtotime("$sigi__ +1 year"));

									$endDay2__=explode('-',$endDay__);
									//echo $endDay__;
									$validEndDay=$endDay2__[0].$endDay2__[1].$endDay2__[2];
									//$validEndDay=$validEndDay;
									///echo 'validEndDay'; echo $validEndDay;
									include '../../pop_up/ajax/php/hcurl.php';
							}
							break;
				   }

				   //echo $msg;
				   $qboard='1'; //킥보드 2 대리 1 2021-12-12
				   include "../../pop_up/ajax/php/coSms.php";//smsAjax.php에서 사용
				   include "../../pop_up/ajax/php/smsQuery.php";
			
					



					//대리회사중에서 관리자가 여러명인 경우 각 관리자에게 문자를 보낸다 ..2022-10-10

					//$DaeriCompanyNum 값으로 2012Costomer 아이디가 몇개인가를 찾고 


					

					
			}//sms
			/*
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
			}*/
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
				include "../../pop_up/ajax/php/smsData.php";//endorseAjaxSerch.php에서 공동으로 사용
				
			echo"</data>";

	} 
	
		


	

		
echo"<data>\n";
	echo "<num>".$endorse_num."</num>\n";
	echo "<care>".$push."</care>\n";
	echo "<message>".$message."</message>\n";
	echo "<cMsg>".$cMsag."</cMsg>\n";
	echo "<comP>".$comP."</comP>\n";
	echo "<sunbun>".$sunbun."</sunbun>\n";
echo"</data>";


?>
