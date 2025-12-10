<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$DaericompanyNum=iconv("utf-8","euc-kr",$_GET['DaericompanyNum']);//
	$memberNum=iconv("utf-8","euc-kr",$_GET['memberNum']);//메모내용

	//include "../../insuranceGubun.php";

	$Sql="UPDATE 2012DaeriCompany SET MemberNum='$memberNum' WHERE num='$DaericompanyNum'";
	$rs=mysql_query($Sql,$connect);
	

// 관리자를 지정변경 하면 통장도 바꿔라는 메세지를 알려주기 위해 ....


	$sql="SELECT pBankNum FROM 2012DaeriCompany WHERE  num='$DaericompanyNum'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	$Bsql="SELECT pNumber FROM bankAccount WHERE num='$row[pBankNum]'";

	$rs=mysql_query($Bsql,$connect);

	$row=mysql_fetch_array($rs);

	if($row[pNumber]!=$memberNum){

			$mess2="통장도 변경하세요 !!";

			$Sql="UPDATE 2012DaeriCompany SET pBankNum='' WHERE num='$DaericompanyNum'";
			$rs=mysql_query($Sql,$connect);
	}


	$message='관리자 지정 완료!';


		echo"<data>\n";
			echo "<cname>".$Bsql.$Sql."</cname>\n";
			echo "<message>".$message.$mess2."</message>\n";
			
		echo"</data>";



	?>