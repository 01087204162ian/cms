<?
  $Ypreminum=$pRow[yPreminum];//$startyDay증권의시작일 $nabang//분납$nabang_1=$row3['nabang_1'];;//납입회차 $endorseDay 현재날자

  $daumY=date("Y-m-d ",strtotime("$startyDay +1 year"));
  //$gigan=
  
  $gigan=(strtotime("$daumY")-strtotime("$startyDay"))/60/60/24; //보험기간

  $before_gijun=(strtotime("$endorseDay")-strtotime("$startyDay"))/60/60/24; //경과기간

  $after_gijun=$gigan-$before_gijun;//미경과기간


  $dailyPreminum=round(($Ypreminum/$gigan),-1);


  //미경과기간에 대한 보험료 
  // 미경과기간X1일보험료
  
	$yearPrem=$Ypreminum;
	$InsuraneCompany=$DB->Recode[InsuranceCompany];

	//echo " $InsuraneCompany <Br>";
    //echo "$yearPrem <br>";

	//echo " nabang $nabang_1 <br>";
	include "./php/dayInsPreminumCount.php";

	//$yp가 보험회사에 낼보험료 
  ?>