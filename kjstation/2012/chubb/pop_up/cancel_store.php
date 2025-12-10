<?
include '../../include/lib_session.php';
include '../../../csd/include/new_dbcon.php';




//cancle_certi 와 num으로 조회 후 동일 한 것이 있으면 UPDATE,없으면 insert

$reason=strip_tags($reason);
	$insert_sql="INSERT INTO kibs_list_cancel (kibs_list_num,cancel_certi,new_certi,reason,wdate,useruid) ";
    $insert_sql.="values ('$num','$cancel_certi','$new_certi','$reason','$start','$userid')";


	mysql_query($insert_sql,$connect);

	$sql="SELECT num FROM kibs_list_cancel WHERE cancel_certi='$cancel_certi' and kibs_list_num='$num' ";

	//echo "sql $sql <br>";
	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);


	$num=$row['num'];
//return false;

echo "<form name=back method=post action='/kibs_admin/consulting/print/cancel_print.php'>";

echo"<input type='hidden' name='cal_num' value='$num'>";

echo "<script>document.back.submit()</script>";
?>


