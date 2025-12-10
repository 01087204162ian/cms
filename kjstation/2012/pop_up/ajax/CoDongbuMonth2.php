<?
echo "<num>".$update.$dayOFberfore.$qSql."</num>\n";
echo "<num>".$Pe."</num>\n";
echo "<num2>".$Pe2."</num2>\n";
for($_u_=0;$_u_<6;$_u_++){
	echo "<giPrem".$_u_.">".number_format($giPrem[$_u_])."</giPrem".$_u_.">\n";//일자
	echo "<gi2Prem".$_u_.">".number_format($gi2Prem[$_u_])."</gi2Prem".$_u_.">\n";//일자
	//$giPrem[$_u_]=iconv("utf-8","euc-kr",$_GET['a'.$_u_]);
	//$gi2Prem[$_u_]=iconv("utf-8","euc-kr",$_GET['a2a'.$_u_]);
}
echo "<aPeriod>".$Period."</aPeriod>\n";
echo "<sigi>".$startDay."</sigi>\n";
 for($_u_=0;$_u_<$Period;$_u_++){
	// echo "<smsNum".$_u_.">".$_k_."</smsNum".$_u_.">\n";
	echo "<d".$_u_.">".$p[$_u_]."</d".$_u_.">\n";//일자
	echo "<p".$_u_.">".$p2[$_u_]."</p".$_u_.">\n";//일반보험료26세~30세
	echo "<p2p".$_u_.">".$p3[$_u_]."</p2p".$_u_.">\n";//탁송보험료

	echo "<q".$_u_.">".$q2[$_u_]."</q".$_u_.">\n";//일반보험료31세~45세
	echo "<q2q".$_u_.">".$q3[$_u_]."</q2q".$_u_.">\n";//탁송보험료

	echo "<r".$_u_.">".$r2[$_u_]."</r".$_u_.">\n";//일반보험료46세~50
	echo "<r2r".$_u_.">".$r3[$_u_]."</r2r".$_u_.">\n";//탁송보험료


	echo "<s".$_u_.">".$s2[$_u_]."</s".$_u_.">\n";//일반보험료51세~55
	echo "<s2s".$_u_.">".$s3[$_u_]."</s2s".$_u_.">\n";//탁송보험료

	echo "<t".$_u_.">".$t2[$_u_]."</t".$_u_.">\n";//일반보험료51세~55
	echo "<t2t".$_u_.">".$t3[$_u_]."</t2t".$_u_.">\n";//탁송보험료


	echo "<u".$_u_.">".$u2[$_u_]."</u".$_u_.">\n";//일반보험료51세~55
	echo "<u2u".$_u_.">".$u3[$_u_]."</u2u".$_u_.">\n";//탁송보험료

 }


			 ?>