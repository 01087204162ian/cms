<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);

	 switch($InsuraneCompany)
		{
			case 1 :
				$InscompanyN='흥국';
				
				break;
			case 2 :
				$InscompanyN='DB';
				
				break;
			case 3 :
				$InscompanyN='KB';
				
				break;
			case 4 :
				$InscompanyN='현대';
				
				break;
			case 5 :
				$InscompanyN='한화';
				
				break;
			case 7 :
				$InscompanyN='MG';
				
				break;
			case 8 :
				$InscompanyN='삼성';
				
				break;
		}
	$message="보험료 테이블!!";


	$Sql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
	$Rs=mysql_query($Sql,$connect);
	$Row=mysql_fetch_array($Rs);

	//탁송여부 확인하기 

	$eSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$eRs=mysql_query($eSql,$connect);
	$eRow=mysql_fetch_array($eRs);


	if($eRow[moValue]!=1){//모계약이 아니면  모계약의 할인율을 따라와야 한다


		

	}	
		$a[30]=$eRow[gita];

		switch($a[30]){

			case 1 :
				//$a[30]='일반';
				break;
			case 2 :
				$a[30]='탁송';
				break;
		}
		
		if($a[30]=='1'){ 
			$a[30]='';
		}else{

			$a[30]="[".$a[30]."]";
		}
	
	//나이구분하여 찾기
		$nSql="SELECT * FROM 2012Cpreminum WHERE DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$CertiTableNum' and InsuraneCompany='$InsuraneCompany' order by sunso  asc";

		//echo $nSql;
		$nRs=mysql_query($nSql,$connect);
		$mRnum=mysql_num_rows($nRs);




		echo"<data>\n";
		    //echo "<nSql>".$nSql."</nSql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<DaeriCompanyNum>".$Row[company].$a[30]."</DaeriCompanyNum>\n";
			echo "<Insurane>".$InsuraneCompany."</Insurane>\n";//
			echo "<InsuraneCompany>".$InscompanyN."</InsuraneCompany>\n";//
			echo "<moRate>".$eRow[moRate]."</moRate>\n";//
			echo "<moValue>".$eRow[moValue]."</moValue>\n";//
			echo "<mRnum>".$mRnum."</mRnum>\n";//1회차


			$Dsql="SELECT FirstStart FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
			$Drs=mysql_query($Dsql,$connect);
			$DRow=mysql_fetch_array($Drs);
			$startDay=$DRow[FirstStart];
			$SigiDay=explode("-",$startDay);

				$fstartyDay=$eRow[fstartyDay];

			//echo $startDay;
			//echo $fstartyDay;
			//2015-03-19  광명7080대리운전  각 증권별로 월 받는 날이 다름 5일자 25일자 구분 하기 

			// 동일 대리운전회사가 증권별로 보험료 정산일이 다른 경우 

			if($fstartyDay){$SigiDay[2]=$fstartyDay;}

			include "./php/NewMonthDay.php";

			echo "<FirstStartDay>".$startDay."</FirstStartDay>\n";
			echo "<SigiDay>".$SigiDay[2]."</SigiDay>\n";
			echo "<thisDay>".$thisDay."</thisDay>\n";
			echo "<ss>".$pdayOFberfore."</ss>\n";
			echo "<aPeriod>".$Period."</aPeriod>\n";
			for($_u_=0;$_u_<$Period;$_u_++){
				// echo "<smsNum".$_u_.">".$_k_."</smsNum".$_u_.">\n";
				echo "<d".$_u_.">".$p[$_u_]."</d".$_u_.">\n";//일자는 한번만 계산만 되지만 
				
			 }
			for($i=0;$i<$mRnum;$i++){

				$mRow=mysql_fetch_array($nRs);

				$m1P=round($mRow[yPreminum]*0.2,-1);
				$m2P=round($mRow[yPreminum]*0.1,-1);
				$m3P=round($mRow[yPreminum]*0.05,-1);
				$m4P=round($mRow[yPreminum]/12,-1);

				$monthsP=$mRow[mPreminum];//일별로 보험료 계산할때 필요한 월보험료 값
				include "./php/NewMonthDay.php";
			echo "<sunso".$i.">".$mRow[sunso]."</sunso".$i.">\n";//
			echo "<s".$i.">".$mRow[sPreminum]."</s".$i.">\n";//시작나이
			if($mRow[ePreminum]=='999'){$mRow[ePreminum]='';}//
			echo "<e".$i.">".$mRow[ePreminum]."</e".$i.">\n";//종료나이
			echo "<y".$i.">".number_format($mRow[yPreminum])."</y".$i.">\n";//년간보험료
			echo "<y1y".$i.">".number_format($m1P)."</y1y".$i.">\n";//초회
			echo "<y2y".$i.">".number_format($m2P)."</y2y".$i.">\n";//2회
			echo "<y9y".$i.">".number_format($m3P)."</y9y".$i.">\n";//9회
			echo "<y10y".$i.">".number_format($m4P)."</y10y".$i.">\n";//9회

			echo "<m".$i.">".number_format($mRow[mPreminum])."</m".$i.">\n";//월보험료
			echo "<daily".$i.">".number_format($mRow[dPreminum])."</daily".$i.">\n";//일보험료
				//일일보험료 
			for($_u_=0;$_u_<$Period;$_u_++){//보험료는 나이 구분에 따라 계속 예26세~30세 계산후 다음 구분나이...

				// echo "<smsNum".$_u_.">".$_k_."</smsNum".$_u_.">\n";
				
				echo "<f".$i."f".$_u_.">".$p2[$_u_]."</f".$i."f".$_u_.">\n";//
			 }
			
			}
			
			
		echo"</data>";



	?>