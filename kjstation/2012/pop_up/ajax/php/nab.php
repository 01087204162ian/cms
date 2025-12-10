<?if($e[14]){//이미저장 되어 있는것이 없으면

	$cha=$e[14];
}else{
	//echo "ad $rCertiRow[startyDay] <br>";
	for($_i=1;$_i<10;$_i++){ 
		$_j=$_i+1;
		$nex[$_j] =date("Y-m-d ", strtotime("$rCertiRow[startyDay] + $_i month"));//다음 응당일

		$du[$_j]=date("Y-m-d ", strtotime("$nex[$_j] +1 month"));//응당일 다음달 
		$dd=date(t, strtotime("$nex[$_j] +1 month")); //말일
		$dd2=date("Y-m ", strtotime("$nex[$_j] +1 month"));
					$silhy[$j]=$dd2."-".$dd;

		//echo "$_j $nex[$_j] <br>";
		//echo "du $du[$_j] 말일 $dd <br>";

		
	//$now_time="2013-01-13";

	//echo "ww  $nex[$_j] now $now_time <br>";
		if($nex[$_j]<=$now_time)
		{
			$cha=$_j;
		}

	}

	if(!$cha){$cha=1;}
//echo "cha $cha <br>";
}

?>