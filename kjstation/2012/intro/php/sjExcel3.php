<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';






if($s_contents){

	/*	if($insuranceComNum!=99){
			$where=" and InsuraneCompany='$insuranceComNum'  ";
		}else{

*/
	//	}

		$sql="SELECT  *  FROM 2012Costomer a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.company like '%$s_contents%'";
		$sql.="$where order by a.num desc";
		
	}else{
		//$where="WHERE endorse_day >='$sigi' and endorse_day <='$end' order by start asc ";
		/*if($insuranceComNum!=99){
			$where="WHERE InsuraneCompany='$insuranceComNum' and startyDay>='$sigi' order by FirstStart  desc ";
		}else{

		   //$where="WHERE startyDay>='$sigi' order by num desc";
		}*/
		if($damdanja!=9999){
			$where2="WHERE b.MemberNum='$damdanja'";
		}
		$sql="SELECT  *  FROM 2012Costomer a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num $where2 order by a.num asc";
		//WHERE  b.company like '%$s_contents%'";
		//$sql.="$where order by a.num desc";
		//$sql="Select * FROM 2012Costomer     $where ";	
	}
	

//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	
	$Rnum=mysql_num_rows($result2);

echo "번호	대리운전회사	I.D	핸드폰번호	담당자	분납회차	분납보험료	년간보험료\n ";
$k=1;
//while($row=mysql_fetch_array($result2)){
for($_j=0;$_j<$Rnum;$_j++){
	$row=mysql_fetch_array($result2);
	$DaeriCompanyNum=$row['2012DaeriCompanyNum'];
//대리기사 인원을 파악하기 위해 
	$kSql="SELECT * FROM 2012DaeriMember  WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and push='4'";
	//echo "kSql $kSql <br>";
	$kRs=mysql_query($kSql,$connect);

	$kNum=mysql_num_rows($kRs);

		//대리회사명을 찾기위해
		//	$dSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
		//	$dRs=mysql_query($dSql,$connect);
		//	$dRow=mysql_fetch_array($dRs);

			//담당자를 찾기위해 
			$sSql="SELECT * FROM 2012Member  WHERE num='$row[MemberNum]'";
			$sRs=mysql_query($sSql,$connect);
			$sRow=mysql_fetch_array($sRs);

			$a[9]=$sRow[name];

			
	   $a[2]=$row[name];
	  // $a[12]=mysql_result($result2,$count,"DaeriCompany");
	  // $a[3]=mysql_result($result2,$count,"InsuraneCompany");


	  

	
	echo "$k	$a[2]	$row[mem_id]	$row[hphone]	$a[9]	$row[nabang_1]	$kNum	$yp\n ";
	$k++;


	$TOTAL+=$kNum;
}
	echo "						$TOTAL	\n ";															
   

?>
   