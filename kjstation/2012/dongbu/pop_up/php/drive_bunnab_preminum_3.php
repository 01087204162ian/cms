<?/////////////2006년 11월1일 계약부터 적용
$today_=$year."-".$month."-".$day;

$gi_today="2006-10-26";

if($today_>=$gi_today){
	if($bunnab_3=='10'){
		$bunnab_3=11;
		//$nai=30;
	}
}

/////////////////////////////////////////////////////////

//	echo "b $bunnab_3 <br>";
//$echo "nex $nex_ <br>";
//echo "bunnab_3 $bunnab_3 <br>";
///신규 저장에서 사용하기 위해//

//echo "start $start <br>";
if($start && $preminum && $nab){
	
		$bunnab_3=$nab;
		$total_3_30=$preminum;
		
			$gi_today="2006-10-26";

			if($start>=$gi_today){
				if($bunnab_3=='10'){
					$bunnab_3=11;
				
				}

				$nai=30;
			}else{
				$nai=30;
			}
		
	}
//if($nab){
//$bunnab_3=$nab;
//}
//echo "preminum $preminum <br>";
//$total_3_30=$preminum;
//echo "nai $nai <br>";
//echo "total_3_30 $total_3_30 <br>";
//$nai=30;
//echo "bunnab_3 $bunnab_3 <br>";
//echo "nai $nai <br>";
////////////////////////////////////////////////////
//시기와 종기가 일치 하면 아니면

///$new_start =date("Y-m-d ",strtotime("$start ")+60*60*24);

//echo "new_start $new_start <br>";
if($start==$new_jeonggi || $new_jeonggi=='' ){

include "./bunnab_case_2.php";//분납보험료 산출을 위해
}else{

	include "./bunnab_case_1.php";
}
//echo "nai $nai <br>";
//echo "first $first_3_20_1 <br>";
//echo "bunnab_3 $bunnab_3 <br>";
if($bunnab_3==1) {
	//$first=$total_p;
	$first=$total_3_30;
}else {
	switch($nai){
		 case '20_1';
		 $first   = $first_3_20_1;
		 $second  = $second_3_20_1;
		 $third   = $third_3_20_1;
		 $fourth  = $fourth_3_20_1;
		 $fifth   = $fifth_3_20_1;
		 $sixth   = $sixth_3_20_1;
		 $seventh = $seventh_3_20_1;
		 $eighth  = $eighth_3_20_1;
		 $nineth  = $nineth_3_20_1;
		 $tenth   = $tenth_3_20_1;
		 break;

		 case '20_2';
		 $first   = $first_3_20_2;
		 $second  = $second_3_20_2;
		 $third   = $third_3_20_2;
		 $fourth  = $fourth_3_20_2;
		 $fifth   = $fifth_3_20_2;
		 $sixth   = $sixth_3_20_2;
		 $seventh = $seventh_3_20_2;
		 $eighth  = $eighth_3_20_2;
		 $nineth  = $nineth_3_20_2;
		 $tenth   = $tenth_3_20_2;
		 break;

		 case '21_1';
		 $first   = $first_3_21_1;
		 $second  = $second_3_21_1;
		 $third   = $third_3_21_1;
		 $fourth  = $fourth_3_21_1;
		 $fifth   = $fifth_3_21_1;
		 $sixth   = $sixth_3_21_1;
		 $seventh = $seventh_3_21_1;
		 $eighth  = $eighth_3_21_1;
		 $nineth  = $nineth_3_21_1;
		 $tenth   = $tenth_3_21_1;
		 break;

		 case '21_2';
		 $first   = $first_3_21_2;
		 $second  = $second_3_21_2;
		 $third   = $third_3_21_2;
		 $fourth  = $fourth_3_21_2;
		 $fifth   = $fifth_3_21_2;
		 $sixth   = $sixth_3_21_2;
		 $seventh = $seventh_3_21_2;
		 $eighth  = $eighth_3_21_2;
		 $nineth  = $nineth_3_21_2;
		 $tenth   = $tenth_3_21_2;
		 break;

		 

		 case '24_1';
		 $first   = $first_3_24_1;
		 $second  = $second_3_24_1;
		 $third   = $third_3_24_1;
		 $fourth  = $fourth_3_24_1;
		 $fifth   = $fifth_3_24_1;
		 $sixth   = $sixth_3_24_1;
		 $seventh = $seventh_3_24_1;
		 $eighth  = $eighth_3_24_1;
		 $nineth  = $nineth_3_24_1;
		 $tenth   = $tenth_3_24_1;
		 break;

		 case '24_2';
		 $first   = $first_3_24_2;
		 $second  = $second_3_24_2;
		 $third   = $third_3_24_2;
		 $fourth  = $fourth_3_24_2;
		 $fifth   = $fifth_3_24_2;
		 $sixth   = $sixth_3_24_2;
		 $seventh = $seventh_3_24_2;
		 $eighth  = $eighth_3_24_2;
		 $nineth  = $nineth_3_24_2;
		 $tenth   = $tenth_3_24_2;
		 break;

		 case '26';
		 $first   = $first_3_26;
		 $second  = $second_3_26;
		 $third   = $third_3_26;
		 $fourth  = $fourth_3_26;
		 $fifth   = $fifth_3_26;
		 $sixth   = $sixth_3_26;
		 $seventh = $seventh_3_26;
		 $eighth  = $eighth_3_26;
		 $nineth  = $nineth_3_26;
		 $tenth   = $tenth_3_26;
		 break;

		

		 case '30';
		 
		 $first   = $first_3_30;
		 $second  = $second_3_30;
		 $third   = $third_3_30;
		 $fourth  = $fourth_3_30;
		 $fifth   = $fifth_3_30;
		 $sixth   = $sixth_3_30;
		 $seventh = $seventh_3_30;
		 $eighth  = $eighth_3_30;
		 $nineth  = $nineth_3_30;
		 $tenth   = $tenth_3_30;
		 break;
	 }
}
/*
if($start && $preminum && $nab){
 echo "first   = $first <br>";
	 echo "second  = $second <br>";
	 echo "third   = $third <br>";
	 echo "fourth  = $fourth <br>";
	 echo "fifth   = $fifth <br>";
	 echo "sixth   = $sixth <br>";
	 echo "seventh = $seventh <br>";
	 echo "eighth  = $eighth <br>";
	 echo "nineth  = $nineth <br>";
	 echo "tenth   = $tenth <br>";
}
	 /*for($g=1;$g<10;$g++){
		 echo "$g 회차  $bunnab_day[$g] <br>";
	 }*/
	 if($second){ $da_2="2 회차"; }else{$da_2="";}
	 if($third) { $da_3="3 회차"; }else{$da_3="";}
	 if($fourth){ $da_4="4 회차"; }else{$da_4="";}
	 if($fifth) { $da_5="5 회차"; }else{$da_5="";}
	 if($sixth) { $da_6="6 회차"; }else{$da_6="";}
	 if($seventh) { $da_7="7 회차"; }else{$da_7="";}
	 if($eighth){ $da_8="8 회차"; }else{$da_8="";}
	 if($nineth){ $da_9="9 회차"; }else{$da_9="";}
	 if($tenth){ $da_10="10 회차"; }else{$da_10="";}

	 if($second){ $second_day=$bunnab_day[1]; }else{$second_day="";}//분납일
	 if($third) { $third_day=$bunnab_day[2]; }else{$third_day="";}
	 if($fourth){ $fourth_day=$bunnab_day[3]; }else{$fourth_day="";}
	 if($fifth) { $fifth_day=$bunnab_day[4]; }else{$fifth_day="";}
	 if($sixth) { $sixth_day=$bunnab_day[5]; }else{$sixth_day="";}
	 if($seventh) { $seventh_day=$bunnab_day[6]; }else{$seventh_day="";}
	 if($eighth){ $eighth_day=$bunnab_day[7]; }else{$eighth_day="";}
	 if($nineth){ $nineth_day=$bunnab_day[8]; }else{$nineth_day="";}
	 if($tenth){ $tenth_day=$bunnab_day[9]; }else{$tenth_day="";}
//echo "second_day $bunnab_day[2] <br>";
//echo "first $first <br>";
//return false;
if($dongbu!=10){?>
<script>
	if(confirm("분납 보험료가 산출되었습니다")){
		//alert('1')
		opener.document.form1.bohum_daily.value = '<?=$bohum_daily?>'
	opener.document.form1.first.value = '<?=$first?>'
	opener.document.form1.second.value = '<?=$second?>'
	opener.document.form1.third.value = '<?=$third?>'
	opener.document.form1.fourth.value = '<?=$fourth?>'
	opener.document.form1.fifth.value = '<?=$fifth?>'
	opener.document.form1.sixth.value = '<?=$sixth?>'
	opener.document.form1.seventh.value = '<?=$seventh?>'
	opener.document.form1.eighth.value = '<?=$eighth?>'
	opener.document.form1.nineth.value = '<?=$nineth?>'
	opener.document.form1.tenth.value = '<?=$tenth?>'
	opener.document.form1.da_2.value = '<?=$da_2?>' //2회차
	opener.document.form1.da_3.value = '<?=$da_3?>'
	opener.document.form1.da_4.value = '<?=$da_4?>'
	opener.document.form1.da_5.value = '<?=$da_5?>'
	opener.document.form1.da_6.value = '<?=$da_6?>'
	opener.document.form1.da_7.value = '<?=$da_7?>'
	opener.document.form1.da_8.value = '<?=$da_8?>'
	opener.document.form1.da_9.value = '<?=$da_9?>'
	opener.document.form1.da_10.value = '<?=$da_10?>' //10회차
	opener.document.form1.daum_day_1.value='<?=$start?>'
	opener.document.form1.daum_day_2.value='<?=$second_day?>'
	opener.document.form1.daum_day_3.value='<?=$third_day?>'
	opener.document.form1.daum_day_4.value='<?=$fourth_day?>'
	opener.document.form1.daum_day_5.value='<?=$fifth_day?>'
	opener.document.form1.daum_day_6.value='<?=$sixth_day?>'
	opener.document.form1.daum_day_7.value='<?=$seventh_day?>'
	opener.document.form1.daum_day_8.value='<?=$eighth_day?>'
	opener.document.form1.daum_day_9.value='<?=$nineth_day?>'
	opener.document.form1.daum_day_10.value='<?=$tenth_day?>'
}
	self.close()
	</script>
	<?}?>