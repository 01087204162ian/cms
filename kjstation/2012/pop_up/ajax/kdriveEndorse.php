<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	$id=iconv("utf-8","euc-kr",$_GET['id']);
	$val=iconv("utf-8","euc-kr",$_GET['val']);
	$date=iconv("utf-8","euc-kr",$_GET['date']);

	
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);


	$update_data=$id."="."'".$val."'";

	
			
	  
			$update="UPDATE kdriveendorse SET ";
			$update.=$update_data;
			$update.=" WHERE endorse_day='$date' and daericompanyNum='$DaeriCompanyNum'";

			//echo $update;
			mysql_query($update,$connect);
			$message="¿Ï·á!!";



		
		
			$sql="SELECT * FROM kdriveendorse WHERE endorse_day='$date' and daericompanyNum='$DaeriCompanyNum'";
			$rs= mysql_query($sql,$connect);

			$row=mysql_fetch_array($rs);

			$total_p=$row['prem1_1']-$row['prem1_2']+$row['prem2_1']-$row['prem2_2'];

			

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<message>".$message."</message>\n";
			echo "<prem1_1>".$row['prem1_1']."</prem1_1>\n";
			echo "<prem1_2>".$row['prem1_2']."</prem1_2>\n";
			echo "<prem2_1>".$row['prem2_1']."</prem2_1>\n";
			echo "<prem2_2>".$row['prem2_2']."</prem2_2>\n";
			echo "<total_p>".$total_p."</total_p>\n";
			
			
		echo"</data>";



	?>