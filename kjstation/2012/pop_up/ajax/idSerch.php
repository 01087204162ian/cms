<?php
   include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$num=iconv("utf-8","euc-kr",$_GET['num']);
	

	$Csql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$Crs=mysql_query($Csql,$connect);
	$Crow=mysql_fetch_array($Crs);

	$a[1]=$Crow[company];

	$iSql="SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum='$num'";
	$iRs=mysql_query($iSql,$connect);
	$iRow=mysql_fetch_array($iRs);

	$a[0]=$iRow[num];
	$a[2]=$iRow[mem_id];

	$a[3]=$Crow[hphone];
	
	$a[5]=$iRow[permit];

	
	if($a[0]){
		$a[6]='수정';
	}else{
		$a[6]='저장';
	}
	
		$message='조회완료!!';
	
		echo"<data>\n";
		echo "<sql>".$Csql.$iSql."</sql>\n";//
		for($_u_=0;$_u_<7;$_u_++){
		   echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		 }
		echo "<message>".$message."</message>\n";
		echo"</data>";



	?>