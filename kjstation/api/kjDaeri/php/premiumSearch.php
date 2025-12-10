<?

$mSql="SELECT monthly_premium1,monthly_premium2,monthly_premium_total  FROM kj_premium_data WHERE cNum = '$cNum' AND start_month<='$age' and end_month>='$age'";
		//	echo $mSql;
			$mrs=mysql_query($mSql,$conn);
			$mrow=mysql_fetch_array($mrs);

			//echo $mrow['monthly_premium1'];

			$monthly_premium1=$mrow['monthly_premium1']; // 월 기본보험료
			$monthly_premium2=$mrow['monthly_premium2']; // 월 특약보험료
			$monthly_premium_total=$mrow['monthly_premium_total']; // 기본+특약 합계



$mSql2="SELECT payment10_premium1,payment10_premium2,payment10_premium_total  FROM kj_insurance_premium_data WHERE policyNum = '$policyNum' AND start_month<='$age' and end_month>='$age'";
//echo $mSql2;
$mrs2=mysql_query($mSql2,$conn);
$mrow2=mysql_fetch_array($mrs2);
			$payment10_premium1=$mrow2['payment10_premium1']; // 월 기본보험료
			$payment10_premium2=$mrow2['payment10_premium2']; // 월 특약보험료
			$payment10_premium_total=$mrow2['payment10_premium_total']; // 기본+특약 합계
?>