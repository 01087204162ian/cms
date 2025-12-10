<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	echo "<values>\n";

			$sql="SELECT * FROM 2012CertiTable WHERE num='$certiTableNum'";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);

			//echo "startyDay $row[startyDay] "; //단체보험인 경우 시작일
			//echo "divi $row[divi]";			 // 1 정상 2. 1/12
			//echo "personal $row[personal]" ; // 1.단체 2개인

if($row[personal]==1){
    $startyDay=$row[startyDay];
    $nabang=$row[nabang];
	$nabang_1=$row[nabang_1];
	$state=$cRow[state];
	$endorseDay=$now_time;
	$InsuraneCompany=$row[InsuraneCompany];
}else{

	//2015-09-07 순수 개인보험인경우

  /*	$mSql="SELECT * FROM 2014DaeriMember WHERE num='$memberNum'";

	//echo " $mSql"; 
	$mRs=mysql_query($mSql,$connect);
	$mRow = mysql_fetch_array($mRs);
	$startyDay= $mRow[dongbusigi];
	if(!$startyDay){ //개별 시작일이 없으면 
		$startyDay=$mRow[InPutDay];
		$endDay=date("Y-m-d ",strtotime("$startyDay +1 year"));
	}
	$nabang=10;//무조건 10회분납이라고 생각한다 2015-06-16
	$nabang_1=$mRow[nabang_1];

	// 개인보험인 경우 처음에 회차는 1회차, 시기는 배서일 ,종기일을 입력하기 위해 

	if(!$nabang_1){
		$usql="UPDATE 2014DaeriMember SET dongbusigi='$startyDay',dongbujeongi='$endDay',nabang_1='1' WHERE num='$memberNum'";

		//echo "$usql";
		$nabang_1=1;

	}
	//echo "$nabang_1";
	$state=$mRow[state];*/
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
	
		include "./php/dayInsPreminumCount.php";

	echo("\t\t<before_preminum>".number_format($beforePreminum)."</before_preminum>\n"); //경과기간보험료
	echo("\t\t<after_preminum>".number_format($totalPreminum)."</after_preminum>\n");//미경과기간보험료
  
	$thisDay2=$nabang_1+4;
	echo("\t\t<thisDay>".$thisDay2."</thisDay>\n");
	echo("\t</policy>\n");

	}


	echo "</values>";
	?>