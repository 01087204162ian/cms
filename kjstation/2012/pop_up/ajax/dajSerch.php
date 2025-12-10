<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);
	//$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	//$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);

	
	$sql="SELECT * FROM 2013dajoong  WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	

	$a[1]=$row[Name];
	$a[2]=$row[Jumin];
	$a[3]=$row[hphone];
	$a[16]=$row[phone];
	$a[4]=$row[sigi];
	if(!$a[4]){$a[4]=$now_time;}
	$a[5]=$row[email];
	$a[6]=$row[businessNum];
	$a[7]=$row[company];
	$a[8]=$row[comKind]; //업종
	$a[9]=$row[gi]; //기초산출수
	$a[15]=$row[gi2]; //기초산출수

	$a[10]=$row[serial];
	$serial=explode("-",$a[10]);
	$c[10]=$serial[0].$serial[1].$serial[2].$serial[3];
include "./php/daP.php";
	


	$a[11]=$row[wdate];
	//$a[1]=$row[name];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];


	$a[12]=$row[dCh];//1 신청 2 팩스받음 3 .입금대기 ,4 입금확인함
if($num){
	$message='조회완료';
}else{
	$message='신규입력 하세요!! ';
}




echo"<data>\n";
	echo "<message>".$message.$a[100]."</message>\n";
	echo "<a1a>".$a[1]."</a1a>\n";
	echo "<a2a>".$a[2]."</a2a>\n";
	echo "<a3a>".$a[3]."</a3a>\n";
	echo "<a4a>".$a[4]."</a4a>\n";
	echo "<a5a>".$a[5]."</a5a>\n";
	echo "<a6a>".$a[6]."</a6a>\n";
	echo "<a7a>".$a[7]."</a7a>\n";
	echo "<a8a>".$a[8]."</a8a>\n";
	echo "<a9a>".$a[9]."</a9a>\n";
	echo "<a15a>".$a[15]."</a15a>\n";
	echo "<a10a>".$a[10]."</a10a>\n";
	echo "<a11a>".$a[11]."</a11a>\n";
	echo "<a12a>".$a[12]."</a12a>\n";
	echo "<a16a>".$a[16]."</a16a>\n";

	echo "<a28a>".$a[28]."</a28a>\n";
	echo "<a29a>".$a[29]."</a29a>\n";
	echo "<a30a>".$a[30]."</a30a>\n";

	echo "<a31a>".$pRow[daein]."</a31a>\n";
	echo "<a33a>".$pRow[daemol]."</a33a>\n";

	
	echo "<a34a>".$a[34]."</a34a>\n";//소방청정보
	echo "<a54a>".$a[54]."</a54a>\n";//소방청정보 상호
	echo "<a55a>".$a[55]."</a55a>\n";//소방청정보 주소
	echo "<a56a>".$a[56]."</a56a>\n";//소방청정보 주소

    echo "<c10c>".$c[10]."</c10c>\n";






//$inNum=$row[num];
include "./php/dajoongInsSerch.php";//보험회사 정보 조회 

	$a[4]=$a[3];//문자 표시
	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
	
echo"</data>";

	?>