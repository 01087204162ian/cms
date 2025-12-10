<?php
//echo "현재일 $now_time";
	//기준일이 30일 또는 31일 경우와 아닌 경우

	/*if($SigiDay[2]<10){
		$SigiDay[2]="0".$SigiDay[2];
	}*/

	if($SigiDay[2]==30 || $SigiDay[2]==31){
		$tm=explode("-",$now_time);
		$dayOFberfore =date("Y-m-d ", strtotime("$now_time -1 month"));//1달전
		//$pdayOFberfore =date("Y-m-d ", strtotime("$now_time -1 month"));//1달전
		if($tm[2]==30){
				//$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -1 day"));//1달전
				$dayOFberfore =date("Y-m-d ", strtotime("$now_time -30 day"));//1달전
		}
		if($tm[2]==31){
				//$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -3 day"));//1달전
				$dayOFberfore =date("Y-m-d ", strtotime("$now_time -31 day"));//1달전
		}
	/*	if($tm[2]==29){
				//$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -3 day"));//1달전
				$dayOFberfore =date("Y-m-d ", strtotime("$now_time -33 day"));//1달전
		}*/
		$m=date(t, strtotime("$dayOFberfore"));
		$mn=explode("-",$dayOFberfore);
		$sigi=$mn[0]."-".$mn[1]."-".$m;

		

		

		$tm=explode("-",$now_time);
		$lm=date(t, strtotime("$now_time"));

			//받은날이 30일인경우에 당월은 31일인경우에 예외로 하기 위해
		if(($SigiDay[2]==30)&&($tm[2]==31)){//받은날이 30일인경우에 당월은 31일인경우에 예외로 하기 위해
			
			   $Ssigi=strtotime("$now_time"); //tlrl 
			   $sigi=$now_time;
			   $nexMonth =date("Y-m-d ", strtotime("$now_time + 1 month"));//종기
			   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
			   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
			
		}else{
			   $nexMonth =$tm[0]."-".$tm[1]."-".$lm;//종기
			   $Ssigi=strtotime("$sigi"); //tlrl 
			   $SnextMonth=strtotime("$nexMonth");	//다음날기준일

			  
			   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
				if($mn[1]==02){//시기가 2월인경우에만
					$Period=32;
			   }
		}
				include "./php/NewPeriod.php";
	}else{

		
		if($SigiDay[2]<=$today){ //예 18일이 받는 날인데 현재일이 20일 경우

			
		//echo "성준";
		   $sigi=$toyear."-".$tomonth."-".$SigiDay[2];
		   $nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
	
		/*   echo "시기 $sigi";
		   echo $Ssigi;
		   echo "//";
		   echo $SnextMonth;*/
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 


		include "./php/NewPeriod.php";
			
		}else{

			$sigi=$toyear."-".$tomonth."-".$SigiDay[2];
			$sigi =date("Y-m-d ", strtotime("$sigi -1 month"));
			$nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		    $Ssigi=strtotime("$sigi"); //tlrl 
		    $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		    $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   include "./php/NewPeriod.php";

		}	
	}


	



	?>