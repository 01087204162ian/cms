
<?

	$Csql="SELECT * FROM 2012CertiTable  WHERE num='$CertiTableNum'";

	$Crs=mysql_query($Csql,$connect);

	$Crow=mysql_fetch_array($Crs);

//흥국,동부는 배서기준일이 만나이 계산하는 

//그외의 보험회사는 보험증권의 초일이 만나이 계산하는 기준일

switch($insuranceNum){
	case 1 ://흥국
		$m=explode("-",$endorseDay);		
		break;
	case 2 :
		$m=explode("-",$endorseDay);	
		break;
	case 3:
		$m=explode("-",$Crow[startyDay]);	
		break;
	case 4:
		$m=explode("-",$Crow[startyDay]);	
		break;
	case 5:
		$m=explode("-",$Crow[startyDay]);	
		break;
	

	case 7:
		$m=explode("-",$Crow[startyDay]);	
		break;

}




    $_year=substr($jumin1,0,2);
	
	$a=19;

	$__year=$a.$_year;
	
	$_month			= substr($jumin1,2,2);
	$_day				= substr($jumin1,4,2);
	
	
	$birthday			=$__year.$_month.$_day;
	
	//$to_day				=date(Y).date(m).date(d);//보험기간 변수
	
	$to_day=$m[0].$m[1].$m[2];
	$cal_age			=$to_day-$birthday;

	
	$age					=floor(substr($cal_age,0,2));
	$sex					= substr($jumin2,0,1);


	?>