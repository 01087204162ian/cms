<?$sql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
//echo "Sql $sql <bR>";
$ks=mysql_query($sql,$connect);
$krow=mysql_fetch_array($ks);
//$start=$krow[start];
$company=$krow[company];


//$sql2="SELECT startyDay FROM 2012CertiTable WHERE num='$CertiTableNum'";
//$rs2=mysql_query($sql2,$connect);
//$row=mysql_fetch_array($rs2);


$start=$krow[FirstStart];
//echo "start $start <BR>";
    $bnai[0]="26技~30技";
	$bnai[1]="31技~45技";
	$bnai[2]="46技~50技";
	$bnai[3]="51技~55技";
	$bnai[4]="56技~60技";
	$bnai[5]="61技 捞惑";

	$nai[10]= date("Y-m-d ",strtotime("$now_time -61 year"));//60技 惑
	$nai[9]= date("Y-m-d ",strtotime("$nai[10] - 1day"));//60技
	$nai[8]= date("Y-m-d ",strtotime("$now_time -56 year"));//56技
	$nai[7]= date("Y-m-d ",strtotime("$nai[8] - 1day"));//55技
	$nai[6]= date("Y-m-d ",strtotime("$now_time -51 year"));//51技
	$nai[5]= date("Y-m-d ",strtotime("$nai[6] - 1day"));//50技
	$nai[4]= date("Y-m-d ",strtotime("$now_time -46 year"));//46技
	$nai[3]= date("Y-m-d ",strtotime("$nai[4] - 1day"));//45技
	$nai[2]= date("Y-m-d ",strtotime("$now_time -31 year"));//31技
	$nai[1]= date("Y-m-d ",strtotime("$nai[2] - 1day"));//30技
	$nai[0]= date("Y-m-d ",strtotime("$now_time -26 year"));//26技

	//echo $bnai[1];
	?>