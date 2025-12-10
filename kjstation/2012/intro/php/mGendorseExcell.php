<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';


	
	//echo "ins $insuranceComNum ";

		if($insuranceComNum!=99){
			$where=" and company='$insuranceComNum'  ";
		}else{


		}
		if($push!=99){
			$where2="push='$push'  ";
		}else{
			$where2="push>='1' ";
		}
		$where="WHERE $where2 $where $where3 $where4 and endorse_day='$end' and joong='2' order by SeqNo desc ";
		$sql="Select * FROM SMSData      $where ";	
		
	

//echo "sql $sql <br>";

	$result2 = mysql_query($sql,$connect);

echo "번호$end	운전자	주민앞	유형	대리운전회사	분납회차	분납보험료	년간보험료\n ";
$k=1;
while($row=mysql_fetch_array($result2)){
		$a[6]=$row['push'];
	  
	   switch($a[6]){
		   case 2 :
				$a[6]="해지";
			   break;
			case 4 :
				$a[6]="청약";
				break;
	   }
	//회차
		$a[7]=$row['2012DaeriMemberNum'];

			$msql="SELECT * FROM 2012DaeriMember WHERE num='$a[7]' ";
			$mrs=mysql_query($msql,$connect);
			$mrow=mysql_fetch_array($mrs);


			switch($mrow[etag]){
				case 1 : 
					$metat="";

					break;
				case 2 :

					$metat="[탁]";
					break;
			}

		$a[7]=$mrow[Name];
		$a[8]=$mrow[Jumin];	

	
	   $daeComNum=$row['2012DaeriCompanyNum'];
		

	//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$daeComNum'";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);

	

	//모계약을 찾기 위해 

			$qSql="SELECT * FROM 2012CertiTable  WHERE num='$mrow[CertiTableNum]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);
						$moNum=$qRow[moNum];
			if($moNum){
					//모계약의 certi번호를 찾아서 대리운전회사를 찾기위해 ....

						$rSql="SELECT * FROM 2012CertiTable  WHERE num='$moNum'";
						$rRs=mysql_query($rSql,$connect);
						$rRow=mysql_fetch_array($rRs);
						$mDaeriCompanyNum=$rRow['2012DaeriCompanyNum'];



						switch($rRow[gita]){
							case 1 :

								$metat="";
								break;
							case 2 :
								$metat="[탁]";
								break;
						}//2014-07-16

						$moSql="SELECT * FROM  2012DaeriCompany WHERE num='$mDaeriCompanyNum'";
						$moRs=mysql_query($moSql,$connect);
						$moRow=mysql_fetch_array($moRs);

			//모계약의 certi 번호를 기사별로 저장하기 위해 

						//$UmDate="UPDATE 2012DaeriMember SET moCertiNum='$moNum' WHERE num='$a[1]'";

						//mysql_query($UmDate,$connect);
			//moCertiNum

			}	
			
			$mm=$moRow[company].$metat;//
	echo "$k	$a[7]	$a[8]	$a[6]	$mm		$p	$yp\n ";

	$metat='';
	$k++;
}?>