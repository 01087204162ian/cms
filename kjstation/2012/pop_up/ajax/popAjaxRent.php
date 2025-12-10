<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$num =iconv("utf-8","euc-kr",$_GET['num']);
for($i=1;$i<15;$i++){
	$a[$i] =iconv("utf-8","euc-kr",$_GET['a'.$i]);					//은행명
		
}	
for($i=28;$i<31;$i++){
	$a[$i] =iconv("utf-8","euc-kr",$_GET['a'.$i]);					//은행명
		
}	

if($num){//대리운전 회사가 이미 등록 되어 있다고 하면 update;

	$sql="UPDATE 2014RentCompnay SET ";
	$sql.="company='$a[1]',Pname='$a[2]',jumin='$a[3]',hphone='$a[4]',cphone='$a[5]',damdanga='$a[6]',divi='$a[7]',fax='$a[8]', ";
	$sql.="cNumber='$a[9]',lNumber='$a[10]',a11='$a[11]',a12='$a[12]',a13='$a[13]',a14='$a14', ";
	$sql.="postNum='$a[28]',address1='$a[29]',address2='$a[30]' ";
	$sql.="WHERE num='$num'";

	mysql_query($sql,$connect);
	
	$message='수정되었습니다!!';
	$store='2';
	$ksql="SELECT * FROM 2014RentCompnay WHERE num='$num'";
	$krs=mysql_query($ksql,$connect);
	$krow=mysql_fetch_array($krs);

}else{//없다면 store;


//입력 전 주민번호로 다시한번 체크 해야 겠네 막 저장할 수 도 있으니까
	$sql="INSERT INTO 2014RentCompnay (company,Pname,jumin,hphone,cphone,damdanga,divi,fax, ";
	$sql.="cNumber,lNumber,a11,a12,a13,a14,postNum,address1,address2 ) ";
	$sql.="values ('$a[1]','$a[2]','$a[3]','$a[4]','$a[5]','$a[6]','$a[7]','$a[8]','$a[9]', ";
	$sql.="'$a[10]','$a[11]','$a[12]','$a[13]','$a[14]','$a[28]','$a[29]','$a[30]')";

	mysql_query($sql,$connect);


	//저장후 num을 찾기 위해 


	$ksql="SELECT * FROM 2014RentCompnay WHERE jumin='$a[3]'";
	$krs=mysql_query($ksql,$connect);
	$krow=mysql_fetch_array($krs);

	$num=$krow[num];
	$message='저장 되었습니다!!';
	$store='2';
}

//담당자를 찾기위해 

		$dSql="SELECT name FROM 2012Member WHERE num='$krow[MemberNum]'";
		//echo "s $dSql <Br>";
		$drs=mysql_query($dSql,$connect);

		$dRow=mysql_fetch_array($drs);


	$a[6]=$dRow[name];
/*	
for($_u_=1;$_u_<15;$_u_++){
		$a[$_u_]=$_u_;//
		
    }*/
echo"<data>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	//$a[28]=28;$a[29]=29;$a[30]=30;
	for($_u_=28;$_u_<31;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	echo "<name32>"."수정"."</name32>\n";//
	echo "<message>".$message."</message>\n";
	echo "<store>".$store."</store>\n";// 기본 정보 수정 확인 메세지를 띠우기 위해
	//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$num' and startyDay>='$yearbefore'";

	$rs=mysql_query($certiSql,$connect);
	$Rnum=mysql_num_rows($rs);

//$inWonTotal=0;//초기화
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
				$newShin='신규입력';
				$unjen='운전자추가';
			}
			$inPnum=$InsuraneCompany[$_m];
			include "./php/nabState.php";//nabSunsoChange.php 공동으로 사용
			$b[15]=$rCertiRow[gita];
			//메모 내용을 모두 찾아서 

			$content[$_m]=$rCertiRow[content];

			//
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

	echo "<inWonTotal>".number_format($inWonTotal)."</inWonTotal>\n";
	include "./php/smsQuery.php";
	include "./php/smsData.php";//smsAjax.php도 공동 사용;
	include "./php/memoData.php";
	$cSize=sizeof($content);
	for($_v=0;$_v<$cSize;$_v++){

		echo "<content>".$content[$_v]."</content>\n";
	}
echo"</data>";

	?>