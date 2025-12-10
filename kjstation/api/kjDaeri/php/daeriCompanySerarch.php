<?  
/*$dNum=$row['2012DaeriCompanyNum'];  */
/* 대리운전회사명 찾기*/
$sqlr="SELECT * FROM `2012DaeriCompany` WHERE num='$dNum'";
//echo $sqlr;
$rsr = mysql_query($sqlr,$conn);
$rowr =mysql_fetch_array($rsr);
	if ($rowr['company'] && is_string($rowr['company'])) {
					$rowr['company'] = @iconv("EUC-KR", "UTF-8", $rowr['company']);
	}
	$row['daeriCompany']=$rowr['company'];
	$row['dNum']=$dNum;

// 베서리스트 신청한 시간 찾기 $pNum
/*
$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$cNum' and pnum='$pNum' ";

$eRs =mysql_query($eSql,$conn);

$eRow =mysql_fetch_array($eRs);

$row['wdate']=$eRow['wdate'];*/

/*
$sqlr="SELECT * FROM 2019rate WHERE policy='$a[8]' and jumin='$a[16]'";

					//	echo $sqlr ;

		$rsr=mysql_query($sqlr,$conn);

		$rowr=mysql_fetch_array($rsr);

	$row['rate']=$rowr['rate'];*/
?>