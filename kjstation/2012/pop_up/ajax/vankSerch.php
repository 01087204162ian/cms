<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	//$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);

	
	$sql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	$control=$row[control]; //운전자 추가 가능 1 불가능 2
//
$policyNum=$row[policyNum];

$vSql="SELECT * FROM 2012Vbank  WHERE InsuraneCompany='$row[InsuraneCompany]' and policyNum='$policyNum'";
//echo "vSQl $vSql <br>";
$vRs=mysql_query($vSql,$connect);
$vRow=mysql_fetch_array($vRs);


//$Sname=$vRow[name];
$Sjumin=$vRow[jumin];
$Svank=$vRow[vbank];
$Sv_number=$vRow[v_number];


if(!$Svank){
	$Svank=$row[vbank];

}
if(!$Sv_number){
	$Sv_number=$row[v_number];

}
	//2012member에서 조회 하기위해 
	if($Sjumin){
		$jSql="SELECT * FROM  2012Member  WHERe jumin1='$Sjumin'";
		//echo" j $jSql <br>";
		$jRs=mysql_query($jSql,$connect);
		$jRow=mysql_fetch_array($jRs);
	}



	$message='조회 완료';

	switch($row[InsuraneCompany]){
		case 1 :
			$inSuranCeName='흥국';
		break;
		case 2 :
			$inSuranCeName='동부';
		break;
		case 3 :
			$inSuranCeName='LiG';
		break;
		case 4 :
			$inSuranCeName='현대';
		break;
		case 5 :
			$inSuranCeName='한화';
		break;
	}
	

	$Dsql="SELECT * FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
	$Drs=mysql_query($Dsql,$connect);
	$Drow=mysql_fetch_array($Drs);

if($policyNum){ //증권번호 해당하는 계약자 그리고 환급,카드 조회한다
	$B6b='  수정  ';
}else{
	$B6b='  저장  ';
}

echo"<data>\n";
	echo "<certiNum>".$sql.$certiNum."</certiNum>\n";
	echo "<company>".$Drow[company]."</company>\n";
	echo "<iComNum>".$row[InsuraneCompany]."</iComNum>\n";
	echo "<iCom>".$inSuranCeName."</iCom>\n";
	echo "<Sname>".$jRow[name]."</Sname>\n";
	echo "<Sjumin>".$Sjumin."</Sjumin>\n";
	echo "<pNum>".$policyNum."</pNum>\n";
	echo "<vbank>".$Svank."</vbank>\n";	
	echo "<v_number >".$Sv_number."</v_number>\n";//
	echo "<sDay>".$row[startyDay]."</sDay>\n";//
	echo "<D1>".$jRow[card1]."</D1>\n";//
	echo "<D2>".$jRow[card2]."</D2>\n";//
	echo "<D3>".$jRow[card3]."</D3>\n";//
	echo "<D4>".$jRow[bankNum]."</D4>\n";//
	echo "<message>".$message."</message>\n";
	echo "<B6b>".$B6b."</B6b>\n";

	echo "<control>".$control."</control>\n";
	
echo"</data>";

	?>