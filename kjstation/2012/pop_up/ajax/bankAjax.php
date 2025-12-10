<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$DaericompanyNum=iconv("utf-8","euc-kr",$_GET['DaericompanyNum']);
	$pBankNum=iconv("utf-8","euc-kr",$_GET['pBankNum']);//메모내용

	//include "../../insuranceGubun.php";


	//계좌주인과 관리자가 동일인 인가 확인부터 하자

	$Sql4="SELECT pNumber FROM bankAccount WHERE num='$pBankNum'";
	$rs4=mysql_query($Sql4,$connect);
	$row=mysql_fetch_array($rs4);

	$dsql="SELECT MemberNum FROM 2012DaeriCompany WHERE num='$DaericompanyNum'";
	$drs=mysql_query($dsql,$connect);
	$drow=mysql_fetch_array($drs);

	if($row[pNumber]==$drow[MemberNum]){

		
			$Sql="UPDATE 2012DaeriCompany SET pBankNum='$pBankNum' WHERE num='$DaericompanyNum'";
			$rs=mysql_query($Sql,$connect);
	
			$message='입금 계좌 지정 완료!';

	}else{


			$message='계좌주와 관리자가 다릅니다 !!!';

	}


	




		echo"<data>\n";
			echo "<cname>".$dsql.$Sql."</cname>\n";
			echo "<message>".$message."</message>\n";
			
		echo"</data>";



	?>