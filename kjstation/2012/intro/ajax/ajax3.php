<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//state 1 실효 제외 state 2 : 포함
	//////////////////////////////////////////////////////////////////
	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$state	      =iconv("utf-8","euc-kr",$_GET['state']);

	
	//보험회사 순 정렬
	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);
	//ch값 순 정렬
	$chr=iconv("utf-8","euc-kr",$_GET['chr']);

	$damdanja=iconv("utf-8","euc-kr",$_GET['damdanja']);

	if($damdanja>=3){$damdanja=99;}//전윤선이가 
	if($s_contents){

		if($insuranceComNum!=99){
			$where2="and a.InsuranceCompany='$insuranceComNum'";
		}
		if($chr!=11){
			$where3="and a.ch='$chr'";
		}
		$sql="SELECT  *  FROM 2012EndorseList a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.company like '%$s_contents%' and";
		$sql.="(a.wdate  >='$sigi' and a.wdate  <='$end') $where2 $where3 order by a.endorse_day desc";
		
	}else{

		if($insuranceComNum!=99){
			$where2="and a.InsuranceCompany='$insuranceComNum'";
		}

		if($chr!=11){
			$where3="and a.ch='$chr'";
		}
		if($damdanja!=99){

			$whrer4="and b.MemberNum='$damdanja'";
		}
			$where="WHERE (a.wdate  >='$sigi' and a.wdate  <='$end') $where2 $where3  $whrer4 order by a.endorse_day desc ";
		//$where="order by num desc ";
		//$sql="Select * FROM 2012EndorseList    $where ";

		$sql="SELECT  *  FROM 2012EndorseList a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num $where ";
	}
	
	

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
	if($total-$count<20){//총개수-$congt 32-20인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;
echo"<data>\n";
	//echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");
	   $a[2]=mysql_result($result2,$count,"2012DaeriCompanyNum");

			//대리운전회사 찾기 
		$cSql="SELECT * FROM  2012DaeriCompany WHERE num='$a[2]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

		$a[12]=$cRow[company];

		
		//담당자를 찾기위해 
			$sSql="SELECT * FROM 2012Member  WHERE num='$cRow[MemberNum]'";
			$sRs=mysql_query($sSql,$connect);
			$sRow=mysql_fetch_array($sRs);

			$a[15]=$sRow[name];

		
	   $a[3]=mysql_result($result2,$count,"InsuranceCompany");
	   $a[4]=mysql_result($result2,$count,"CertiTableNum");
	   $a[5]=mysql_result($result2,$count,"policyNum");
	   $a[6]=mysql_result($result2,$count,"e_count");
	   $a[7]=mysql_result($result2,$count,"e_count_2");
	   $a[8]=mysql_result($result2,$count,"userid");
	   $a[9]=mysql_result($result2,$count,"wdate");
	   $a[13]=mysql_result($result2,$count,"endorse_day");
	   $a[10]=mysql_result($result2,$count,"sangtae");
	   $a[11]=mysql_result($result2,$count,"ch");
	   $a[14]=mysql_result($result2,$count,"pnum");

	   
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";//2012DaeriCompanyNum
	   echo "<a3>".$a[3]."</a3>\n";//InsuranceCompany
	   echo "<a4>".$a[4]."</a4>\n";//CertiTableNum
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[7]."/".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";
	   echo "<a11>".$a[11]."</a11>\n";
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";
	    echo "<a14>".$a[14]."</a14>\n";//pnum
		 echo "<a15>".$a[15]."</a15>\n";//pnum
		
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