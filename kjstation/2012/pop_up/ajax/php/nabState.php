<?	



//$nex[$j] //분납응당일  $silhy[$j]//실효일
if($naBang!=10){
	for($i=1;$i<10;$i++){ 
			$j=$i+1;
			$nex[$j] =date("Y-m-d ", strtotime("$sigiStart + $i month"));

			if($inPnum==1 || $inPnum==3 || $inPnum==7){//흥국,LiG
			     $silhy[$j]=date("Y-m-d ", strtotime("$nex[$j] + 30 day"));
			}else{
				$du[$j]=date("Y-m-d ", strtotime("$nex[$j] +1 month"));//응당일 다음달 
				$dd=date(t, strtotime("$nex[$j] +1 month")); //말일
				$dd2=date("Y-m ", strtotime("$nex[$j] +1 month"));
				$silhy[$j]=$dd2."-".$dd;
			}
			
			//현재 1회차를 납입했으면 다음응달일 전까지는 납입이 되ㄱ
			//응당일부터 실효일까지는  유예가 되고 
			//실효일 예정일이 지나면 실요가 된다

			$daum=$naBang+1;

			if($j==$daum){
				if(date("Y-m-d ", strtotime("$now_time"))<=date("Y-m-d ", strtotime("$nex[$j]"))){
					$naState='납';
					$gigan=((strtotime("$nex[$j]"))-(strtotime("$now_time")))/(60*60*24);
					$naColor=1;
					
				}else if(date("Y-m-d ", strtotime("$now_time"))>date("Y-m-d ", strtotime("$nex[$j]"))&&(date("Y-m-d ", strtotime("$now_time"))<=date("Y-m-d ", strtotime("$silhy[$j]")))){
					$naState='유';
					$naColor=2;
					
					$gigan=((strtotime("$silhy[$j]"))-(strtotime("$now_time")))/(60*60*24);
				}else{

					$naState='실';
					$naColor=3;
					$gigan='';
				}

			}
			//echo "<bunnab".$_m.$j.">".$nex[$j]."</bunnab".$_m.$j.">\n";//
			//echo "<silhy".$_m.$j.">".$silhy[$j]."</silhy".$_m.$j.">\n";//
		
	 }
	 
}else{

	$naState='완';
	$naColor=1;
	$gigan='';

}
			?>