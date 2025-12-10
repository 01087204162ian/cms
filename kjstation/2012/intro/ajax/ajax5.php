<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$state	      =iconv("utf-8","euc-kr",$_GET['state']);
	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);

    $push=iconv("utf-8","euc-kr",$_GET['push']);//추가 해지 
	$damdanja =iconv("utf-8","euc-kr",$_GET['damdanja']);//담당자

	if($damdanja>=3){$damdanja=9999;}//전윤선이가 
	if($s_contents){

		if($insuranceComNum!=99){
			$where=" and InsuraneCompany='$insuranceComNum'  ";
		}else{


		}

		$sql="SELECT  *  FROM SMSData a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.company like '%$s_contents%' and push>='1' order by push asc";
		$sql.="$where ";
		
	}else{
		$where7="and endorse_day ='$sigi' ";
		if($insuranceComNum!=99){
			$where4="and insuranceCom='$insuranceComNum'  ";
		}else{
			
		}
		if($damdanja=='9999'){
			   $where3="";
			}else{
				$where3="and  damdanga='$damdanja'";
		}
		if($push!=99){
			$where2="push='$push'  ";
		}else{
			$where2="push>='1' ";
		}
			$where="WHERE $where2 $where3 $where4  $where7 order by SeqNo desc ";
		//$sql="Select * FROM SMSData      $where ";	

		$sql="SELECT  *  FROM SMSData a left join 2012Member b ";
		$sql.="ON a.damdanga = b.num $where ";
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
	$totalDaeriMember=0;
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	    $a[30]=mysql_result($result2,$count,"SeqNo");
	   $a[1]=mysql_result($result2,$count,"ssang_c_num");//2012CertiTableNum
	   $DaeriCompnayNum[$k]=mysql_result($result2,$count,"2012DaeriCompanyNum");

		//대리회사명을 찾기위해
			$dSql="SELECT company,MemberNum FROM 2012DaeriCompany WHERE num='$DaeriCompnayNum[$k]'";
			$dRs=mysql_query($dSql,$connect);
			$dRow=mysql_fetch_array($dRs);

			//담당자를 찾기위해 
			$sSql="SELECT * FROM 2012Member  WHERE num='$dRow[MemberNum]'";
			$sRs=mysql_query($sSql,$connect);
			$sRow=mysql_fetch_array($sRs);
			$a[11]=$sRow[name];

			
	   $a[2]=$dRow[company];
	   $a[3]=mysql_result($result2,$count,"company");
	 //  $a[4]=mysql_result($result2,$count,"policyNum");
	   $a[5]=mysql_result($result2,$count,"endorse_day");
	   $a[6]=mysql_result($result2,$count,"push");
	   $a[9]=mysql_result($result2,$count,"preminum");
	   $endorse_num=mysql_result($result2,$count,"endorse_num");
	   
	   switch($a[6]){
		   case 2 :
				$a[6]="해지";
			   break;
			case 4 :
				$a[6]="추가";
				break;
	   }
	   $a[7]=mysql_result($result2,$count,"2012DaeriMemberNum");

			$msql="SELECT * FROM 2012DaeriMember WHERE num='$a[7]' ";
			$mrs=mysql_query($msql,$connect);
			$mrow=mysql_fetch_array($mrs);


			switch($mrow[etag]){
				case 1 : 
					$metat="";

					break;
				case 2 :

					$metat="[탁]";
					break;
				default:
					$metat="";
					break;
			}

		$a[7]=$mrow[Name].$metat;
		$a[8]=$mrow[Jumin];	

		$a[4]=$mrow[dongbuCerti];
		

		$p_buho =$mrow[p_buho];
		//모계약을 찾기 위해 

			$qSql="SELECT * FROM 2012CertiTable  WHERE num='$mrow[CertiTableNum]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);
						$moNum=$qRow[moNum];
			if($moNum){
					//모계약의 certi번호를 찾아서 대리운전회사를 찾기위해 ....

						$rSql="SELECT * FROM 2012CertiTable  WHERE num='$moNum'";
						$rRs=mysql_query($rSql,$connect);
						$rRow=mysql_fetch_array($rRs);
						$mDaeriCompanyNum=$rRow['2012DaeriCompanyNum'];

						switch($rRow[gita]){
							case 1 :

								$metat="";
								break;
							case 2 :

								$metat="[탁]";
								break;
						}

						$moSql="SELECT * FROM  2012DaeriCompany WHERE num='$mDaeriCompanyNum'";
						$moRs=mysql_query($moSql,$connect);
						$moRow=mysql_fetch_array($moRs);

			//모계약의 certi 번호를 기사별로 저장하기 위해 

						//$UmDate="UPDATE 2012DaeriMember SET moCertiNum='$moNum' WHERE num='$a[1]'";

						//mysql_query($UmDate,$connect);
			//moCertiNum

			}	//
	   $a[12]=mysql_result($result2,$count,"get");

	   $a[10]=mysql_result($result2,$count,"joong");
	   $a[40]=mysql_result($result2,$count,"c_preminum");  // 보험회사내는 보험료

	   if($a[40]=='<INPUT style="WIDTH: 70px" id='){

			$update="UPDATE  SMSData SET c_preminum='' WHERE SeqNo='$a[30]'";
			mysql_query($update,$connect);

	   }


	  $a[50]=mysql_result($result2,$count,"jeongsan");
	    echo"<p_buho>".$p_buho ."</p_buho>\n";
	   echo "<a1>".$DaeriCompnayNum[$k]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";  
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";
	   echo "<pnum>".$endorse_num."</pnum>\n";//pnum
	   echo "<a11>".$a[11]."</a11>\n";//
	   echo "<a12>".$a[12]."</a12>\n";//받음 미수
	  //   echo "<a40>".$a[40]."</a40>\n";//보험사 보험료
	   echo "<a20>".$a[20]."</a20>\n";   
	   echo "<cNum>".$a[1]."</cNum>\n";//certiTableNum
	   echo "<pBankNum>".$pBankNum."</pBankNum>\n";
	    echo "<a30>".$a[30]."</a30>\n";  //SeqNo 
		echo "<a17>".$moRow[company]."</a17>\n";//모계약
		echo "<a40>".$a[40]."</a40>\n";//
		 echo "<a50>".$a[50]."</a50>\n";//보험사로부터 받었는지 안받었는지
		$moRow[company]='';
	   $count++;
	  
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($totalDaeriMember)."명"."</totalMember>\n";
echo"</data>";

	?>