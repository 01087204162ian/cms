<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../dbcon.php';

//echo "num $num <br>";

//$jum=explode("-",$num);

$mem=1;


//echo "ym $YM <br>";

echo "번호	배서일	성명	보험회사	가입/해지	보험료	보험료2\n ";


$sql="SELECT  *  FROM SMSData a left join 2012DaeriCompany  b ";
$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.num='$num' and push>='1' ";
$sql.="and  substring(endorse_day,1,7)='$YM' order by SeqNo desc";
		

//echo "sSql $sql <Br>";
$srs=mysql_query($sql,$connect);
$sNUM=mysql_num_rows($srs);



	//$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	//$iRs=mysql_query($iSql,$connect);
	//$iRow=mysql_fetch_array($iRs);
for($j=0;$j<$sNUM;$j++){

		$sRow=mysql_fetch_array($srs);


		 $a[30]=$sRow[SeqNo];
		  $a[5]=$sRow[endorse_day];
		  $a[7]=$sRow['2012DaeriMemberNum'];


		  $a[50]=$sRow[preminum2];

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
				default:
					$metat="";
					break;
			}

		$a[7]=$mrow[Name].$metat;
		$a[8]=$mrow[Jumin];	

		$a[6]=$sRow[push];

		$a[9]=$sRow[preminum];
		 switch($a[6]){
		   case 2 :
				$a[6]="해지";
			    $a[9]=-$a[9];
			   break;
			case 4 :
				$a[6]="추가";
				$a[9]=$a[9];
				break;
	   }

	   	switch($sRow[insuranceCom]){
			case 1 :
				$rRow[nab]='흥국';
			//	include "./php/naiPreminumu1.php";
				break;
			case 2 :
				$rRow[nab]='동부';
			//	include "./php/naiPreminumu2.php";

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$rRow[nab]='KB';
			//    include "./php/naiPreminumu3.php";
				break;
			case 4 :
				$rRow[nab]='현대';
			//    include "./php/naiPreminumu4.php";
				break;
			case 5 :
				$rRow[nab]='한화';
			//    include "./php/naiPreminumu5.php";
				break;
			case 6 :
				$rRow[nab]='더케이';
			//    include "./php/naiPreminumu5.php";
				break;
			case 7 :
				$rRow[nab]='MG';
			//    include "./php/naiPreminumu5.php";
				break;
			case 8 :
				$rRow[nab]='삼성';
			//    include "./php/naiPreminumu5.php";
				break;


		}
		/*//보험료을 구하기 위해 
				$pSql="SELECT * FROM 2012CertiTable WHERE num='$sRow[CertiTableNum]'";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);

		//보험료을 구하기 위해 

		//대리운전회사 찾기 위해 
		$daeriComNum=$sRow['2012DaeriCompanyNum'];
		$iSql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriComNum'";
		$iRs=mysql_query($iSql,$connect);
		$iRow=mysql_fetch_array($iRs);

		//
		$psql="SELECT * FROM 2012Cpreminum  WHERE ePreminum>='$sRow[nai]' and sPreminum<='$sRow[nai]' ";
			$psql.="and CertiTableNum='$sRow[CertiTableNum]'";
			$pRs=mysql_query($psql,$connect);
			$pRow=mysql_fetch_array($pRs);
			$p[$j]=$pRow[preminum1]=$pRow[mPreminum];


		switch($sRow[InsuranceCompany]){
			case 1 :
				$rRow[nab]='흥국';
			//	include "./php/naiPreminumu1.php";
				break;
			case 2 :
				$rRow[nab]='동부';
			//	include "./php/naiPreminumu2.php";

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$rRow[nab]='LiG';
			//    include "./php/naiPreminumu3.php";
				break;
			case 4 :
				$rRow[nab]='현대';
			//    include "./php/naiPreminumu4.php";
				break;
			case 5 :
				$rRow[nab]='한화';
			//    include "./php/naiPreminumu5.php";
				break;
			case 6 :
				$rRow[nab]='더케이';
			//    include "./php/naiPreminumu5.php";
				break;
			case 7 :
				$rRow[nab]='MG';
			//    include "./php/naiPreminumu5.php";
				break;


		}
		
		switch($sRow[etag]){
			default:

				$etage[$j]="일반";
				break;
			case 2 :
				$etage[$j]="탁송";	
				break;
		}*/
		$a[9]=number_format($a[9]);

		  echo "$a[30]	$a[5]	$a[7]	$rRow[nab]	$a[6]	$a[9]		$sRow[nai]	$p[$j]	$etage[$j]	$sRow[nabang_1]	$a[50]\n ";

		$mem++;

	
}















?>