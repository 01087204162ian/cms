<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);
	$preminum=iconv("utf-8","euc-kr",$_GET['preminum']);

	$pr=explode(",",$preminum);
	$preminum=$pr[0].$pr[1].$pr[2];
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
	$sP=iconv("utf-8","euc-kr",$_GET['sP']);//나이시작
	$eP=iconv("utf-8","euc-kr",$_GET['eP']);//나이끝
	
	

	  //입력이 되어 있느지 여부에 따라 

	  $sql="SELECT * FROM 2012Cpreminum WHERE CertiTableNum='$CertiTableNum' and DaeriCompanyNum='$DaeriCompanyNum' ";
	  $sql.="and sunso='$sunso' ";
	  $rs=mysql_query($sql,$connect);

	  $row=mysql_fetch_array($rs);
		//마직막에는 나이가 최고
			
	  
			$update="UPDATE 2012Cpreminum SET mPreminum='$preminum' ";
			$update.="WHERE num='$row[num]'";
			mysql_query($update,$connect);
			$message="완료!!";


			$monthsP=$preminum;//월별보험료

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n";
			echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";//
			echo "<preminum>".number_format($preminum)."</preminum>\n";//월보험료
			echo "<sunso>".$sunso."</sunso>\n";

			//월보험료를 기준으로 매일매일의 보험료를 산출하여 던지기

			$Dsql="SELECT FirstStart FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
			$Drs=mysql_query($Dsql,$connect);
			$DRow=mysql_fetch_array($Drs);
			$startDay=$DRow[FirstStart];
			$SigiDay=explode("-",$startDay);
			include "./php/NewMonthDay.php";

			echo "<FirstStartDay>".$startDay."</FirstStartDay>\n";
			echo "<SigiDay>".$SigiDay[2]."</SigiDay>\n";
			echo "<thisDay>".$thisDay."</thisDay>\n";
			echo "<aPeriod>".$Period."</aPeriod>\n";
			//일일보험료 
			for($_u_=0;$_u_<$Period;$_u_++){
				// echo "<smsNum".$_u_.">".$_k_."</smsNum".$_u_.">\n";
				echo "<d".$_u_.">".$p[$_u_]."</d".$_u_.">\n";//일자
				echo "<f".$_u_.">".$p2[$_u_]."</f".$_u_.">\n";//
			 }
		echo"</data>";



	?>