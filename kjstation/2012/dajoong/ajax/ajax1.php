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
	$kind	      =iconv("utf-8","euc-kr",$_GET['kind']);//업종

	$damdanja =iconv("utf-8","euc-kr",$_GET['damdanja']);//담당자
	if($damdanja>=3){$damdanja=9999;}//전윤선이가 
	$jin   =iconv("utf-8","euc-kr",$_GET['jin']);////진행상태
	

	if($s_contents){
		/*if($getDay!='9999'){
		$where2="and  FirstStartDay='$getDay'";
		}

		if($damdanja!='9999'){
		$where3="and  MemberNum='$damdanja'";
		}*/

		$where="WHERE Name like '%$s_contents%' $where2 $where3 order by num desc ";
	}else{
		if($jin=='99'){
		   $where2="";
		}else{
			$where2=" and dCh='$jin'";
		}
		if($kind=='99'){
		   $where3="";
		}else{
			$where3="and  comKind='$kind'";
		}

/*	if($damdanja=='9999'){$damdanja='';}
		$where="Where  $where2 $where3 ";
		*/  
		$where="WHERE  substring(wdate,1,10)>='$sigi' and substring(wdate,1,10)<='$end' $where2 $where3 order by num desc";
	}
	
	$sql="Select * FROM 2013dajoong   $where ";

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

	   //대리기사 인원을 구하기 위해 
		$mSql="SELECT * FROM 2012DaeriMember  WHERE 2012DaeriCompanyNum='$a[1]' and push='4'";
		$mRs=mysql_query($mSql,$connect);
		$mRnum[$k]=mysql_num_rows($mRs);

	   //
	   $a[2]=mysql_result($result2,$count,"Name");
	   $a[3]=mysql_result($result2,$count,"Jumin");

	   $ju=explode("-",$a[3]);
	   $a[3]=$ju[0].$ju[1];
	   $a[4]=mysql_result($result2,$count,"hphone");
	   $a[5]=mysql_result($result2,$count,"sigi");
	   $a[6]=mysql_result($result2,$count,"email");
	   $a[7]=mysql_result($result2,$count,"businessNum");
	   $a[8]=mysql_result($result2,$count,"company");
	   $a[9]=mysql_result($result2,$count,"comKind");
	   switch($a[9]){

				case '1' :
				     $a[9]='전화방업(화상대화방업)';
						break;
				case '2' :
					$a[9]='비디오감상실';
					break;
				case '3' :
					$a[9]='비디오소극장';
					break;
				case '4' :
					$a[9]='수면방업';
					break;
				case '5' : 
					$a[9]='산후조리원';
					break;
				case '6' :
					$a[9]='스크린골프장';
					break;
				case '7' :
					$a[9]='노래방';
					break;
				case '8' :
				    $a[9]='유흥주점';
					break;
				case '9' :
					$a[9]='콜라텍';
					break;
				case '10' :
					$a[9]='안마시술소';
					break;
				case '11' :
					$a[9]='영화상영관';
					break;
				case '12' :
					$a[9]='학원';
					break;
				case '13' :
					$a[9]='목욕탕(찜질방)';
					break;
				case '14' :
					$a[9]='고시원';
					break;
				case '15' :
					$a[9]='일반음식점';
					break;
				case '16' :
					$a[9]='휴게음식점';
					break;
				case '17' :
					$a[9]='제과점';
					break;
				case '18' :
					$a[9]='PC방';
					break;
				case '19' :
					$a[9]='게임제공업';
					break;
				case '20' :
					$a[9]='복합유통제공업';
					break;	
				case '21' :
					$a[9]='실내권총사격장';
					break;	



	   }
	   $a[10]=mysql_result($result2,$count,"serial");
	   $serial=explode("/",$a[10]);

	   $a[10]=$serial[1].$serial[2].$serial[3];
	   $a[16]=mysql_result($result2,$count,"policyNum");
	   $a[11]=mysql_result($result2,$count,"wdate");

		
		$wdat=explode("-",$a[11]);
		//$tdat=explode(":",$wdat[3]);

		$a[11]=$wdat[1].$wdat[2];
		//$a[11]=$wdat[3];
	   $a[12]=mysql_result($result2,$count,"dCh");//진행상황
	   $a[13]=mysql_result($result2,$count,"preminum");//
	   $a[14]=mysql_result($result2,$count,"fax");//
	   
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
	   echo "<a13>".number_format($a[13])."</a13>\n";
	   echo "<a14>".$a[14]."</a14>\n";
	    echo "<a16>".$a[16]."</a16>\n";//전체인원

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