<? 
	if($sRow[InsuraneCompany]==2){  

			if($endorse==2){

				$now_time=$endorseDay;
			}else{

				$now_time=$startyDay;
			}
			$sigi=explode("-",$now_time);
			$gijun=$sigi[0].$sigi[1].$sigi[2];  //기준일
			if($juminLength==14){
				$bir = explode("-",$jumin);
			}else{
				$bir[0] =substr($jumin, 0, 6);
				$a_jumin=substr($jumin,-7);
				$jumin=$bir[0]."-".$a_jumin;
			}
			$birth="19".$bir[0];
			$nai=$gijun-$birth;
			$nai=floor(substr($nai,0,2));

			if($dongliStartDay){
						$sigi=explode("-",$dongliStartDay);
						$gijun=$sigi[0].$sigi[1].$sigi[2];  //기준일
						if($juminLength==14){ // 하이푼이 있는  경우
							$bir = explode("-",$jumin);
						}else{
							$bir[0] =substr($jumin, 0, 6);
							$a_jumin=substr($jumin,-7);
							$jumin=$bir[0]."-".$a_jumin;
						}
						$birth="19".$bir[0];
						$nai2=$gijun-$birth;
						$nai2=floor(substr($nai2,0,2));
			}





			//동부화재 인경우만
			$policyNum=$data->sheets[0]['cells'][$i][7];
			$p_si=explode("-",$startyDay);
			//$policyNum=$p_si[0]."-".$policyNum;
			$policyNum=floor(substr($p_si[0],2,4))."-".$policyNum;

		}else{

			$sigi=explode("-",$startyDay);
			$gijun=$sigi[0].$sigi[1].$sigi[2];  //기준일
			if($juminLength==14){ // 하이푼이 있는  경우
				$bir = explode("-",$jumin);
			}else{
				$bir[0] =substr($jumin, 0, 6);
				$a_jumin=substr($jumin,-7);
				$jumin=$bir[0]."-".$a_jumin;
			}
			$birth="19".$bir[0];
			$nai=$gijun-$birth;
			$nai=floor(substr($nai,0,2));


			if($dongliStartDay){
						$sigi=explode("-",$dongliStartDay);
						$gijun=$sigi[0].$sigi[1].$sigi[2];  //기준일
						if($juminLength==14){ // 하이푼이 있는  경우
							$bir = explode("-",$jumin);
						}else{
							$bir[0] =substr($jumin, 0, 6);
							$a_jumin=substr($jumin,-7);
							$jumin=$bir[0]."-".$a_jumin;
						}
						$birth="19".$bir[0];
						$nai2=$gijun-$birth;
						$nai2=floor(substr($nai2,0,2));
			}

		}

	//	echo "nai $nai ";
	//Mg 화재는 2014년4월1일 이전 계약만
	//$mg=(strtotime("$startyDay")strtotime("2014-04-01"))/60/60/24;//
	if(strtotime("$startyDay")<strtotime("2014-04-01")){
		if($sRow[InsuraneCompany]==7){	
			$psql="SELECT * FROM 2012Cpreminum  WHERE (e_nai>='$nai' and s_nai<='$nai') ";
			$psql.="and CertiTableNum='$certiTableNum'";
			$pRs=mysql_query($psql,$connect);
			$pRow=mysql_fetch_array($pRs);
			$policyNum=$pRow[certi];

		}
	}

/* 중복 검사 * 가입되어 있는 가 확인한다 */

	$dSql="SELECT * FROM 2021DaeriMember WHERE Jumin='$jumin' and push='4' and CertiTableNum='$certiTableNum'";

	$drS=mysql_query($dSql,$connect);
	$dCount[$i]=mysql_num_rows($drS);


	
	if($dCount[$i]){

			$duplicte[$i]="중복";
	}

/* 지역 지사  매칭이 되는지 확인한다*/
/*if($local && $jisa){
	$Lsql="SELECT * FROM 2012Local  WHERE 2014DaeriCompanyNum='$daeriCompanyNum' AND";
	$Lsql.=" local='$local' AND jisa='$jisa' ";

	$Lrs=mysql_query($Lsql,$connect);
	$Lcount[$j]=mysql_num_rows($Lrs);

	if($Lcount[$j]==1){
				$L_Row=mysql_fetch_array($Lrs);
				$L_num[$j]=$L_Row[num];
		 $Lcount[$j]='';
	}else{
		  
			$Lcount[$j]='불일치';
			$l_button=1;

	}
}*/
/* 최저 나이 계산 하자*/

$cSql="SELECT * FROM 2021Cpreminum WHERE CertiTableNum='$certiTableNum' order by sPreminum ";
//echo $cSql;
$cRs=mysql_query($cSql,$connect);
$cNum=mysql_num_rows($cRs);


for($_p_=0;$_p_<$cNum;$_p_++){
		$cRow=mysql_fetch_array($cRs);

		$c_nai[$_p_]=$cRow[sPreminum];

		if($_p_==0){$min=$c_nai[$_p_];}

		if($min>$c_nai[$_p_]){

			$min=$c_nai[$_p_];
		}
}


//최저나이보다 작은 나이는 등록 불가

if($nai<$min)
{

		$n_duplicate[$j]='나이제한';
		$n_duplicate2[$j]=1;
}

$userId=$_SESSION['ssID'];

	//2014DaeriMember 
	if($sj==1){  //오류가 없으면 저장합니다
		$sql = "insert into 2021DaeriMember "; 
		$sql .= " (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum,Name,Jumin,nai,push,etag,sangtae,Hphone,in_put_day,dongbuCerti,nabang_1,a6b,a7b,a8b)" ;
		$sql .= " values ";
		
		$sql .= "('$daeriCompanyNum','$sRow[InsuraneCompany]','$certiTableNum','$pName', ";
		$sql .= "'$jumin','$nai','4','$gita','2','$hphone',";
		$sql .="'$endorseDay','$policyNum','1', ";
		$sql .="'$shp','$nhp','$address')";

		
		mysql_query($sql,$connect);
		unlink($fileName);
	}

	if($sj==2){  // 배서 추가 조회 
		$sql = "insert into 2021DaeriMember "; 
		$sql .= " (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum,Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,dongbuCerti,nabang_1,a6b,a7b,a8b)" ;
		$sql .= " values ";
		
		$sql .= "('$daeriCompanyNum','$sRow[InsuraneCompany]','$certiTableNum','$pName', ";
		$sql .= "'$jumin','$nai','1','$gita','1','$hphone',";
		$sql .="'$endorseDay','$policyNum','1', ";
		$sql .="'$shp','$nhp','$address')";

		mysql_query($sql,$connect);

	//청약과 동시에 해지 처리 를 합니다

		//echo "sql $sql <br>";
		
		//
		$bsql="SELECT * FROM 2021DaeriMember WHERE Jumin='$jumin' AND push='1' AND sangtae='1' AND CertiTableNum='$certiTableNum' AND ";
		$bsql.="2012DaeriCompanyNum='$daeriCompanyNum' AND InPutDay='$endorseDay'";
		

	//	echo "b $bsql ";
		$brs=mysql_query($bsql,$connect);
		$brow=mysql_fetch_array($brs);
		
		
		$memberTableNum=$brow[num];
		$push=4;
		//include "./php/pushChange.php";
		
		$updateSj="UPDATE 2021DaeriMember SET push='$push',sangtae='2',cancel='$cancel', ";	
		$updateSj.="InPutDay ='$endorseDay' ";  // 
		$updateSj.=" WHERE num='$memberTableNum'";

		//echo "updateSj $updateSj";

		mysql_query($updateSj);

/* 배서후 처리를 위하여 */
		$Fir=explode("-",$FirstStartDay); //업체로부터 받는날
		$Now=explode("-",$endorseDay); //현재일자,배서화면에서는 배서일자가 되겠지
	
//보험료 받는날자 초기일부터 계산
   include "./php/dateCount.php"; 
	
//보험료 계산

	$psql="SELECT * FROM 2021Cpreminum  WHERE (sPreminum>='$nai' and ePreminum<='$nai') ";
	$psql.="and CertiTableNum='$certiTableNum'";

	//echo $psql;
	$pRs=mysql_query($psql,$connect);
	$pRow=mysql_fetch_array($pRs);
	$PreminumMonth=$pRow[mPreminum];
	$InsuraneCompany=$sRow[InsuraneCompany];
	include "./php/dayPreminumCount.php"; //1/12 보험료 찾기였고


	$Ypreminum=$pRow[yPreminum];//$startyDay증권의시작일 $nabang//분납$nabang_1=$row3['nabang_1'];;//납입회차 $Now 날자
	$DB->Recode[InsuranceCompany]=$sRow[InsuraneCompany];
    include "./php/yPreminum.php";

			if($dongliStartDay){ //특저일 기준의 1/12 보험료 구하자
				//$dongliNaiPreminum=$endorsePreminum;
					$psql="SELECT * FROM 2012Cpreminum  WHERE (sPreminum>='$nai2' and ePreminum<='$nai2') ";
					$psql.="and CertiTableNum='$certiTableNum'";
					$pRs=mysql_query($psql,$connect);
					$pRow=mysql_fetch_array($pRs);
					$PreminumMonth=$pRow[mPreminum];
					include "./php/dayPreminumCount.php"; //1/12 보험료 찾기였고

				

				//$endorsePreminum=
			}


		$sTableNum=$daeriCompanyNum;
		$policyNum=$policyNum;
		$inSuranceCom=$sRow[InsuraneCompany];
		$CertiTableNum=$certiTableNum;
		$endosDay=$endorseDay;


		//담당자를 찾기 위해 

					$jSql="SELECT * FROM 2012DaeriCompany WHERE num='$sTableNum'";
					$jRs=mysql_query($jSql,$connect);
					$jRow=mysql_fetch_array($jRs);

					$damdanga=$jRow[damdanga];

					if($damdanga==0 || $damdanga=='') { $damdanga=99;}  //미지정을 제일 큰숫자로 만들어서 배열 순서를 만들기 위해
		//include "./php/coSms.php";  /2016-01-01  // 
		unlink($fileName);
	}
?>