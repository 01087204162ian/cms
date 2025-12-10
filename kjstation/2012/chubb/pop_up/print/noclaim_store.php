<?include '../../../../dbcon.php';



$sailing=$year."-".$month."-".$day;
//cancle_certi 와 num으로 조회 후 동일 한 것이 있으면 UPDATE,없으면 insert


	$insert_sql="INSERT INTO kibs_list_noclaim (kibs_list_num,policy,invoice,vessel,sailing,sigi,wdate,useruid) ";
    $insert_sql.="values ('$num','$policy','$invoice','$vessel','$sailing','$sigi','$start','$userid')";

//echo "insert_sql $insert_sql <br>";
	mysql_query($insert_sql,$connect);

	$sql="SELECT num FROM kibs_list_noclaim WHERE policy='$policy' and kibs_list_num='$num' ";

	//echo "sql $sql <br>";
	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);


	$num=$row['num'];
	
	//return false;

echo "<form name=back method=post action='./noclaim_print.php'>";

echo"<input type='hidden' name='num' value='$num'>";
echo"<input type='hidden' name='dt' value='1'>";

echo "<script>document.back.submit()</script>";
?>


