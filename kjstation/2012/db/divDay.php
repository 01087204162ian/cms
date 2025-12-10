<?include '../../2012/lib/lib_auth.php';

 $sql="SELECT * FROM 2012DaeriCompany ";

	$rs=mysql_query($sql,$connect);

	$num=mysql_num_rows($rs);

	for($j=0;$j<$num;$j++){


		$row=mysql_fetch_array($rs);

		echo "$row[num] | $row[company] | $row[FirstStart] <br>";

	}
?>