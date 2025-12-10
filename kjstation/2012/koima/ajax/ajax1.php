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

	$damdanja =iconv("utf-8","euc-kr",$_GET['damdanja']);//담당자
	if($damdanja>=3){$damdanja=9999;}//전윤선이가 
	$getDay   =iconv("utf-8","euc-kr",$_GET['getDay']);//받는날
	
	
	


	if($s_contents){
		/*if($getDay!='9999'){
		$where2="and  FirstStartDay='$getDay'";
		}

		if($damdanja!='9999'){
		$where3="and  MemberNum='$damdanja'";
		}*/

		$where="WHERE company like '%$s_contents%' $where2 $where3 order by FirstStartDay desc ";
	}else{
		if($getDay=='9999'){
		   $where2="FirstStartDay>='01'";
		}else{
			$where2="FirstStartDay='$getDay'";
		}
		if($damdanja=='9999'){
		   $where3="";
		}else{
			$where3="and  MemberNum='$damdanja'";
		}

/*	if($damdanja=='9999'){$damdanja='';}
		$where="Where  $where2 $where3 ";
		*/  
		
	}
	
	$sql="Select * FROM 2012koima   $where ";

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
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");

	   //대리기사 인원을 구하기 위해 
		$mSql="SELECT * FROM 2012DaeriMember  WHERE 2012DaeriCompanyNum='$a[1]' and push='4'";
		$mRs=mysql_query($mSql,$connect);
		$mRnum[$k]=mysql_num_rows($mRs);

	   //
	   $a[2]=mysql_result($result2,$count,"companyName");
	   $a[3]=mysql_result($result2,$count,"phone");
	   $a[4]=mysql_result($result2,$count,"division");
	   //$a[5]=mysql_result($result2,$count,"hphone");
	   //$a[6]=mysql_result($result2,$count,"cphone");
	 //  $a[7]=mysql_result($result2,$count,"damdanga");

	   //멤버 담당자를 찾기 위해 
	 // $MemberNum=mysql_result($result2,$count,"MemberNum");
	 //  $Msql="SELECT name FROM 2012Member WHERE num='$MemberNum'";
	/*   $mrs=mysql_query($Msql,$connect);
	   $mRow=mysql_fetch_array($mrs);

	   $a[7]=$mRow[name];

	   $a[8]=mysql_result($result2,$count,"FirstStartDay");

	   //돈받을 통장 
	   
		$pBankNum=mysql_result($result2,$count,"pBankNum");
		
		$pSql="SELECT bankName  FROM bankAccount WHERE num='$pBankNum'";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);

		$a[9]=$pRow[bankName];*/
	   
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$mRnum[$k]."</a10>\n";//전체인원
	   echo "<pBankNum>".$pBankNum."</pBankNum>\n";

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