
<?// echo "DendorseDay $DendorseDay <Br>";?>

<?if($FirstStartDay==30 || $FirstStartDay==31){
		$tm=explode("-",$DendorseDay);
		$dayOFberfore =date("Y-m-d ", strtotime("$DendorseDay -1 month"));//1달전
		if($tm[2]==30){
				$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -1 day"));//1달전
		}
		if($tm[2]==31){
				$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -2 day"));//1달전
		}
		if($tm[2]==29){
				$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -3 day"));//1달전
		}
		$m=date(t, strtotime("$dayOFberfore"));
		$mn=explode("-",$dayOFberfore);
		$sigi=$mn[0]."-".$mn[1]."-".$m;
		//$tm=explode("-",$DendorseDay);

		if(($FirstStartDay==30)&&($tm[2]==31)){//받은날이 30일인경우에 당월은 31일인경우에 예외로 하기 위해
			
			   $Ssigi=strtotime("$now_time"); //tlrl 
			   $sigi=$now_time;
			   $nexMonth =date("Y-m-d ", strtotime("$DendorseDay + 1 month"));//종기
			   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
			   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
			$aj=1;
		}else{

			$lm=date(t, strtotime("$DendorseDay"));
				$nexMonth =$tm[0]."-".$tm[1]."-".$lm;//종기
				   $Ssigi=strtotime("$sigi"); //tlrl 
				   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
				   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
			$aj=1;
		}
				include "./php/EndorseDay_2.php";
	}else{
		if($FirstStartDay<=$today){ //예 18일이 받는 날인데 현재일이 20일 경우

		   $sigi=$toyear."-".$tomonth."-".$FirstStartDay;
		   $nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 

			$aj=2;
		    //  include "./php/EndorseDay.php";
			
		}else{

			$sigi=$toyear."-".$tomonth."-".$FirstStartDay;
			$sigi =date("Y-m-d ", strtotime("$sigi -1 month"));
			$nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		    $Ssigi=strtotime("$sigi"); //tlrl 
		    $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		    $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		  // include "./php/EndorseDay.php";
			$aj=3;
		}
		

		  include "./php/EndorseDay_2.php";
	}?>