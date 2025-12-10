<?	switch($bunnab_3) {
		case 1 :

			$nex =date("Y-m-d ", strtotime("$start + 1 month"));
			//$nex_2 = explode("-",$nex);
			$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
			$nex_day=explode("-",$nex);
			include "./date_cal.php";


			break;

		case 2 :
			//echo" 2회납 <br>";
			 //30세이상  		
		  $first_3_30 = round($total_3_30*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_30 = $total_3_30 - $first_3_30;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_30 = $total_3_30-($second_3_30);
		  $a_30 = $imsi_30-$first_3_30;
			if($a_30)
			{
				$first_3_30 = round($first_3_30 + $a_30,-1); 
			}
			
		//26세 ~29세		
		  $first_3_26 = round($total_3_26*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_26 = $total_3_26 - $first_3_26;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_26 = $total_3_26-($second_3_26);
		  $a_26 = $imsi_26-$first_3_26;
			if($a_26)
			{
				$first_3_26 = round($first_3_26 + $a_26,-1); 
			}

		//24세 ~24세		남자 
		  $first_3_24_1 = round($total_3_24_1*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_24_1 = $total_3_24_1 - $first_3_24_1;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_24_1 = $total_3_24_1-($second_3_24_1);
		  $a_24_1 = $imsi_24_1-$first_3_24_1;
			if($a_24_1)
			{
				$first_3_24_1 = round($first_3_24_1 + $a_24_1,-1); 
			}
		
		//24세 ~24세		여자
		  $first_3_24_2 = round($total_3_24_2*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_24_2 = $total_3_24_2 - $first_3_24_2;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_24_2 = $total_3_24_2-($second_3_24_2);
		  $a_24_2 = $imsi_24_2-$first_3_24_2;
			if($a_24_2)
			{
				$first_3_24_2 = round($first_3_24_2 + $a_24_2,-1); 
			}
		//21세 ~23세		남자 
		  $first_3_21_1 = round($total_3_21_1*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_21_1 = $total_3_21_1 - $first_3_21_1;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_21_1 = $total_3_21_1-($second_3_21_1);
		  $a_21_1 = $imsi_21_1-$first_3_21_1;
			if($a_21_1)
			{
				$first_3_21_1 = round($first_3_21_1 + $a_21_1,-1); 
			}
		
		//21세 ~23세		여자
		  $first_3_21_2 = round($total_3_21_2*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_21_2 = $total_3_21_2 - $first_3_21_2;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_21_2 = $total_3_21_2-($second_3_21_2);
		  $a_21_2 = $imsi_21_2-$first_3_21_2;
			if($a_21_2)
			{
				$first_3_21_2 = round($first_3_21_2 + $a_21_2,-1); 
			}
		//20세 이하		남자 
		  $first_3_20_1 = round($total_3_20_1*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_20_1 = $total_3_20_1 - $first_3_20_1;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_20_1 = $total_3_20_1-($second_3_20_1);
		  $a_20_1 = $imsi_20_1-$first_3_20_1;
			if($a_20_1)
			{
				$first_3_20_1 = round($first_3_20_1 + $a_20_1,-1); 
			}
		
		//20세 이하		여자
		  $first_3_20_2 = round($total_3_20_2*0.6,-1);  //2회납 1회차 보험료
	  	  
		  $second_3_20_2 = $total_3_20_2 - $first_3_20_2;//2회납 2회차 보험료 ,1,차는 원단위 절사로 인해 오차발생 따라서 년간보험료 -(1,2,3회보험료)
		  $imsi_20_2 = $total_3_20_2-($second_3_20_2);
		  $a_20_2 = $imsi_20_2-$first_3_20_2;
			if($a_20_2)
			{
				$first_3_20_2 = round($first_3_20_2 + $a_20_2,-1); 
			}

		$nex =date("Y-m-d ", strtotime("$start + 5 month"));
		//echo "nex $nex <br>";
		//$nex_2 = explode("-",$nex);
			$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
			$nex_day=explode("-",$nex);
			//echo "nex_day $nex_day <br>";
			include "./date_cal.php";
		
		$bunnab_day[1] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

		//echo "bunnab_day $bunnab_day[2] <br>";
			break;


		  case 4 :
				//30세 이상
          $first_3_30 = round($total_3_30*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_30= round($total_3_30*0.25,-1); //4회납 2회차 보험료
		  $third_3_30 = round(($total_3_30-$first_3_30-$second_3_30)/2,-1); //4회납 3회차 보험료
		  $fourth_3_30 = round(($total_3_30-$first_3_30-$second_3_30)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_30 = $total_3_30 -($second_3_30+$third_3_30+$fourth_3_30);
		  $a_30 = $imsi_30-$first_3_30;
			if($a_30)
			{
				$first_3_30 = round($first_3_30 + $a_30,-1);
			}


			//26세 ~29세
          $first_3_26 = round($total_3_26*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_26= round($total_3_26*0.25,-1); //4회납 2회차 보험료
		  $third_3_26 = round(($total_3_26-$first_3_26-$second_3_26)/2,-1); //4회납 3회차 보험료
		  $fourth_3_26 = round(($total_3_26-$first_3_26-$second_3_26)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_26 = $total_3_26 -($second_3_26+$third_3_26+$fourth_3_26);
		  $a_26 = $imsi_26-$first_3_26;
			if($a_26)
			{
				$first_3_26 = round($first_3_26 + $a_26,-1);
			}

			//24세 ~25세 남
          $first_3_24_1 = round($total_3_24_1*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_24_1= round($total_3_24_1*0.25,-1); //4회납 2회차 보험료
		  $third_3_24_1 = round(($total_3_24_1-$first_3_24_1-$second_3_24_1)/2,-1); //4회납 3회차 보험료
		  $fourth_3_24_1 = round(($total_3_24_1-$first_3_24_1-$second_3_24_1)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_24_1 = $total_3_24_1 -($second_3_24_1+$third_3_24_1+$fourth_3_24_1);
		  $a_24_1 = $imsi_24_1-$first_3_24_1;
			if($a_24_1)
			{
				$first_3_24_1 = round($first_3_24_1 + $a_24_1,-1);
			}
		

		//24세 ~25세 여
          $first_3_24_2 = round($total_3_24_2*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_24_2= round($total_3_24_2*0.25,-1); //4회납 2회차 보험료
		  $third_3_24_2 = round(($total_3_24_2-$first_3_24_2-$second_3_24_2)/2,-1); //4회납 3회차 보험료
		  $fourth_3_24_2 = round(($total_3_24_2-$first_3_24_2-$second_3_24_2)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_24_2 = $total_3_24_2 -($second_3_24_2+$third_3_24_2+$fourth_3_24_2);
		  $a_24_2 = $imsi_24_2-$first_3_24_2;
			if($a_24_2)
			{
				$first_3_24_2 = round($first_3_24_2 + $a_24_2,-1);
			}
		//21세 ~23세 남
          $first_3_21_1 = round($total_3_21_1*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_21_1= round($total_3_21_1*0.25,-1); //4회납 2회차 보험료
		  $third_3_21_1 = round(($total_3_21_1-$first_3_21_1-$second_3_21_1)/2,-1); //4회납 3회차 보험료
		  $fourth_3_21_1 = round(($total_3_21_1-$first_3_21_1-$second_3_21_1)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_21_1 = $total_3_21_1 -($second_3_21_1+$third_3_21_1+$fourth_3_21_1);
		  $a_21_1 = $imsi_21_1-$first_3_21_1;
			if($a_21_1)
			{
				$first_3_21_1 = round($first_3_21_1 + $a_21_1,-1);
			}
		

		//21세 ~23세 여
          $first_3_21_2 = round($total_3_21_2*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_21_2= round($total_3_21_2*0.25,-1); //4회납 2회차 보험료
		  $third_3_21_2 = round(($total_3_21_2-$first_3_21_2-$second_3_21_2)/2,-1); //4회납 3회차 보험료
		  $fourth_3_21_2 = round(($total_3_21_2-$first_3_21_2-$second_3_21_2)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_21_2 = $total_3_21_2 -($second_3_21_2+$third_3_21_2+$fourth_3_21_2);
		  $a_21_2 = $imsi_21_2-$first_3_21_2;
			if($a_21_2)
			{
				$first_3_21_2 = round($first_3_21_2 + $a_21_2,-1);
			}

		//20세 이하 남
          $first_3_20_1 = round($total_3_20_1*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_20_1= round($total_3_20_1*0.25,-1); //4회납 2회차 보험료
		  $third_3_20_1 = round(($total_3_20_1-$first_3_20_1-$second_3_20_1)/2,-1); //4회납 3회차 보험료
		  $fourth_3_20_1 = round(($total_3_20_1-$first_3_20_1-$second_3_20_1)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_20_1 = $total_3_20_1 -($second_3_20_1+$third_3_20_1+$fourth_3_20_1);
		  $a_20_1 = $imsi_20_1-$first_3_20_1;
			if($a_20_1)
			{
				$first_3_20_1 = round($first_3_20_1 + $a_20_1,-1);
			}
		

		//20세 이하 여
          $first_3_20_2 = round($total_3_20_2*0.35,-1);  //4회납 1회차 보험료
	  	  $second_3_20_2= round($total_3_20_2*0.25,-1); //4회납 2회차 보험료
		  $third_3_20_2 = round(($total_3_20_2-$first_3_20_2-$second_3_20_2)/2,-1); //4회납 3회차 보험료
		  $fourth_3_20_2 = round(($total_3_20_2-$first_3_20_2-$second_3_20_2)/2,-1);//4회납 4회차 보험료 ,
		  $imsi_20_2 = $total_3_20_2 -($second_3_20_2+$third_3_20_2+$fourth_3_20_2);
		  $a_20_2 = $imsi_20_2-$first_3_20_2;
			if($a_20_2)
			{
				$first_3_20_2 = round($first_3_20_2 + $a_20_2,-1);
			}


			  for($i=1;$i<$bunnab_3;$i++){ 
			if($i==1){
				$a=2;
			
				$nex =date("Y-m-d ", strtotime("$start + $a month"));
				$nex_day=explode("-",$nex);
			
				$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
				include "./date_cal.php";
			}
			
			else{
				$a=$i*3-1 ;
				
				$nex =date("Y-m-d ", strtotime("$start + $a month"));
				$nex_day=explode("-",$nex);
			
				$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
				include "./date_cal.php";
			}
			$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];
	
		}
			
			  break;
			

			case 6 :
				//30세 이상
		  $first_3_30= round($total_3_30*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_30= round($total_3_30*0.15,-1); //6회납 2회차 보험료
		  $third_3_30= round($total_3_30*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_30= round($total_3_30*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_30= round($total_3_30*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_30= round($total_3_30 - ($first_3_30+$second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30));//6회납 5회차 보험료
		 $imsi_30 = $total_3_30 -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30);
		  $a_30 = $imsi-$first_3_30;
			if($a_30)
			{
				$first_30= round($first_3_30+ $a_30,-1);
			}


		//26세이상 29세
		  $first_3_26= round($total_3_26*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_26= round($total_3_26*0.15,-1); //6회납 2회차 보험료
		  $third_3_26= round($total_3_26*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_26= round($total_3_26*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_26= round($total_3_26*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_26= round($total_3_26 - ($first_3_26+$second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26));//6회납 5회차 보험료
		 $imsi_26 = $total_3_26 -($second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26+$sixth_3_26);
		  $a_26 = $imsi-$first_3_26;
			if($a_26)
			{
				$first_26= round($first_3_26+ $a_26,-1);
			}


		//24세이상 25세 남
		  $first_3_24_1= round($total_3_24_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_24_1= round($total_3_24_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_24_1= round($total_3_24_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_24_1= round($total_3_24_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_24_1= round($total_3_24_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_24_1= round($total_3_24_1 - ($first_3_24_1+$second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1));//6회납 5회차 보험료
		  $imsi_24_1 = $total_3_24_1 -($second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1+$sixth_3_24_1);
		  $a_24_1 = $imsi-$first_3_24_1;
			if($a_24_1)
			{
				$first_24_1= round($first_3_24_1+ $a_24_1,-1);
			}
		
		//24세이상 25세 여
		  $first_3_24_2= round($total_3_24_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_24_2= round($total_3_24_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_24_2= round($total_3_24_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_24_2= round($total_3_24_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_24_2= round($total_3_24_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_24_2= round($total_3_24_2 - ($first_3_24_2+$second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2));//6회납 5회차 보험료
		  $imsi_24_2 = $total_3_24_2 -($second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2+$sixth_3_24_2);
		  $a_24_2 = $imsi-$first_3_24_2;
			if($a_24_2)
			{
				$first_24_2= round($first_3_24_2+ $a_24_2,-1);
			}
		//21세이상 23세 남
		  $first_3_21_1= round($total_3_21_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_21_1= round($total_3_21_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_21_1= round($total_3_21_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_21_1= round($total_3_21_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_21_1= round($total_3_21_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_21_1= round($total_3_21_1 - ($first_3_21_1+$second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1));//6회납 5회차 보험료
		  $imsi_21 = $total_3_21_1 -($second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1+$sixth_3_21_1);
		  $a_21_1 = $imsi-$first_3_21_1;
			if($a_21)
			{
				$first_21= round($first_3_21_1+ $a_21,-1);
			}

		//21세이상 23세 여
		  $first_3_21_2= round($total_3_21_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_21_2= round($total_3_21_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_21_2= round($total_3_21_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_21_2= round($total_3_21_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_21_2= round($total_3_21_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_21_2= round($total_3_21_2 - ($first_3_21_2+$second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2));//6회납 5회차 보험료
		 $imsi_21_2 = $total_3_21_2 -($second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2+$sixth_3_21_2);
		  $a_21_2 = $imsi-$first_3_21_2;
			if($a_21)
			{
				$first_21= round($first_3_21_2+ $a_21,-1);
			}

		//20세 이하 남
		  $first_3_20_1= round($total_3_20_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_20_1= round($total_3_20_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_20_1= round($total_3_20_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_20_1= round($total_3_20_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_20_1= round($total_3_20_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_20_1= round($total_3_20_1 - ($first_3_20_1+$second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1));//6회납 5회차 보험료
		  $imsi_20 = $total_3_20_1 -($second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1+$sixth_3_20_1);
		  $a_20 = $imsi-$first_3_20_1;
			if($a_20)
			{
				$first_20= round($first_3_20_1+ $a_20,-1);
			}

		//20세 이하 여
		  $first_3_20_2= round($total_3_20_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_20_2= round($total_3_20_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_20_2= round($total_3_20_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_20_2= round($total_3_20_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_20_2= round($total_3_20_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_20_2= round($total_3_20_2 - ($first_3_20_2+$second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2));//6회납 5회차 보험료
		  $imsi_20 = $total_3_20_2 -($second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2+$sixth_3_20_2);
		  $a_20 = $imsi-$first_3_20_2;
			if($a_20)
			{
				$first_20= round($first_3_20_2+ $a_20,-1);
			}
		


 for($i=1;$i<$bunnab_3;$i++)
	  	{ 
		
				if($i==1)
				{
					$a=$i;
					$nex =date("Y-m-d ", strtotime("$start + $a month"));
					$nex_day=explode("-",$nex);
					$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
					include "./php/date_cal.php";
				}
				else
				{
					$a=2*$i-1;
					$nex =date("Y-m-d ", strtotime("$start + $a month"));
					$nex_day=explode("-",$nex);
					$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
					include "./date_cal.php";
				
				}
				$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

	}

				break;
			case 7 :

			//	echo "bunnab $bunnab <br>";
				$bunnab=$bunnab-1;
				//30세 이상
		  $first_3_30= round($total_3_30*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_30= round($total_3_30*0.15,-1); //6회납 2회차 보험료
		  $third_3_30= round($total_3_30*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_30= round($total_3_30*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_30= round($total_3_30*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_30= round($total_3_30 - ($first_3_30+$second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30));//6회납 5회차 보험료
		 $imsi_30 = $total_3_30 -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30);
		  $a_30 = $imsi-$first_3_30;
			if($a_30)
			{
				$first_30= round($first_3_30+ $a_30,-1);
			}


		//26세이상 29세
		  $first_3_26= round($total_3_26*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_26= round($total_3_26*0.15,-1); //6회납 2회차 보험료
		  $third_3_26= round($total_3_26*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_26= round($total_3_26*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_26= round($total_3_26*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_26= round($total_3_26 - ($first_3_26+$second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26));//6회납 5회차 보험료
		 $imsi_26 = $total_3_26 -($second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26+$sixth_3_26);
		  $a_26 = $imsi-$first_3_26;
			if($a_26)
			{
				$first_26= round($first_3_26+ $a_26,-1);
			}


		//24세이상 25세 남
		  $first_3_24_1= round($total_3_24_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_24_1= round($total_3_24_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_24_1= round($total_3_24_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_24_1= round($total_3_24_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_24_1= round($total_3_24_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_24_1= round($total_3_24_1 - ($first_3_24_1+$second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1));//6회납 5회차 보험료
		  $imsi_24_1 = $total_3_24_1 -($second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1+$sixth_3_24_1);
		  $a_24_1 = $imsi-$first_3_24_1;
			if($a_24_1)
			{
				$first_24_1= round($first_3_24_1+ $a_24_1,-1);
			}
		
		//24세이상 25세 여
		  $first_3_24_2= round($total_3_24_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_24_2= round($total_3_24_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_24_2= round($total_3_24_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_24_2= round($total_3_24_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_24_2= round($total_3_24_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_24_2= round($total_3_24_2 - ($first_3_24_2+$second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2));//6회납 5회차 보험료
		  $imsi_24_2 = $total_3_24_2 -($second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2+$sixth_3_24_2);
		  $a_24_2 = $imsi-$first_3_24_2;
			if($a_24_2)
			{
				$first_24_2= round($first_3_24_2+ $a_24_2,-1);
			}
		//21세이상 23세 남
		  $first_3_21_1= round($total_3_21_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_21_1= round($total_3_21_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_21_1= round($total_3_21_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_21_1= round($total_3_21_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_21_1= round($total_3_21_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_21_1= round($total_3_21_1 - ($first_3_21_1+$second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1));//6회납 5회차 보험료
		  $imsi_21 = $total_3_21_1 -($second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1+$sixth_3_21_1);
		  $a_21_1 = $imsi-$first_3_21_1;
			if($a_21)
			{
				$first_21= round($first_3_21_1+ $a_21,-1);
			}

		//21세이상 23세 여
		  $first_3_21_2= round($total_3_21_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_21_2= round($total_3_21_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_21_2= round($total_3_21_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_21_2= round($total_3_21_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_21_2= round($total_3_21_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_21_2= round($total_3_21_2 - ($first_3_21_2+$second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2));//6회납 5회차 보험료
		 $imsi_21_2 = $total_3_21_2 -($second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2+$sixth_3_21_2);
		  $a_21_2 = $imsi-$first_3_21_2;
			if($a_21)
			{
				$first_21= round($first_3_21_2+ $a_21,-1);
			}

		//20세 이하 남
		  $first_3_20_1= round($total_3_20_1*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_20_1= round($total_3_20_1*0.15,-1); //6회납 2회차 보험료
		  $third_3_20_1= round($total_3_20_1*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_20_1= round($total_3_20_1*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_20_1= round($total_3_20_1*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_20_1= round($total_3_20_1 - ($first_3_20_1+$second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1));//6회납 5회차 보험료
		  $imsi_20 = $total_3_20_1 -($second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1+$sixth_3_20_1);
		  $a_20 = $imsi-$first_3_20_1;
			if($a_20)
			{
				$first_20= round($first_3_20_1+ $a_20,-1);
			}

		//20세 이하 여
		  $first_3_20_2= round($total_3_20_2*0.25,-1);  //6회납 1회차 보험료
	  	  $second_3_20_2= round($total_3_20_2*0.15,-1); //6회납 2회차 보험료
		  $third_3_20_2= round($total_3_20_2*0.15,-1); //6회납 3회차 보험료
		  $fourth_3_20_2= round($total_3_20_2*0.15,-1); //6회납 4회차 보험료
		  $fifth_3_20_2= round($total_3_20_2*0.15,-1); //6회납 5회차 보험료
		  $sixth_3_20_2= round($total_3_20_2 - ($first_3_20_2+$second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2));//6회납 5회차 보험료
		  $imsi_20 = $total_3_20_2 -($second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2+$sixth_3_20_2);
		  $a_20 = $imsi-$first_3_20_2;
			if($a_20)
			{
				$first_20= round($first_3_20_2+ $a_20,-1);
			}
		


			 for($i=1;$i<$bunnab_3;$i++){ 
					
							
								$a=$i;
								$nex =date("Y-m-d ", strtotime("$start + $a month"));
								$nex_day=explode("-",$nex);
								$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
								include "./date_cal.php";
							
							
							$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

				}

				break;

			case 10 :
				
			 $first_3_30 = round($total_3_30*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_30= round($total_3_30*0.10,-1); //10회납 2회차 보험료
		 		$third_3_30 = round($total_3_30*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_30 = round($total_3_30*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_30 = round($total_3_30*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_30 = round($total_3_30*0.10,-1);//10회납 6회차 보험료
				$seventh_3_30 = round($total_3_30*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_30 = round($total_3_30*0.10,-1);//10회납 8회차 보험료
		 		$nineth_3_30 = round($total_3_30*0.10,-1);//10회납 9회차 보험료
		  		$tenth_3_30 = round($total_3_30*0.10,-1);//10회납 10회차 보험료
				$imsi = $total_3_30 -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30+$seventh_3_30+$eighth_3_30+$nineth_3_30+$tenth_3_30);
		  		//echo "firs_3_30 $first_3_30 <br>";
				$a_30 = $imsi-$first_3_30;
				//echo "imsi $imsi <br>";
				if($a_30)
				{
					$first_3_30= round($first_3_30+ $a_30,-1);
				}
	  		//26세 ~29세 보험료
		  		$first_3_26 = round($total_3_26*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_26= round(($total_3_30-$first_3_30)/9,-1); //10회납 2회차 보험료
		 		$third_3_26 = round(($total_3_30-$first_3_30)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_26 = round(($total_3_30-$first_3_30)/9,-1); //10회납 4회차 보험료
		        $fifth_3_26 = round(($total_3_30-$first_3_30)/9,-1); //10회납 3회차 보험료
		
				$sixth_3_26 = round(($total_3_30-$first_3_30)/9,-1);//10회납 6회차 보험료
				$seventh_3_26 = round(($total_3_30-$first_3_30)/9,-1);
		 		$eighth_3_26 = round(($total_3_30-$first_3_30)/9,-1);
		 		$nineth_3_26 = round(($total_3_30-$first_3_30)/9,-1);
		  		$tenth_3_26 = round(($total_3_30-$first_3_30)/9,-1);
				$imsi = $total_3_26 -($second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26+$sixth_3_26+$seventh_3_26+$eighth_3_26+$nineth_3_26+$tenth_3_26);
		  		
  		
				$a_26 = $imsi-$first_3_26;
				if($a_26)
				{
					$first_3_26= round($first_3_26+ $a_26,-1);
				}
			//24세 ~25세 보험료 남자보험료
		  		$first_3_24_1 = round($total_3_24_1*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_24_1= round(($total_3_24_1-$first_3_24_1)/9,-1); //10회납 2회차 보험료
		 		$third_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1); //10회납 4회차 보험료
		        $fifth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1); //10회납 5회차 보험료
		
				$sixth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1);//10회납 6회차 보험료
				$seventh_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1);
		 		$eighth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1);
		 		$nineth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1);
		  		$tenth_3_24_1 = round(($total_3_24_1-$first_3_24_1)/9,-1);
				$imsi = $total_3_24_1 -($second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1+$sixth_3_24_1+$seventh_3_24_1+$eighth_3_24_1+$nineth_3_24_1+$tenth_3_24_1);
		  		//echo "3_24_1 $first_3_24_1 <br>";
				$a_24_1 = $imsi-$first_3_24_1;
				//echo "a_24_1 $a_24_1 <br>";
				if($a_24_1)
				{
					$first_3_24_1= round($first_3_24_1+ $a_24_1,-1);
				}
				//echo "3_24_1 $first_3_24_1 <br>";
			//24세 ~25세 보험료 여자보험료
		  		$first_3_24_2 = round($total_3_24_2*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_24_2= round(($total_3_24_2-$first_3_24_2)/9,-1); //10회납 2회차 보험료
		 		$third_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1); //10회납 4회차 보험료
		        $fifth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1); //10회납 5회차 보험료
		
				$sixth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1);//10회납 6회차 보험료
				$seventh_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1);
		 		$eighth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1);
		 		$nineth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1);
		  		$tenth_3_24_2 = round(($total_3_24_2-$first_3_24_2)/9,-1);
				$imsi = $total_3_24_2 -($second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2+$sixth_3_24_2+$seventh_3_24_2+$eighth_3_24_2+$nineth_3_24_2+$tenth_3_24_2);
		  		
				$a_24_2 = $imsi-$first_3_24_2;
				if($a_24_2)
				{
					$first_3_24_2= round($first_3_24_2+ $a_24_2,-1);
				}
			//21세~23세  남보험료
		  		$first_3_21_1 = round($total_3_21_1*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_21_1= round(($total_3_21_1-$first_3_21_1)/9,-1); //10회납 2회차 보험료
		 		$third_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1); //10회납 4회차 보험료
		        $fifth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1); //10회납 3회차 보험료
		
				$sixth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1);//10회납 6회차 보험료
				$seventh_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1);
		 		$eighth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1);
		 		$nineth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1);
		  		$tenth_3_21_1 = round(($total_3_21_1-$first_3_21_1)/9,-1);
				$imsi = $total_3_21_1 -($second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1+$sixth_3_21_1+$seventh_3_21_1+$eighth_3_21_1+$nineth_3_21_1+$tenth_3_21_1);
		  		
				$a_21_1 = $imsi-$first_3_21_1;
				if($a_21_1)
				{
					$first_3_21_1= round($first_3_21_1+ $a_21_1,-1);
				}

				//21세~23세  여보험료
		  		$first_3_21_2 = round($total_3_21_2*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_21_2= round(($total_3_21_2-$first_3_21_2)/9,-1); //10회납 2회차 보험료
		 		$third_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1); //10회납 4회차 보험료
		        $fifth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1); //10회납 3회차 보험료
		
				$sixth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1);//10회납 6회차 보험료
				$seventh_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1);
		 		$eighth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1);
		 		$nineth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1);
		  		$tenth_3_21_2 = round(($total_3_21_2-$first_3_21_2)/9,-1);
				$imsi = $total_3_21_2 -($second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2+$sixth_3_21_2+$seventh_3_21_2+$eighth_3_21_2+$nineth_3_21_2+$tenth_3_21_2);
		  		
				$a_21_2 = $imsi-$first_3_21_2;
				if($a_21_2)
				{
					$first_3_21_2= round($first_3_21_2+ $a_21_2,-1);
				}

			//20세 이하 남보험료
		  		$first_3_20_1 = round($total_3_20_1*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_20_1= round(($total_3_20_1-$first_3_20_1)/9,-1); //10회납 2회차 보험료
		 		$third_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1); //10회납 4회차 보험료
		        $fifth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1); //10회납 5회차 보험료
		
				$sixth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1);//10회납 6회차 보험료
				$seventh_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1);
		 		$eighth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1);
		 		$nineth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1);
		  		$tenth_3_20_1 = round(($total_3_20_1-$first_3_20_1)/9,-1);
				$imsi = $total_3_20_1 -($second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1+$sixth_3_20_1+$seventh_3_20_1+$eighth_3_20_1+$nineth_3_20_1+$tenth_3_20_1);
		  		
				$a_20_1 = $imsi-$first_3_20_1;
				if($a_20_1)
				{
					$first_3_20_1= round($first_3_20_1+ $a_20_1,-1);
				}

				//20세 이하 여보험료
		  		$first_3_20_2 = round($total_3_20_2*0.10,-1);  //10회납 1회차 보험료
	  	  		$second_3_20_2= round(($total_3_20_2-$first_3_20_2)/9,-1); //10회납 2회차 보험료
		 		$third_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1); //10회납 3회차 보험료
		  		$fourth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1); //10회납 4회차 보험료
		        $fifth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1); //10회납 5회차 보험료
		
				$sixth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1);//10회납 6회차 보험료
				$seventh_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1);
		 		$eighth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1);
		 		$nineth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1);
		  		$tenth_3_20_2 = round(($total_3_20_2-$first_3_20_2)/9,-1);
				$imsi = $total_3_20_2 -($second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2+$sixth_3_20_2+$seventh_3_20_2+$eighth_3_20_2+$nineth_3_20_2+$tenth_3_20_2);
		  		
				$a_20_2 = $imsi-$first_3_20_2;
				if($a_20_2)
				{
					$first_3_20_2= round($first_3_20_2+ $a_20_2,-1);
				}
	


     for($i=1;$i<10;$i++){ 
			$a=$i;
			$nex =date("Y-m-d ", strtotime("$start + $a month"));
			$nex_day=explode("-",$nex);
			
			$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
			include "./date_cal.php";

			$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

	 }
				break;

				case 11 ://11월1일 계약부터 10회 분납 처리를 위해
				//echo "tjd 11 <br> ";
			 //30세 이상 보험료
				//echo "total $total_3_30 <br>";
		  		$first_3_30 = round($total_3_30*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_30= round($total_3_30*0.10,-1); //10회납 2회차 보험료
		 		$third_3_30 = round($total_3_30*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_30 = round($total_3_30*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_30 = round($total_3_30*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_30 = round($total_3_30*0.10,-1);//10회납 6회차 보험료
				$seventh_3_30 = round($total_3_30*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_30 = round($total_3_30*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_30 = round($total_3_30*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_30 = round($total_3_30*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_30 -($second_3_30+$third_3_30+$fourth_3_30+$fifth_3_30+$sixth_3_30+$seventh_3_30+$eighth_3_30+$nineth_3_30+$tenth_3_30);
		  		//echo "firs_3_30 $first_3_30 <br>";
				$a_30 = $imsi-$first_3_30;
				//echo "imsi $imsi <br>";
				if($a_30)
				{
					$first_3_30= round($first_3_30+ $a_30,-1);
				}
	  		//26세 ~29세 보험료
		  		$first_3_26 = round($total_3_26*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_26= round($total_3_26*0.10,-1); //10회납 2회차 보험료
		 		$third_3_26 = round($total_3_26*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_26 = round($total_3_26*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_26 = round($total_3_26*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_26 = round($total_3_26*0.10,-1);//10회납 6회차 보험료
				$seventh_3_26 = round($total_3_26*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_26 = round($total_3_26*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_26 = round($total_3_26*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_26 = round($total_3_26*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_26 -($second_3_26+$third_3_26+$fourth_3_26+$fifth_3_26+$sixth_3_26+$seventh_3_26+$eighth_3_26+$nineth_3_26+$tenth_3_26);
		  		
				$a_26 = $imsi-$first_3_26;
				if($a_26)
				{
					$first_3_26= round($first_3_26+ $a_26,-1);
				}
			//24세 ~25세 보험료 남자보험료
		  		$first_3_24_1 = round($total_3_24_1*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_24_1= round($total_3_24_1*0.10,-1); //10회납 2회차 보험료
		 		$third_3_24_1 = round($total_3_24_1*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_24_1 = round($total_3_24_1*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_24_1 = round($total_3_24_1*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_24_1 = round($total_3_24_1*0.10,-1);//10회납 6회차 보험료
				$seventh_3_24_1 = round($total_3_24_1*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_24_1 = round($total_3_24_1*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_24_1 = round($total_3_24_1*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_24_1 = round($total_3_24_1*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_24_1 -($second_3_24_1+$third_3_24_1+$fourth_3_24_1+$fifth_3_24_1+$sixth_3_24_1+$seventh_3_24_1+$eighth_3_24_1+$nineth_3_24_1+$tenth_3_24_1);
		  		//echo "3_24_1 $first_3_24_1 <br>";
				$a_24_1 = $imsi-$first_3_24_1;
				//echo "a_24_1 $a_24_1 <br>";
				if($a_24_1)
				{
					$first_3_24_1= round($first_3_24_1+ $a_24_1,-1);
				}
				//echo "3_24_1 $first_3_24_1 <br>";
			//24세 ~25세 보험료 여자보험료
		  		$first_3_24_2 = round($total_3_24_2*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_24_2= round($total_3_24_2*0.10,-1); //10회납 2회차 보험료
		 		$third_3_24_2 = round($total_3_24_2*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_24_2 = round($total_3_24_2*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_24_2 = round($total_3_24_2*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_24_2 = round($total_3_24_2*0.10,-1);//10회납 6회차 보험료
				$seventh_3_24_2 = round($total_3_24_2*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_24_2 = round($total_3_24_2*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_24_2 = round($total_3_24_2*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_24_2 = round($total_3_24_2*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_24_2 -($second_3_24_2+$third_3_24_2+$fourth_3_24_2+$fifth_3_24_2+$sixth_3_24_2+$seventh_3_24_2+$eighth_3_24_2+$nineth_3_24_2+$tenth_3_24_2);
		  		
				$a_24_2 = $imsi-$first_3_24_2;
				if($a_24_2)
				{
					$first_3_24_2= round($first_3_24_2+ $a_24_2,-1);
				}
			//21세~23세  남보험료
		  		$first_3_21_1 = round($total_3_21_1*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_21_1= round($total_3_21_1*0.10,-1); //10회납 2회차 보험료
		 		$third_3_21_1 = round($total_3_21_1*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_21_1 = round($total_3_21_1*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_21_1 = round($total_3_21_1*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_21_1 = round($total_3_21_1*0.10,-1);//10회납 6회차 보험료
				$seventh_3_21_1 = round($total_3_21_1*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_21_1 = round($total_3_21_1*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_21_1 = round($total_3_21_1*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_21_1 = round($total_3_21_1*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_21_1 -($second_3_21_1+$third_3_21_1+$fourth_3_21_1+$fifth_3_21_1+$sixth_3_21_1+$seventh_3_21_1+$eighth_3_21_1+$nineth_3_21_1+$tenth_3_21_1);
		  		
				$a_21_1 = $imsi-$first_3_21_1;
				if($a_21_1)
				{
					$first_3_21_1= round($first_3_21_1+ $a_21_1,-1);
				}

				//21세~23세  여보험료
		  		 $first_3_21_2 = round($total_3_21_2*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_21_2= round($total_3_21_2*0.10,-1); //10회납 2회차 보험료
		 		$third_3_21_2 = round($total_3_21_2*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_21_2 = round($total_3_21_2*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_21_2 = round($total_3_21_2*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_21_2 = round($total_3_21_2*0.10,-1);//10회납 6회차 보험료
				$seventh_3_21_2 = round($total_3_21_2*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_21_2 = round($total_3_21_2*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_21_2 = round($total_3_21_2*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_21_2 = round($total_3_21_2*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_21_2 -($second_3_21_2+$third_3_21_2+$fourth_3_21_2+$fifth_3_21_2+$sixth_3_21_2+$seventh_3_21_2+$eighth_3_21_2+$nineth_3_21_2+$tenth_3_21_2);
		  		
				$a_21_2 = $imsi-$first_3_21_2;
				if($a_21_2)
				{
					$first_3_21_2= round($first_3_21_2+ $a_21_2,-1);
				}

			//20세 이하 남보험료
		
		  		 $first_3_20_1 = round($total_3_20_1*0.25,-1);  //10회납 1회차 보험료
				 
	  	  		$second_3_20_1= round($total_3_20_1*0.10,-1); //10회납 2회차 보험료
		 		$third_3_20_1 = round($total_3_20_1*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_20_1 = round($total_3_20_1*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_20_1 = round($total_3_20_1*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_20_1 = round($total_3_20_1*0.10,-1);//10회납 6회차 보험료
				$seventh_3_20_1 = round($total_3_20_1*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_20_1 = round($total_3_20_1*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_20_1 = round($total_3_20_1*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_20_1 = round($total_3_20_1*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_20_1 -($second_3_20_1+$third_3_20_1+$fourth_3_20_1+$fifth_3_20_1+$sixth_3_20_1+$seventh_3_20_1+$eighth_3_20_1+$nineth_3_20_1+$tenth_3_20_1);
		  		
				$a_20_1 = $imsi-$first_3_20_1;
				if($a_20_1)
				{
					$first_3_20_1= round($first_3_20_1+ $a_20_1,-1);
				}

				//20세 이하 여보험료

				
		  		 $first_3_20_2 = round($total_3_20_2*0.25,-1);  //10회납 1회차 보험료
	  	  		$second_3_20_2= round($total_3_20_2*0.10,-1); //10회납 2회차 보험료
		 		$third_3_20_2 = round($total_3_20_2*0.10,-1); //10회납 3회차 보험료
		  		$fourth_3_20_2 = round($total_3_20_2*0.10,-1); //10회납 4회차 보험료
		        $fifth_3_20_2 = round($total_3_20_2*0.10,-1); //10회납 5회차 보험료
		
				$sixth_3_20_2 = round($total_3_20_2*0.10,-1);//10회납 6회차 보험료
				$seventh_3_20_2 = round($total_3_20_2*0.10,-1);//10회납 7회차 보험료
		 		$eighth_3_20_2 = round($total_3_20_2*0.05,-1);//10회납 8회차 보험료
		 		$nineth_3_20_2 = round($total_3_20_2*0.05,-1);//10회납 9회차 보험료
		  		$tenth_3_20_2 = round($total_3_20_2*0.05,-1);//10회납 10회차 보험료
				$imsi = $total_3_20_2 -($second_3_20_2+$third_3_20_2+$fourth_3_20_2+$fifth_3_20_2+$sixth_3_20_2+$seventh_3_20_2+$eighth_3_20_2+$nineth_3_20_2+$tenth_3_20_2);
		  		
				$a_20_2 = $imsi-$first_3_20_2;
				if($a_20_2)
				{
					$first_3_20_2= round($first_3_20_2+ $a_20_2,-1);
				}
	


				 for($i=1;$i<10;$i++){ 
						$a=$i;
						$nex =date("Y-m-d ", strtotime("$start + $a month"));
						$nex_day=explode("-",$nex);
						
						$new_start=explode("-",$start);//Y-m-d형태로 나눈 것 위의 start를 
						include "./date_cal.php";

						$bunnab_day[$i] = $nex_day[0]."-".$nex_day[1]."-".$nex_day[2];

				 }

				 $bunnab_3=10; //다시 변수를 되돌린다
				break;
}
?>