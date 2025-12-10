<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include


include "./php/encryption.php";
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);
// POST 변수 가져오기
$num = isset($_POST['num']) ? $_POST['num'] : '';  //2012MemberTable num
$status = isset($_POST['status']) ? $_POST['status'] : '';
$push = isset($_POST['push']) ? $_POST['push'] : '';
$manager = isset($_POST['userName']) ? $_POST['userName'] : '';
$manager = @iconv("UTF-8//IGNORE", "EUC-KR", $manager);
$reasion =isset($_POST['reasion']) ? $_POST['reasion'] : '';
$reasion = @iconv("UTF-8//IGNORE", "EUC-KR", $reasion);
$smsContents = isset($_POST['smsContents']) ? $_POST['smsContents'] : '';
$smsContents = @iconv("UTF-8//IGNORE", "EUC-KR", $smsContents);

// 응답 생성
if (empty($num) || empty($status)) {
    $response = array(
        "success" => false,
        "error" => "필수 필드(num, status)가 누락되었습니다."
    );
} else {

		$sql = "SELECT  *FROM DaeriMember WHERE num='$num'";

		$rs=mysql_query($sql,$conn);
		$row=mysql_fetch_array($rs);

		if($row[sangtae]==2){
			$message='이미 처리 된 건 입니다';
			$message=@iconv("UTF-8//IGNORE", "EUC-KR", $message);
			echo"<data>\n";
				echo "<message>".$message."</message>\n";
			echo"</data>";

		}else{ ///// sangtae 1 인 경우 처리 한다
		// cNum ,pNum, policyNum,jumin,
				$dNum=$row['2012DaeriCompanyNum'];  // 
				$cNum=$row['CertiTableNum'];  // 
				$pNum=$row['EndorsePnum'];
				$policyNum=$row['dongbuCerti'];

				include "./php/decrptJuminHphone.php"; 
				$jumin=$row['Jumin'];
				$ju=explode('-',$jumin); 
				$InsuraneCompany=$row['InsuranceCompany'];  //보험회사 
				$etag=$row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 
				$endorse_day=$row['endorse_day'];
				$personRate = $row['rate'];
 //const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}
				switch($push){//
					
					case 1 : //청약을 정상으로 
					 $push_2=4; //  SMSData push 저장하기 위한 변수 
					 $push=4;
					 $sms=1;//문자보냄
					 $message="가입완료";
					break;

					case 6 : //청약을 취소
					 $push_2=6; //  SMSData push 저장하기 위한 변수 
					 $push=1;
					 $cancel=12;
					 $sms=2;//$smsContents
					 $message="청약취소";
					break;

					case 3 : //청약을 거절
					 $push_2=3; //  SMSData push 저장하기 위한 변수 
					 $push=1;
					 $cancel=13;
					 $sms=2;//$smsContents
					 $message="청약거절";
					break;

					case 4: //정상을 해지로 
					 $push_2=2; //  SMSData push 저장하기 위한 변수 
					 $push=2;
					 $caccel=42;
					 $sms=1;//문자보냄
					 $message="해지완료";
					break;

					case 5: //해지 신청한 것을 취소 
					 $push_2=5; //  SMSData push 저장하기 위한 변수 
					 $push=4;
					 $cancel=45; 
					 $sms=2;//$smsContents
					 $message="해지취소";
					break;
				}

				//echo "sms"; echo $sms;
					if($insuranceCompany==2){  //DB 손보인 경우

								$updateSj="UPDATE 2012DaeriMember SET push='$push',sangtae='2',cancel='$cancel',";	
								$updateSj.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii',ch='1',reasion='$reasion' ,manager='$manager' ";
								$updateSj.="WHERE num='$num'";
							// 암호화 부분인데 완벽해지면 상기 부부분은 삭제
								$updateNew="UPDATE DaeriMember SET push='$push',sangtae='2',cancel='$cancel',";	
								$updateNew.="dongbuSelNumber='$selNum', dongbusigi='$sigi',dongbujeongi='$jeonggi',nabang_1='$cheriii',ch='1',reasion='$reasion' ,manager='$manager' ";
								$updateNew.="WHERE num='$num'";
					}else{


						$updateSj="UPDATE 2012DaeriMember SET push='$push',sangtae='2',cancel='$cancel',ch='1',reasion='$reasion' ,manager='$manager' ";	
						$updateSj.="WHERE num='$num'";
						// 암호화 부분인데 완벽해지면 상기 부부분은 삭제
						$updateNew="UPDATE DaeriMember SET push='$push',sangtae='2',cancel='$cancel',ch='1',reasion='$reasion' ,manager='$manager' ";	
						$updateNew.="WHERE num='$num'";
					}
				
				

					mysql_query($updateSj,$conn);mysql_query($updateNew,$conn);


					// 배서건수 정리 //


					$qSql="SELECT e_count,e_count_2,endorse_day FROM 2012EndorseList WHERE pnum='$pNum' AND CertiTableNum='$cNum'";
					$qrs=mysql_query($qSql,$conn);
					$qRow=mysql_fetch_array($qrs);//
					
					
					$e_count_2=$qRow[e_count_2]+1;//신청건수
					$updateE="UPDATE 2012EndorseList SET e_count_2='$e_count_2' WHERE pnum='$pNum' AND CertiTableNum='$cNum'";
					mysql_query($updateE,$conn);

					

					//신청건수와 배서건수가 같으면  sangtae를 2로 변경,ch 값을 10로 변경한다

					$eSql="SELECT e_count_2 FROM 2012EndorseList WHERE pnum='$pNum' AND CertiTableNum='$cNum'";
					$ers=mysql_query($eSql,$conn);

					$erow=mysql_fetch_array($ers);

					if($qRow[e_count]==$erow[e_count_2]){

						$updateQ="UPDATE 2012EndorseList SET ch=10,sangtae='2' WHERE pnum='$pNum' AND CertiTableNum='$cNum'";
							mysql_query($updateQ,$conn);
					}

				//여기부터 보험료 산출 
				
			//		include "./php/personRate.php"; //$personRate2 리턴함
						include "./php/rateSearch.php";//($jumin,$policyNum 사용함)
					$personRate = $row['rate'];
		//	include "./php/personRate.php"; //$personRate2 리턴함

			
			$discountRate = calculatePersonalRate($row['rate']);

					// CertiTableNum  조회하여 증권의 
					// 보험시작일 $startDay     
					// 분납횟수    $nabang  
					// 분납회차    $nabang_1 
					// calculateProRatedFee 함수에서 사용
					include "./php/certi_search.php"; 

					//기준일 $m 
					//(보험증권의 시작일 또는 배서기준일(DB손보))
					include "./php/nai.php"; 
					$personInfo = calculateAge($ju[0], $ju[1], $m); 
					$age=$personInfo['age'];

					// 대리업체 정기결제일 $dueDay 
					// calculateProRatedFee 함수에서 사용
					include "./php/dNum_search.php"; 

					// 배서발생일 calculateProRatedFee 함수에서 사용
					list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);
					// 보험료부터 찾기
					// $cNum, $age 로 kj_premium_data Table 보험료 찾기
					// 월 기본보험료 $monthly_premium1
					// 월 렌트보험료 $monthly_premium2, 
					// 합계 보험료  $monthly_premium_total
					//  calculateProRatedFee 함수에서 사용
					// (1/10) 정상 기본보험료 $payment10_premium1
					// (1/10) 정상 렌트보험료$payment10_premium2
					// (1/10) 합계 $payment10_premium_total
					//
				include "./php/premiumSearch.php";


				//월보험료 산출
				$insurancePremiumData=calculateProRatedFee($monthly_premium1,$monthly_premium2,$monthly_premium_total,$InsuraneCompany, $dueDay,$eDay, $eMonth, $eYear,$discountRate['rate'],$etag);
				$row2['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];
				$PreminumMonth=$insurancePremiumData['monthlyFee3']; //월 보험료


				//보험회사 보험료 산출
				$EndorsePremiumYpremium=calculateEndorsePremium($startDay, $endorse_day, $nabang, $nabang_1,$payment10_premium1, $payment10_premium2,$payment10_premium_total,$InsuraneCompany,$discountRate['rate'], $etag);
				$row2['Endorsement_insurance_company_premium']= (int)$EndorsePremiumYpremium['i_endorese_premium'];
				$PreminumYear=(int)$EndorsePremiumYpremium['payment10_premium_total']*10; //년 보험료
				

				// 상세 계산 정보를 Debug 
				//include "./php/endorseDebug.php";

				// 보험료 산출 끝


				//문자 보내기 
					$company_tel="070-7841-5962";
				   $con_phone1=$Drow['hphone'];
				   $endorseCh=1;//send_name을 drive로 하기 위해 ....
			if($sms==1){

				
				

			//문자보냄
					// 처리 결과를 문자 보내기 위해 .....
				   
				   

						switch($InsuraneCompany)
						{
							case 1 : 
								$insCom="흥국화재";
							//	$policy=explode("-",$cRow[policyNum]);
							//	$po=$policy[0]."-".$policy[1];
								$po=$policyNum;
								break;
							case 2 : 
								$insCom="DB화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po="017-".$policyNum."-000";
								$po="2-20".$policyNum."-000";
								break;
							case 3 : 
								$insCom="KB화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[0]."-".$policy[1];
								$po=$policyNum;
								break;
							case 4 : 
								$insCom="현대화재";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[0]."-".$policy[1];
								$po=$policyNum;
								break;
							case 5 : 
								$insCom="한화화재";
								$policy=explode("-",$cRow[policyNum]);
								$po=$policy[1]."-".$policy[2];
								$po=$policyNum;
								break;
							case 6 : 
								$insCom="더케이";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$policyNum;
								break;
							case 7 : 
								$insCom="MG";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$policyNum;
								break;
							case 8 : 
								$insCom="삼성";
								//$policy=explode("-",$cRow[policyNum]);
								//$po=$policy[1]."-".$policy[2];
								$po=$policyNum;
								break;
						}
						
				   switch($etag){
					   case 1 :
							$etagName='대리보험';
						   break;
					   case 2 :
							$etagName='탁송보험';
						   break;
					   case 3 :
							$etagName='대리/렌트';
						   break;
					   case 4 :
							$etagName='탁송/렌트';
						   break;
				   }
				
				   switch($push){
						case 2 :	//해지"
							if($divi==1){ //정상분납인 경우
								$msg=$insCom.$etagName.$row[Name]."님 해지기준일[".$endorse_day."][".$po."]"."배".number_format($row2['Endorsement_insurance_company_premium']);
								
							}else{
								$msg=$insCom.$etagName.$row[Name]."님 해지기준일[".$endorse_day."][".$po."]".number_format($row2['Endorsement_insurance_premium']);
							
							}


							break;
						case 4 :  //청약//
							if($divi==1){ //정산분납인 경우
							
								$msg=$insCom.$etagName.$row[Name]."님 기준일[".$endorse_day."][".$po."] 년".number_format($PreminumYear)."배".number_format($row2['Endorsement_insurance_company_premium']);
							}else{
								$msg=$insCom.$etagName.$row[Name]."님 기준일[".$endorse_day."][".$po."]월".number_format($PreminumMonth)."배".number_format($row2['Endorsement_insurance_premium']);
							}


							//2024-12-16 현대해상 체결 동의 

							if($InsuraneCompany==4){
									$jumin_=explode('-',$row[Jumin]);
									$hnumber_=explode('-',$row[Hphone]);
									$driverCompony=$Drow[company];  // 대리운전회사
									$driverName=$row[Name];         //성명
									$data =$hnumber_[0].$hnumber_[1].$hnumber_[2]; //핸드폰번호// 암호화할 데이터를 준비합니다.
									$data2=$jumin_[0].$jumin_[1];//주민번호
									$po_=explode("-",$po);
									$policyNumber=$po_[0].$po_[1];//증권번호
									$startDay__=explode('-',$Crow[startyDay]);
									$validStartDay=$startDay__[0].$startDay__[1].$startDay__[2];
									$sigi__ =$cRow[startyDay];
									$endDay__=date("Y-m-d ",strtotime("$sigi__ +1 year"));

									$endDay2__=explode('-',$endDay__);
									//echo $endDay__;
									$validEndDay=$endDay2__[0].$endDay2__[1].$endDay2__[2];
									//$validEndDay=$validEndDay;
									///echo 'validEndDay'; echo $validEndDay;
									include './php/hcurl.php';
							}
							break;
				   }

				   //echo $msg;
				   $qboard='1'; //킥보드 2 대리 1 2021-12-12
				   include "./php/coSms.php";//smsAjax.php에서 사용
				  // include "../../pop_up/ajax/php/smsQuery.php";

					//대리회사중에서 관리자가 여러명인 경우 각 관리자에게 문자를 보낸다 ..2022-10-10

					//$DaeriCompanyNum 값으로 2012Costomer 아이디가 몇개인가를 찾고 
			}else{  //청약을 취소하거나, 거절, 해지를 취소하는 경우
					
					$msg=$smsContents ;	

					//echo $smsContents;
					include "./php/coSms.php";//smsAjax.php에서 사용

					//echo "$insert_sql"; echo $insert_sql;
			}
			$response = array(
				"success" => true,
				"row" => $row2  // 단일 결과로 전달
			);
		}
		
}
echo json_encode_php4($response);
// 데이터베이스 연결 닫기
mysql_close($conn);
?>