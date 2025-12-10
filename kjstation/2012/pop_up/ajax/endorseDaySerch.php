<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);//2012DaeriCompanyNum
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$eNum =iconv("utf-8","euc-kr",$_GET['eNum']);
	

if($num){//


	$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$CertiTableNum' and pnum='$eNum'";
	$eRs=mysql_query($eSql,$connect);

	$eRow=mysql_fetch_array($eRs);


	$endorse_day=$eRow[endorse_day];

		
	$sql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$a[1]=$row[company];
	$a[2]=$row[Pname];
	$a[3]=$row[jumin];
	$a[4]=$row[hphone];
	$a[5]=$row[cphone];
	$a[6]=$row[damdanga];
	$a[7]=$row[divi];
/*	$a[8]=$row[fax];
	$a[9]=$row[cNumber];
	$a[10]=$row[lNumber];
	$a[11]=$row[a11];
	$a[12]=$row[a12];
	$a[13]=$row[a13];
	$a[14]=$row[a14];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];*/

	

	mysql_query($sql,$connect);
	
	$message='조회 되었습니다!!';


	//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$rs=mysql_query($certiSql,$connect);
	$rCertiRow=mysql_fetch_array($rs);

	$a[14]=$rCertiRow[InsuraneCompany];//보험회사

	switch($a[14]){
		case 1 :
			$a[8]='흥국';
			break;
		case 2 :
			$a[8]='동부';
			break;
		case 3 :
			$a[8]='LiG';
			break;
		case 4 :
			$a[8]='현대';
			break;
	}
	$a[9]=$rCertiRow[policyNum];
	$a[10]=$rCertiRow[startyDay];
	

}

echo"<data>\n";
	echo "<sql>".$eSql."</sql>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<2;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	
	echo "<endorse_day>".$endorse_day."</endorse_day>\n";
	echo "<endorse_day2>".$endorse_day."</endorse_day2>\n";
	echo "<message>".$message."</message>\n";
echo"</data>";

	?>