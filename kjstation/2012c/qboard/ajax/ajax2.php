<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$state	      =iconv("utf-8","euc-kr",$_GET['state']);
	$cNum	      =iconv("utf-8","euc-kr",$_GET['cNum']);
	

	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);
	if($insuranceComNum!=99){
			$where2="and InsuranceCompany='$insuranceComNum'";
	}
	if($s_contents){
		$where="WHERE Name like '%$s_contents%' $where order by InPutDay asc ";
	}else{
		//$where="WHERE start<='$end' order by start asc ";
		$where="WHERE    sangtae='1' and 2012DaeriCompanyNum='$cNum' $where2 order by Jumin  asc";	
	}
	
	$sql="Select * FROM 2021DaeriMember    $where ";

//	echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	///페이지 마다 20개의 Data 만 불러 온다                                            //
	/////////////////////////////////////////////////////////////////////////////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =20;
	$count = $max_num * $page - 20;
	if($total<20){
		$last=$total;
	}else{
		$last=20;
	}
	$last=$count+$last;
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");
	   $a[2]=mysql_result($result2,$count,"Name");
	   $a[3]=mysql_result($result2,$count,"Jumin");
	   $a[4]=mysql_result($result2,$count,"nai");
	   $a[5]=mysql_result($result2,$count,"push");
	   $a[6]=mysql_result($result2,$count,"etag");

		if(!$a[6]){$a[6]=1;}//a[6] 없을 경우 일반으로 본다
	   $a[7]=mysql_result($result2,$count,"InsuranceCompany");
	   $a[9]=mysql_result($result2,$count,"InPutDay");
	   $a[10]=mysql_result($result2,$count,"dongbuSelNumber");

	    $a[14]=mysql_result($result2,$count,"ch");

	   if($a[10]=='0000-00-00'){
			$a[10]='';
	   }
	   $a[11]=mysql_result($result2,$count,"2012DaeriCompanyNum");
	   
	    $a[13]=mysql_result($result2,$count,"CertiTableNum");
	    $a[42]=mysql_result($result2,$count,"cancel");

		if($a[42]==42 && $a[5]==4){
			
			$a[5]=8;//해지중이기위해서
		}
	  //대리운전회사 찾기 
		$cSql="SELECT * FROM  2012DaeriCompany WHERE num='$a[11]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

		$a[12]=$cRow[company];
	  /////////////////////////////////
	 //	현대화재 또는 동부화재인경우 
	  ////////////////////////////////
		 $a[8]= mysql_result($result2,$count,"dongbuCerti");



		$a[15]=$cRow[MemberNum];

		$mSql="SELECT * FROM 2012Member WHERE num='$a[15]'";

		//echo $mSql;
		$mRs=mysql_query($mSql,$connect);
		$mRow=mysql_fetch_array($mRs);

		$a[15]=$mRow[name];


	/*if(!$dongbuCerti){
	   //증권번호를 찾기 위해 
		$pSql="SELECT policyNum FROM 2021CertiTable WHERE num='$a[13]'";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);

		$a[8]=$pRow[policyNum];
	}else{

		$a[8]=$dongbuCerti;
	}*/
	   ///

	   $eNum=mysql_result($result2,$count,"EndorsePnum");// 배서번호


	   $eSql="SELECT * FROM 2021EndorseList WHERE pnum='$eNum' and  CertiTableNum='$a[13]'";
	   //echo "eSql $eSql <Br>";
	   $eRs=mysql_query($eSql,$connect);
	   $eRow=mysql_fetch_array($eRs);

		
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2].'['.$a[4].']'."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";//InsuranceCompany
	   echo "<a8>".$a[8]."</a8>\n";//policyNum
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";//설계번호 동부화재
	   echo "<a11>".$a[11]."</a11>\n";//2021DaeriCompanyNum
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";//CertiTableNum
	   echo "<a14>".$a[14]."</a14>\n";//CertiTableNum
	   echo "<eNum>".$eNum."</eNum>\n"; //eNum
	   echo "<endorseDay>".$eRow[endorse_day]."</endorseDay>\n";
	    echo "<a15>".$a[15]."</a15>\n";//CertiTableNum
	   
		
	   $count++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($TotalMember)."명"."</totalMember>\n";
echo"</data>";

	?>