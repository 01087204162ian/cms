<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	

	$mnum=iconv("utf-8","euc-kr",$_GET['mnum']);
	$certi=iconv("utf-8","euc-kr",$_GET['certi']);

	
	

	
			
	  
			$update="UPDATE 2012Cpreminum SET ";
			
			$update.="certi='$certi' ";
			$update.="WHERE num='$mnum'";
			mysql_query($update,$connect);
			$message="완료!!";


			


		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n";
			echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";//
			echo "<preminum>".number_format($preminum)."</preminum>\n";//년간보험료
			echo "<preminum1>".number_format($m1P)."</preminum1>\n";//1회차
			echo "<preminum2>".number_format($m2P)."</preminum2>\n";//2회차
			echo "<preminum9>".number_format($m3P)."</preminum9>\n";//9회차
			echo "<preminum10>".number_format($m4P)."</preminum10>\n";///1/12
			

			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";



	?>