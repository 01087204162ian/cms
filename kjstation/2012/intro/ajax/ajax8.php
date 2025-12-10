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
		/*if($getDay=='9999'){
		   $where2="FirstStartDay>='01'";
		}else{
			$where2="FirstStartDay='$getDay'";
		}*/
		if($damdanja=='9999'){
		   $where3="";
		}else{
			$where3=" MemberNum='$damdanja' and FirstStartDay>='01'";
		}

	if($damdanja=='9999'){$damdanja='';}
		$where="Where  $where2 $where3 ";
		  
		
	}
	
	$sql="Select * FROM 2012DaeriCompany   $where   order by FirstStartDay asc ";

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
	   $a[2]=mysql_result($result2,$count,"company");
	   $a[3]=mysql_result($result2,$count,"Pname");
	   $a[4]=mysql_result($result2,$count,"jumin");
	   $a[5]=mysql_result($result2,$count,"hphone");
	   $a[6]=mysql_result($result2,$count,"cphone");
	 //  $a[7]=mysql_result($result2,$count,"damdanga");

	   //멤버 담당자를 찾기 위해 
	  $MemberNum=mysql_result($result2,$count,"MemberNum");
	   $Msql="SELECT name FROM 2012Member WHERE num='$MemberNum'";
	   $mrs=mysql_query($Msql,$connect);
	   $mRow=mysql_fetch_array($mrs);

	   $a[7]=$mRow[name];

	   $a[8]=mysql_result($result2,$count,"FirstStartDay");

	   //돈받을 통장 
	   
		$pBankNum=mysql_result($result2,$count,"pBankNum");
		
		$pSql="SELECT bankName  FROM bankAccount WHERE num='$pBankNum'";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);

		$a[9]=$pRow[bankName];

		//증권별보험료를 구하기 대리운전회사로부터 받는 보험료 산출

	

		$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$a[1]' and startyDay>='$yearbefore' order by InsuraneCompany asc ";
	    $rs=mysql_query($certiSql,$connect);
	    $cNum=mysql_num_rows($rs);

		for($j=0;$j<$cNum;$j++){

			$rCertiRow=mysql_fetch_array($rs);

	        $pQsQl="SELECT * FROM 2012Cpreminum WHERE CertiTableNum='$rCertiRow[num]' order by sunso asc";
			$pRs=mysql_query($pQsQl,$connect);
			$pNum=mysql_num_rows($pRs);

			for($_j=0;$_j<$pNum;$_j++){// 

				$pRow=mysql_fetch_array($pRs);
					
					
					//$pRow[sPreminum]
					$where="(nai>='$pRow[sPreminum]' and nai<='$pRow[ePreminum]')";
					$pRow[sPreminum]=$pRow[sPreminum].'세~';
					$pRow[ePreminum]=$pRow[ePreminum].'세';
					if($pRow[ePreminum]==999){$pRow[ePreminum]='';}
					$nai[$_j]=$pRow[sPreminum].$pRow[ePreminum];
					$etag[$_j]='일반';
						//$ewhere="and (etag='' or etag='1')";
					$monThP[$_j]=$pRow[mPreminum];

			
				if($nai[$_j]){
					$sql2="SELECT num FROM 2012DaeriMember  WHERE $where and push='4' and  CertiTableNum='$rCertiRow[num]' $ewhere ";

					//echo "sql $sql2 <bR>";
					$rs2=mysql_query($sql2);

					 $inwon[$_j]=mysql_num_rows($rs2);//인원

					 
				}
					// echo "$_j $inwon[$_j] <Br>";
					 $naiName[$_j]=$nai[$_j];//나이
					 $toPm[$_j]=$inwon[$_j]*$monThP[$_j]; //인원X월보험료
					
					 $certiInWon+=$inwon[$_j];
					 $certiInPtotal+=$toPm[$_j]; //대리운전회사로 부터 받는 보험료합
			}

		}//증권별보험료를 구하기 대리운전회사로부터 받는 보험료 산출

		//보험회사에 내는 보험료 산출을 위해 

				
	/*	$iSql="SELECT distinct dongbuCerti  FROM 2012DaeriMember  WHERE 2012DaeriCompanyNum='$a[1]' and push='4'";

		
		$iRs=mysql_query($iSql,$connect);
		$iNum=mysql_num_rows($iRs);

		for($_t=0;$_t<$iNum;$_t++){

			$iRow=mysql_fetch_array($iRs);

			$t_sql="SELECT * FROM 2012Certi WHERE certi='$iRow[dongbuCerti]'";
			$t_rs=mysql_query($t_sql,$connect);
			$t_row=mysql_fetch_array($t_rs);

			$preminun25=$t_row[preminun25]*10/12;
			$i_mSql="SELECT * FROM  2012DaeriMember  WHERE  dongbuCerti='$iRow[dongbuCerti]' and push='4' and 2012DaeriCompanyNum='$a[1]' and (nai>='19' and nai<='25')";
			$i_mRs=mysql_query($i_mSql,$connect); //증권별 인원
			$i_num=mysql_num_rows($i_mRs);

			//보험회사에 내는 보험료를 찾깅 ㅟ해 
			$c_inWonP25=$i_num*$preminun25;

			$preminun44=$t_row[preminun44]*10/12;
			$i_mSql="SELECT * FROM  2012DaeriMember  WHERE  dongbuCerti='$iRow[dongbuCerti]' and push='4' and 2012DaeriCompanyNum='$a[1]' and (nai>='26' and nai<='44')";
			$i_mRs=mysql_query($i_mSql,$connect); //증권별 인원
			$i_num=mysql_num_rows($i_mRs);

			//보험회사에 내는 보험료를 찾깅 ㅟ해 
			$c_inWonP44=$i_num*$preminun44;


			$preminun49=$t_row[preminun49]*10/12;
			$i_mSql="SELECT * FROM  2012DaeriMember  WHERE  dongbuCerti='$iRow[dongbuCerti]' and push='4' and 2012DaeriCompanyNum='$a[1]' and (nai>='45' and nai<='49')";
			$i_mRs=mysql_query($i_mSql,$connect); //증권별 인원
			$i_num=mysql_num_rows($i_mRs);

			//보험회사에 내는 보험료를 찾깅 ㅟ해 
			$c_inWonP49=$i_num*$preminun49;


			$preminun50=$t_row[preminun50]*10/12;
			$i_mSql="SELECT * FROM  2012DaeriMember  WHERE  dongbuCerti='$iRow[dongbuCerti]' and push='4' and 2012DaeriCompanyNum='$a[1]' and (nai>='50' and nai<='99')";
			$i_mRs=mysql_query($i_mSql,$connect); //증권별 인원
			$i_num=mysql_num_rows($i_mRs);

			//보험회사에 내는 보험료를 찾깅 ㅟ해 
			$c_inWonP50=$i_num*$preminun50;



			$c_P=$c_inWonP25+$c_inWonP44+$c_inWonP49+$c_inWonP50;

		}*/
	   
	   echo "<a1>".$preminun44.$a[1]."</a1>\n";
	   echo "<a2>".$i_num.$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$mRnum[$k]."</a10>\n";//전체인원
	   echo "<a11>".number_format($certiInPtotal)."</a11>\n";  // 대리운전회사로 부터 받는 보험료합
	   echo "<a12>".$c_P."</a12>\n";  // 대리운전회사로 부터 받는 보험료합
	   echo "<pBankNum>".$pBankNum."</pBankNum>\n";

	   $count++;
	   $certiInWon='';
	   $certiInPtotal='';
	   $c_P='';
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