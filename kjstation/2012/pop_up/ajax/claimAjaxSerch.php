<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);//2012DaeriCompanyNum
	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DariMemberNum =iconv("utf-8","euc-kr",$_GET['DariMemberNum']);
	
	//$endorseDay =iconv("utf-8","euc-kr",$_GET['endorseDay']);
if($num){//



	

		
	$sql="SELECT * FROM 2012DaeriCompany WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$FirstStartDay=$row[FirstStartDay];
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
	$a[14]=$rCertiRow[InsuraneCompany];//보험회사
	switch($a[14]){
		case 1 :
			$a[8]='흥국';
			break;
		case 2 :
			$a[8]='동부';
			break;
		case 3 :
			$a[8]='LiG';
			break;
		case 4 :
			$a[8]='현대';
			break;
		case 5 :
			$a[8]='한화';
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
	$Dsql="SELECT * FROM 2012DaeriMember WHERE num='$DariMemberNum'";
	
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


		$OutPutDay=mysql_result($result2,$count,"OutPutDay");	

		$smSql="SELECT * FROM SMSData WHERE 2012DaeriMemberNum='$DariMemberNum' and push='2' and endorse_day='$OutPutDay'";
		$smRs=mysql_query($smSql,$connect);
		$smRow=mysql_fetch_array($smRs);

		
		$e[27]=$smRow[c_preminum];//삼성화재 보험료
	
		$e[30]=$smRow[preminum]; // 월보험료
		

	//	}
		//moValue가 2이면 모증권 번호르 찾아서 모증권의 보험료 기준으로 현재일의 보험료를 구한다

		$moValue[$k]=$mRow[moValue];
		$moNum[$k]=$mRow[moNum];

		
		//$DRow[num]
		switch($e[1]){
			case 1 :

				$etag='일반';
				break;
			case  2:
				$etag='탁송';
				break;
			default:
				$etag='일반';
				break;
		}
		

		//보험료 구하기 위해 

//
//사고 내역을 조사하기 위해 


		$saSql="SELECT * FROM 2012sago WHERE memberNum='$DariMemberNum' ";

		$saRs=mysql_query($saSql,$connect);

		$saRow=mysql_fetch_array($saRs);

	//echo "<member2Num".$k.">"."[".$Dsangte."]".$aj."</member2Num".$k.">\n";//
	echo "<memberNum>".$e[3]."</memberNum>\n";//
	echo "<Name>".$e[4]."[".$e[15]."]"."</Name>\n";//
	echo "<Jumin>".$e[5]."</Jumin>\n";//
	echo "<push>".$e[6]."</push>\n";//
	echo "<EndorsePnum>".$e[11]."</EndorsePnum>\n";//
	echo "<EndorsePreminum>".number_format($e[30])."</EndorsePreminum>\n";//
	echo "<Endorse2Preminum>".$e[30]."</Endorse2Preminum>\n";//
	echo "<dongbuCerti>".$e[12]."</dongbuCerti>\n";//
	echo "<dongbuSelNumber>".$e[13]."</dongbuSelNumber>\n";//
	echo "<dongbusigi>".$e[7]."</dongbusigi>\n";//
	echo "<dongbujeongi>".$e[8]."</dongbujeongi>\n";//
	echo "<nabang_1>".$cha."</nabang_1>\n";//

		echo "<date>".$saRow[date] ."</date>\n";
		echo "<comme>".$saRow[comment] ."</comme>\n";
		
	//echo "<sPrem>".$beforePreminum.'/'.$afterPreminum.'/'.$thisPreminum.'/'.$daumPreminum.'/'.$yPreminum.$nabang_1.'/'.$before_gijun.'/'.$after_gijun.'/'.$beforePreminum.'/'.$afterPreminum."</sPrem>\n";//삼성보험료

	echo "<sPrem>".$e[27]."</sPrem>\n";//삼성보험료

	
	



	echo "<chCom>".$chCom."</chCom>\n";//

	echo "<etag>".$etag."</etag>\n";//
	//echo "<push>".$e[6]."</push>\n";//
	echo "<cancel>".$e[9]."</cancel>\n";//
	echo "<sangtae>".$e[2]."</sangtae>\n";//

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
		echo "<endorse_day>".$OutPutDay."</endorse_day>\n";
		echo "<message>".$message."</message>\n";
		

	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
		
	
echo"</data>";

	?>