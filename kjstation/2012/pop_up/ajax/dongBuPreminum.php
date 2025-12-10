<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$CertiTableNum	    =iconv("utf-8","euc-kr",$_GET['dongbu2_c_num']);
	include "../../../sj/dongbu/pop_up/ajax/php/coP.php";

$endorse_day=iconv("utf-8","euc-kr",$_GET['psigi']);

$ks_search="SELECT num FROM new_dong_bu WHERE CertiTableNum='$CertiTableNum'";

//echo "sql $ks_search <Br>";
$search_rs=mysql_query($ks_search,$connect);
$search_row=mysql_fetch_array($search_rs);

$newDongbuNum=$search_row['num'];


if($newDongbuNum){
	$ks_dong="UPDATE new_dong_bu SET hphone='$phone',wdate=now(),ttime=now(), daein='$daein_3',";
	$ks_dong.="daemool='$daemool_3',jason='$jason_3',sele='$sele_3',cha='$char_3',jagibudam='$jagibudam_3', ";
	$ks_dong.="sago='$sago_3',law='$law_3',bumwi='$bumwi_3',bunnab='$bunnab_3',bohum_start='$endorse_day',daeri='$daeri',CertiTableNum='$CertiTableNum' ";
	$ks_dong.="WHERE num='$newDongbuNum'";
	mysql_query($ks_dong,$connect);
}else{

	//echo "num $num <br>";

	$ks_dong = "INSERT INTO new_dong_bu (name,jumin1,jumin2,hphone,wdate,ttime,daein,daemool,jason,sele,cha,jagibudam,sago,law,bumwi, ";
	$ks_dong .="bunnab,bohum_start,daeri,CertiTableNum) ";
	$ks_dong .="VALUES ('$c_name','$jumin1','$jumin2','$hphone',now(),now(),'$daein_3','$daemool_3','$jason_3','$sele_3', ";
	$ks_dong .="'$char_3','$jagibudam_3','$sago_3','$law_3','$bumwi_3','$bunnab_3','$endorse_day','$daeri','$CertiTableNum')";
	$ks_rs =mysql_query($ks_dong,$connect);
}
	$coDongbuP=1;//coDongbu에서 사용하기 위해 


echo"<data>\n";
	include "./CoDongbuP.php"; //dongbuAllSerch.php에서 사용
	
echo"</data>";
	?>




