<?
	
	$sql="SELECT num FROM print_table WHERE table_name_num='$ab' and wdate='$now_time' and table_num='$num'";

	//echo "sql $sql <br>";
	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);

	$se_num=$row['num'];

	 if($se_num){

		$update="UPDATE print_table SET wdate=now() WHERE num='$se_num'";

		//echo "kup $update <Br>";
		
		$rs=mysql_query($update,$connect);
	 }else{
		$insert_sql="INSERT INTO print_table(num,wdate,table_name_num,table_num ) ";
		$insert_sql.="values ('',now(),'$ab','$num')";
		//echo "ins $insert_sql <br>";
		$rs=mysql_query($insert_sql,$connect);


	 }

?>