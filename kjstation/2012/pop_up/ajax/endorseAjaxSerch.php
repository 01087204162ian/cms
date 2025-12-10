<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);//2012DaeriCompanyNum
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$eNum =iconv("utf-8","euc-kr",$_GET['eNum']);
	
	//$endorseDay =iconv("utf-8","euc-kr",$_GET['endorseDay']);
if($num){//


//$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$CertiTableNum' and pnum='$eNum' ";
$eSql="SELECT * FROM 2012EndorseList WHERE CertiTableNum='$CertiTableNum' and pnum='$eNum' ";

//echo $eSql;
	$eRs=mysql_query($eSql,$connect);
	$eRow=mysql_fetch_array($eRs);


		//선택을 접수로 변경하기 위해 

		if($eRow[ch]==11){
		  $qupate="UPDATE 2012EndorseList SET ch='1' WHERE num='$eRow[num]'";
		  mysql_query($qupate,$connect);
		}
	$endorse_day=$eRow[endorse_day];

		
	$sql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$FirstStartDay=$row[FirstStartDay];  //처음 받는날 

	 


	$a[1]=$row[company];
	$a[2]=$row[Pname];
	$a[3]=$row[jumin];
	$a[4]=$row[hphone];
	$a[5]=$row[cphone];

	//담당자를 찾기위해 

		$dSql="SELECT name FROM 2012Member WHERE num='$row[MemberNum]'";
		$drs=mysql_query($dSql,$connect);

		$dRow=mysql_fetch_array($drs);


	$a[6]=$dRow[name];
	//$a[6]=$row[damdanga];
	$a[7]=$row[FirstStart];
/*	$a[8]=$row[fax];
	$a[9]=$row[cNumber];
	$a[10]=$row[lNumber];
	$a[11]=$row[a11];
	$a[12]=$row[a12];
	$a[13]=$row[a13];
	$a[14]=$row[a14];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];*/

	

	mysql_query($sql,$connect);
	
	$message='조회 되었습니다!!';


	//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
	$rs=mysql_query($certiSql,$connect);
	$rCertiRow=mysql_fetch_array($rs);

	$fstartyDay=$rCertiRow[fstartyDay];

	//2015-03-19  광명7080대리운전  각 증권별로 월 받는 날이 다름 5일자 25일자 구분 하기 

	// 동일 대리운전회사가 증권별로 보험료 정산일이 다른 경우 

	if($fstartyDay){$FirstStartDay=$fstartyDay;}

	$a[14]=$rCertiRow[InsuraneCompany];//보험회사
	switch($a[14]){
		case 1 :
			$a[8]='흥국';
			break;
		case 2 :
			$a[8]='DB';
			break;
		case 3 :
			$a[8]='KB';
			break;
		case 4 :
			$a[8]='현대';
			break;
		case 5 :
			$a[8]='롯데';
			break;
		case 6 :
			$a[8]='더케이';
			break;
		case 7 :
			$a[8]='MG';
			break;
		case 8 :
			$a[8]='삼성';
			break;
	}
	$a[9]=$rCertiRow[policyNum];
	$a[10]=$rCertiRow[startyDay];
	
	$endE=date("Y-m-d ", strtotime("$a[10] + 1 year"));

	$sigiS=explode("-",$a[10]);

	$endS=explode("-",$endE);

	$a[10]=$sigiS[0].".".$sigiS[1].".".$sigiS[2];
	$end=$endS[0].".".$endS[1].".".$endS[2];
	$a[10]=$a[10]."~".$end;
	$a[11]=$rCertiRow[nabang_1]."/".$rCertiRow[nabang];
	$a[12]=$rCertiRow[nabang_1];

	//$a[13]=		$rCertiRow[divi];

	switch($rCertiRow[divi])
		{
				case 1 :
					$a[13]='정상';

					break;
				case 2:
					$a[13]='1/12씩';
					break;
				default:
					$rCertiRow[divi]=1;
					$a[13]='정상';
					break;
		}

}
//배서 총 보험료 
$totalPreminum=0;
$haejiInwon=0;
$chugaInwon=0;
echo"<data>\n";
	echo "<sql>".$qupate."</sql>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
///	
	$Dsql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum' AND ";
	$Dsql.="( InPutDay='$endorse_day' or OutPutDay='$endorse_day' )";
	$Dsql.=" order by Jumin desc";
	 //echo "Dsql $Dsql <br>";
	//$Drs=mysql_query($Dsql,$connect);
	//$DNum=mysql_num_rows($Drs);
    
//페이지 구성 
    $result2 = mysql_query($Dsql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	///페이지 마다 20개의 Data 만 불러 온다                                            //
	/////////////////////////////////////////////////////////////////////////////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =9;
	$count = $max_num * $page - 9;
	if($total<9){
		$last=$total;
	}else{
		$last=9;
	}
	if($total-$count<9){//총개수-$congt 32-9인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;


	$tast=$last-$count;
	echo "<Rnum>".$total."</Rnum>\n";
//	for($_m=0;$_m<$DNum;$_m++)
//	{
	echo "<total>".$total."</total>\n";
	echo "<count>".$count."</count>\n";
	echo "<last>".$tast."</last>\n";
	for($k=$count;$k<$last;$k++){


		//$DRow=mysql_fetch_array($Drs);
		$e[1]=mysql_result($result2,$count,"etag");//,탁송일반
		$e[2]=mysql_result($result2,$count,"sangtae");
		$e[3]=mysql_result($result2,$count,"num");
		$e[4]=mysql_result($result2,$count,"Name");
		$e[5]=mysql_result($result2,$count,"Jumin");
		$e[6]=mysql_result($result2,$count,"push");


		$e[7]=mysql_result($result2,$count,"dongbusigi");//,탁송일반
		$e[8]=mysql_result($result2,$count,"dongbujeongi");//$e[8];
		$e[9]=mysql_result($result2,$count,"cancel");//$e[9];
		$e[10]=mysql_result($result2,$count,"changeCom");//$e[10];
		$e[11]=mysql_result($result2,$count,"EndorsePnum");//$e[11];
		$e[12]=mysql_result($result2,$count,"dongbuCerti");//$e[12];
		$e[13]=mysql_result($result2,$count,"dongbuSelNumber");	//$e[13];

		$e[14]=mysql_result($result2,$count,"nabang_1");
		$e[15]=mysql_result($result2,$count,"nai");	


		$e[30]=mysql_result($result2,$count,"InsuranceCompany");	
		$e[27]=mysql_result($result2,$count,"sPrem");//삼성화재 보험료


		$e[50]= mysql_result($result2,$count,"2012DaeriCompanyNum");
	
	//if(!$e[15]){//나이 계산을 위해 

		if($a[30]==2){// DB 화재 인경우
			
			
			//DB화재는 가입일 기준이지만 가입일이 2011.09.10일이고 su
			//모증권의 보험시작일이 2012.09.11일인 경우  가입일보다 
			//2012.09.11일이 보험 나이  계산 기준일이 된다
			
			    $mSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);
			if(mysql_result($result2,$count,"InPutDay")>$mRow[startyDay]){
				$mRow[startyDay]=mysql_result($result2,$count,"InPutDay");
			}else{
				$mRow[startyDay]=$mRow[startyDay];//DB화재는 가입일 기준으로 
			}	//
		}else{
			
			//만나이 계산을 위해 
				$mSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);	
			//	echo "mSql $mSql <bR>";
				//DB화재를 제외한 나머지 회사는 가입일 기준으로 
			//

			// Lig 화재는 2012CertiTable 기준이 아니라 2012Certi 기준을로 나이 계산을 하기 위해 

			if($e[30]==3 || $e[30]==4 ){

					$rSql="SELECT * FROM  2012Certi  WHERE certi='$e[12]'";
					$rRs=mysql_query($rSql,$connect);

					  $rRow=mysql_fetch_array($rRs);
					  
					  $mRow[startyDay] =$rRow[sigi];


			}


		}
		//만나이 계산을 위해 
			$juminLength=strlen($e[5]);
			if($juminLength==14){
				$p=explode("-",$e[5]);
				$p[0]=$p[0];
			}else{

				$p[0] =substr($e[5], 0, 6);
			}
			$s=explode("-",$mRow[startyDay]);
			$m1=substr($mRow[startyDay],0,4);
			$m2=substr($mRow[startyDay],5,2);
			$m3=substr($mRow[startyDay],8,2);
			$sigi=$m1.$m2.$m3;			
			$birth="19".$p[0];
			$p[0]=$sigi-$birth;
			$p[0]=floor(substr($p[0],0,2));



		//만나이 계산을 완료 후  저장한다
		//if($a[14]==7){ //Mg화재 증권번호 입력을 위해
		//		//include "./php/mgCerti.php"; 
		//}else{
			$tupdate="UPDATE 2012DaeriMember SET nai='$p[0]' WHERE num='$e[3]'";

			//echo "tu $tupdate ";
			mysql_query($tupdate,$connect);
		//}

		$e[15]=$p[0];



		//개인별 손해율 찾기 위해 

		//2019-10-19
		include "./php/personRate.php";

		//inSurancePreminum 에서 사용함
		//preminumNaiEndorse.php 내의 EndorseDay.php 에서 사용함
	//	}
		//moValue가 2이면 모증권 번호르 찾아서 모증권의 보험료 기준으로 현재일의 보험료를 구한다

		$moValue[$k]=$mRow[moValue];
		$moNum[$k]=$mRow[moNum];


//보험회사에 내는 보험료 산출 2016-03-19 정리 연령변경 반영
		if($e[30]==8){  //삼성화재
			include "./php/inSurancePreminum.php";
		}
		

		if($e[30]==3){  //Lig 화재 
			include "./php/inSurancePreminum3.php";
		}

		if($e[30]==4){  //현대해상에 내는 보험료 계산하기위해
			include "./php/inSurancePreminum4.php";
		}

		if($e[30]==5){  //롯데에 내는 보험료 계산하기위해
			include "./php/inSurancePreminum5.php";
		}
		//$e[12]

		//echo "this $thisPreminum ";
		if($e[27]){				
			$thisPreminum=$e[27];//삼성화재,KB 보험료가 수정된 것이 있다면 
		}
		
		//$DRow[num]
		switch($e[1]){
			case 1 :

				$etag='일반';
				break;
			case  2:
				$etag='탁송';
				break;
			case  3:
				$etag='확탁송';
				break;
			default:
				$etag='일반';
				break;
		}
		

		//보험료 구하기 위해 
$pchagne=1;//preminumNaiEndorse.php 에서 사용하기 위해 2는 endorseChange.php 
//배서기준일 기준으로 보험료 산출하기 위해 2012-10-16
//echo "dd $endorse_day <br>";

$em=explode("-",$endorse_day);
 
//echo "today $today ";

$today=$em[2];

//
		include "./php/preminumNaiEndorse.php";  
		//$fakeEndorsePre[$k] 정상분납인 경우에도 월납 배서보험료를 계산하여 업체에게 알려주기 위함
		// $fakeEndorsePre[$k], $endorsePre[$k] 계산됨

		
	if($e[2]==2){//배서처리후 
		switch($Dpush){
			case 1 :	
				$Dpush=3;//
				break;
			case 2 :
			    $Dpush=4;
				break;
			case 4 :

				$Dpush=1;
				break;
		}
		
	}
		switch($Dpush){
				case 1 ://청약		
					$totalPreminum+=$endorsePre[$k];
					$chugaInwon++;
					break;
				case 4 ://해지
					$totalPreminum+=$endorsePre[$k];
					$haejiInwon++;
					break;
			}


    //DB화재 인 경우 회차를 구하기 위해 

	include "./php/nab.php";


    if(!$e[7]){//시기가 배서기준일

		$e[7]=$endorse_day;

		//$DR=explode("
		$e[8]=$endE;
	}
	//echo "<member2Num".$k.">"."[".$Dsangte."]".$aj."</member2Num".$k.">\n";//
	echo "<memberNum>".$e[3]."</memberNum>\n";//
	echo "<Name>".$e[4]."[".$e[15]."]"."</Name>\n";//
	echo "<Jumin>".$e[5]."</Jumin>\n";//
	echo "<push>".$e[6]."</push>\n";//
	echo "<EndorsePnum>".$e[11]."</EndorsePnum>\n";
	echo "<EndorsePreminum>".number_format($endorsePre[$k])."</EndorsePreminum>\n";//
	echo "<Endorse2Preminum>".$endorsePre[$k].$fakeEndorsePre[$k]."</Endorse2Preminum>\n";//// 월납인 경우 내야하는 보험료
	//echo "ㅂㅐ서 $endorsePreminum";
		//2018-11-29
		//이파리인 경우 
		// 자회사는 기존 대로 모회사 기준 보험료 구하이기 위해 
		if($mRow['moValue']==2){
			$m_sql="SELECT * FROM 2012CertiTable WHERE policyNum='$mRow[policyNum]' AND moValue='1'";
			//echo $m_sql; echo "<br>";
			$m_rs=mysql_query($m_sql,$connect);
			$m_row=mysql_fetch_array($m_rs);
			$CertiTableNum=$m_row[num];

			include "./php/preminumNaiEndorse.php";


			$m_endorsePreminum=$endorsePreminum;

			//echo "$CertiTableNum ㅂㅐ서 $endorsePreminum";
		}
	echo "<m_endorsePreminum>".$m_endorsePreminum."</m_endorsePreminum>\n";//모계약 기준 보험료 
	
	echo "<dongbuCerti>".$e[12]."</dongbuCerti>\n";//
	echo "<dongbuSelNumber>".$e[13]."</dongbuSelNumber>\n";//
	echo "<dongbusigi>".$e[7]."</dongbusigi>\n";//
	echo "<dongbujeongi>".$e[8]."</dongbujeongi>\n";//
	echo "<nabang_1>".$cha."</nabang_1>\n";//
	//echo "<sPrem>".$beforePreminum.'/'.$afterPreminum.'/'.$thisPreminum.'/'.$daumPreminum.'/'.$yPreminum.$nabang_1.'/'.$before_gijun.'/'.$after_gijun.'/'.$beforePreminum.'/'.$afterPreminum."</sPrem>\n";//보험회사에 내는 보험료

	echo "<sPrem>".$thisPreminum."</sPrem>\n";//보험회사에 내는 보험료
	echo "<personRate>".$personRate."</personRate>\n";//개인별 손해율 적용여부

	//증권의 시작일이 특정한 날 보다 큰 경우
	//
	$gijunDay__='2019-10-30';

	//echo strtotime($mRow[startyDay]); echo "<BR>";
	//echo strtotime($gijunDay_);
	if(strtotime($mRow[startyDay])>strtotime($gijunDay__)){
		$startyDay2=1; //배서하면서 개인별 요율을 묻기 위해
	}else{
		$startyDay2=2;
	}
	echo "<startyDay2>".$startyDay2."</startyDay2>\n";//증권의 시작일
	echo "<startyDay>".$mRow[startyDay]."</startyDay>\n";//증권의 시작일
	if($e[9]==13 || $e[9]==12){//가입한 다른보험회사가 있나 찾는다

			$Csql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' ";
			$Csql.="and InsuraneCompany!='$a[14]' and startyDay>='$yearbefore'";
			
			$Crs=mysql_query($Csql,$connect);
			$Cnum=mysql_num_rows($Crs);

				$C2num=$Cnum+1;//개수보다 하나 많게 하는 이유은 ?
				echo "<Cnum>".$C2num."</Cnum>\n";
				
			for($_p=0;$_p<$C2num;$_p++){

				$Crow=mysql_fetch_array($Crs);

				$Ccnum[$_p]=$Crow[num];
				$Cinsum[$_p]=$Crow[InsuraneCompany];

				//$Snum[$_p]=$Crow[changeCom];
				if($_p==$Cnum){// 앞부분에 선택을 만들기 위해 
					$Ccnum[$_p]='99999999';
					$Cinsum[$_p]='99';
				}
				echo "<Ctnum".$k.$_p.">".$Ccnum[$_p]."</Ctnum".$k.$_p.">\n";//2012CertiTablenum
				echo "<inum".$k.$_p.">".$Cinsum[$_p]."</inum".$k.$_p.">\n";
				
			}


		}

	if(!$e[10]){$e[10]=9999;}//취소또는 거절후 선택한 보험회사가 없는 경우에 대비
	echo "<Qnum>".$e[10]."</Qnum>\n";////변경한 보험회사 증권 2012CertiTablenum  이며 
	//이것을 통해 보험횟를 찾는다

		if($e[10]!='9999'){
			$C2sql="SELECT * FROM 2012CertiTable WHERE num='$e[10]'";
			//echo "C  $C2sql <br>";
			$C2rs=mysql_query($Csql,$connect);
			$C2row=mysql_fetch_array($C2rs);

			switch($C2row[InsuraneCompany]){
				case 1 :
						$chCom='흥국';
					break;
				case 2 :
						$chCom='DB';
					break;
				case 3 :
						$chCom='KB';
					break;
				case 4 :
						$chCom='현대';
					break;
				case 5 :
						$chCom='롯데';
					break;
				case 6 :
						$chCom='메리츠';
					break;
				case 7 :
						$chCom='MG';
					break;
				case 8 :
						$chCom='삼성';
					break;
			}
		}



	echo "<chCom>".$chCom."</chCom>\n";//

	echo "<etag>".$etag."</etag>\n";//
	//echo "<push>".$e[6]."</push>\n";//
	echo "<cancel>".$e[9]."</cancel>\n";//
	echo "<sangtae>".$e[2]."</sangtae>\n";//
	//echo "<sangtae11>".$yPreminum."</sangtae11>\n";//
/*	if($endorsePre[$k]==0){
		$endorsePre[$k]='';
	}*/
	

$count++;
	}
	

	$smsContent="추가".$chugaInwon."명 해지".$haejiInwon."명 보험료 ".number_format($totalPreminum);
    /***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
		echo "<smsContent>".$smsContent."</smsContent>\n";
		echo "<endorse_day>".$endorse_day."</endorse_day>\n";
		echo "<message>".$message."</message>\n";

	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>