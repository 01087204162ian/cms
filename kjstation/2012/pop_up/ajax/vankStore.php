<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	$B0b=iconv("utf-8","euc-kr",$_GET['B0b']);//보험회사
	$B2b=iconv("utf-8","euc-kr",$_GET['B2b']);//계약자
	$B3b=iconv("utf-8","euc-kr",$_GET['B3b']);//주민번호
	$B5b=iconv("utf-8","euc-kr",$_GET['B5b']);//증권번호
	$C3b=iconv("utf-8","euc-kr",$_GET['C3b']);//가상은행
	$C4b=iconv("utf-8","euc-kr",$_GET['C4b']);//가상계좌번호

	$D1b=iconv("utf-8","euc-kr",$_GET['D1b']);//카드1
	$D2b=iconv("utf-8","euc-kr",$_GET['D2b']);//카드2
	$D3b=iconv("utf-8","euc-kr",$_GET['D3b']);//카드3
	$D4b=iconv("utf-8","euc-kr",$_GET['D4b']);//카드4



//2012Vbank 에서 증권번호와 보험료회사 조회 후 

$Sql="SELECT * FROM 2012Vbank WHERE InsuraneCompany='$B0b' and policyNum='$B5b'";

$rs=mysql_query($Sql,$connect);
$row=mysql_fetch_array($rs);

if($row[num]){

	$update="UPDATE 2012Vbank SET InsuraneCompany='$B0b',policyNum='$B5b', ";
	$update.="jumin='$B3b',vbank='$C3b',v_number='$C4b' ";
	//$update.="jumin='$B3b',vbank='$C3b'card2='$D2b',card3='$D3b', ";name='$B2b',
	//$update.="bankNum='$D4b' WHERE num='$row[num]' ";
	$update.=" WHERE num='$row[num]' ";

	mysql_query($update,$connect);
	//새로운 2101meber 에 주민번호 기준으로 카드 환급계좌를 입력하기 위해 
	$jSql="SELECT * FROM  2012Member  WHERE jumin1='$B3b'";
	$jRs=mysql_query($jSql,$connect);
	$jRow=mysql_fetch_array($jRs);

	if($jRow[num]){

			$jUpdate="UPDATE 2012Member SET ";
			$jUpdate.="card1='$D1b',card2='$D2b',card3='$D3b', ";
			$jUpdate.="bankNum='$D4b' WHERE num='$jRow[num]' ";
			
			//echo "jU $jUpdate <br>";
			mysql_query($jUpdate,$connect);
	}else{
			$jinsert="INSERT INTO  2012Member (mem_id,passwd,name,jumin1,card1,card2,card3,bankNum, ";
			$jinsert.="hphone,email,level,mail_check,wdate,inclu,kind,pAssKey,pAssKeyoPen)";
			$jinsert.="VALUES ('','','$B2b','$B3b','$D1b','$D2b','$D3b','$D4b', ";
			$jinsert.="'','','','','','','','','')";
			//echo "insert $insert <br>";
			mysql_query($jinsert,$connect);
	}

	
}else{

	$insert="INSERT INTO  2012Vbank (InsuraneCompany,policyNum,jumin,vbank,v_number )";
	$insert.="VALUES ('$B0b','$B5b','$B3b','$C3b','$C4b')";
	
	mysql_query($insert,$connect);


	mysql_query($update,$connect);
	//새로운 2101meber 에 주민번호 기준으로 카드 환급계좌를 입력하기 위해 
	$jSql="SELECT * FROM  2012Member  WHERE jumin1='$B3b'";
	$jRs=mysql_query($jSql,$connect);
	$jRow=mysql_fetch_array($jRs);

	if($jRow[num]){

			$jUpdate="UPDATE 2012Member SET ";
			$jUpdate.="card1='$D1b',card2='$D2b',card3='$D3b' ";
			$jUpdate.="bankNum='$D4b' WHERE num='$jRow[num]' ";
			
			echo "jU2 $jUpdate <br>";
			mysql_query($jUpdate,$connect);
	}else{
			$jinsert="INSERT INTO  2012Member (mem_id,passwd,name,jumin1,card1,card2,card3,bankNum, ";
			$jinsert.="hphone,email,level,mail_check,wdate,inclu,kind,pAssKey,pAssKeyoPen)";
			$jinsert.="VALUES ('','','$B2b','$B3b','$D1b','$D2b','$D3b','$D4b', ";
			$jinsert.="'','','','','','','','','')";
			//echo "insert $insert <br>";
			mysql_query($insert,$connect);
	}

}

$message='처리 완료!!';


echo"<data>\n";
	echo "<certiNum>".$CertiTableNum."</certiNum>\n";
	echo "<company>".$update."</company>\n";
	echo "<iCom>".$insert."</iCom>\n";
	echo "<pNum>".$row[policyNum]."</pNum>\n";
	echo "<vbank>".$row[vbank]."</vbank>\n";	
	echo "<v_number >".$row[v_number]."</v_number>\n";//
	echo "<sDay>".$row[startyDay]."</sDay>\n";//
	echo "<message>".$message."</message>\n";
	echo "<B6b>".$B6b."</B6b>\n";
	
echo"</data>";
	?>