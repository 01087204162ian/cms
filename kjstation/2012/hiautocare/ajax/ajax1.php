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
	//$kind	      =iconv("utf-8","euc-kr",$_GET['kind']);//업종

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
	/*if($kind=='99'){
		   $where3="";
		}else{
			$where3="and  comKind='$kind'";
		}*/

/*	if($damdanja=='9999'){$damdanja='';}
		$where="Where  $where2 $where3 ";
		*/  
		$where="WHERE  substring(wdate,1,10)>='$sigi' and substring(wdate,1,10)<='$end' $where2 $where3 order by num desc";
	}
	
	$sql="Select * FROM 2012hiauto   $where ";

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
	 //  $a[5]=mysql_result($result2,$count,"sigi");
	   $a[6]=mysql_result($result2,$count,"email");
	   $hiautoCarNum=mysql_result($result2,$count,"hiautoCarNum");
			//2012hiautoCar  maker,차종

			$sql="SELECT * FROM 2012hiautoCar WHERE num='$hiautoCarNum'";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);

			switch($row[maker]){
				  
					case 1 :
						$a[7]='현대';
					break;
					case 2 :
						$a[7]='기아';
					break;
					case 3 :
						$a[7]='쉐보레';
					break;
					case 4 :
						$a[7]='쌍용';
					break;
					case 5 :
						$a[7]='르노삼성';
					break;

					
				}
			switch($row[kind]){
						   
							case 1 :
								$a[8]='승용';
							break;
							case 2 :
								$a[8]='RV';
							break;
	
						}
			$a[9]=$row[carName];

			$a[20]=mysql_result($result2,$count,"a8");//차량번호
			$a[21]=mysql_result($result2,$count,"a9");//차대번호
			$a[22]=mysql_result($result2,$count,"a10");//최초등록일

	  // $a[8]=mysql_result($result2,$count,"company");


	  $juhang=mysql_result($result2,$count,"juhang");//주행거리
	  $bunanb=mysql_result($result2,$count,"bunanb");//분납
	  $gigan=mysql_result($result2,$count,"gigan");//기간1년2년3년
	  $taie=mysql_result($result2,$count,"taie");//

	  include "./php/carPreminum.php";
	   $a[16]=mysql_result($result2,$count,"policyNum");
	   $a[11]=mysql_result($result2,$count,"wdate");

		
		$wdat=explode("-",$a[11]);
		//$tdat=explode(":",$wdat[3]);

		$a[11]=$wdat[1].$wdat[2];
		//$a[11]=$wdat[3];
	   $a[12]=mysql_result($result2,$count,"dCh");//진행상황
	   $a[13]=$preminum;
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
	   echo "<a10>".$a[10]."</a10>\n";//
	   echo "<a11>".$a[11]."</a11>\n";
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".number_format($a[13])."</a13>\n";
	   echo "<a14>".$a[14]."</a14>\n";
	   echo "<a16>".$a[16]."</a16>\n";
	   echo "<a20>".$a[20]."</a20>\n";//차량번호
	   echo "<a21>".$a[21]."</a21>\n";//차대번호
	   echo "<a22>".$a[22]."</a22>\n";//최초등록일
	  

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