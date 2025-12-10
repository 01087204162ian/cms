<?//배서의 개수 부터 구하기 위해
		//SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum'
	$eSql="SELECT *  FROM 2012EndorseList WHERE pnum='$eNum' and CertiTableNum='$CertiTableNum'";
	//echo "eSql $eSql <br>";
	$eRs=mysql_query($eSql,$connect);
	$eRow=mysql_fetch_array($eRs);
	$e_count=$eRow[e_count];

	if($e_count<=3){$e_count=3;}

	//상품설명서가 프린트 하기 위해 흥국화재
	$Dsql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum' and push='1' and InsuranceCompany='1'";
	$Drs=mysql_query($Dsql,$connect);
	$Dnum=mysql_num_rows($Drs);



	







?>