<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../dbcon.php';

//echo "mNum $mNum <br>"; //daerimember의 num

//2012DaeriMember에서 num 값으로 pnum 값을 찾아서 배서 일자를 찾는다

$mSql="SELECT * FROM 2012DaeriMember WHERE num='$mNum'";
$mRs=mysql_query($mSql,$connect);
$mRow=mysql_fetch_array($mRs);



//echo "mCertiNum $mCertiNum <br>";//
		$eSql="SELECT * FROM 2012EndorseList WHERE pnum='$mRow[EndorsePnum]' and  CertiTableNum='$mRow[CertiTableNum]'";
	//echo "eSql $eSql <Br>";
	   $eRs=mysql_query($eSql,$connect);
	   $eRow=mysql_fetch_array($eRs);

		$aSql="SELECT * FROM 2012DaeriMember WHERE sangtae='1' AND InsuranceCompany='7' ";
		$aSql.="AND moCertiNum='$mRow[moCertiNum]'";
		$aRs=mysql_query($aSql,$connect);
		$aRnum=mysql_num_rows($aRs);

//모증권 대리회사명을 찾기 위해


	$rSql="SELECT * FROM 2012CertiTable WHERE num='$mRow[moCertiNum]'";
	$rSs=mysql_query($rSql,$connect);
	$rSow=mysql_fetch_array($rSs);
	
	$rNum=$rSow['2012DaeriCompanyNum'];


	switch($rSow[gita]){

		case 1 :

			$gita="[일반]";
			break;
		case 2 :

			$gita="[탁송]";
			break;
		default :

			$gita="[일반]";
			break;

	}

	$hSql="SELECT * FROM 2012DaeriCompany WHERE num='$rNum'";
	//echo "h $hSql ";
	$hRs=mysql_query($hSql,$connect);
	$hRow=mysql_fetch_array($hRs);

//증권번호 찾기 위해 
	$nSql="SELECT * FROM 2012Cpreminum WHERE  CertiTableNum='$mRow[moCertiNum]'  order by sunso  asc";
	$nRs=mysql_query($nSql,$connect);
	$mRnum=mysql_num_rows($nRs);
	for($j=0;$j<$mRnum;$j++){
		$mRow=mysql_fetch_array($nRs);

		$certi[$j]=$mRow[certi];

	}
	//echo "$hRow[company]";
//

$m=0;$n=0;

	for($j=0;$j<$aRnum;$j++){

		$sRow=mysql_fetch_array($aRs);
		if($sRow[cancel]==42 && $sRow[push]==4){
			
			$a[5]=8; //해지
		}
		if($sRow[push]==1){
			$a[5]=1; //청약
		}


		switch($a[5]){

				case 1 :// 청약
				$mg[$m]=$sRow[num];
				$m++;
					break;
				
				case 8 ://해지
	
				$mg2[$n]=$sRow[num];
				$n++;
					break;
		}


	}

	echo"MG 손해보험 배서 신청서 \n ";
	echo"업체명: "; echo" $hRow[company] $gita\n ";
	echo"증권번호 :"; echo"$certi[0] $certi[1] $certi[2] \n ";
	echo"배서신청메일주소 greendrive7@naver.com,457320@korea.com \n";
	echo"배서기준일 :"; echo "$eRow[endorse_day] \n";


	echo "순번	기존/감소 가입자	주민번호	연령	증권번호	추가/대체 가입자	주민번호	연령	증권번호	비고	\n ";
for($_j=0;$_j<$j;$_j++){
	
	/*
		if($sRow[cancel]==42 && $sRow[push]==4){
			
			$a[5]='해지';
		}
		if($sRow[push]==1){
			$a[5]='청약';
		}*/
		$m=$_j+1;
		//해지 부터 
		 $ySql="SELECT * FROM 2012DaeriMember WHERE  num='$mg2[$_j]'";
		 $yRs=mysql_query($ySql,$connect);
		 $yRow=mysql_fetch_array($yRs);
		
				$tSql="SELECT * FROM 2012EndorseList WHERE pnum='$yRow[EndorsePnum]' and  CertiTableNum='$yRow[CertiTableNum]'";
				$tRs=mysql_query($tSql,$connect);
				$tRow=mysql_fetch_array($tRs);

			//배서 기준일이 다르면
				if($tRow[endorse_day]!=$eRow[endorse_day]){

					$yRow[Name]='';	
					$yRow[Jumin]='';
					$yRow[nai]='';	
					$yRow[dongbuCerti]='';
				}
		
		 //청약
		  $zSql="SELECT * FROM 2012DaeriMember WHERE  num='$mg[$_j]'";
		  $zRs=mysql_query($zSql,$connect);
		  $zRow=mysql_fetch_array($zRs);
				
				$sSql="SELECT * FROM 2012EndorseList WHERE pnum='$zRow[EndorsePnum]' and  CertiTableNum='$zRow[CertiTableNum]'";
				$sRs=mysql_query($sSql,$connect);
				$sRow=mysql_fetch_array($sRs);

			//배서 기준일이 다르면
				if($sRow[endorse_day]!=$eRow[endorse_day]){

					$zRow[Name]='';	
					$zRow[Jumin]='';
					$zRow[nai]='';	
					$zRow[dongbuCerti]='';
				}
		
		  if($zRow[ch]==3){

				$chName="심사완료";

		  }

		  
			echo "$m	$yRow[Name]	$yRow[Jumin]	$yRow[nai]	$yRow[dongbuCerti]	$zRow[Name]	$zRow[Jumin]	$zRow[nai]	$zRow[dongbuCerti]	$chName\n ";
		

		$zRow[ch]='';
		$chName="";
}















?>