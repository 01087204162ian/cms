<?//include '../../2012c/lib/lib_auth.php';?>
<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");?>

<? //echo "cNum $cNum ";


include '../../dbcon.php';

$now_time="2015-09-25";
$where="WHERE 2012DaeriCompanyNum='$cNum' AND InPutDay='$now_time' AND (push='4' or push='1')";
$sql="Select * FROM 2012DaeriMember   $where ";

//echo "sql $sql ";

$rs=mysql_query($sql,$connect);
$num=mysql_num_rows($rs);
for($j=0;$j<$num;$j++){

	$row=mysql_fetch_array($rs);

	//echo "$j $row[Jumin] $row[Name]  $row[CertiTableNum] $row[nai]  <br>";


	$certiTableNum=$row[CertiTableNum];
	
			$sql3="SELECT * FROM 2012CertiTable WHERE num='$certiTableNum'";

			//echo "sql $sql ";
			$rs3=mysql_query($sql3,$connect);
			$row3=mysql_fetch_array($rs3);

			//echo "startyDay $row[startyDay] "; //단체보험인 경우 시작일
			//echo "divi $row[divi]";			 // 1 정상 2. 1/12
			//echo "personal $row[personal]" ; // 1.단체 2개인

if($row3[personal]==1){
    $startyDay=$row3[startyDay];
    $nabang=$row3[nabang];
	$nabang_1=$row3[nabang_1];
	$state=$cRow[state];
	$endorseDay=$now_time;
	$InsuraneCompany=$row3[InsuraneCompany];
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


	$table = "2012Cpreminum ";
	$where =" WHERE CertiTableNum='$certiTableNum' and sPreminum<='$row[nai]' and ePreminum>='$row[nai]'";		
	$order ="order by sunso asc";
	$sql2 = "select * from " . $table . $where.$order ;

	$rs2=mysql_query($sql2,$connect);
	$row2=mysql_fetch_array($rs2);


$row[personal]=$row3[personal];
	//echo "sql2  $sql2";



	include "./ajax/php/InsdateCount.php";

	$Ypreminum=$row2[yPreminum];
//	  $dailyPreminum=round(($Ypreminum/$gigan),-1); //1일보험료
	
/*	echo("\t<policy>\n");
	echo("\t\t<startyDay>".$startyDay."~".$daumY."</startyDay>\n");
	echo("\t\t<before_gijun>".$before_gijun."</before_gijun>\n"); //경과기간
	echo("\t\t<after_gijun>".$after_gijun."</after_gijun>\n");//미경과기간
	echo("\t\t<endorseDay>".$endorseDay."~".$daumY."</endorseDay>\n");//기준일자
	echo("\t\t<s_nai>".$row2[sPreminum]."</s_nai>\n");
	echo("\t\t<e_nai>".$row2[ePreminum]."</e_nai>\n");
	echo("\t\t<yPreminum>".number_format($row2[yPreminum])."</yPreminum>\n");
	echo("\t\t<dailyPreminum><![CDATA[".number_format($dailyPreminum)."]]></dailyPreminum>\n");
	echo("\t\t<certi><![CDATA[".$DB->Recode[certi]."]]></certi>\n");
*/
	$yearPrem=$row2[yPreminum];
	
		include "./ajax/php/dayInsPreminumCount2.php";

	/*echo("\t\t<before_preminum>".number_format($beforePreminum)."</before_preminum>\n"); //경과기간보험료
	echo("\t\t<after_preminum>".number_format($totalPreminum)."</after_preminum>\n");//미경과기간보험료
  
	$thisDay2=$nabang_1+4;
	echo("\t\t<thisDay>".$thisDay2."</thisDay>\n");
	echo("\t</policy>\n");*/


	$k=$j+1;

	

	//echo "m $m ";

	//echo "$j $row[Jumin]   $row[CertiTableNum] $row[nai]  <br>";

	//echo "$k	$now_time	$row[Name]	$row[Jumin]	$row[dongbuCerti]	";
	
}  
echo "번호	보험가입일	성명	주민번호	증권번호	";	
	$p=0;
	for($_k=$nabang_1;$_k<=$nabang;$_k++){
		
			
		    if($_k==$nabang_1){
				echo "초회보험료	";
			}else{
				$p++;
			  $daumMonth=date("m",strtotime("$now_time $p month"));
			 echo "$daumMonth"."월자동출금	";
			}
		
		}
echo "합계\n";


$where="WHERE 2012DaeriCompanyNum='$cNum' AND InPutDay='$now_time' AND (push='4' or push='1')";
$sql="Select * FROM 2012DaeriMember   $where ";

//echo "sql $sql ";

$rs=mysql_query($sql,$connect);
$num=mysql_num_rows($rs);
for($j=0;$j<$num;$j++){

	$row=mysql_fetch_array($rs);

	//echo "$j $row[Jumin] $row[Name]  $row[CertiTableNum] $row[nai]  <br>";
		if($row[cancel] ==12){

			continue;
		}

	$certiTableNum=$row[CertiTableNum];
	
			$sql3="SELECT * FROM 2012CertiTable WHERE num='$certiTableNum'";

			//echo "sql $sql ";
			$rs3=mysql_query($sql3,$connect);
			$row3=mysql_fetch_array($rs3);

			//echo "startyDay $row[startyDay] "; //단체보험인 경우 시작일
			//echo "divi $row[divi]";			 // 1 정상 2. 1/12
			//echo "personal $row[personal]" ; // 1.단체 2개인

if($row3[personal]==1){
    $startyDay=$row3[startyDay];
    $nabang=$row3[nabang];
	$nabang_1=$row3[nabang_1];
	$state=$cRow[state];
	$endorseDay=$now_time;
	$InsuraneCompany=$row3[InsuraneCompany];
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


	$table = "2012Cpreminum ";
	$where =" WHERE CertiTableNum='$certiTableNum' and sPreminum<='$row[nai]' and ePreminum>='$row[nai]'";		
	$order ="order by sunso asc";
	$sql2 = "select * from " . $table . $where.$order ;

	$rs2=mysql_query($sql2,$connect);
	$row2=mysql_fetch_array($rs2);


$row[personal]=$row3[personal];
	//echo "sql2  $sql2";



	include "./ajax/php/InsdateCount.php";

	$Ypreminum=$row2[yPreminum];
//	  $dailyPreminum=round(($Ypreminum/$gigan),-1); //1일보험료
	
/*	echo("\t<policy>\n");
	echo("\t\t<startyDay>".$startyDay."~".$daumY."</startyDay>\n");
	echo("\t\t<before_gijun>".$before_gijun."</before_gijun>\n"); //경과기간
	echo("\t\t<after_gijun>".$after_gijun."</after_gijun>\n");//미경과기간
	echo("\t\t<endorseDay>".$endorseDay."~".$daumY."</endorseDay>\n");//기준일자
	echo("\t\t<s_nai>".$row2[sPreminum]."</s_nai>\n");
	echo("\t\t<e_nai>".$row2[ePreminum]."</e_nai>\n");
	echo("\t\t<yPreminum>".number_format($row2[yPreminum])."</yPreminum>\n");
	echo("\t\t<dailyPreminum><![CDATA[".number_format($dailyPreminum)."]]></dailyPreminum>\n");
	echo("\t\t<certi><![CDATA[".$DB->Recode[certi]."]]></certi>\n");
*/
	$yearPrem=$row2[yPreminum];
	
		include "./ajax/php/dayInsPreminumCount2.php";

	/*echo("\t\t<before_preminum>".number_format($beforePreminum)."</before_preminum>\n"); //경과기간보험료
	echo("\t\t<after_preminum>".number_format($totalPreminum)."</after_preminum>\n");//미경과기간보험료
  
	$thisDay2=$nabang_1+4;
	echo("\t\t<thisDay>".$thisDay2."</thisDay>\n");
	echo("\t</policy>\n");*/


	$k=$j+1;

	

	//echo "m $m ";

	//echo "$j $row[Jumin]   $row[CertiTableNum] $row[nai]  <br>";

	if($row[dongbuCerti]){
		$dongbucerti="2-20".$row[dongbuCerti]."-000";
	}
	

	$g++;
	echo "$g	$now_time	$row[Name]	$row[Jumin]	$dongbucerti	";
	 for($_k=$nabang_1;$_k<=$nabang;$_k++){

		 $m2=number_format($month_[$_k]);

			echo "$m2	";

			
		}
		$total=number_format($totalPreminum);
	echo "$total\n";

}  




//include "./ExcelContent/inWon_Excel_content.php";


?>