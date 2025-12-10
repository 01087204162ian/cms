<?
//시기 종기 일치 인경우
//echo "start $start <br>";
//echo  "new_jeonggi $new_jeonggi <br>";

//echo "preminum $preminum <br>";

$str_start=strtotime("$start");//보험시작일

$str_new_jeonggi=strtotime("$new_jeonggi");//종기

$bohum_daily=($str_new_jeonggi-$str_start)/60/60/24;//보험기간

//echo "bohum_daily $bohum_daily <br>";

$daily_preminum=$preminum/$bohum_daily;

//echo "daily_preminum $daily_preminum <br>";

$year_preminum=round($daily_preminum*365,-1);

//echo "year_preminum $year_preminum <br>";


list($start_01,$start_02,$start_03)=explode("-",$new_jeonggi);//종기년도에서-1을 시기를 새로잡는다

$start_01=$start_01 - 1;

$new_new_start=$start_01."-".$start_02."-".$start_03;

//echo "start $start <br>";
	switch($bunnab_3) {
		case 1 :

			$nex =date("Y-m-d ", strtotime("$new_new_start + 1 month"));
			//$nex_2 = explode("-",$nex);
			$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
			$nex_day=explode("-",$nex);
			include "./date_cal.php";


			break;

		case 2 :
			//echo" 2회납 <br>";
			 //30세이상  		
		  $first_3_30 = round($year_preminum*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_30 = $year_preminum - $first_3_30;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $first_3_30 = $preminum-($second_3_30);
		  
			
		
			break;

		  case 4 :
				//30세 이상
          $first_3_30 = round($year_preminum*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_30= round($year_preminum*0.25,-1); //4회납 2회차 보험료
		  $third_3_30 = round(($year_preminum-$first_3_30-$second_3_30)/2,-1); //4회납 3회차 보험료
		  $fourth_3_30 = round(($year_preminum-$first_3_30-$second_3_30)/2,-1);//4회납 4회차 보험료 ,
		  $first_3_30= $preminum -($second_3_30+$third_3_30+$fourth_3_30);
		 

			break;

			case 6 :
				//30세 이상
		  $first_3_30= round($year_preminum*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_30= round($year_preminum*0.15,-1); //6회납 2회차 보험료
		  $third_3_30= round($year_preminum*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_30= round($year_preminum*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_30= round($year_preminum*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_30= round($year_preminum - ($first_3_30+$second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30));//6회납 5회차 보험료
		 $first_3_30 = $preminum -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30);
		  


		

		

 for($i=1;$i<$bunnab_3;$i++)
	  	{ 
		
				if($i==1)
				{
					$a=$i;
					$nex =date("Y-m-d ", strtotime("$new_new_start + $a month"));
					$nex_day=explode("-",$nex);
					$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
					include "./php/date_cal.php";
				}
				else
				{
					$a=2*$i-1;
					$nex =date("Y-m-d ", strtotime("$new_new_start + $a month"));
					$nex_day=explode("-",$nex);
					$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
					include "./date_cal.php";
				
				}
				$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

	}

				break;
			case 7 :

			//	echo "bunnab $bunnab <br>";
				$bunnab=$bunnab-1;
				//30세 이상
		  $first_3_30= round($year_preminum*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_30= round($year_preminum*0.15,-1); //6회납 2회차 보험료
		  $third_3_30= round($year_preminum*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_30= round($year_preminum*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_30= round($year_preminum*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_30= round($year_preminum - ($first_3_30+$second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30));//6회납 5회차 보험료
		  $first_3_30 = $preminum -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30);
		  


		


			 for($i=1;$i<$bunnab_3;$i++){ 
					
							
								$a=$i;
								$nex =date("Y-m-d ", strtotime("$new_new_start + $a month"));
								$nex_day=explode("-",$nex);
								$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
								include "./date_cal.php";
							
							
							$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

				}
			
				break;

			case 10 :
				
			 $first_3_30 = round($year_preminum*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_30= round($year_preminum*0.10,-1); //10회납 2회차 보험료
		 		$third_3_30 = round($year_preminum*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_30 = round($year_preminum*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_30 = round($year_preminum*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_30 = round($year_preminum*0.10,-1);//10회납 6회차 보험료
				$seventh_3_30 = round($year_preminum*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_30 = round($year_preminum*0.10,-1);//10회납 8회차 보험료
		 		$nineth_3_30 = round($year_preminum*0.10,-1);//10회납 9회차 보험료
		  		$tenth_3_30 = round($year_preminum*0.10,-1);//10회납 10회차 보험료
				$first_3_30= $preminum -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30+$seventh_3_30+$eighth_3_30+$nineth_3_30+$tenth_3_30);
		  		//echo "firs_3_30 $first_3_30 <br>";
				
	  		
			
			

		
	


     for($i=1;$i<10;$i++){ 
			$a=$i;
			$nex =date("Y-m-d ", strtotime("$new_new_start + $a month"));
			$nex_day=explode("-",$nex);
			
			$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
			include "./date_cal.php";

			$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

	 }
				break;

				case 11 ://11월1일 계약부터 10회 분납 처리를 위해
				//echo "tjd 11 <br> ";
			 //30세 이상 보험료
				//echo "total $year_preminum <br>";
		  		$first_3_30 = round($year_preminum*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_30= round($year_preminum*0.10,-1); //10회납 2회차 보험료
		 		$third_3_30 = round($year_preminum*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_30 = round($year_preminum*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_30 = round($year_preminum*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_30 = round($year_preminum*0.10,-1);//10회납 6회차 보험료
				$seventh_3_30 = round($year_preminum*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_30 = round($year_preminum*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_30 = round($year_preminum*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_30 = round($year_preminum*0.05,-1);//10회납 10회차 보험료
				$first_3_30 = $preminum -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30+$seventh_3_30+$eighth_3_30+$nineth_3_30+$tenth_3_30);
		  		//echo "firs_3_30 $first_3_30 <br>";
				
	


				 for($i=1;$i<10;$i++){ 
						$a=$i;
						$nex =date("Y-m-d ", strtotime("$new_new_start + $a month"));
						$nex_day=explode("-",$nex);
						
						$new_start=explode("-",$new_new_start);//Y-m-d형태로 나눈 것 위의 start를 
						include "./date_cal.php";

						$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

				 }

				 $bunnab_3=10; //다시 변수를 되돌린다
				break;
}
//return false;
?>

