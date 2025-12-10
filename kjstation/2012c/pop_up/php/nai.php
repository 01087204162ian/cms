
<?


$jumin1=$a3b[$i];
$_year=substr($jumin1,0,2);
	
	$a=19;

	$__year=$a.$_year;
	
	$_month			= substr($jumin1,2,2);
	$_day				= substr($jumin1,4,2);
	
	
	$birthday			=$__year.$_month.$_day;
	
	$to_day				=date(Y).date(m).date(d);//보험기간 변수
	
	if($endorseDay){ 
		list($sphone1,$sphone2,$sphone3)=explode("-",$endorseDay);
		$to_day=$sphone1.$sphone2.$sphone3;
		
	}
	$cal_age			=$to_day-$birthday;

	
	$age					=floor(substr($cal_age,0,2));
	$sex					= substr($jumin2,0,1);

$a6b[$i]=$age;
	?>