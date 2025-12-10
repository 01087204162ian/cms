<?
		//모증권의 분납회차와 납입회차를 구하자
		
		$nSql="SELECT * FROM 2012CertiTable  WHERE num='$moNum[$k]'";
		$nRs=mysql_query($nSql,$connect);
		$nRow=mysql_fetch_array($nRs);
			

		  $startyDay =$nRow[startyDay];

		  $daumY=date("Y-m-d ",strtotime("$startyDay +1 year"));
		  //$gigan=
		  
		  $gigan=(strtotime("$daumY")-strtotime("$startyDay"))/60/60/24; //보험기간

		  $before_gijun=(strtotime("$endorse_day")-strtotime("$startyDay"))/60/60/24; //경과기간

		  $after_gijun=$gigan-$before_gijun;//미경과기간



//년간보험료를 구하기 위해
			$hsql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$e[15]' and sPreminum<='$e[15]' ";
			$hsql.="and CertiTableNum='$moNum[$k]'";

			$hrs=mysql_query($hsql,$connect);

			$hrow=mysql_fetch_array($hrs);
			$yPreminum =$hrow[yPreminum]; //년간보험료


		$beforePreminum=floor((($yPreminum/$gigan)*$before_gijun)/10)*10;//경과기간보험료
		$afterPreminum=floor(((($yPreminum/$gigan)*$after_gijun)/10))*10;//미경과기간에 해당하는 총보험료	
		//$afterPreminum=($yPreminum/$gigan)*$after_gijun;//미경과기간에 해당하는 총보험료	


		  $dailyPreminum=round(($yPreminum/$gigan),-1). //1일보험료
		  $nabang=$nRow[nabang];// 분납
		  $nabang_1=$nRow[nabang_1];// 납입회차

		  switch($e[30]){//보험회사에 따라
				case 8 :   //삼성 화재 

			
						if($nabang_1==1){
							  $daumPreminum=$yPreminum*0.75;
						}else if($nabang_1==2){
							  $daumPreminum=$yPreminum*0.60;//2회까지 내었다면 1회25%,2회15%,남은 60%
						}else if($nabang_1==3){
							   $daumPreminum=$yPreminum*0.45;//3회까지 내었다면 1회25%,2회15%,3회15%남은 45%
						}else if($nabang_1==4){
							   $daumPreminum=$yPreminum*0.3;//4회까지 내었다면 1회25%,2회15%,3회15%,4회15% 남은 30%
						}else if($nabang_1==5){
							   $daumPreminum=$yPreminum*0.15;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==6){
							   $daumPreminum=0;
						}
					break;
		  }



		$thisPreminum=$afterPreminum-(floor($daumPreminum/10))*10; //

		//$thisPreminum=floor(($afterPreminum-$daumPreminum)/10)*10 //
  //미경과기간에 대한 보험료 
  // 미경과기간X1일보험료
  

?>