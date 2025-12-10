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
	$push =iconv("utf-8","euc-kr",$_GET['push']);
	$kind =iconv("utf-8","euc-kr",$_GET['kind']);

	if($push==1){//정상인경우만

		$where2="and push='4'";
	}

	if($kind==1){ //성명으로 조회 하기 

	
			if($s_contents){
				$where="WHERE Name like '%$s_contents%' $where2 order by Jumin asc ";
			}else{
				//$where="WHERE start<='$end' order by start asc ";
				$where="order by num desc ";
			}
	}else{  //주민번호로 조회 하기 

			if($s_contents){
				$where="WHERE Jumin='$s_contents' $where2 order by Jumin asc ";
			}else{
				//$where="WHERE start<='$end' order by start asc ";
				$where="order by num desc ";
			}

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
	if($total-$count<20){//총개수-$congt 32-20인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;
echo"<data>\n";
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
	   $a[10]=mysql_result($result2,$count,"OutPutDay");

	   if($a[10]=='0000-00-00'){
			$a[10]='';
	   }
	   $a[11]=mysql_result($result2,$count,"2012DaeriCompanyNum");
	   
	    $a[13]=mysql_result($result2,$count,"CertiTableNum");
	    $a[14]=mysql_result($result2,$count,"cancel");
			 $a[29]=mysql_result($result2,$count,"Hphone");

		$a[20]=mysql_result($result2,$count,"sago");//사고

		if($a[14]==42 && $a[5]==4){
			
			$a[5]=8;//해지중이기위해서
		}
	  //대리운전회사 찾기 
		$cSql="SELECT num,company FROM  2012DaeriCompany WHERE num='$a[11]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

		$a[12]=$cRow[company];
	  /////////////////////////////////
	 //	현대화재 또는 동부화재인경우 
	  ////////////////////////////////
		 $dongbuCerti= mysql_result($result2,$count,"dongbuCerti");

	if(!$dongbuCerti){
	   //증권번호를 찾기 위해 
		$pSql="SELECT * FROM 2021CertiTable WHERE num='$a[13]'";
		$pRs=mysql_query($pSql,$connect);
		$mRow=mysql_fetch_array($pRs);

		$a[8]=$mRow[policyNum];
	}else{

		$a[8]=$dongbuCerti;
		$pSql="SELECT * FROM 2021CertiTable WHERE num='$a[13]'";
		$pRs=mysql_query($pSql,$connect);
		$mRow=mysql_fetch_array($pRs);
	}
	   ///

	   if($a[5]==4){	//정상인 경우만 	
		switch($a[7]){
			case 1 :
				$rRow[nab]='흥국';
				include "./php/naiPr1.php";
				break;
			case 2 :
				$rRow[nab]='동부';
				include "./php/naiPr2.php";

				//$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$rRow[nab]='KB';
			    include "./php/naiPr3.php";
				break;
			case 4 :
				$rRow[nab]='현대';
			    include "./php/naiPr4.php";
				break;
			case 5 :
				$rRow[nab]='한화';
			   include "./php/naiPr5.php";
				break;


			case 9 :
				$rRow[nab]='메리츠';
			   include "./php/naiPr9.php";
				break;


		}
		//$a[10]=number_format($a[10]);
}	
	
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2].'['.$a[4].']'."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";//InsuranceCompany
	   echo "<a8>".$a[8]."</a8>\n";//policyNum
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";
	   echo "<a11>".$a[11]."</a11>\n";//2012DaeriCompanyNum
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";//CertiTableNum
	   echo "<a20>".$a[20]."</a20>\n";//
echo "<a29>".$a[29]."</a29>\n";//CertiTableNum
	   
		
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