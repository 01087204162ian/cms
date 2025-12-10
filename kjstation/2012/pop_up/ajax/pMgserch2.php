<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

///보험회사 설계하기
	
	$CertiTableNum	    =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum	    =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	$InsuraneCompany	    =iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);
	

	$dSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
	$dRs=mysql_query($dSql,$connect);
	$dRow=mysql_fetch_array($dRs);

	//현대 선택하는 증권의  모계약 의 번호 

		$cSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

	//
	$Dsql="SELECT * FROM 2012CertiTable WHERE InsuraneCompany='$InsuraneCompany' and moValue='1' and startyDay>='$yearbefore'";
	//$Drs=mysql_query($Dsql,$connect);
	//$Dnum=mysql_num_rows($Drs);



	$result2 = mysql_query($Dsql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	///페이지 마다 20개의 Data 만 불러 온다                                            //
	/////////////////////////////////////////////////////////////////////////////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =10;
	$count = $max_num * $page - 10;
	if($total<10){
		$last=$total;
	}else{
		$last=10;
	}
	if($total-$count<10){//총개수-$congt 32-10인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;


	$tast=$last-$count;

$message="조회완료 !!";

echo"<data>\n";
	echo "<sql>".$cSql.$cRow[moNum]."</sql>\n";
	echo "<mRnum>".$Dnum."</mRnum>\n";
	echo "<company>".$dRow[company]."</company>\n";
	echo "<total>".$total."</total>\n";
	echo "<count>".$count."</count>\n";
	echo "<last>".$tast."</last>\n";
	
	for($k=$count;$k<$last;$k++){
	
		$e[1]=mysql_result($result2,$count,"2012DaeriCompanyNum");


		$e[2]=mysql_result($result2,$count,"num");

		//모계약의 num 과 e[2] 같으면 선택 아니면 비선택 
			
					if($e[2]==$cRow[moNum]){

						$e[6]=1;

					}else{

						$e[6]=2;
					}
			
		//$e[3]=mysql_result($result2,$count,"company");	
		$e[4]=mysql_result($result2,$count,"moRate");
		$e[5]=mysql_result($result2,$count,"gita");	

		$e[7]=mysql_result($result2,$count,"jagi");	
		
		

			//모계약의 대리운전회명을 찾기위해

			$nSql="SELECT * FROM 2012DaeriCompany WHERE num='$e[1]'";
			$nRs=mysql_query($nSql,$connect);
			$nRow=mysql_fetch_array($nRs);

	

		echo "<mNum>".$e[2]."</mNum>\n";//모계약의 certiNum
		echo "<mCompany>".$nRow[company]."</mCompany>\n"; 
		echo "<mRate>".$e[4]."</mRate>\n"; 
		echo "<mGita>".$e[5]."</mGita>\n";
		echo "<a6>".$e[6]."</a6>\n";
		echo "<jagi>".$e[7]."</jagi>\n";
		//echo "<certi>".$mRow[certi]."</certi>\n"; 
		$count++;
	}
	 /***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>