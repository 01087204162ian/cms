<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);
	
	$jumin=iconv("utf-8","euc-kr",$_GET['jumin']);
if($num){//대리운전 회사가 이미 등록 되어 있다고 하면 update;
	$sql="SELECT * FROM 2014RentCompnay WHERE num='$num'";
}
if($jumin){

	$sql="SELECT * FROM 2014RentCompnay WHERE jumin='$jumin'";
}
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

if($jumin){ // 주민번호로 조회 할때 num 값이 필요 

	$num=$row[num];
}

if($num){
		$store='2';
		$name32='수정';
		$message='조회 되었습니다!!';
}else{
		$store='1';//새로운 저장을 위해 
		$name32='저장';
		$message='새로운 저장을 하세요!!';
}
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
	//업체 I.D 찾기 위해 

		$iSql="SELECT * FROM 2012Costomer WHERE 2012DaeriCompanyNum='$num'";
		$iRs=mysql_query($iSql,$connect);
		$iRow=mysql_fetch_array($iRs);

		switch($iRow[permit]){
			case 1 :
				$permitName='허용';
				break;
			case 2 :
				$permitName='차단';
				break;
		}
	$a[7]=$iRow[mem_id].$permitName;
	$a[8]=$row[fax];
	$a[9]=$row[cNumber];
	$a[10]=$row[lNumber];//흥국일경우 계약자 성명
	$a[11]=$row[FirstStart];

//2012CertiTable 의 content  모두 조회해서 


	$a[12]=$row[a12];


	$a[13]=$row[a13];
	$a[14]=$row[a14];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];



	mysql_query($sql,$connect);
	
	





echo"<data>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	//$a[28]=28;$a[29]=29;$a[30]=30;
	for($_u_=28;$_u_<31;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	echo "<name32>".$name32."</name32>\n";//
	echo "<message>".$message."</message>\n";

	//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' and startyDay>='$yearbefore'";
	//$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' ";
	$rs=mysql_query($certiSql,$connect);
	$Rnum=mysql_num_rows($rs);

$inWonTotal=0;//초기화
	echo "<Rnum>".$Rnum."</Rnum>\n";
	for($_m=0;$_m<$Rnum;$_m++)
	{
			$rCertiRow=mysql_fetch_array($rs);

	
			$daeriInwonSql="SELECT num FROM 2012DaeriMember WHERE CertiTableNum='$rCertiRow[num]' and push='4'";
			$dRs=mysql_query($daeriInwonSql,$connect);
			$inWon[$_m]=mysql_num_rows($dRs);
			$inWonTotal+=$inWon[$_m];

			switch($rCertiRow[divi])
		{
				case 1 :
					$divi[$_m]='정상';

					break;
				case 2:
					$divi[$_m]='1/12씩';
					break;
				default:
					$rCertiRow[divi]=1;
					$divi[$_m]='정상';
					break;
		}
			$certiNum[$_m]=$rCertiRow[num];
			
			$InsuraneCompany[$_m]=$rCertiRow[InsuraneCompany];
			
			$startyDay[$_m]=$rCertiRow[startyDay];
			$policyNum[$_m]=$rCertiRow[policyNum];
			$nabang[$_m]=$rCertiRow[nabang];
			$nabang_1[$_m]=$rCertiRow[nabang_1];

			//각증권별 납입 상태를 파악하기 위해
			$sigiStart=$startyDay[$_m];
			$naBang=$nabang_1[$_m];
			if($certiNum[$_m]){
				$newShin='신규';
				$unjen='배서';
			}
			$inPnum=$InsuraneCompany[$_m];
			include "./php/nabState.php";//nabSunsoChange.php 공동으로 사용

			$b[15]=$rCertiRow[gita];
			//메모 내용을 모두 찾아서 

			$content[$_m]=$rCertiRow[content];

	
		echo "<naColor".$_m.">".$naColor."</naColor".$_m.">\n";//
		echo "<naState".$_m.">".$naState.$gigan."</naState".$_m.">\n";//
		echo "<certiNum".$_m.">".$certiNum[$_m]."</certiNum".$_m.">\n";//
		echo "<InsuraneCompany".$_m.">".$InsuraneCompany[$_m]."</InsuraneCompany".$_m.">\n";//
		echo "<startyDay".$_m.">".$startyDay[$_m]."</startyDay".$_m.">\n";//
		echo "<policyNum".$_m.">".$policyNum[$_m]."</policyNum".$_m.">\n";//
		echo "<nabang".$_m.">".$nabang[$_m]."</nabang".$_m.">\n";//
		echo "<nabang_1".$_m.">".$nabang_1[$_m]."</nabang_1".$_m.">\n";//
		echo "<inWon".$_m.">".$inWon[$_m]."</inWon".$_m.">\n";//
		echo "<sujeong".$_m.">"."수정"."</sujeong".$_m.">\n";//
		echo "<diviCo".$_m.">".$rCertiRow[divi]."</diviCo".$_m.">\n";//
		echo "<divi".$_m.">".$divi[$_m]."</divi".$_m.">\n";//
		echo "<moth".$_m.">".$rCertiRow[divi]."</moth".$_m.">\n";//
		echo "<input".$_m.">".$newShin."</input".$_m.">\n";//
		echo "<unjen".$_m.">".$unjen."</unjen".$_m.">\n";//
		echo "<b15".$_m.">".$b[15]."</b15".$_m.">\n";//
		
	}
	echo "<inWonTotal>".number_format($inWonTotal)."</inWonTotal>\n";
	echo "<store>".$store."</store>\n";
	include "./php/memoData.php";
	include "./php/smsQuery.php";
include "./php/smsData.php";//smsAjax.php도 공동 사용;

	

	$cSize=sizeof($content);
if($cSize>=1){
	for($_v=0;$_v<$cSize;$_v++){

		echo "<content>".$content[$_v]."</content>\n";
	}
}	
echo"</data>";

	?>