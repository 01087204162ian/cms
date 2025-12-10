<?
$output_file_name='1';
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';


	 $where="WHERE    a.sangtae='1'  and a.InsuranceCompany='2'  order by a.Jumin asc,a.InPutDay   asc,a.OutPutDay   asc,a.dongbuCerti  desc";

	$sql="SELECT  *  FROM 2012DaeriMember a left join 2012DaeriCompany  b ";
	$sql.="ON a.2012DaeriCompanyNum = b.num $where ";

//echo "sql $sql <br>";

	$result2 = mysql_query($sql,$connect);

echo "번호	운전자	주민앞	설계번호	대리운전회사	분납회차	분납보험료	년간보험료	담당자	탁송여부	기타\n ";
$k=1;
while($row=mysql_fetch_array($result2)){
	//회차


	
	   $daeComNum=$row['2012DaeriCompanyNum'];
		

	//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$daeComNum'";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);


		//만나이 계산을 위해 

		//echo " dd $row[InsuranceCompany] <br>";

		if($row[InsuranceCompany]==3){

			$pSql="SELECT * FROM  2012Certi	WHERE certi='$num'";

			$pRs=mysql_query($pSql,$connect);
			$pPow=mysql_fetch_array($pRs);

			 $pPow[sigi];
			

			if($pPow[sigi]){//시기가 있을때만
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
			}
		}

		///

		 switch($row[etag]){
			case 1 :
				$etagName='일반';
				break;
			case 2 :
				$etagName='탁송';
				break;
			case 3 :
				$etagName='확탁송';
				break;
		}
	$preminum1=number_format($row[preminum1]);
	
	echo "$k	$row[Name]	$row[Jumin]	$row[dongbuSelNumber]	$sRow[company]	$rRow[nab]	$preminum1	$yp	$sRow[MemberNum]	$etagName	$row[a8b]\n ";
	$k++;
}?>