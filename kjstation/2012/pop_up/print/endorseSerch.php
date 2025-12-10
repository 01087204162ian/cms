<?


//담당자 정보를 찾자

$uSql="SELECT name FROM 2012Member WHERE mem_id='$userId'";
//echo "uSql $uSql <br>";
$urs=mysql_query($uSql,$connect);
$urow=mysql_fetch_array($urs);

//증권번호를 찾기위해
$pSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";

//echo "pSql $pSql <bR>";
$prs=mysql_query($pSql,$connect);
$prow=mysql_fetch_array($prs);


switch($prow[InsuraneCompany]){
		case 1 :
			$a[8]='흥국화재';
			$coTitle="김 미영님(02-726-1930/0504-800-0507)";
			break;
		case 2 :
			$a[8]='동부화재';
			$coTitle="김 미영님(02-726-1930/0504-800-0507";
			break;
		case 3 :
			$a[8]='LiG화재';
			$coTitle="LiG화재";
			break;
		case 4 :
			$a[8]='현대화재';
			$coTitle="김 미영님(02-726-1930/0504-800-0507)";
			break;
		case 7 :
			$a[8]='MG화재';
			$coTitle="김 효원님 ";

			$sql="Select *  FROM 2012Cpreminum  WHERE InsuraneCompany='7' and  ";

			$sql.="DaeriCompanyNum='$DaeriCompanyNum'  and  CertiTableNum='$CertiTableNum' order by certi asc  ";

			//ECHO "SQL $sql <br>";
			$rs=mysql_query($sql,$connect);
			$Rnum=mysql_num_rows($rs);

			for($m=0;$m<$Rnum;$m++){
						$mGrow=mysql_fetch_array($rs);

					$mgCerti[$m]=$mGrow[certi];
			}

			break;
		}

//echo  $a[8];
//배서기준일을 찾기 위해

$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$CertiTableNum' and pnum='$eNum'";
//echo "eSql $eSql <br>";
$eRs=mysql_query($eSql,$connect);
$eRow=mysql_fetch_array($eRs);


$LigeNum=$eRow[enNum];
if($eRow[InsuranceCompany]==3){
	$LigeNum="               Lig배서 번호".$LigeNum;
}



//대리업체 명을 찾기위해  
$cSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
$cRs=mysql_query($cSql,$connect);
$cRow=mysql_fetch_array($cRs);

$Dsql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum'";
//echo "Dsql $Dsql <br>";
	$Drs=mysql_query($Dsql,$connect);
	$DNum=mysql_num_rows($Drs);

	//echo "<Rnum>".$DNum."</Rnum>\n";
	for($_m=0;$_m<$DNum;$_m++)
	{
		$DRow=mysql_fetch_array($Drs);

				switch($DRow[etag]){
					case 1 :

						$etag[$_m]='일반';
						break;
					case  2:
						$etag[$_m]='탁송';
						break;
					default:
						$etag[$_m]='일반';
						break;
				}
		$driverName[$_m]=$DRow[Name];
		$driverJumin[$_m]=$DRow[Jumin];
		$driverNai[$_m]=$DRow[nai];

		if($DRow[push]==4 && $DRow[cancel]==42){
				$DRow[push]=2;
		}
		$driverPush[$_m]=$DRow[push];
//echo  $driverPush[$_m] ;
		$driverCancel[$_m]=$DRow[sangtae];	
		
		$dcerrti[$_m]=$DRow[dongbuCerti];
	}


?>