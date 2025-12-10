<? 

$sql="SELECT kibs_list_num,policy,invoice,vessel,sailing,sigi,wdate FROM kibs_list_noclaim WHERE num='$cal_num'";


//echo "sql $sql <br>";
$rs=mysql_query($sql,$connect);

$row=mysql_fetch_array($rs);

$nowtime=$row['wdate'];

$policy=$row['policy'];
$invoice=$row['invoice'];
$vessel=$row['vessel'];
$sailing=$row['sailing'];
$sigi=$row['sigi'];




$kibs_list_num=$row['kibs_list_num'];

$num=$kibs_list_num;
 $sql_2="SELECT com_name,com_eur_name FROM kibs_list WHERE num='$kibs_list_num'";


//echo "sql_2 $sql_2 <br>";
$rs_2=mysql_query($sql_2,$connect);

$row_2=mysql_fetch_array($rs_2);

$com_name=$row_2['com_name'];
$com_eur_name=$row_2['com_eur_name'];












?>