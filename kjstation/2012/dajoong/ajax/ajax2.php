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
	$cokind	      =iconv("utf-8","euc-kr",$_GET['cokind']);//업종
	$sido	      =iconv("utf-8","euc-kr",$_GET['sido']);//업종


		

	
		switch($cokind){
				case 2 : //일련번호

						$where="WHERE serial='$s_contents' ";
					break;

				case 3 ://상호

				if($sido!='99'){

					$where2="and  sido='$sido'";
				}
						$where="WHERE company like '%$s_contents%' $where2 order by num desc ";

					break;
		}	
	
	$sql="Select * FROM 2013sobang   $where ";

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
	//	$mSql="SELECT * FROM 2012DaeriMember  WHERE 2012DaeriCompanyNum='$a[1]' and push='4'";
	//	$mRs=mysql_query($mSql,$connect);
	//	$mRnum[$k]=mysql_num_rows($mRs);

	   //
	   $a[2]=mysql_result($result2,$count,"sido");
	  $a[3]=mysql_result($result2,$count,"serial");

	   $ju=explode("-",$a[3]);
	   $a[3]=$ju[0].$ju[1];
	   $a[4]=mysql_result($result2,$count,"comkind");
	   
	   $a[5]=mysql_result($result2,$count,"company");
	  $a[6]=mysql_result($result2,$count,"address");
	   
	   
	   
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";//전체인원
	   echo "<a11>".$a[11]."</a11>\n";
	   echo "<a12>".$a[12]."</a12>\n";

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