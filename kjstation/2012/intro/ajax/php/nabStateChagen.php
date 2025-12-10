<?

$stSql="SELECT * FROM 2012CertiTable WHERE nabang_1!='10' and startyDay>='$yearbefore'";
$stRs=mysql_query($stSql,$connect);
$stNum=mysql_num_rows($stRs);


for($_q_=0;$_q_<$stNum;$_q_++){

	$stRow=mysql_fetch_array($stRs);
	$sigiStart=$stRow[startyDay];
	$naBang=$stRow[nabang_1];
	$inPnum=$stRow[InsuraneCompany];
	//$num[$_q_]=$stRow[nabang_1];
	include "../../pop_up/ajax/php/nabState.php";//nabSunsoChange.php 공동으로 사용

	//////////////////////////////////////////////////////////
	//
	//납입상태를 저장하는데 ,FirstStart  기준일 FirstStartDay 는 유예남은일자$gigan,state 1 납,2유예 3실효
	//
	// FirstStart  기준일과 now_time을 비교하여 다를경우에  다음모줄이 실행된다.

	$Gigan[$_q_]=$gigan;
	$State[$_g_]=$naColor;

	$stUpdate="UPDATE 2012CertiTable SET FirstStart='$now_time',FirstStartDay='$Gigan[$_q_]',state='$State[$_g_]' ";
	$stUpdate.="WHERE num='$stRow[num]'";

	//echo "stU $stUpdate <Br>";

	mysql_query($stUpdate,$connect);

}



?>