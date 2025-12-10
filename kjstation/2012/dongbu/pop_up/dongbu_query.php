<? $dong_query="SELECT * FROM new_dong_bu WHERE num='$num'";
//echo "don $dong_query <br>";
	
	$dong_ks=mysql_query($dong_query,$connect);

	$dong_row=mysql_fetch_array($dong_ks);
	if($num_2=='4'){
	$name2			=$dong_row['name'];
	if($dong_row['jumin1']){
	$jumin1			=$dong_row['jumin1'];
	$jumin2			=$dong_row['jumin2'];
	
	}
	$hphone			=$dong_row['hphone'];
	$gubun			=$dong_row['gubun'];
	$booking	    =$dong_row['booking'];
	
	list($phone_1,$phone_2,$phone_3)=explode("-",$hphone);
	}
	$adate			=$dong_row['adate'];
	$atime			=$dong_row['atime'];
	switch($atime){
		case '1' :
		$atime_name="오전 10시 ~오전 12시";
		break;

		case '2' :
			$atime_name="오전 12시 ~오후 2시";
		break;

		case '3' :
			$atime_name="오후 2시 ~오후 4시";
		break;

		case '4' :
			$atime_name="오후 4시 ~오후 6시";
		break;

		default :
			$atime_name="유선 상담";
		break;
	}
	$wdate			=$dong_row['wdate'];
	$ttime			=$dong_row['ttime'];
	$bohum_start		=$dong_row['bohum_start'];

	//echo "보험시작일 $bohum_start <br>";
	list($year_s,$month_s,$day_s)=explode("-",$bohum_start);

	$daein_3		=$dong_row['daein'];
	if(!$daein_3){
		$daein_3=1;
	}

	$booking_date=$dong_row['booking_date'];
	list($booking_year,$booking_month,$booking_day)=explode("-",$booking_date);
	$booking_time=$dong_row['booking_time'];
	//echo "booking_date $booking_date <br>";
	//echo "bookig_time $booking_time <br>";
	
	$daemool_3		=$dong_row['daemool'];
	//echo "daemool_3 $daemool_3 <br>";
	$daein_p		=number_format($dong_row['daein_p']);
	$daemool_p		=number_format($dong_row['daemool_p']);

	$jason_3		=$dong_row['jason'];
	$jason_p		=number_format($dong_row['jason_p']);

	$sele_3			=$dong_row['sele'];
	$char_3			=$dong_row['cha'];

	$jagibudam_3	=$dong_row['jagibudam'];

	$cha_p			=number_format($dong_row['cha_p']);

	$sago_3			=$dong_row['sago'];

	
	$sago_p			=number_format($dong_row['sago_p']);

	$law_3			=$dong_row['law'];


	$law_p			=number_format($dong_row['law_p']);


   $bunnab_3		=$dong_row['bunnab'];
   

   $bumwi_3			=$dong_row['bumwi'];
   switch($bumwi_3){
	   case '1' :
		   $bumwi_name="개인계약";
			$harin_rate="할인없슴";
	   break;
	   case '0.95' :
		   $bumwi_name="6명~10명";
			$harin_rate="5%할인";
	   break;
	   case '0.90' :
		   $bumwi_name="11명이상";
			$harin_rate="10%할인";
	   break;
   }

 $total_p		=number_format($dong_row['total_p']);
 $first			=number_format($dong_row['first']);
 $second		=number_format($dong_row['second']);
 $third			=number_format($dong_row['third']);
 $fourth		=number_format($dong_row['fourth']);
 $fifth			=number_format($dong_row['fifth']);
 $sixth			=number_format($dong_row['sixth']);
 $seventh		=number_format($dong_row['seventh']);
 $eighth		=number_format($dong_row['eighth']);
 $nineth		=number_format($dong_row['nineth']);
 $tenth			=number_format($dong_row['tenth']);

 $content		=$dong_row['content'];
 //echo "bunnab_3 $bunnab_3 <br>";
switch($bunnab_3){
	case '10':
		$da_2="2회차";
	   $da_3="3회차";
	   $da_4="4회차";
	   $da_5="5회차";
	   $da_6="6회차";
	   $da_7="7회차";
	   $da_8="8회차";
	   $da_9="9회차";
	   $da_10="10회차";

	   break;

	   case '2':
		$da_2="2회차";
	  

	   break;

	   case '4':
		$da_2="2회차";
	   $da_3="3회차";
	   $da_4="4회차";
	   

	   break;

		case '7':
		$da_2="2회차";
	   $da_3="3회차";
	   $da_4="4회차";
	   $da_5="5회차";
	   $da_6="6회차";
	   
	   break;
}
	include "./php/switch_3.php";

	switch($booking){
				case '1' :
					$booking_name="계약";
				
				break;
				case '2' :
					$booking_name="예약";
				
				break;
				case '3' :
					$booking_name="종결";
			
				break;
				case '4' :
					$booking_name="약속";
			
				break;
				case '5' :
					$booking_name="안받음";
		
				break;
				case '6' :
					$booking_name="없는번호";
				
				break;
				case '7' :
					$booking_name="생각중";
			
				break;
				case '8' :
					$booking_name="산출만";
		
				break;
				case '9' :
					$booking_name="조금있다";
			
				break;
				case '10' :
					$booking_name="팩스";
			
				break;
				case '11' :
					$booking_name="메일";
			
				break;
			}
	?>
