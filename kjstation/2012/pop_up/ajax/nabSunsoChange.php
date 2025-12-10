<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$nabsunso=iconv("utf-8","euc-kr",$_GET['nabsunso']);	
	$certiTableNum =iconv("utf-8","euc-kr",$_GET['certiTableNum']);	
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);	//몇번째인지
	
	


	//납부 회차를 upDate 한다.

		$update="UPDATE 2012CertiTable  SET nabang_1='$nabsunso' ";
		$update.="WHERE num='$certiTableNum'";
		mysql_query($update,$connect);


   //증권별 시작일 찾아서 납입상태를 

		$nSql="SELECT * FROM 2012CertiTable WHERE num='$certiTableNum'";
		$nRs=mysql_query($nSql,$connect);
		$nRow=mysql_fetch_array($nRs);



		$sigiStart=$nRow[startyDay];
		$naBang=$nabsunso;

		$inPnum=$nRow[InsuraneCompany];
		include "./php/nabState.php";//popAjaxSerch.php 공동으로 사용

		$policyNum=$nRow[policyNum];

		/////동일보험 증권을 모두 찾아서

		$iSql="SELECT * FROM 2012CertiTable WHERE policyNum='$policyNum' and InsuraneCompany='$inPnum'";
		$iRs=mysql_query($iSql,$connect);
		$iNum=mysql_num_rows($iRs);

		for($_p=0;$_p<$iNum;$_p++){

			$iRow=mysql_fetch_array($iRs);


			$iUpdate="UPDATE 2012CertiTable SET nabang_1='$nabsunso' WHERE num='$iRow[num]'";
			mysql_query($iUpdate,$connect);

		}



		///////////////////////////////////////

		$message=$nabsunso."회차를 납입하였습니다";

echo"<data>\n";
	echo "<update>".$iSql."[".$iNum."]".$update."</update>\n";//
	echo "<naColor>".$naColor."</naColor>\n";//
	echo "<naState>".$naState.$gigan."</naState>\n";//
	echo "<val>".$sunso."</val>\n";
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>