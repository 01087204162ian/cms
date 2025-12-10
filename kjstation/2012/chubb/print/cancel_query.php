<? 

$sql="SELECT kibs_list_num,cancel_certi,new_certi,reason,wdate FROM kibs_list_cancel WHERE num='$cal_num'";

$rs=mysql_query($sql,$connect);

$row=mysql_fetch_array($rs);

$nowtime=$row['wdate'];

$cancel_certi=$row['cancel_certi'];
$new_certi=$row['new_certi'];
$reason=$row['reason'];




$kibs_list_num=$row['kibs_list_num'];

$num=$kibs_list_num;
 $sql_2="SELECT com_name,com_eur_name FROM kibs_list WHERE num='$kibs_list_num'";


//echo "sql_2 $sql_2 <br>";
$rs_2=mysql_query($sql_2,$connect);

$row_2=mysql_fetch_array($rs_2);

$com_name=$row_2['com_name'];
$com_eur_name=$row_2['com_eur_name'];












?>