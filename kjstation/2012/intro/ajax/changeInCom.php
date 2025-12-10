<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$driverNum=iconv("utf-8","euc-kr",$_GET['memberNum']);
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);


include '../../../dbcon.php';



	//$CertiTableNum 으로 증권번호찾기

	$Sql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$rs=mysql_query($Sql,$connect);
	$row=mysql_fetch_array($rs);

	$policyNum=$row[policyNum];
	$InsuraneCompany=$row[InsuraneCompany];

		$update="UPDATE 2012DaeriMember  SET dongbuCerti='$policyNum',InsuranceCompany='$InsuraneCompany',CertiTableNum='$CertiTableNum' WHERE num='$driverNum'";
		mysql_query($update,$connect);
		$message='증권번호 입력 완료!!';


	

  echo"<data>\n";
		echo "<phone>".$update."</phone>\n";
		echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
		echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";
		echo "<driverNum>".$driverNum."</driverNum>\n";
		echo "<sunso>".$sunso."</sunso>\n";
		echo "<policyNum>".$policyNum."</policyNum>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>