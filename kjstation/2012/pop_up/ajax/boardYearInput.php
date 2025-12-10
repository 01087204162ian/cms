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

	  $sql="SELECT * FROM 2021Cpreminum WHERE CertiTableNum='$CertiTableNum' and DaeriCompanyNum='$DaeriCompanyNum' ";
	  $sql.="and sunso='$sunso' ";
	  $rs=mysql_query($sql,$connect);

	  $row=mysql_fetch_array($rs);
		//마직막에는 나이가 최고
			
	  
			$update="UPDATE 2021Cpreminum SET ";
			
			$update.="yPreminum='$preminum' ";
			$update.="WHERE num='$row[num]'";
			mysql_query($update,$connect);
			$message="완료!!";


			$m1P=round($preminum*0.2,-1);
			$m2P=round($preminum*0.1,-1);
			$m3P=round($preminum*0.05,-1);
			$m4P=round($preminum/12,-1);


		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n";
			echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";//
			echo "<preminum>".number_format($preminum)."</preminum>\n";//년간보험료
			echo "<preminum1>".number_format($m1P)."</preminum1>\n";//1회차
			echo "<preminum2>".number_format($m2P)."</preminum2>\n";//2회차
			echo "<preminum9>".number_format($m3P)."</preminum9>\n";//9회차
			echo "<preminum10>".number_format($m4P)."</preminum10>\n";///1/12
			

			echo "<sunso>".$sunso."</sunso>\n";
			
		echo"</data>";



	?>