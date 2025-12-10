<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';
//삼성 화재 계약 정리




$sql="SELECT * FROM 2012DaeriMember WHERE substring(dongbuCerti,1,3)='113' AND push='4'  order by Jumin asc";
//$sql="SELECT * FROM 2012DaeriMember WHERE substring(dongbuCerti,1,3)='114' AND push='4'  order by Jumin asc";
	//echo "sql $sql <br>";

	$rs=mysql_query($sql,$connect);

	//$Rnum=mysql_num_rows($rs);
//echo "Rnum $Rnum <br>";


	$result2 = mysql_query($sql,$connect);

echo "번호	운전자	주민앞	증권번호	대리운전회사	분납회차	분납보험료	년간보험료	담당자\n ";
$k=1;
while($row=mysql_fetch_array($result2)){
	//회차


	
	   $daeComNum=$row['2012DaeriCompanyNum'];
		
		
	//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$daeComNum'";

		//echo "sql $sqC";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);

	$a[23]=$sRow[MemberNum];
		//만나이 계산을 위해 

		//echo " $a[23]  <br>"; 

		//echo " dd $row[InsuranceCompany] <br>";

		if($row[InsuranceCompany]==3){

			$pSql="SELECT * FROM  2012Certi	WHERE certi='$num'";

			$pRs=mysql_query($pSql,$connect);
			$pPow=mysql_fetch_array($pRs);

			 $pPow[sigi];
			

		/*	if($pPow[sigi]){//시기가 있을때만
					 //만나이 계산을 위해 
					$p=explode("-",$row[Jumin]);
					$s=explode("-",$pPow[sigi]);
					$m1=substr($pPow[sigi],0,4);
					$m2=substr($pPow[sigi],5,2);
					$m3=substr($pPow[sigi],8,2);
					$sigi=$m1.$m2.$m3;			
					$birth="19".$p[0];
					$p[0]=$sigi-$birth;
					$p[0]=floor(substr($p[0],0,2));


					//echo "주민 $row[jumin] 시기 $pPow[sigi] <br>";
					$tupdate="UPDATE 2012DaeriMember SET nai='$p[0]' WHERE num='$row[num]'";

					//echo "tu $tupdate ";
					mysql_query($tupdate,$connect);
			}*/
		}
			//$tupdate="UPDATE 2012DaeriMember SET nabang_1='10' WHERE num='$row[num]'";

			//echo "tu $tupdate <br>";
			//mysql_query($tupdate,$connect);

		//담당자 찾기
		$qSql="SELECT * FROM 2012Member  WHERE num='$a[23]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);

			$a[23]=$qRow[name];
		///

	
	echo "$k	$row[Name]	$row[Jumin]	$row[dongbuCerti]	$sRow[company]	$rRow[nab]	$p[0]	$yp	$a[23]	 $row[nabang_1]\n ";
	$k++;
}?>