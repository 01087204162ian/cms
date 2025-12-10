<?
$sql_sj="SELECT * FROM kibs_list WHERE num='$num'";

//echo "sql_sj $sql_sj <br>";
$sj_rs=mysql_query($sql_sj,$connect);


$row_sj = mysql_fetch_array($sj_rs);



$contents			=$row_sj['contents'];
$product			=$row_sj['product'];
$post				=$row_sj['post'];
$address_1			=$row_sj['address_1'];
$address_2			=$row_sj['address_2'];
$com_name			=$row_sj['com_name'];
$com_eur_name		=$row_sj['com_eur_name'];
$com_number			=$row_sj['com_number'];
$com_number_2		=$row_sj['com_number_2'];
$name_1				=$row_sj['name_1'];
$phone_1			=$row_sj['phone_1'];
$fax_1				=$row_sj['fax_1'];
$mail_1				=$row_sj['mail_1'];
$name_2				=$row_sj['name_2'];
$phone_2			=$row_sj['phone_2'];
$fax_2				=$row_sj['fax_2'];
$mail_2				=$row_sj['mail_2'];
$name_3				=$row_sj['name_3'];
$phone_3			=$row_sj['phone_3'];
$fax_3				=$row_sj['fax_3'];
$mail_3				=$row_sj['mail_3'];
$name_4				=$row_sj['name_4'];
$phone_4			=$row_sj['phone_4'];
$fax_4				=$row_sj['fax_4'];
$mail_4				=$row_sj['mail_4'];
$name_5				=$row_sj['name_5'];
$phone_5			=$row_sj['phone_5'];
$fax_5				=$row_sj['fax_5'];
$mail_5				=$row_sj['mail_5'];
$name_6				=$row_sj['name_6'];
$phone_6			=$row_sj['phone_6'];
$fax_6				=$row_sj['fax_6'];
$mail_6				=$row_sj['mail_6'];
$item_1				=$row_sj['item_1'];
$item_2				=$row_sj['item_2'];
$item_3				=$row_sj['item_3'];
$giro_1				=$row_sj['giro_1'];
$insurance_name		=$row_sj['insurance_name'];
$bank_name			=$row_sj['bank_name'];
$bank_number		=$row_sj['bank_number'];
$company_name		=$row_sj['company_name'];
$content			=$row_sj['content'];
$wdate				=$row_sj['wdate'];
$userid				=$row_sj['userid'];
$sigi				=$row_sj['sigi'];
$jeonggi			=$row_sj['jeonggi'];
$check_1				=$row_sj['check_1'];
$delivery			=$row_sj['delivery'];
$young_su			=$row_sj['young_su'];
$cord				=$row_sj['cord'];
$messenger			=$row_sj['messenger'];
$harin				=$row_sj['harin'];
$messenger_1		=$row_sj['messenger_1'];
$messenger_2		=$row_sj['messenger_2'];
$messenger_3		=$row_sj['messenger_3'];
$messenger_4		=$row_sj['messenger_4'];
$messenger_5		=$row_sj['messenger_5'];
$messenger_6		=$row_sj['messenger_6'];
$forwarder			=$row_sj['forwarder'];
$job_1				=$row_sj['job_1'];
$job_2				=$row_sj['job_2'];
$job_3				=$row_sj['job_3'];
$job_4				=$row_sj['job_4'];
$job_5				=$row_sj['job_5'];
$job_6				=$row_sj['job_6'];


//echo "messenger $messenger_1 <br>";
$list				=$row_sj['list'];
list($sigi_year, $sigi_month, $sigi_day) = explode("-", $sigi);
list($jeonggi_year, $jeonggi_month, $jeonggi_day) = explode("-", $jeonggi);

include "../../dongbu/pop_up/php/post_query.php";
?>