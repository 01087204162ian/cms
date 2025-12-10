<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기
	
	$CertiTableNum	    =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	//$DaeriCompanyNum	    =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	//$InsuraneCompany	    =iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);
	

	
	$Csql="SELECT * FROM 2012CertiTable  WHERE num='$CertiTableNum'";
	$Crs=mysql_query($Csql,$connect);
	$CRow=mysql_fetch_array($Crs);

	$DaeriCompanyNum=$CRow['2012DaeriCompanyNum'];
	//$dSql="SELECT * 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
	$dSql="SELECT * FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
	$dRs=mysql_query($dSql,$connect);
	$dRow=mysql_fetch_array($dRs);

	//$nSql="SELECT * FROM 2012Cpreminum WHERE DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$CertiTableNum' and InsuraneCompany='$InsuraneCompany' order by sunso  asc";
	$nSql="SELECT * FROM 2012Cpreminum WHERE  CertiTableNum='$CertiTableNum'  order by sunso  asc";
	$nRs=mysql_query($nSql,$connect);
	$mRnum=mysql_num_rows($nRs);
if($mRnum>=1){

	$message='증권번호 조회 결과입니다!!';

}else{

	
	$message='연령구분 부터 하고 증권번호 입력하셔야 합니다!!';
}
	

echo"<data>\n";
	echo "<sql>".$dSql."</sql>\n";
	echo "<mRnum>".$mRnum."</mRnum>\n";
	echo "<company>".$dRow[company]."</company>\n";
	for($j=0;$j<$mRnum;$j++){
		$mRow=mysql_fetch_array($nRs);


		echo "<mNum>".$mRow[num]."</mNum>\n";
		echo "<sPreminum>".$mRow[sPreminum]."</sPreminum>\n"; 
		echo "<ePreminum>".$mRow[ePreminum]."</ePreminum>\n"; 
		echo "<certi>".$mRow[certi]."</certi>\n"; 
	}
	
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>