<?
		//모증권의 분납회차와 납입회차를 구하자
		
	/*	$nSql="SELECT * FROM 2012CertiTable  WHERE num='$moNum[$k]'";
		$nRs=mysql_query($nSql,$connect);
		$nRow=mysql_fetch_array($nRs);*/
		$rSql="SELECT * FROM  2012Certi  WHERE certi='$e[12]'";

		//echo $rSql;
		$rRs=mysql_query($rSql,$connect);

		  $rRow=mysql_fetch_array($rRs);
	      $nabang=10;
		  $nabang_1=$rRow[nab];// 납입회차
		  $startyDay =$rRow[sigi];

		  //echo "s $startyDay";
/*
if($startyDay<"2016-01-01"){
		 if($e[15]<26){   $yPreminum=$rRow[preminun25]*10; }
		 if($e[15]>=26 && $e[15]<=44){   $yPreminum=$rRow[preminun44]*10; }
		 if($e[15]>=45 && $e[15]<=49){   $yPreminum=$rRow[preminun49]*10; }
		 if($e[15]>=50){   $yPreminum=$rRow[preminun50]*10; }

}else if($startyDay>="2016-01-01" && $startyDay<"2016-11-01"){

		 if($e[15]<26){   $yPreminum=$rRow[preminun25]*10; }
		 if($e[15]>=26 && $e[15]<=40){   $yPreminum=$rRow[preminun44]*10; }
		 if($e[15]>=41 && $e[15]<=49){   $yPreminum=$rRow[preminun49]*10; }
		 if($e[15]>=50){   $yPreminum=$rRow[preminun50]*10; }


}else if($startyDay>="2016-11-01" && $startyDay<"2018-07-31"){

		 if($e[15]<26){   $yPreminum=$rRow[preminun25]*10; }
		 if($e[15]>=26 && $e[15]<=40){   $yPreminum=$rRow[preminun44]*10; }
		 if($e[15]>=41 && $e[15]<=49){   $yPreminum=$rRow[preminun49]*10; }
		 if($e[15]>=50  && $e[15]<=59){   $yPreminum=$rRow[preminun50]*10; }
         if($e[15]>=60){   $yPreminum=$rRow[preminun60]*10; }


}else if($startyDay>="2018-07-31"){

		 if($e[15]<26){   $yPreminum=$rRow[preminun25]*10; }
		 if($e[15]>=26 && $e[15]<=40){   $yPreminum=$rRow[preminun44]*10; }
		 if($e[15]>=41 && $e[15]<=49){   $yPreminum=$rRow[preminun49]*10; }
		 if($e[15]>=50  && $e[15]<=55){   $yPreminum=$rRow[preminun50]*10; }
         if($e[15]>=56){   $yPreminum=$rRow[preminun60]*10; }


}*/

	

		
		 if($e[15]>=26 && $e[15]<=36){   $yPreminum=$rRow[preminun25]*10; }
		 if($e[15]>=37 && $e[15]<=58){   $yPreminum=$rRow[preminun44]*10; }
		
		 if($e[15]>=59){   $yPreminum=$rRow[preminun49]*10; }



//echo $yPreminum;

		  $daumY=date("Y-m-d ",strtotime("$startyDay +1 year"));
		  //$gigan=
		  
		  $gigan=(strtotime("$daumY")-strtotime("$startyDay"))/60/60/24; //보험기간

		  $before_gijun=(strtotime("$endorse_day")-strtotime("$startyDay"))/60/60/24; //경과기간

		  $after_gijun=$gigan-$before_gijun;//

//echo $after_gijun

//년간보험료를 구하기 위해
	/*		$hsql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$e[15]' and sPreminum<='$e[15]' ";
			$hsql.="and CertiTableNum='$moNum[$k]'";

			$hrs=mysql_query($hsql,$connect);

			$hrow=mysql_fetch_array($hrs);
			$yPreminum =$hrow[yPreminum]; //년간보험료*/
		
		$beforePreminum=round(($yPreminum/$gigan)*$before_gijun,-1);//경과기간보험료
		$afterPreminum=round(($yPreminum/$gigan)*$after_gijun,-1);//미경과기간에 해당하는 총보험료	


		  $dailyPreminum=round(($yPreminum/$gigan),-1); //1일보험료

		 // echo "하루보험료"; echo $dailyPreminum;
		  $nabang=10;
		  $nabang_1=$rRow[nab];// 납입회차

		/*echo "배서기준일 "; echo $endorse_day; echo "<br>";
		echo "연간보험료"; echo $yPreminum; echo "<Br>";
		echo "기간"; echo $gigan; echo "<Br>";
		echo "경과기간"; echo $before_gijun; echo "<Br>";
		echo "미경과기간"; echo $after_gijun; echo "<Br>";

		echo "경과기간보험료"; echo $beforePreminum; echo "<Br>";
		echo "미경과기간에 해당하는 총보험료"; echo $afterPreminum; echo "<Br>";
		echo "일일보험료"; echo $dailyPreminum; echo "<Br>";
		
		//echo"qhgja";	echo $e[30]; echo "<br>"; */
		  switch($e[30]){//보험회사에 따라
				case 3 :   // kb

			
						if($nabang_1==1){
							  $daumPreminum=$yPreminum*0.8;
						}else if($nabang_1==2){
							  $daumPreminum=$yPreminum*0.70;//2회까지 내었다면 1회25%,2회15%,남은 60%
						}else if($nabang_1==3){
							   $daumPreminum=$yPreminum*0.6;//3회까지 내었다면 1회25%,2회15%,3회15%남은 45%
						}else if($nabang_1==4){
							   $daumPreminum=$yPreminum*0.5;//4회까지 내었다면 1회25%,2회15%,3회15%,4회15% 남은 30%
						}else if($nabang_1==5){
							   $daumPreminum=$yPreminum*0.4;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==6){
							   $daumPreminum=$yPreminum*0.3;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==7){
							     $daumPreminum=$yPreminum*0.2;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==8){
							     $daumPreminum=$yPreminum*0.1;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==9){
							     $daumPreminum=$yPreminum*0.05;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==10){
							     $daumPreminum=0;
						}

					break;
				case 4 :   // 현대

			
						if($nabang_1==1){
							  $daumPreminum=$yPreminum*0.8;
						}else if($nabang_1==2){
							  $daumPreminum=$yPreminum*0.70;//2회까지 내었다면 1회25%,2회15%,남은 60%
						}else if($nabang_1==3){
							   $daumPreminum=$yPreminum*0.6;//3회까지 내었다면 1회25%,2회15%,3회15%남은 45%
						}else if($nabang_1==4){
							   $daumPreminum=$yPreminum*0.5;//4회까지 내었다면 1회25%,2회15%,3회15%,4회15% 남은 30%
						}else if($nabang_1==5){
							   $daumPreminum=$yPreminum*0.4;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==6){
							   $daumPreminum=$yPreminum*0.3;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==7){
							     $daumPreminum=$yPreminum*0.2;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==8){
							     $daumPreminum=$yPreminum*0.1;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==9){
							     $daumPreminum=$yPreminum*0.05;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==10){
							     $daumPreminum=0;
						}

					break;
			case 5 :   // 롯데 

			
						if($nabang_1==1){
							  $daumPreminum=$yPreminum*0.8;
						}else if($nabang_1==2){
							  $daumPreminum=$yPreminum*0.70;//2회까지 내었다면 1회25%,2회15%,남은 60%
						}else if($nabang_1==3){
							   $daumPreminum=$yPreminum*0.6;//3회까지 내었다면 1회25%,2회15%,3회15%남은 45%
						}else if($nabang_1==4){
							   $daumPreminum=$yPreminum*0.5;//4회까지 내었다면 1회25%,2회15%,3회15%,4회15% 남은 30%
						}else if($nabang_1==5){
							   $daumPreminum=$yPreminum*0.4;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==6){
							   $daumPreminum=$yPreminum*0.3;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==7){
							     $daumPreminum=$yPreminum*0.2;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==8){
							     $daumPreminum=$yPreminum*0.1;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==9){
							     $daumPreminum=$yPreminum*0.05;//5회까지 내었다면 1회25%,2회15%,3회15%,4회15% 5회 15% 남은 15%
						}else if($nabang_1==10){
							     $daumPreminum=0;
						}

					break;
		  }


		//2019-10-20
		//개인별요율
		//echo "납방"; echo $nabang_1; echo "//"; echo "<br>";
		
		//echo $personRate2; echo "//";
		//적용
		//$thisPreminum=($afterPreminum-$daumPreminum)*$personRate3; // 개인별 할인할증 적용할 경우
		//echo $afterPreminum; echo "<br>"; echo "//";
		//echo $daumPreminum;
		$thisPreminum=round(($afterPreminum-$daumPreminum),-1); //
		//$thisPreminum=$yPreminum; //
  //미경과기간에 대한 보험료 
  // 미경과기간X1일보험료
  

?>