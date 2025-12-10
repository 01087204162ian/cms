<?
//echo "td $todayfullTime <br>";
//$current_time			= date(H);
//echo "현재 시간분 $current_time <br>";
//echo "요일 $yoil <br>";
//현재시간이 당일 17시50분 후
//금요일이면(3) 토요면(2),일요일(1)평일(1) 그리고 쉬은날이면
include '../../php/holi_query.php';
//echo "yoio $yoil <br>";
$current_time=date(Hi);
//echo "t $current_time <br>";
switch($yoil){
		case 'Mon':
			if($current_time>=1830){$yoil_2=1;}else{$yoil_2=0;}
			break;
		case 'Tue':
			if($current_time>=1830){$yoil_2=1;}else{$yoil_2=0;}
			break;
		case 'Wed':
			 if($current_time>=1830){$yoil_2=1;}else{$yoil_2=0;}
			break;
		case 'Thu':
			 if($current_time>=1830){$yoil_2=1;}else{$yoil_2=0;}
			break;
		case 'Fri':
			 if($current_time>=1830){$yoil_2=1;}else{$yoil_2=0;}
			break;
		case 'Sat':
			$yoil_2=2;
			break;
		case 'Sun':
			$yoil_2=1;
			break;
	}
$after_day=1;//처음에는 요일과 시간에 따라 더해주는 일이다른다
	// echo "now1 $now_time <br>";
	 //echo "uo $yoil_2 <br>";

	 if($now_time==$holiday_end){

		$yoil_2=1;
	 }else if($now_time<$holiday_end){

		$yoil_2=2;
	 }
	 
do{

       if($after_day=='1'){
		$now_time	 = date("Y-m-d ",strtotime("$now_time +$yoil_2 day"));//처음에는 요일과 시간에 따라 더해주는 일이다른다
		$after_day='0';//공휴일과 토요일,일요일에 2가 된다
		//echo "tjd ";
	   }else{
		//echo "gn <br>";
		$now_time	 = date("Y-m-d ",strtotime("$now_time +1 day"));//토요일,공휴일,일요일에는 1일씩 해가면 요일을 평가 하여 근무일 기준으로 배서일 설정
		//echo "tjdwn";

	   }
		
		// echo "now2 $now_time <br>";
		list($_syear,$_smonth,$_sday)=explode('-',$now_time);
		$new_yoil=date("D",mktime(0,0,0,$_smonth,$_sday,$_syear));
		//echo "s $_sday <bR>";
		//echo "dd $new_yoil <BR>";
	//echo "uo2 $yoil_3 <br>";
	//echo "휴일 시작 $holiday <br>";
	//echo "휴일 끝 $holiday_end <br>";
	//echo "현재 $now_time <br>";
	//echo "afet $after_day <br>";
}
//while(($now_time>=$holiday && $now_time<=$holiday_end) || ($new_yoil=='Sat') ||($new_yoil=='Sun'));
while(($now_time>=$holiday && $now_time<=$holiday_end) || ($new_yoil=='Sat') ||($new_yoil=='Sun'));
//while(( $now_time<$holiday_end) || ($new_yoil=='Sat') ||($new_yoil=='Sun'));
		
//echo " $now_time ";

?>

