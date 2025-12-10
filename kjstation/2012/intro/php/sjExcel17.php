<?$output_file_name=$num;
header( "Content-type: application/vnd.ms-excel" );   
header( "Content-type: application/vnd.ms-excel; charset=euc-kr");  
header( "Content-Disposition: attachment; filename = {$output_file_name}.xls" );  
   

header( "Content-Description: PHP4 Generated Data" );

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';


$where="WHERE dongbuCerti='$num'  and push='4' $where order by jumin asc ";
		
$sql="Select * FROM 2012DaeriMember $where ";

//echo "sql $sql <br>";

	$result2 = mysql_query($sql,$connect);

echo "번호	운전자		주민앞		나이	증권번호	대리운전회사	분납회차	만나이	10%보험료	사교유무	적용보험료	담당자	탁송여부	핸드폰\n ";
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
		}

		//사고유무
		$tSql="SELECT * FROM 2019rate WHERE jumin='$row[Jumin]' AND policy='$num' ";
		$trs=mysql_query($tSql,$connect);
		$trow=mysql_fetch_array($trs);

		//보험회사에 내는 보험료 구하기 

		$aSql="SELECT * FROM 2012Certi WHERE certi='$num'";


		$sRs=mysql_query($aSql,$connect);
		$aRow=mysql_fetch_array($sRs);
		   $a[13]=$aRow[preminun25]; //26세~28세
		   $a[14]=$aRow[preminun44]; //29세~40세
		   $a[15]=$aRow[preminun49]; //41세~49세
		   $a[16]=$aRow[preminun50]; //50세~55세
		   $a[17]=$aRow[preminun60]; //56세~65세
		   $a[18]=$aRow[preminun66]; //66세

		   if($p[0]>19 && $p[0]<=28){
				$yp=$a[13];		
		   }else if($p[0]>29 && $p[0]<=40){
				$yp=$a[14];	
		   }else if($p[0]>41 && $p[0]<=49){
				$yp=$a[15];	
		   }else if($p[0]>50 && $p[0]<=55){
				$yp=$a[16];	
		   }else if($p[0]>56 && $p[0]<=65){
				$yp=$a[17];	
		   }else if($p[0]>66 ){
				$yp=$a[18];	
		   }


		   switch($trow[rate]){
			   case 1 :
					$rate_=1;
				   break;
				case 2 :

					$rate_=0.9;
					break;
				
		   }


			

		   $yp2_=$yp*$rate_; //적용보험료 

		   //echo $yp2_;
	
	echo "$k	$row[Name]	$row[Jumin]	$row[nai]	$row[dongbuCerti]	$sRow[company]	$rRow[nab]	$p[0]	$yp	$trow[rate]	$yp2_	$sRow[MemberNum]	$etagName	$row[Hphone]	$row[InsuranceCompany]\n ";
	$k++;
}?>