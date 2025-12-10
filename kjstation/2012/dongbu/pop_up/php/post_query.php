<?
//$ab=1:/kibs_list
$sql="SELECT num FROM print_table WHERE table_name_num='$ab' and wdate='$now_time' and table_num='$num'";

	//echo "sql $sql <br>";
	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);

	$p_check=$row['num'];


?>