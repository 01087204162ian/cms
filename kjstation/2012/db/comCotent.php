<?for($j=0;$j<$sNum;$j++){

	
	$row=mysql_fetch_array($rs);

	$a0[$j]=$row[num];
	$a1[$j]=$row[company];
	$a2[$j]=$row[c_name];
	$a3[$j]=$row[con_jumin1]."-".$row[con_jumin2];
	$a4[$j]=$row[con_phone1];
	$a5[$j]=$row[con_phone2];
	$a6[$j]=$row[b_number];// 
	$a7[$j];// 이번에 필요없다... 각증권 2012CertiTable 
	$a8[$j];//fax
	$a9[$j]=$row[c_number]; //사업자 번호
	$a10[$j]=$row[b_number];//법인번호
	$a11[$j]=$row[v_bank];  //가상 은행
	$a12[$j]=$row[v_number];//가상계좌
	$a13[$j]=$row[content];//구 메모 내용;
	$a14[$j];

//echo "1 a4 $a4[$j] <Br>";


	$a15[$j]=$row[MonthPrem];
    if($_k==1){//흥국화재만아닌 경우
		$a16[$j]=$row[MonthPrem];
		$a17[$j]=$row[MonthPrem];
		
	}else{
		$a16[$j]=$row[MonthQrem];
		$a17[$j]=$row[MonthRrem];
		$a18[$j]=$row[MonthSrem];
		$a19[$j]=$row[MonthTrem];
		$a20[$j]=$row[MonthUrem];

		$a21[$j]=$row[MonthPrem2];
		$a22[$j]=$row[MonthQrem2];
		$a23[$j]=$row[MonthRrem2];
		$a24[$j]=$row[MonthSrem2];
		$a25[$j]=$row[MonthTrem2];
		$a26[$j]=$row[MonthUrem2];

	}
	
//echo "j $j $row[company] <br>";
if($_k==1){//흥국화재 인경우
	$q[$j]=$row[certi_number];
	$c=explode("-",$q[$j]);
	$q[$j]=$c[3]."-".$c[4];//증권번호
	$nabang[$j]=$row[nabang];
	$nabang_1[$j]=$row[nabang_1];
	$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미


}

if($_k==2){//동부화재 인경우
//	$q[$j]=$row[certi_number];
//	$c=explode("-",$q[$j]);
//	$q[$j]=$c[3]."-".$c[4];//증권번호
$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미
}

if($_k==3){//lig화재 인경우
	switch($row[mother]){
		case 1 ://모계약인경우
			$q[$j]=$row[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호
			$nabang[$j]=$row[nabang];
			$nabang_1[$j]=$row[nabang_1];
			$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미

			break;
		case 2 : //기본계약인경우
			$q[$j]=$row[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호
			$nabang[$j]=$row[nabang];
			$nabang_1[$j]=$row[nabang_1];
			$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미
			break;
		case 3 : //차일드 계약인경우

			$tSql="SELECT * FROM lig_c WHERE num='$row[motherNum]'";
			$tRs=mysql_query($tSql,$connect);
			$tRow=mysql_fetch_array($tRs);
			$q[$j]=$tRow[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호

			$nabang[$j]=$tRow[nabang];
			$nabang_1[$j]=$tRow[nabang_1];
			$start[$j]=$tRow[start];//증권의 시작이 보험기간의 시작일을 의미
			break;
	}
}
if($_k==4){//현대화재 인경우

	switch($row[mother]){
		case 1 ://모계약인경우
			$q[$j]=$row[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호
			$nabang[$j]=$row[nabang];
			$nabang_1[$j]=$row[nabang_1];
			$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미
			break;
		case 2 : //기본계약인경우
			$q[$j]=$row[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호
			$nabang[$j]=$row[nabang];
			$nabang_1[$j]=$row[nabang_1];
			$start[$j]=$row[start];//증권의 시작이 보험기간의 시작일을 의미
			break;
		case 3 : //차일드 계약인경우

			$tSql="SELECT * FROM hyundai_c WHERE num='$row[motherNum]'";
			//echo "tSql $tSql <br>";
			$tRs=mysql_query($tSql,$connect);
			$tRow=mysql_fetch_array($tRs);
			$q[$j]=$tRow[certi_number];
			$c=explode("-",$q[$j]);
			$q[$j]=$c[0]."-".$c[1];//증권번호
			$nabang[$j]=$tRow[nabang];
			$nabang_1[$j]=$tRow[nabang_1];
			$start[$j]=$tRow[start];//증권의 시작이 보험기간의 시작일을 의미
			break;
	}
	
}
	

	$a28[$j]=$row[post];
	$a29[$j]=$row[address_1];
	$a30[$j]=$row[address_2];

	$a31[$j]=$row[pNum];//담당자
	$a34[$j]=$row[MonthStart];//월별로 받는 것을 의미

	$m=explode("-",$a34[$j]);
	
	$a33[$j]=$m[2];
	//echo "2012DaeriCompany ";

	//echo "company | Pname | jumin | hphone | cphone | damdanga | divi | fax | cNumber | lNumber | a11 |
	//| a12 || a13 || a14 ||postNum | address1 |address2 |FirstStartDay |FirstStart |";

	$Dsql="SELECT * FROM 2012DaeriCompany WHERE jumin='$a3[$j]'";
	
	//if($a[3]=='671221-1548617'){ echo "d  $Dsql <Br>";}


//echo "Dsql $Dsql <Br>";
	$drs=mysql_query($Dsql,$connect);
	$drow=mysql_fetch_array($drs);

	$dNum=mysql_num_rows($drs);
	//echo "dero $drow[jumin] ";

	//echo "$j  || "; echo "$dNum  <br>"; 
	if(!$dNum){//주민번호가 이미 있다면 등록할 필요가 없잖아
		$sql="INSERT INTO 2012DaeriCompany (company,Pname,jumin,hphone,cphone,damdanga,divi,fax, ";
		$sql.="cNumber,lNumber,a11,a12,a13,a14,postNum,address1,address2, ";
		$sql.="MemberNum,pBankNum,FirstStartDay,FirstStart) ";
		$sql.="values ('$a1[$j]','$a2[$j]','$a3[$j]','$a4[$j]','$a5[$j]','$a6[$j]','$a7[$j]','$a8[$j]', ";
		$sql.="'$a9[$j]','$a10[$j]','','','','','$a28[$j]','$a29[$j]','$a30[$j]', ";
		$sql.="'$a31[$j]','','$a33[$j]','$a34[$j]')";

	//echo "$j  $a34[$j] ";
	//echo "$j sql $sql <br>";
		mysql_query($sql,$connect);

		
		$gSql="SELECT num FROM 2012DaeriCompany WHERE jumin='$a3[$j]'";
		$grs=mysql_query($gSql,$connect);
		$grow=mysql_fetch_array($grs);

		$drow[num]=$grow[num];//2012DaeriCompanyNum
		//그리고 저장하고 나서 
		if($_k==1){//흥국화재
			$yearp1[$j]=585500;
		}

		if($_k==2){//동부화재인경우만
				$q[$j]='';
				$nabang_1[$j]='10';
			}
		if($_k==4){//현대화재인경우

				$a18[$j]=$a17[$j];
			}
		//if($start[$j]>='2011-06-01'){
			$ksql="INSERT INTO 2012CertiTable  (2012DaeriCompanyNum,DaeriCompany,InsuraneCompany,startyDay,FirstStart,FirstStartDay, ";
			$ksql.="policyNum,nabang,nabang_1,divi, ";
			$ksql.="vbank,v_number,content ) ";
			//$ksql.="preminum1,preminum2,preminum3,preminum4,preminum5,preminum6,preminum7,preminum8,preminum9,preminum10, ";
			//$ksql.="preminumE1,preminumE2,preminumE3,preminumE4,preminumE5,preminumE6,preminumE7,preminumE8,preminumE9,preminumE10, ";
			//$ksql.="yearP1,yearP2,yearP3,yearP4,yearP5,yearP6, ";
			//$ksql.="yearPE1,yearPE2,yearPE3,yearPE4,yearPE5,yearPE6 )";
			$ksql.="values ('$drow[num]','$a1[$j]','$_k','$start[$j]','$a34[$j]','$a33[$j]',  ";
			$ksql.="'$q[$j]','$nabang[$j]','$nabang_1[$j]','2',";
			$ksql.="'$a11[$j]','$a12[$j]','$a13[$j]') ";
			//$ksql.="'$a15[$j]','$a16[$j]','$a17[$j]','$a18[$j]','$a19[$j]','$a20[$j]','','','','', ";
			//$ksql.="'$a21[$j]','$a22[$j]','$a23[$j]','$a24[$j]','$a25[$j]','$a26[$j]','','','','', ";
			//$ksql.="'$yearp1[$j]','$yearp1[$j]','$yearp1[$j]','','','', ";
			//$ksql.="'','','','','','')";
//if($a[3]=='610426-1041211'){ echo "$a[3] $sql $ksql <br>";}


		//echo "$j ksql $ksql <bR>"; 
			mysql_query($ksql,$connect);

			//저장후 CertiTableNum 찾아야 2012DaeriMember 에저장 하지

			$psql="SELECT num FROM 2012CertiTable WHERE policyNum='$q[$j]' and 2012DaeriCompanyNum='$drow[num]'";
			$prs=mysql_query($psql,$connect);
			$prow=mysql_fetch_array($prs);
			$CertiTableNum=$prow[num];

			//대리운전회사 월별보험료 입력과 나이구분을 입력하자
			include "./pReminum.php";
			/////////////////////////////////////////////
			//대리기사들을 조회하자
			//$sdSql="SELECT * FROM $dbDrive WHERE $ssNum ='$a0[$j]' and push='4'";
			$sdSql="SELECT * FROM $dbDrive WHERE $ssNum ='$a0[$j]' and push!='1' ";

			//echo "sdSql $sdSql <br>";
			$sdrs=mysql_query($sdSql,$connect);

			$sdNum=mysql_num_rows($sdrs);
			for($p=0;$p<$sdNum;$p++){

				$sdrow=mysql_fetch_array($sdrs);

				//나이 계산을 해야 하는데
				$jumin1=$sdrow[jumin];

				if($_k==3 || $_k==4){//현대 ,lig 기준

					$endorseDay=$start[$j];
				}

				include "./nai.php";

				$nai[$p]=$age;
				//각각의 대리기사을 2012DaeriMember  저장을 해야 하겠지

			if($_k==2){// 동부화재인 경우만
				//납입회차를 조회 하기위해
				$nSql="SELECT * FROM  dongbu2perBankList  WHERE driver_num='$sdrow[num]'";
				$nrs=mysql_query($nSql,$connect);
				$nRow=mysql_fetch_array($nrs);

				//$nRow[nab] //납입회차
				//echo "$sdrow[num] || $sdrow[name]  || $nRow[nab] <Br>";
				//	$nRow[nab] //납입회차
				if($nRow[nab]==5 || $nRow[nab]==6){

					$donbuJeongGi='2013-09-11';
				}
				if($nRow[nab]==7){
					$donbuJeongGi='2013-08-30';
				}
				if($nRow[nab]==1 || $nRow[nab]==2){
					$donbuJeongGi='2014-01-19';
				}	


			}

			if($_k!=2){//현대화재

				$sdrow[certiNum]=$sdrow[appNumber];
				$sdrow[appNumber]='';
			}
			
			$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
			$insertSql.="Name,Jumin,nai,push,etag,Hphone,InPutDay, ";
			$insertSql.="OutPutDay,EndorsePnum,dongbuCerti,dongbuSelNumber,dongbujeongi,nabang_1)";
			$insertSql.="values ('$drow[num]','$_k','$CertiTableNum', ";
			$insertSql.="'$sdrow[name]','$sdrow[jumin]','$nai[$p]','$sdrow[push]','$sdrow[etag]','','$sdrow[wdate]', ";
			$insertSql.="'','','$sdrow[certiNum]','$sdrow[appNumber]','$donbuJeongGi','$nRow[nab]')";
			//if($_k==2){
			//	echo "insertSql $insertSql <br>";

			if($a[3]=='671221-1548617'){ echo "d  $insertSql <Br>";}
			//}
			mysql_query($insertSql,$connect);

			}

			//아이디 업체 정리를 위하여 
			//흥국인경우 ssang_produce
		if($_k!=3){//lig가 아닌 경우에
				$ppSql="SELECT * FROM $produce   WHERE permit='5' and $ssNum ='$a0[$j]'";
			
				//echo "ppSql  $ppSql<br>";
				$ppRs=mysql_query($ppSql,$connect);
				$ppNum=mysql_num_rows($ppRs);
				//echo "ppNum $ppNum <Br>";
				for($_j=0;$_j<$ppNum;$_j++){
					$ppRow=mysql_fetch_array($ppRs);


					$newPmemberNum[$_j]=$ppRow[newPmemberNum];

				/*	switch($ppRow[permit]){
						case 5 :
							$permit[$j]=1;
							break;
						case 6 :
							$permit[$j]=2;
							break;
					}*/
					$pMsql="SELECT * FROM $pmember  WHERE num='$newPmemberNum[$_j]'";


					//echo "pMS $pMsql <br>";
					$pRs=mysql_query($pMsql,$connect);
					$pRow=mysql_fetch_array($pRs);
					$mem_id[$_j]=$pRow[mem_id];
					$npame[$_j]=$pRow[name];


					if(!$pRow[mem_id]){continue;}
				
					$hphone[$_j]=$a4[$j];
					//비밀번호 핸드폰번호로 setting 
					//list($sphone1[$_j],$sphone2[$_j],$sphone3[$_j])=explode("-",$hphone[$_j]);
					//$passwd[$_j]=$sphone2[$_j].$sphone3[$_j];
					$tun=explode("-",$hphone[$_j]);
					//$passwd[$_j]=$sphone2[$_j].$sphone3[$_j];
					$passwd[$_j]=$tun[1].$tun[2];	
				      //echo "$j $a3[$j]  $a1[$j]  $passwd[$_j] || $tun[1] || $tun[2] <br>";
					$pas[$_j]=md5($passwd[$_j]);

					///
					$level[$_j]=$pRow[level];
					$kind[$_j]=$pRow[kind];
					//$cSql="SELECT num FROM 2012Costomer WHERE mem_id='$mem_id[$_j]'";

					$cSql="SELECT num FROM 2012Costomer WHERE 2012DaeriCompanyNum='$drow[num]' and mem_id='$mem_id[$_j]'";
					//echo "cSql $cSql <bR>";
					$cRs=mysql_query($cSql,$connect);
					$cNum=mysql_num_rows($cRs);

					//echo "cNum $cNum <br>";

					if(!$cNum){

					$insert="INSERT INTO 2012Costomer(2012DaeriCompanyNum,mem_id,passwd,name,jumin1,jumin2,hphone,email, ";
					$insert.="level,mail_check,wdate,inclu,kind,pAssKey,pAssKeyoPen,permit )";
					$insert.="values ('$drow[num]','$mem_id[$_j]','$pas[$_j]','$npame[$_j]','','','$hphone[$_j]','', ";
					$insert.="'$level[$_j]','','','','$kind[$_j]','','','1')";
					
					//echo "insert $insert <br>";
					// echo " 1 $a1[$j] $mem_id[$_j]  $hphone[$_j] insert $insert <BR>";
					mysql_query($insert,$connect);
					}
				}
			}
		//}	
	}else{//주민번호 가 있는데 증권번호가 다르면 증권번호만 따로 2012CertiTable  입력후 이하 과정을 거쳐야 겠지요


		//현재 2012CertiTable  저장 되어 있는 증권번호와 동일이 아닌 경우 
		
		$hsql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$drow[num]'";
		$hrs=mysql_query($hsql,$connect);
		$hrow=mysql_fetch_array($hrs);
		
		if($q[$j]!=$hrow[policyNum]){//입력되어 있는 증권번호가 다르다면 새로 입력한다

		//$gSql="SELECT num FROM 2012DaeriCompany WHERE jumin='$a3[$j]'";
		//$grs=mysql_query($gSql,$connect);
		//$grow=mysql_fetch_array($grs);

		//$drow[num]=$grow[num];//2012DaeriCompanyNum
		//그리고 저장하고 나서 

		//if($start[$j]>='2011-05-01'){
			/*$ksql="INSERT INTO 2012CertiTable  (2012DaeriCompanyNum,InsuraneCompany,startyDay,policyNum,nabang,nabang_1 )";
			$ksql.="values ('$drow[num]','$_k','$start[$j]','$q[$j]','$nabang[$j]','$nabang_1[$j]')";*/
			if($_k==1){//흥국화재
			$yearp1[$j]=585500;
			}
			if($_k==2){//동부화재인경우만
				$q[$j]='';
				$nabang_1[$j]='10';
			}
			if($_k==4){//

				$a18[$j]=$a17[$j];
			}
			$ksql="INSERT INTO 2012CertiTable  (2012DaeriCompanyNum,DaeriCompany,InsuraneCompany,startyDay,FirstStart,FirstStartDay, ";
			$ksql.="policyNum,nabang,nabang_1,divi, ";
			$ksql.="vbank,v_number,content) ";
			//$ksql.="preminum1,preminum2,preminum3,preminum4,preminum5,preminum6,preminum7,preminum8,preminum9,preminum10, ";
			//$ksql.="preminumE1,preminumE2,preminumE3,preminumE4,preminumE5,preminumE6,preminumE7,preminumE8,preminumE9,preminumE10, ";
			//$ksql.="yearP1,yearP2,yearP3,yearP4,yearP5,yearP6, ";
			//$ksql.="yearPE1,yearPE2,yearPE3,yearPE4,yearPE5,yearPE6 )";
			$ksql.="values ('$drow[num]','$a1[$j]','$_k','$start[$j]','$a34[$j]','$a33[$j]',  ";
			$ksql.="'$q[$j]','$nabang[$j]','$nabang_1[$j]','2',";
			$ksql.="'$a11[$j]','$a12[$j]','$a13[$j]') ";
			//$ksql.="'$a15[$j]','$a16[$j]','$a17[$j]','$a18[$j]','$a19[$j]','$a20[$j]','','','','', ";
			//$ksql.="'$a21[$j]','$a22[$j]','$a23[$j]','$a24[$j]','$a25[$j]','$a26[$j]','','','','', ";
			//$ksql.="'$yearp1[$j]','$yearp1[$j]','$yearp1[$j]','','','', ";
			//$ksql.="'','','','','','')";
//if($a[3]=='610426-1041211'){ echo "$a[3] $sql  $ksql<br>";}
		//echo "$sj $ksql <br>";
//echo "ksql $ksql <br>";
//if($_k==3){echo "ksql $ksql <br>";}
			mysql_query($ksql,$connect);

			//저장후 CertiTableNum 찾아야 2012DaeriMember 에저장 하지

			$psql="SELECT num FROM 2012CertiTable WHERE policyNum='$q[$j]' and 2012DaeriCompanyNum='$drow[num]'";

			//echo "psql $psql <br>";
			$prs=mysql_query($psql,$connect);
			$prow=mysql_fetch_array($prs);

			$CertiTableNum=$prow[num];

			//대리운전회사 월별보험료 입력과 나이구분을 입력하자
			include "./pReminum.php";
			/////////////////////////////////////////////
			//대리기사들을 조회하자

			//$sdSql="SELECT * FROM $dbName WHERE $dbDrive ='$a0[$j]' and push='4'";
			$sdSql="SELECT * FROM $dbDrive WHERE $ssNum ='$a0[$j]' and push!='1'";
			
			//echo "k $_k <br>";
			//echo "sdSql $sdSql <br>";

			$sdrs=mysql_query($sdSql,$connect);

			$sdNum=mysql_num_rows($sdrs);
				for($p=0;$p<$sdNum;$p++){

					$sdrow=mysql_fetch_array($sdrs);

					//나이 계산을 해야 하는데
					$jumin1=$sdrow[jumin];
					if($_k==3 || $_k==4){//현대 ,lig 기준

						$endorseDay=$start[$j];
					}
					include "./nai.php";
					$nai[$p]=$age;
					//각각의 대리기사을 2012DaeriMember  저장을 해야 하겠지
         /*
				$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
				$insertSql.="Name,Jumin,nai,push,etag,Hphone,InPutDay )";
				$insertSql.="values ('$drow[num]','$_k','$CertiTableNum', ";
				$insertSql.="'$sdrow[name]','$sdrow[jumin]','$nai[$p]','$sdrow[push]','$sdrow[etag]','','$sdrow[wdate]')";

		*/
			if($_k==2){// 동부화재인 경우만
				//납입회차를 조회 하기위해
				$nSql="SELECT * FROM  dongbu2perBankList  WHERE driver_num='$sdrow[num]'";

				//echo "sn $nSql <Br>";
				$nrs=mysql_query($nSql,$connect);
				$nRow=mysql_fetch_array($nrs);

			//	$nRow[nab] //납입회차
				if($nRow[nab]==5 || $nRow[nab]==6){

					$donbuJeongGi='2013-09-11';
				}
				if($nRow[nab]==7){
					$donbuJeongGi='2013-08-30';
				}
				if($nRow[nab]==1 || $nRow[nab]==2){
					$donbuJeongGi='2014-01-19';
				}	


			}

			

			if($_k!=2){//현대화재

				$sdrow[certiNum]=$sdrow[appNumber];
				$sdrow[appNumber]='';
			}
			$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
			$insertSql.="Name,Jumin,nai,push,etag,Hphone,InPutDay, ";
			$insertSql.="OutPutDay,EndorsePnum,dongbuCerti,dongbuSelNumber,dongbujeongi,nabang_1 )";
			$insertSql.="values ('$drow[num]','$_k','$CertiTableNum', ";
			$insertSql.="'$sdrow[name]','$sdrow[jumin]','$nai[$p]','$sdrow[push]','$sdrow[etag]','','$sdrow[wdate]', ";
			$insertSql.="'','','$sdrow[certiNum]','$sdrow[appNumber]','$donbuJeongGi','$nRow[nab]')";

				if($a[3]=='671221-1548617'){ echo "d  $insertSql <Br>";}
				mysql_query($insertSql,$connect);

				}
			//}	
		}
		
		//아이디 


		if($_k!=3){//lig가 아닌 경우에
				$ppSql="SELECT * FROM $produce   WHERE permit='5' and $ssNum ='$a0[$j]'";
			
				//echo "ppSql  $ppSql<br>";
				$ppRs=mysql_query($ppSql,$connect);
				$ppNum=mysql_num_rows($ppRs);
				//echo "ppNum $ppNum <Br>";
				for($_j=0;$_j<$ppNum;$_j++){
					$ppRow=mysql_fetch_array($ppRs);


					$newPmemberNum[$_j]=$ppRow[newPmemberNum];

				/*	switch($ppRow[permit]){
						case 5 :
							$permit[$j]=1;
							break;
						case 6 :
							$permit[$j]=2;
							break;
					}*/
					$pMsql="SELECT * FROM $pmember  WHERE num='$newPmemberNum[$_j]'";


					//echo "pMS $pMsql <br>";
					$pRs=mysql_query($pMsql,$connect);
					$pRow=mysql_fetch_array($pRs);
					$mem_id[$_j]=$pRow[mem_id];
					$npame[$_j]=$pRow[name];
					if(!$pRow[mem_id]){continue;}
					//echo "name $npame[$_j]  $pRow[name] <br>";
					//$passwd[$_j]=$pRow[passwd];
					//$hphone[$_j]=$pRow[hphone];

					//echo "성준 $a4[$j] <Br>";
					$hphone[$_j]=$a4[$j];
					//비밀번호 핸드폰번호로 setting 
					//list($sphone1[$_j],$sphone2[$_j],$sphone3[$_j])=explode("-",$hphone[$_j]);
					$tun=explode("-",$hphone[$_j]);
					//$passwd[$_j]=$sphone2[$_j].$sphone3[$_j];
					$passwd[$_j]=$tun[1].$tun[2];

				    // echo "$j $a3[$j]  $a1[$j] || $a4[$j] || $hphone[$_j] ㅖㅖ $passwd[$_j] || $tun[1] || $tun[2] <br>";
					$pas[$_j]=md5($passwd[$_j]);
					///
					$level[$_j]=$pRow[level];
					$kind[$_j]=$pRow[kind];
					//$cSql="SELECT num FROM 2012Costomer WHERE mem_id='$mem_id[$_j]'";

					$cSql="SELECT num FROM 2012Costomer WHERE 2012DaeriCompanyNum='$drow[num]' and mem_id='$mem_id[$_j]'";
					//echo "cSql $cSql <bR>";
					$cRs=mysql_query($cSql,$connect);
					$cNum=mysql_num_rows($cRs);
				//echo "cNum $cNum <br>";
					if(!$cNum){

					$insert="INSERT INTO 2012Costomer(2012DaeriCompanyNum,mem_id,passwd,name,jumin1,jumin2,hphone,email, ";
					$insert.="level,mail_check,wdate,inclu,kind,pAssKey,pAssKeyoPen,permit )";
					$insert.="values ('$drow[num]','$mem_id[$_j]','$pas[$_j]','$npame[$_j]','','','$hphone[$_j]','', ";
					$insert.="'$level[$_j]','','','','$kind[$_j]','','','1')";
					//  echo " 2 $a1[$j] $mem_id[$_j]  $hphone[$_j] insert $insert <BR>";
					
					mysql_query($insert,$connect);
					}
				/*	$hphone[$_j]=$a4[$j];
					//비밀번호 핸드폰번호로 setting 
					list($sphone1[$_j],$sphone2[$_j],$sphone3[$_j])=explode("-",$hphone[$_j]);
					$passwd[$_j]=$sphone2[$_j].$sphone3[$_j];

				    //echo "$j $a3[$j]  $a1[$j]  $passwd[$_j] <br>";
					$passwd[$_j]=md5($passwd[$_j]);

					///
					$level[$_j]=$pRow[level];
					$kind[$_j]=$pRow[kind];

					//$cSql="SELECT num FROM 2012Costomer WHERE mem_id='$mem_id[$_j]'";

					$cSql="SELECT num FROM 2012Costomer WHERE 2012DaeriCompanyNum='$drow[num]'";
					//echo "cSql $cSql <bR>";
					$cRs=mysql_query($cSql,$connect);
					$cNum=mysql_num_rows($cRs);

					if(!$cNum){

					$insert="INSERT INTO 2012Costomer(2012DaeriCompanyNum,mem_id,passwd,name,jumin1,jumin2,hphone,email, ";
					$insert.="level,mail_check,wdate,inclu,kind,pAssKey,pAssKeyoPen,permit )";
					$insert.="values ('$drow[num]','$mem_id[$_j]','$passwd[$_j]','$npame[$_j]','','','$hphone[$_j]','', ";
					$insert.="'$level[$_j]','','','','$kind[$_j]','','','1')";
					
					//echo "insert $insert <br>";
					 echo " 2 $mem_id[$_j]  $hphone[$_j] insert $insert <BR>";
					mysql_query($insert,$connect);
					}*/
				}
			}

			//아이디작업 끝
	}
}
?>