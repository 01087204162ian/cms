<? $qSql="SELECT * FROM 2012CertiTable  WHERE num='$CertiTableNum'";

   
		$qRs=mysql_query($qSql,$connect);
		$qRow=mysql_fetch_array($qRs);
//if($qRow[startyDay]){//기준일이 저장 되어 있는 경우에 


$deriNum=$qRow['2012DaeriCompanyNum'];

	$q2Sql="SELECT * FROM 2012DaeriCompany  WHERE num='$deriNum'";
	$q2rs=mysql_query($q2Sql,$connect);
	$q2Row=mysql_fetch_array($q2rs);

if($q2Row[FirstStart]){
	//최초거래일을 지정한 후 조회 한다
		
//2011-08-27 시작일이 다른것을 찾아 보자

$gijun=explode("-",$q2Row[FirstStart]);//최초거래일의 일

//현재일
if($gijun[2]==30 || $gijun[2]==31){
	//마지막날이 말일31일경우의 에러 발생 31일에서 한달을 빼면 당월일일 나오므로 하루를 다시 빼자
	$tm=explode("-",$now_time);
	$dayOFberfore =date("Y-m-d ", strtotime("$now_time -1 month"));//1달전
	if($tm[2]==30){
			$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -1 day"));//1달전
	}
	if($tm[2]==31){
			$dayOFberfore =date("Y-m-d ", strtotime("$dayOFberfore -2 day"));//1달전
	}
	$m=date(t, strtotime("$dayOFberfore"));
	$mn=explode("-",$dayOFberfore);
	$sigi=$mn[0]."-".$mn[1]."-".$m;
	//$tm=explode("-",$now_time);
	$lm=date(t, strtotime("$now_time"));
		$nexMonth =$tm[0]."-".$tm[1]."-".$lm;//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   include  "./php/dongbuPeriod.php";
}else{
	if($gijun[2]<=$today){//$today현재일
	
		$sigi=$toyear."-".$tomonth."-".$gijun[2];

		$nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   include  "./php/dongbuPeriod.php";
	}else{
		//$tomonth가 1월인경우와

		$sigi=$toyear."-".$tomonth."-".$gijun[2];

		$sigi =date("Y-m-d ", strtotime("$sigi -1 month"));
		$nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   include  "./php/dongbuPeriod.php";



	}
}
		$giPrem[0]=$qRow[preminum1];$gi2Prem[0]=$qRow[preminumE1];
		$giPrem[1]=$qRow[preminum2];$gi2Prem[1]=$qRow[preminumE2];
		$giPrem[2]=$qRow[preminum3];$gi2Prem[2]=$qRow[preminumE3];
		$giPrem[3]=$qRow[preminum4];$gi2Prem[3]=$qRow[preminumE4];
		$giPrem[4]=$qRow[preminum5];$gi2Prem[4]=$qRow[preminumE5];
		$giPrem[5]=$qRow[preminum6];$gi2Prem[5]=$qRow[preminumE6];

		$sigi=$qRow[startyDay];
	/*	$gijunDay=strtotime("$qRow[MonthStart]");//최초 거래일

		$currentDay=strtotime("$nowtime");//현재일자
		$Pe=floor((($currentDay-$gijunDay)/60/60/24)/30);//현재일자-최초거래일 월
		   $sigi=date("Y-m-d ", strtotime("$qRow[MonthStart] +$Pe month"));
		   //date_default_timezone_set() 
		   $nexMonth =date("Y-m-d ", strtotime("$sigi + 1 month"));//종기
		   $Ssigi=strtotime("$sigi"); //tlrl 
		   $SnextMonth=strtotime("$nexMonth");	//다음날기준일
		   $Period=($SnextMonth-$Ssigi)/60/60/24;//다음달기준일-현재기준일 
		   for($_u_=0;$_u_<$Period;$_u_++){

			   $p[$_u_]=date("Y-m-d ", strtotime("$sigi + $_u_ day"));
			   $p2[$_u_]=round($qRow[MonthPrem]-$qRow[MonthPrem]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p2[$_u_]=number_format($p2[$_u_]);
				
		   }*/


}

?>
			