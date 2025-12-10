<?//연령에 따라 보험료 구하기
if($pchagne==1){//조회할때
		$inSuranceCom=$rCertiRow[InsuraneCompany];           //보험회사
		$Dpush=$e[6];            //1 청약 4해지
		$Dnai=$e[15];              //나이
		$Detag=$e[1]; //1이면 일반,2이면 탁송

		if(!$Detag){  //탁송인지 일반인지 구별이 안되어 있으면... 무조건 일반이라고 해야 보험료 계산이 됨
			$Detag=1;
		}
		$Divi=$rCertiRow[divi];             //1이면 정상,2이면 [1/12]
		$DstartDay=$row[FirstStartDay];//1/12로 낼결우 내는 날
		$DendorseDay=$endorse_day;     //배서 기준일
		$Dsangte=$e[2];       //1이면 미처리 2이면 처리

		$Dpreminum1=$rCertiRow[preminum1];//흥국26~34세  //현대 26~34세   //동부 26~30세
		$Dpreminum2=$rCertiRow[preminum2];//흥국35~47세  //현대 35세~47세 //동부 31~45세
		$Dpreminum3=$rCertiRow[preminum3];//흥국48세이상 //현대 48세이상  //동부 46~50세

		$Dpreminum4=$rCertiRow[preminum4];	  //동부 51세~55세
		$Dpreminum5=$rCertiRow[preminum5];								  //동부 56세~60세
		$Dpreminum6=$rCertiRow[preminum6];								  //동부 61세이상
		//탁송
		$DpreminumE1=$rCertiRow[preminumE1];//흥국26~34세
		$DpreminumE2=$rCertiRow[preminumE2];//흥국35~47세
		$DpreminumE3=$rCertiRow[preminumE3];//흥국48세이상

		$DpreminumE4=$rCertiRow[preminumE4];	                              //동부 51세~55세
		$DpreminumE5=$rCertiRow[preminumE5];								  //동부 56세~60세
		$DpreminumE6=$rCertiRow[preminumE6];								  //동부 61세이상
		//$FirstStartDay=$rCertiRow[FirstStartDay];//

		//$FirstStartDay=$row[FirstStartDay];
		//echo "c4 $rCertiRow[preminum4]";
	}
if($pchagne==2){////처리 미처리 시
	
		$inSuranceCom=$row[InsuranceCompany];           //보험회사
		$Dpush=$push;            //1 청약 4해지
		$Dnai=$row[nai];              //나이
		$Detag=$row[etag];            //1이면 일반,2이면 탁송
		$Divi=$cRow[divi];             //1이면 정상,2이면 [1/12]
		$DstartDay=$dRow[FirstStartDay];//1/12로 낼결우 내는 날
		$DendorseDay=$qRow[endorse_day];     //배서 기준일$qRow
		$Dsangte=$row[sangtae];       //1이면 미처리 2이면 처리

		

		$Dpreminum1=$cRow[preminum1];//26~34세
		$Dpreminum2=$cRow[preminum2];//35~47세
		$Dpreminum3=$cRow[preminum3];//48세이상
		$Dpreminum4=$cRow[preminum4];//

		//echo "c2 $cRow[preminum3] || $Dpreminum2";
		$Dpreminum5=$cRow[preminum5];//
		$Dpreminum6=$cRow[preminum6];//
		//탁송
		$DpreminumE1=$cRow[preminumE1];//26~34세
		$DpreminumE2=$cRow[preminumE2];//35~47세
		$DpreminumE3=$cRow[preminumE3];//48세이상

		$DpreminumE4=$cRow[preminumE4];//
		$DpreminumE5=$cRow[preminumE5];//
		$DpreminumE6=$cRow[preminumE6];//
		//$FirstStartDay=$row[FirstStartDay];
		
		
	}

//$FirstStartDay
	//배서가 처리 되어 있으면 저장 되어 있는 보험료를 불러 오고

		//배서가 처리 되어 있지 않으면 보험료를 계산한다
		//echo "dd $Dsangte  || divi  $rCertiRow[divi] ||  $Divi  탁송 $row[etag]|| <bR>";
switch($Dsangte){
		case  1 :
			
			if($Divi==2){
				/*switch($inSuranceCom){
					case 1 ://흥국
							include "./php/ssDayPreminum.php";
						break;	
					case 2 ://동부
							include"./php/dongbuDayPreminum.php";
						break;
					case 3 ://LiG
						include "./php/LigDayPreminum.php";
						break;
					case 4 ://현대
						include "./php/hyunDayPreminum.php";
						break;
					}*/

					$psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$Dnai' and sPreminum<='$Dnai' ";
					$psql.="and CertiTableNum='$CertiTableNum'";

					//echo $psql;
					$pRs=mysql_query($psql,$connect);
					$pRow=mysql_fetch_array($pRs);
					$PreminumMonth=$pRow[mPreminum];

					//echo $PreminumMonth; echo "//";
				  
					include "../../pop_up/ajax/php/endorseCopreminum_3.php";

					// echo "<aj".$_m.">".$Dpreminum3."</aj".$_m.">\n";//	
				}

				//2022-03-05 
				//정상인 경우에도 월분납으로 계산하여 고객사 안내 // 고객사의 업무 편의를 우해 

				if($Divi==1){

					
					$psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$Dnai' and sPreminum<='$Dnai' ";
					$psql.="and CertiTableNum='$CertiTableNum'";

					//echo $psql;
					$pRs=mysql_query($psql,$connect);
					$pRow=mysql_fetch_array($pRs);
					$PreminumMonth=$pRow[mPreminum];

					//echo $PreminumMonth; echo "//";
				  
					include "../../pop_up/ajax/php/endorseCopreminum_3.php";


					$fakeEndorsePre[$k]=$fakeEndorsePre[$k];
				}
				// 
		break;
		case 2 :		//배서 처리 된 경우에 2012EndorsePreminum 에서 보험료 조회 하자
			//$Esql="SELECT * FROM 2012EndorsePreminum WHERE 2012DaeriMemberNum='$DRow[num]' and pnum='$eNum'";


			$Esql="SELECT * FROM SMSData WHERE 2012DaeriMemberNum='$e[3]' and endorse_num='$eNum'";
			//echo "ESQ l $Esql <Br>";
			$Ers=mysql_query($Esql,$connect);
			$Erow=mysql_fetch_array($Ers);

			switch($e[6]){
				case 2 ://해지	
				$endorsePre[$k]=-$Erow[preminum];
				break;
				case 4 ://정상	
				//echo "성준 $e[6] $DRow[cancel]";
				   if($e[9]!=45){//해지 후 취소가 아닌 경우에
				   $endorsePre[$k]=$Erow[preminum];
				   }
				   // echo "보험료 $endorsePre[$_m]";
				break;
			}
		break;
}
  