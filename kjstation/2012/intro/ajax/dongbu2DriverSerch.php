<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$ssang_c_num	    =iconv("utf-8","euc-kr",$_GET['ssang_c_num']);
	//계약자 정보

	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$gi	    =iconv("utf-8","euc-kr",$_GET['p_ush']);
	$mNab    =iconv("utf-8","euc-kr",$_GET['mNab']);
	$driverName=iconv("utf-8","euc-kr",$_GET['driverName']);
	$damdanja=iconv("utf-8","euc-kr",$_GET['damdanja']);


	$insCompany=iconv("utf-8","euc-kr",$_GET['insCompany']); 
	$p_bunho=iconv("utf-8","euc-kr",$_GET['p_bunho']);
if($damdanja>=3){$damdanja=9999;}//전윤선이가 
	$state=iconv("utf-8","euc-kr",$_GET['state']);

	$manGi=iconv("utf-8","euc-kr",$_GET['manGi']); //가입월

	$c_name=iconv("utf-8","euc-kr",$_GET['c_name']);//1운전자 성명, 4 증권번호 
if($c_name==1){//운전자 성명으로 조회
	if($manGi!=99){
		$where4="and substring(a.InPutDay,6,2)='$manGi' ";
	}
	if($damdanja!=9999){
			$where2="and b.MemberNum='$damdanja'";
		}
	if($state!=9999){//납입 0 유예2 미납 1
			$where3="and a.state='$state'";
		}

	if($p_bunho!=99){//계약자 조회
			$where5="and a.p_buho='$p_bunho'";
		}
	if($mNab==12){// 현재 있는 모든 명단

		if($driverName){
			$sql="SELECT  *  FROM 2012DaeriMember";
		    //$sql.="ON a.2012DaeriCompanyNum = b.num ";
			$sql.=" WHERE  push='4' and Name='$driverName' and InsuranceCompany='$insCompany' order by Jumin asc";
		}else{
			$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany b ";
		    $sql.="ON a.2012DaeriCompanyNum = b.num ";
			$sql.="WHERE  a.push='4' and a.InsuranceCompany='$insCompany' $where2  $where3 $where4 $where5 order by a.dongbuCerti desc,a.nabang_1 desc,a.Jumin desc";

		}

	}else{
		
		$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num ";
		$sql.="WHERE  a.nabang_1='$mNab'   and a.push='4' and a.InsuranceCompany='$insCompany' $where2 $where3 $where4 order by a.dongbuCerti desc,a.nabang_1 desc,a.Jumin desc";
	}
}else{ //증권번호로 조회하기 위해 

	$sql="SELECT  *  FROM 2012DaeriMember";
		 //$sql.="ON a.2012DaeriCompanyNum = b.num ";
	$sql.=" WHERE  dongbuCerti='$driverName' and InsuranceCompany='$insCompany' and push='4' order by Jumin asc";


}
	//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                           //
	////////////////////////////////////////////////////////////////////////////////////
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
	echo "<last>".$last."</last>\n";
	for($k=$count;$k<$last;$k++){
		$DaeriCompanyNum=mysql_result($result2,$count,"2012DaeriCompanyNum");


		//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);



		$driverName=mysql_result($result2,$count,"Name");
		$driverNum = mysql_result($result2,$count,"num");
		$driverJumin = mysql_result($result2,$count,"Jumin");
		$certiNum  = mysql_result($result2,$count,"dongbuCerti");
		$push  = mysql_result($result2,$count,"push");
		
		$p_buho =mysql_result($result2,$count,"p_buho");
		///주민번호 presonalDrive 에서 전번을 찾자

		$wdate= mysql_result($result2,$count,"InPutDay");
		
	    $certi=$certiNum;
		
		$sd=explode("-",$start);
		$sd[0]=substr($sd[0],2,2);
		$start=$sd[0].".".$sd[1].".".$sd[2];
		//$start=$sd[1].".".$sd[2];
		
		$appNumber= mysql_result($result2,$count,"dongbuSelNumber");
		$Pnab= mysql_result($result2,$count,"nabang_1");

		//종기를 찾아야지 

		$FirStart=mysql_result($result2,$count,"FirstStart");
		if($FirStart!=$now_time){
			include "./php/dongbuState.php";
		}
		

		$state=mysql_result($result2,$count,"state");
		switch($state){
			case 1 :
				$SSjigi='미납';
				break;
			case 2 :
				$SSjigi='유예';
				break;
			case -1 :
				$SSjigi='실효';
				break;
			default:
				$SSjigi='납입';
				break;
	   }

		$DSql="SELECT * FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
		$Dsql=mysql_query($DSql,$connect);
		$Drow=mysql_fetch_array($Dsql);
		
		
	   //멤버 담당자를 찾기 위해 
	   //$MemberNum=mysql_result($result2,$count,"$Drow[MemberNum]");
	   $Msql="SELECT name FROM 2012Member WHERE num='$Drow[MemberNum]'";
	   $mrs=mysql_query($Msql,$connect);
	   $mRow=mysql_fetch_array($mrs);

	   
	   echo "<Con>".$last."</Con>\n";
	   echo "<ssNum>".$ssang_c_num."</ssNum>\n";
	   echo "<company>".$sRow[company]."</company>\n";
	   echo "<SSsigi>".$SSjigi."</SSsigi>\n";
	   echo "<state>".$state."</state>\n";
	   echo "<Pnab>".$Pnab."</Pnab>\n";
	   echo"<driveNum>".$driverNum."</driveNum>\n";
	   echo"<ounname>".$driverName."</ounname>\n";
	   echo"<ounjuimin>".$driverJumin."</ounjuimin>\n";
	   echo"<push>".$push."</push>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<user_id>".$user_id."</user_id>\n";
	  
	   echo"<certi>".$certi."</certi>\n";
	   echo"<sangtae>".$sangtaeName."</sangtae>\n";
	   echo"<preminum>".number_format($preminum)."</preminum>\n";
	   echo"<app>".$appNumber."</app>\n";
	   echo"<start>".$start."</start>\n";

	   echo"<damdanja>".$mRow[name]."</damdanja>\n";
	   echo"<com_phone>".$driverPhone."</com_phone>\n";
	   echo"<p_buho>".$p_buho ."</p_buho>\n";

	   $count++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
echo"</data>";

	?>