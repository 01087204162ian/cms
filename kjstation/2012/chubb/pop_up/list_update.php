<?include '../../../2012/lib/popup_lib_auth.php';
$sigi=$sigi_year."-".$sigi_month."-".$sigi_day;
$jeonggi=$jeonggi_year."-".$jeonggi_month."-".$jeonggi_day;
$com_eur_name=addslashes($com_eur_name);
$content=ltrim($content);
//echo "com_eur_name $com_eur_name <br>";
//echo "num $num <br>";
	$sql1 = "UPDATE kibs_list SET contents='$contents',product='$product',post='$postnum',address_1='$address1',address_2='$address_2', ";
	$sql1 .= "com_name='$com_name',com_eur_name='$com_eur_name',com_number='$com_number',com_number_2='$com_number_2',";
	$sql1 .= "name_1='$name_1',phone_1='$phone_1',fax_1='$fax_1',mail_1='$mail_1', ";
	$sql1 .= "name_2='$name_2',phone_2='$phone_2',fax_2='$fax_2',mail_2='$mail_2',";
	$sql1 .= "name_3='$name_3',phone_3='$phone_3',fax_3='$fax_3',mail_3='$mail_3', ";
	$sql1 .= "name_4='$name_4',phone_4='$phone_4',fax_4='$fax_4',mail_4='$mail_4',";
	$sql1 .= "name_5='$name_5',phone_5='$phone_5',fax_5='$fax_5',mail_5='$mail_5',";
	$sql1 .= "name_6='$name_6',phone_6='$phone_6',fax_6='$fax_6',mail_6='$mail_6',";
	$sql1 .= "item_1='$item_1',item_2='$item_2',item_3='$item_3',giro_1='$giro_1',insurance_name='$insurance_name', ";
	$sql1 .= "bank_name='$bank_name',bank_number='$bank_number',company_name='$company_name',content='$content',useruid='$userid', ";
	$sql1 .= "sigi='$sigi',jeonggi='$jeonggi',check_1='$check_1',delivery='$delivery',young_su='$young_su', ";
	$sql1 .= "list='$list',messenger_1='$messenger_1',messenger_2='$messenger_2',messenger_3='$messenger_3', ";
	$sql1 .= "messenger_4='$messenger_4',messenger_5='$messenger_5',messenger_6='$messenger_6',cord='$cord',harin='$harin', ";
	$sql1 .= "forwarder='$forwarder',job_1='$job_1',job_2='$job_2',job_3='$job_3',job_4='$job_4',job_5='$job_5',job_6='$job_6' ";
	$sql1 .= "WHERE num='$num'";

//echo "$sql1";
//return false;
$os_rs = mysql_query($sql1,$connect);

if($os_rs){
		echo "<script>";
		echo "alert('저장되었습니다.');";
		echo "self.close();";		
		echo "</script>";
}else{
		echo "<script>";
		echo "alert('뭐가 잘못되었군.');";
		echo "self.close();";		
		echo "</script>";
}
?>

