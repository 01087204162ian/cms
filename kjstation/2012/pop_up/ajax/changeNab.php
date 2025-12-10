<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$DariMemberNum=iconv("utf-8","euc-kr",$_GET['DariMemberNum']);	
	$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
	$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);
	$policyNum=iconv("utf-8","euc-kr",$_GET['policyNum']);
	$userId=iconv("utf-8","euc-kr",$_GET['userId']);
	$val=iconv("utf-8","euc-kr",$_GET['val']);



		$insertSql="UPDATE 2012DaeriMember SET nabang_1='$val' ";
		$insertSql.="WHERE num='$DariMemberNum'";
		

		
		mysql_query($insertSql,$connect);

		//$e_count++;




		// 유예 납입 등을 조회 하기 위해 
		$stSql="SELECT * FROM 2012DaeriMember  WHERE num='$DariMemberNum'";
        $stRs=mysql_query($stSql,$connect);
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
				
				$thisBun=$_u+1;;

			}
			
		}
		//echo "$_q_ $thisBun[$_q_] <br>";
		$nabCha=$thisBun-$stRow[nabang_1];//현재일자가 5회차인데 납입도 5회차이면
		//echo "$_q_  $stRow[Name] $stRow[nabang_1] || $thisBun[$_q_] || $nabCha[$_q_] <br>";
	   $stUpdate="UPDATE 2012DaeriMember SET FirstStart='$now_time',state='$nabCha' ";
	   $stUpdate.="WHERE num='$DariMemberNum'";


		//납입상황을 처리 후  다시 조회하여 
	//echo "stU $stUpdate <Br>";

	   mysql_query($stUpdate,$connect);
		

		$message=$val.'회차 처리  되었습니다!!';
//}
//include "../php/endorseNumStore.php";
echo"<data>\n";
	//echo "<enday>".$endorseDay.$esql."</enday>\n";
	echo "<num>".$insertSql."</num>\n";
	//for($_u_=1;$_u_<15;$_u_++){
	//	echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";//
		
   // }
	

	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>