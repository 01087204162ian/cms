<?
$stSql="SELECT * FROM 2012DaeriMember  WHERE nabang_1!='10' and push='4' and InsuranceCompany='2'";
$stRs=mysql_query($stSql,$connect);
$stNum=mysql_num_rows($stRs);


for($_q_=0;$_q_<$stNum;$_q_++){
		$stRow=mysql_fetch_array($stRs);
        
		
		$jeongGi= $stRow[dongbujeongi];
		$SSsigi=date("Y-m-d ", strtotime("$jeongGi -1 year"));//시기
		//echo "SS  $SSsigi ";
		for($_u=0;$_u<10;$_u++){
			//echo "$_u ||";
			
				$dd2[$_u]=date("Y-m ", strtotime("$SSsigi +$_u month"));
			
			//echo "$_u $dd2 <br>";
			$thiMonth[$_u]=date("Y-m ", strtotime("$now_time"));
			if($dd2[$_u]==$thiMonth[$_u]){
				
				$thisBun[$_q_]=$_u+1;;

			}
			
		}
		//echo "$_q_ $thisBun[$_q_] <br>";
		$nabCha[$_q_]=$thisBun[$_q_]-$stRow[nabang_1];//현재일자가 5회차인데 납입도 5회차이면
		//echo "$_q_  $stRow[Name] $stRow[nabang_1] || $thisBun[$_q_] || $nabCha[$_q_] <br>";
	$stUpdate="UPDATE 2012DaeriMember SET FirstStart='$now_time',state='$nabCha[$_q_]' ";
	$stUpdate.="WHERE num='$stRow[num]'";

	//echo "stU $stUpdate <Br>";

	mysql_query($stUpdate,$connect);
}


		


?>