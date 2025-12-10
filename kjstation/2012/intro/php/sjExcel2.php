<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';

if($manGi!=99){
		$where4="and substring(a.InPutDay,6,2)='$manGi' ";
	}

if($state!=9999){//납입 0 유예2 미납 1
			$where3="and a.state='$state'";
		}
if($p_bunho!=99){//계약자 조회
			$where5="and a.p_buho='$p_bunho'";
}

//
if($damdanja!=9999){
			$where2="and b.MemberNum='$damdanja'";
		}
	if($mNab==12){// 현재 있는 모든 명단

		if($driverName){
			$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany b ";
		    $sql.="ON a.2012DaeriCompanyNum = b.num ";
			$sql.=" WHERE  a.push='4' and a.Name='$driverName' and a.InsuranceCompany='$insCompany' order by a.nabang_1 desc,a.Jumin desc";
		}else{
			$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany b ";
		    $sql.="ON a.2012DaeriCompanyNum = b.num ";
			$sql.="WHERE  a.push='4' and a.InsuranceCompany='$insCompany' $where2 $where3 $where4 $where5 order by a.nabang_1 desc,a.Jumin desc";

		}

	}else{
		
		$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num ";
		$sql.="WHERE  a.nabang_1='$mNab'   and a.push='4' and a.InsuranceCompany='$insCompany' $where2 $where3 order by a.nabang_1 desc,a.Jumin desc";
	}

//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);

echo "번호	운전자	주민앞	증권번호	대리운전회사	분납회차	분납보험료	년간보험료	가입일	계약자	사고\n ";
$k=1;
while($row=mysql_fetch_array($result2)){
	$DaeriCompanyNum=$row['2012DaeriCompanyNum'];


		//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);
		switch($row[state]){
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
	


	 //계약자 찾기 

	 $p_buho=$row['p_buho'];
	 switch($p_buho){
		 case 1 :
				$p_Name="조홍기";
			 break;
		case 2 :
			   $p_Name="오성준";

			break;
		case 3 :
				$p_Name="이근재";
			 break;
		case 4 :
			   $p_Name="오성엽";

			break;
		case 5 :
			   $p_Name="박종민";

			break;
		case 6 :
			   $p_Name="오경선";

			break;
	 }
	 $p_sago=$row['sago'];
	  switch( $p_sago){
		 case 1 :
				$p_Same="";
			 break;
		case 2 :
			   $p_Same="사고";

			break;
		
	 }
	echo "$k	$row[Name]	$row[Jumin]	$row[dongbuCerti]	$sRow[company]	$row[nabang_1]	$SSjigi	$row[InPutDay]		$p_Name		$p_Same\n ";
	$k++;
}?>