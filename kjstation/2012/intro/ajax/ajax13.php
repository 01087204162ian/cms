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
	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);

	$you=iconv("utf-8","euc-kr",$_GET['you']); //유예기간

	$manGi=iconv("utf-8","euc-kr",$_GET['manGi']); //만기 
	

	$beforeYear=explode("-",$yearbefore);//1년전
	$magGi=$beforeYear[0]."-".$manGi;
	$magGi2=$beforeYear[0];


 //    $sql="SELECT distinct(policyNum),InsuraneCompany ";
//	$sql.="FROM  2012CertiTable  WHERE   startyDay>='$yearbefore' and InsuraneCompany='3' ";
//	$sql.="FROM  2012CertiTable  WHERE   startyDay>='$yearbefore' and InsuraneCompany!='2' ";
	//$sql.="FROM  2012CertiTable  WHERE   substring(policyNum ,1,4)>='$magGi2'  ";
//	$sql.="order by InsuraneCompany asc ";

$sql="SELECT * FROM 2012Certi WHERE sigi>='$yearbefore' order by insurance desc ";
	$rs=mysql_query($sql,$connect);

//	echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	//$total+=1;
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
	//echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){

		$m[1]=	mysql_result($result2,$count,"certi");

		$d=explode("-",$m[1]);
		$a[20]=$m[1];
		//if(!$m[1]){ continue;}

	//	$m[6] = mysql_result($result2,$count,"InsuraneCompany");
		
		
		$aSql="SELECT * FROM 2012Certi WHERE certi='$m[1]'";
		//ECHO $aSql;
		$sRs=mysql_query($aSql,$connect);
		$aRow=mysql_fetch_array($sRs);
	   $a[1]=$aRow[company];
	   $a[2]=$aRow[name];
	   $a[3]=$aRow[jumin];
	   $a[4]=$aRow[sigi];
	   if($a[4]=="0000-00-00"){
		 $a[4]='';
	   }
	   $a[5]=$aRow[nab];
	   $a[6]=$aRow[cord];
	   $a[7]=$aRow[cordPass];
	   $a[8]=$aRow[cordCerti];
	   //$a[9]=$aRow[phone];
	   $a[10]=$aRow[fax];
	   $m[4]=	$aRow[yearRate];
	   $m[5]=	$aRow[harinRate];

	   $a[9]=	$aRow[maxInwon];

	   	$a[11]=	$aRow[owner];

		switch($a[11]){
			case 1 :
				$a_11="오성준";
				break;
			case 2 :
				$a_11="이근재";
				break;
		}


	   $a[10]=$aRow[insurance]; //보험사

	   $sql="SELECT  *  FROM 2012DaeriMember Where dongbuCerti='$m[1]' and push='4'";
		$rs=mysql_query($sql,$connect);
		$rNum=mysql_num_rows($rs);
		$totalDaeriMember+=$rNum;
	   echo "<m1>".$m[1]."</m1>\n";
	   echo "<m3>".$rNum."</m3>\n";
	   echo "<m4>".$m[4]."</m4>\n";
	   echo "<m5>".$m[5]."</m5>\n";
	    echo "<m6>".$a[10]."</m6>\n";
	   echo "<num>".$aRow[num]."</num>\n";
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n"; 
	   echo "<a7>".$a[7]."</a7>\n";
	   echo "<a8>".$a[8]."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	  // echo "<a10>".$a[10]."</a10>\n";

	   echo "<a_11>".$a_11."</a_11>\n";

	   //echo "<a11>".$a[11]."</a11>\n";
	   echo "<a20>".$a[20]."</a20>\n";
	  $totalDaeriMember2+=$a[9];
		
	   $count++;
	  
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember2>".number_format($totalDaeriMember2)."명"."</totalMember2>\n";
	echo"<totalMember>".number_format($totalDaeriMember)."명"."</totalMember>\n";
echo"</data>";

	?>



	<? //data 정리하기 위해 

	$sql="SELECT distinct(policyNum),startyDay,InsuraneCompany  ";
	$sql.="FROM  2012CertiTable  WHERE   startyDay>='$yearbefore' and InsuraneCompany!='2' ";

	$sql.="order by num asc ";

	$rs=mysql_query($sql,$connect);

	//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total_1  = mysql_num_rows($result2);

	for($k_=0;$k_<$total_1;$k_++){

		$m[1]=	mysql_result($result2,$k_,"policyNum");
		$m[2]=	mysql_result($result2,$k_,"startyDay");
		$m[3]=	mysql_result($result2,$k_,"InsuraneCompany");


	//	echo "$k_ $m[1] <br>"; 

		$d=explode("-",$m[1]);
		
		
		
		$aSql="SELECT * FROM 2012Certi WHERE certi='$m[1]'";
		$sRs=mysql_query($aSql,$connect);
		$aNum=mysql_num_rows($sRs);

		if(!$aNum){

			$insert="INSERT into 2012Certi (certi,sigi,insurance)";
			$insert.="values('$m[1]','$m[2]','$m[3]')";
			mysql_query($insert,$connect);

			//echo "insert $insert ";
		}



	}
	?>