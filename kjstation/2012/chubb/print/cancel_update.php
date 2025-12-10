<?include '../../../2012/lib/popup_lib_auth.php';

//echo "num $num <Br>";
//echo "kdd $cal_num <br>";

//cancle_certi 와 num으로 조회 후 동일 한 것이 있으면 UPDATE,없으면 insert


$reason=strip_tags($reason);
	$update_sql="UPDATE kibs_list_cancel SET  ";
	$update_sql.="cancel_certi='$cancel_certi',new_certi='$new_certi',reason='$reason',wdate='$start',useruid='$userid'  ";
    $update_sql.="WHERE num='$cal_num'";


	mysql_query($update_sql,$connect);

	$sql="SELECT num FROM kibs_list_cancel WHERE cancel_certi='$cancel_certi' and kibs_list_num='$num' ";

	//echo "sql $sql <br>";
	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);


	$num=$row['num'];


echo "<form name=back method=post action='./cancel_print.php'>";

echo"<input type='hidden' name='cal_num' value='$num'>";

echo "<script>document.back.submit()</script>";
?>


