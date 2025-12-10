<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$s_contents2	    =iconv("utf-8","euc-kr",$_GET['s_contents2']);
	
	$DaeriCompanyNewNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNewNum']);

	

		$where="WHERE dongbuCerti='$s_contents'  and push='4'  and 2012DaeriCompanyNum='$DaeriCompanyNewNum' $where order by jumin asc ";


		
	$sql="Select * FROM 2012DaeriMember $where ";

//	echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=0;$k<$total;$k++){
	  
		$Rown=mysql_fetch_array($result2); 
	   
		$dnum[$k]=$Rown[num];
	
		$update="UPDATE 2012DaeriMember  SET dongbuCerti='$s_contents2' WHERE num='$dnum[$k]'";
		mysql_query($update,$connect);

//echo " $k ";	echo "$update <br>";
		$message='변경 완료!!';
echo"<ks>".$update."</ks>\n";
	}
	
	
	
	echo"<message>".$message."</message>\n";
	
	
echo"</data>";



	?>