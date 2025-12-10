<?	
	switch($gigan){

		case 1 : //1년
			$giganP=1;
			break;
		case 2 : //2년
			$giganP=1.75;
			break;
		case 3 : //3년
			$giganP=2.5;
			break;


	}
	

	if($taie==1){
		$tSql="SELECT * FROM 2012hiautoCarPreminum WHERE  bojang='4'";
		$tRs=mysql_query($tSql,$connect);
		$tRow=mysql_fetch_array($tRs);
		$tName="보상한도액:100,000";
	}


	$sql="SELECT * FROM 2012hiautoCar WHERE num='$hiautoCarNum'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	$kind=$row[kind];//1승용 2 RV
	$size=$row[size];//1소형 2 중형 3대형


	// 
	//엔진미션수리비용보장 보험료
	$pSql="SELECT * FROM 2012hiautoCarPreminum WHERE kind='$kind' and size='$size' and bojang='1'";
	$pRs=mysql_query($pSql,$connect);
	$pRow=mysql_fetch_array($pRs);
	//일반부품 수리비용보장 보험료
	$gSql="SELECT * FROM 2012hiautoCarPreminum WHERE kind='$kind' and size='$size' and bojang='2'";
	$gRs=mysql_query($gSql,$connect);
	$gRow=mysql_fetch_array($gRs);

	$message="보험료가 산출 되었습니다!!";


	$preminum=floor((($pRow[preminum]+$gRow[preminum]+$tRow[preminum])*$giganP*$bunanb)/100)*100;


	switch($bunanb){

		case 1 : //일시납
			$bunanbP="일시납";
			$bp=1;
			break;
		case 1.02 : //2회납
			$bunanbP="2회납";
			$bp=2;
			break;
		case 1.03 : //4회납
			$bunanbP="4회납";
			$bp=4;
			break;
		case 1.0333 : //12회납
			$bunanbP="12회납";
			$bp=12;
			break;

	}
if($bunanb!=1){
	$bPrem=number_format($preminum/$bp)."씩".$bp."회로 냄";
}