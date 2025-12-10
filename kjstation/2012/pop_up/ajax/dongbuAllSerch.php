<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$CertiTableNum	    =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);

	$DaeriCompanyNum	    =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);

	$Dsql="SELECT FirstStart FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
	$Drs=mysql_query($Dsql,$connect);
	$DRow=mysql_fetch_array($Drs);
	$startDay=$DRow[FirstStart];
	$SigiDay=explode("-",$startDay);


	$ks_search="SELECT * FROM new_dong_bu WHERE CertiTableNum='$CertiTableNum'";

	//echo "sql $ks_search <Br>";
	$search_rs=mysql_query($ks_search,$connect);
	$search_row=mysql_fetch_array($search_rs);
	$daein=daein.$search_row[daein];
	$daemool=daemool.$search_row[daemool];
	$jason=jason.$search_row[jason];
if($search_row[jagibudam]==1){
	switch($search_row[cha]){
		case 1 :
			$char=char."1";
			break;
		case 2 :
			$char=char."2";
			break;
		case 3:
			$char=char."3";
			break;

	}
	
}else{
	switch($search_row[cha]){
		case 1 :
			$char=char."4";
			break;
		case 2 :
			$char=char."5";
			break;
		case 3 :
			$char=char."6";
			break;

	}

}

if($search_row[bohum_start]){
	$endorse_day=$search_row[bohum_start];
}else{
	$endorse_day=$now_time;
}
	


echo"<data>\n";
	
	echo "<daein>".$search_row[daein]."</daein>\n";
	echo "<daemool>".$search_row[daemool]."</daemool>\n";
	echo "<jason>".$search_row[jason]."</jason>\n";
	echo "<char>".$search_row[cha]."</char>\n";
	echo "<jagibudam>".$search_row[jagibudam]."</jagibudam>\n";
	echo "<psigi>".$endorse_day."</psigi>\n";
//echo "sigiwww $sigi ";
	include "./CoDongbuMonth.php";//donbu2MonthPrem.php 공통사용 2012-01-01

	include "./CoDongbuP.php";//dongbuBuPrminum.php 공통사용 2012-01-01	
	include "./CoDongbuMonth2.php";//donbu2MonthPrem.php공통사용 2012-01-01
	$message='조회완료';
	echo "<thisDay>".$thisDay."</thisDay>\n";
	echo "<message>".$message."</message>\n";
echo"</data>";
	?>




