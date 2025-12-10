
<?
//설계번호 보험회사등을 조회하기 위해 



$tSql="SELECT * FROM 2013dajoong  WHERE num ='$num'";
	$tRs=mysql_query($tSql,$connect);
	$row=mysql_fetch_array($tRs);

	$b[1]=$row[inCompany];//보험회사
	$b[2]=$row[sulNum]; //설계번호
	$b[3]=$row[preminum];//보험료
	$b[4]=$row[bankName];//은행명
	$b[5]=$row[bankNum];//가상계좌	
	$b[6]=$row[policyNum];//증권번호
	$b[7]=$row[fax];//처리
	$b[8]=$row[inputDay];//계약일



if(!$b[4]){
	
	$b[4]=0;
}
if($b[8]=="0000-00-00"){

	$b[8]=$now_time;
}

	echo "<b1b>".$b[1]."</b1b>\n";//보험회사
	echo "<b2b>".$b[2]."</b2b>\n";//설계번호
	echo "<b3b>".number_format($b[3])."</b3b>\n";//보험료
	echo "<b4b>".$b[4]."</b4b>\n";//은행명
	echo "<b5b>".$b[5]."</b5b>\n";//가상계좌
	echo "<b6b>".$b[6]."</b6b>\n";//증권번호
	echo "<b7b>".$b[7]."</b7b>\n";//처리유뮤
	echo "<b8b>".$b[8]."</b8b>\n";//계약일



	?>