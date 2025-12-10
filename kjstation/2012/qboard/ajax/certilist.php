<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//state 1 실효 제외 state 2 : 포함
	//////////////////////////////////////////////////////////////////
//	$sigi	    =iconv("utf-8","euc-kr",$_POST['sigi']);
//	$end	    =iconv("utf-8","euc-kr",$_POST['end']);
//	$page	    =iconv("utf-8","euc-kr",$_POST['page']);
	//$s_contents	    =iconv("utf-8","euc-kr",$_POST['s_contents']);
//	$chr	      =iconv("utf-8","euc-kr",$_POST['chr']);
	//$damdanja=iconv("utf-8","euc-kr",$_POST['damdanja']);

	
   if($damdanja>=3){$damdanja=99;}//전윤선이가 

	$insuranceComNum=iconv("utf-8","euc-kr",$_POST['insuranceComNum']);


	if($insuranceComNum!=99){
			$where2="and a.InsuranceCompany='$insuranceComNum'";
	}
//	if($chr!=99){
//			$where3="and a.ch='$chr'";
//	}
	if($damdanja!=99){

			$whrer4="and b.MemberNum='$damdanja'";
		}
	if($s_contents){
		$where="WHERE a.Name like '%$s_contents%' and a.sangtae='1' $where2 $where3 order by a.dongbuCerti  desc ";
	}else{
		//$where="WHERE start<='$end' order by start asc ";
		//$where="WHERE    a.sangtae='1' $where2  $where3  $whrer4 order by a.InPutDay  asc,a.dongbuCerti  desc";	
		$where="WHERE    a.sangtae='1' $where2  $where3  $whrer4 order by a.OutPutDay   asc,a.dongbuCerti  desc";
	}
	
	$sql="SELECT  distinct dongbuCerti   FROM 2021DaeriMember a left join 2012DaeriCompany  b ";
	$sql.="ON a.2012DaeriCompanyNum = b.num $where ";
//$sql="Select * FROM 2012DaeriMember    $where ";

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
echo "<values>\n";

  
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";

	echo("\t<item>\n");
				echo("\t\t<policynum>"."전체"."</policynum>\n");
			echo("\t</item>\n");
	for($k=$count;$k<$last;$k++){
		
	  
	    $a[13]=mysql_result($result2,$count,"dongbuCerti");

		//echo  $a[13];
		//모계약을 찾기 위해 

			$qSql="SELECT * FROM 2021CertiTable  WHERE num='$a[13]'";

			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);
			
			

			echo("\t<item>\n");
				echo("\t\t<policynum>".$a[13]."</policynum>\n");
			echo("\t</item>\n");
	   $count++;
		
	}
	
	
echo "</values>";

	?>