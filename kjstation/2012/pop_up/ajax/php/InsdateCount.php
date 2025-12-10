<?

if($row[personal]==1){ //단체 계약 인겨우

	$endDay =date("Y-m-d ",strtotime("$startyDay +1 year"));
			
					$endYear =date("Y-m-d ",strtotime("$startyDay +1 year"));
								//종기가 윤년인경우
								$yuYear=date(L,strtotime("$startyDay +1 year"));

								if($yuYear==1){
										//시기가 2월28일경우 종기는 2월29일이 된다
										$yuMonDay=explode("-",$startyDay);

										$MonDate=$yuMonDay[1]."-".$yuMonDay[2];

										if($MonDate=="02-28"){
												$endYear =date("Y-m-d ",strtotime("$endYear +1 day"));
										}

									$endDay=$endYear;
								}
				//$upSql="UPDATE 2012CertiTable SET endDay='$endYear '  WHERE num='$certiTableNum' ";
				//mysql_query($upSql,$connect);

				
			
}else{

	$mSql="SELECT * FROM 2014DaeriMember WHERE num='$memberNum'";
	$mRs=mysql_query($mSql,$connect);
	$mRow = mysql_fetch_array($mRs);
	$startyDay= $mRow[dongbusigi];
	if(!$startyDay){ //개별 시작일이 없으면 
		$startyDay=$mRow[InPutDay];


	}
			$endDay=date("Y-m-d ",strtotime("$startyDay +1 year"));

			if($endDay=="0000-00-00"){
					$endYear =date("Y-m-d ",strtotime("$startyDay +1 year"));
								//종기가 윤년인경우
								$yuYear=date(L,strtotime("$startyDay +1 year"));

								if($yuYear==1){
										//시기가 2월28일경우 종기는 2월29일이 된다
										$yuMonDay=explode("-",$startyDay);

										$MonDate=$yuMonDay[1]."-".$yuMonDay[2];

										if($MonDate=="02-28"){
												$endYear =date("Y-m-d ",strtotime("$endYear +1 day"));
										}


								}
				$upSql="UPDATE 2014CertiTable SET endDay='$endYear '  WHERE num='$certiTableNum' ";
				mysql_query($upSql,$connect);

				$endDay=$endYear;
			}



}




	$daumY=$endDay;
 




  
  $gigan=(strtotime("$daumY")-strtotime("$startyDay"))/60/60/24; //보험기간

  $before_gijun=(strtotime("$endorseDay")-strtotime("$startyDay"))/60/60/24; //경과기간

  $after_gijun=$gigan-$before_gijun;//미경과기간


	if($InsuraneCompany==7){//MG 화재인경우
	   if($state==2){
			$nabang_1+=1;
		}
	}
?>