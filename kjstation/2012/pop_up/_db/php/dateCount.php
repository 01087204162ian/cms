<?//	if($Fir[2]==30 || $Fir[2]==31){
	if($Fir[2]==31){


			$sigi=$Now[0]."-".$Now[1]."-01";//현재일이 포함된 월의1일보다 하루 전날이 지난달 말일
			$sigi=date("Y-m-d ",strtotime("$sigi -1 day"));
			$Period=date(t, strtotime("$now_time"));
			//$Period=$Period-1;//말일 전까지 


	}else{		//배서일이 속하는 달로 환산하ㅈ자 처음받는 날을 다시 현재일로 환산 
		$get_start_day=$Now[0]."-".$Now[1]."-".$Fir[2];
		//if(strtotime("$get_start_day")<strtotime("$endorseDay")){
		if($Fir[2] <= $Now[2]){  
			//처음받는 날이  현재일보다 작은경우 즉, 처음받는날은 12일  현재일은 05월13일인경우
			//시작일은 현재월의 처음받은날 (05월12일)
			$sigi=$Now[0]."-".$Now[1]."-".$Fir[2];
			
		   $nexMonth =date("Y-m-d", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   $aj=1;

		}else{
			//처음받는 날이  현재일보다 큰경우 즉, 처음받는날은 16일  현재일은 05월15일인경우
			//시작일은 현재월 의  처음받은날 (05월12일)

			 //$berforMonth =date("Y-m-d", strtotime("$now_time - 1 month"));
			 $berforMonth =date("Y-m-d", strtotime("$endorseDay - 1 month"));
			 $be=explode("-",$berforMonth);
			 $sigi=$be[0]."-".$be[1]."-".$Fir[2];		
			 
			 $nexMonth =date("Y-m-d", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
			
			$aj=2;
		}
}

?>