<? 
	$endorse_day	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	

	$phone	    =iconv("utf-8","euc-kr",$_GET['phone']);
	$daein_3	    =iconv("utf-8","euc-kr",$_GET['daein_3']);
	$daemool_3	    =iconv("utf-8","euc-kr",$_GET['daemool_3']);
	$jason_3	    =iconv("utf-8","euc-kr",$_GET['jason_3']);
	$sele_3	    =iconv("utf-8","euc-kr",$_GET['sele_3']);
	$jagibudam_3	    =iconv("utf-8","euc-kr",$_GET['jagibudam_3']);
	$char_3	    =iconv("utf-8","euc-kr",$_GET['char_3']);
	//$sago_3	    =iconv("utf-8","euc-kr",$_GET['sago_3']);
	//$sago_3	    =iconv("utf-8","euc-kr",$_GET['sago_3']);
	$bunnab_3	    =10;

    $h1 =iconv("utf-8","euc-kr",$_GET['h1']);//할인율
	 $h2 =iconv("utf-8","euc-kr",$_GET['h2']);//할인율

		$nai_26	 = date("Y.m.d ",strtotime("$endorse_day -26 year"));	//echo $nai_26;
		$nai_31	 = date("Y.m.d ",strtotime("$endorse_day -31 year"));
		//echo $nai_30;
		$nai_name="26세 이상~30세";
		$Nai_31	 = date("Y-m-d ",strtotime("$endorse_day -31 year"));
		$nai_30=	date("Y.m.d ",strtotime("$Nai_31 - 1day"))	;
		
		$nai_46	 = date("Y.m.d ",strtotime("$start -46 year"));
		$Nai_46	 = date("Y-m-d ",strtotime("$start -46 year"));
		$nai_45= date("Y.m.d ",strtotime("$Nai_46 - 1day"));

		$nai_51=date("Y.m.d ",strtotime("$endorse_day -51 year"));
		$Nai_51=date("Y-m-d ",strtotime("$endorse_day -51 year"));
		$nai_50=date("Y.m.d ",strtotime("$Nai_51 +1day"));

		$nai_56=date("Y.m.d ",strtotime("$endorse_day -56 year"));
		$Nai_56=date("Y-m-d ",strtotime("$endorse_day -56 year"));
		$nai_55=date("Y.m.d ",strtotime("$Nai_56 +1day"));

		$nai_61=date("Y.m.d ",strtotime("$endorse_day -61 year"));
		$Nai_61=date("Y-m-d ",strtotime("$endorse_day -61 year"));
		$nai_60=date("Y.m.d ",strtotime("$Nai_61 +1day"));
/*
if($age>=26 && $age<30) {
		$nai=1;
}
if($age>=31 && $age<46) {
		$nai=2;
}
if($age>=46 && $age<50) {
		$nai=3;
}
if($age>=51 && $age<56) {
		$nai=4;
}
if($age>=56 && $age<60) {
		$nai=5;
}
if($age>=61) {
		$nai=6;

}*/


$daein=daein.$daein_3;
$daemool=daemool.$daemool_3;
$jason=jason.$jason_3;
if($jagibudam_3==1){
	switch($char_3){
		case 1 :
			$char=char."1";
			break;
		case 2 :
			$char=char."2";
			break;
		case 3:
			$char=char."3";
			break;

	}
	
}else{
	switch($char_3){
		case 1 :
			$char=char."4";
			break;
		case 2 :
			$char=char."5";
			break;
		case 3 :
			$char=char."6";
			break;

	}

}

if($age>=26 && $age<31) {
		$nai=1;
}
if($age>=31 && $age<46) {
		$nai=2;
}
if($age>=46 && $age<50) {
		$nai=3;
}
if($age>=51 && $age<56) {
		$nai=4;
}
if($age>=56 && $age<60) {
		$nai=5;
}
if($age>=61) {
		$nai=6;

};


?>
