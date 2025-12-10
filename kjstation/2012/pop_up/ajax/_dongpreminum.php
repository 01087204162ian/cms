<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	echo "<values>\n";
if($proc == "preminum") {

  

		//daerimemberNum
		$d_sql="SELECT * FROM 2012DaeriMember WHERE num='$daerimemberNum'";
		$d_rs=mysql_query($d_sql,$connect);
		$d_row=mysql_fetch_array($d_rs);


		$certiTableNum=$d_row[CertiTableNum];
 


			$sql="SELECT * FROM 2012CertiTable WHERE num='$certiTableNum'";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);

			//echo "startyDay $row[startyDay] "; //단체보험인 경우 시작일
			//echo "divi $row[divi]";			 // 1 정상 2. 1/12
			//echo "personal $row[personal]" ; // 1.단체 2개인

			//현재납입회차는 4회차 인데 해당일에는 3회차인경우가 있을 수 있다

			$q_sql="SELECT * FROM 2015dongbudailyP WHERE date='$sigi' ";
			$q_sql.="and snai='26' and enai='30' ";
			$q_rs=mysql_query($q_sql,$connect);
			$q_num=mysql_num_rows($q_rs);

			//echo "q_num $q_num <br>";

			if($q_num>0){
				if($q_num!=$row[nabang_1]){
					$row[nabang_1]=$q_num;
				}
			}



if($row[personal]==1){
    $startyDay=$row[startyDay];
    $nabang=$row[nabang];
	$nabang_1=$row[nabang_1];
	$state=$cRow[state];
	$endorseDay=$sigi;
	
	//$endorseDay=$now_time;
	$InsuraneCompany=$row[InsuraneCompany];
}else{

	
}
	
			// 보험료를 연령별로 계산하기 위해 

				$table = "2012Cpreminum ";
				$where =" WHERE CertiTableNum='$certiTableNum'  ";		
				$order ="order by sunso asc";
				$sql2 = "select * from " . $table . $where.$order ;


			//echo "sql $sql2 ";

				$rs2=mysql_query($sql2,$connect);
				$num2=mysql_num_rows($rs2);


				//보험료 받는날자 초기일부터 계산
			include "./php/InsdateCount.php";

			//echo "num2  $num2";

				for($j=0;$j<$num2;$j++){


					$row2=mysql_fetch_array($rs2);


				  $Ypreminum=$row2[yPreminum];
				  $dailyPreminum=round(($Ypreminum/$gigan),-1); //1일보험료
				
				echo("\t<policy>\n");
					echo("\t\t<startyDay>".$startyDay."~".$daumY."</startyDay>\n");
					echo("\t\t<before_gijun>".$before_gijun."</before_gijun>\n"); //경과기간
					echo("\t\t<after_gijun>".$after_gijun."</after_gijun>\n");//미경과기간
					echo("\t\t<endorseDay>".$endorseDay."~".$daumY."</endorseDay>\n");//기준일자
					echo("\t\t<s_nai>".$row2[sPreminum]."</s_nai>\n");
					echo("\t\t<e_nai>".$row2[ePreminum]."</e_nai>\n");
					echo("\t\t<yPreminum>".number_format($row2[yPreminum])."</yPreminum>\n");
					echo("\t\t<dailyPreminum><![CDATA[".number_format($dailyPreminum)."]]></dailyPreminum>\n");
					echo("\t\t<certi><![CDATA[".$DB->Recode[certi]."]]></certi>\n");

					$yearPrem=$row2[yPreminum];

					//if($row2[ePreminum]==999){$row2[ePreminum]=99;}

					 // 2015dongbudailyP 입력이 되었다면 다시 계산 할 필요가 없지
				   $g_sql="SELECT * FROM 2015dongbudailyP WHERE date='$endorseDay' ";

				   $g_sql.="and snai='$row2[sPreminum]' and enai='$row2[ePreminum]'  order by num  asc";

				//echo "g_num $g_sql ";

				   $g_rs=mysql_query($g_sql,$connect);
				   $g_num=mysql_num_rows($g_rs);

				//  echo "g_num $g_num ";

						if($g_num){ 

							include "./php/dayInsPreminumCount2.php";

						}else{
					
						    include "./php/dayInsPreminumCount.php";
						}
					echo("\t\t<before_preminum>".number_format($beforePreminum)."</before_preminum>\n"); //경과기간보험료
					echo("\t\t<after_preminum>".number_format($totalPreminum)."</after_preminum>\n");//미경과기간보험료
				  
					$thisDay2=$nabang_1;
					echo("\t\t<thisDay>".$thisDay2."</thisDay>\n");
				echo("\t</policy>\n");
				$totalPreminum='';
			//g_num끝
		
	}

}else if($proc=='premUpdate'){


	$pre=explode(",",$preiminum);

	$preiminum=$pre[0].$pre[1].$pre[2];


	$dat=explode("~",$date);

	$date=$dat[0];
		

	//if($enai=999){$enai=99;}

		$update="UPDATE 2015dongbudailyP SET preiminum='$preiminum' WHERE date='$date' ";
        $update.=" and snai='$snai' and enai='$enai'  and nabang='$nabang'";

		//echo "update $update ";

		mysql_query($update,$connect);

		//남은기간의 총보험료를 수정하기 위해

			       $g_sql="SELECT * FROM 2015dongbudailyP WHERE date='$date' ";

				   $g_sql.="and snai='$snai' and enai='$enai' ";
				    

					//echo "g_sql $g_sql";
				   $g_rs=mysql_query($g_sql,$connect);
				   $g_num=mysql_num_rows($g_rs);

				   //echo "g_num $g_num ";
				   for($k=0;$k<$g_num;$k++){
						//echo "k $k <br>";
						$g_row=mysql_fetch_array($g_rs);
						$totalPreminum+=$g_row[preiminum];
				   }

		//if($enai=99){$enai=999;}


	echo("\t<policy>\n");
		echo("\t\t<nabang>".$nabang."</nabang>\n");  //회차
		echo("\t\t<preiminum>".number_format($preiminum)."</preiminum>\n"); //보험료
		echo("\t\t<snai>".$snai."</snai>\n");//시작나이
		echo("\t\t<enai>".$enai."</enai>\n");//끝나이
		echo("\t\t<date>".$date."</date>\n");//

		echo("\t\t<totalPreminum>".number_format($totalPreminum)."</totalPreminum>\n");//기준일자
	echo("\t</policy>\n");

}
	echo "</values>";
	?>