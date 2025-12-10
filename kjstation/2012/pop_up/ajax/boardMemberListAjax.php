<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);


	
	if($DaeriCompanyNum){
		$where2="and 2012DaeriCompanyNum='$DaeriCompanyNum'";
	}
	if($InsuraneCompany==99 || $InsuraneCompany==''){
		
	}else{
		$where3="and InsuranceCompany='$InsuraneCompany'";
		if($CertiTableNum){
		$where1="and CertiTableNum='$CertiTableNum'";
		}
	}
	$Csql="SELECT * FROM 2021CertiTable  WHERE num='$CertiTableNum'";
	//echo "C $Csql <br>";
	$Crs=mysql_query($Csql,$connect);
	$Crow=mysql_fetch_array($Crs);
	//echo  $Crow[2012DaeriCompanyNum] ;
//대리운전회사명을 찾기 위해 
				$Dsql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum' ";

				//echo "Dsql $Dsql <br>";
				$Drs=mysql_query($Dsql,$connect);

				$Drow=mysql_fetch_array($Drs);

				//echo " $Drow[company] ";
			//각보험회사별로 대리운전회사명을 달리 사용하는경우에 
			if(!$Crow[DaeriCompany]){
				$Crow[DaeriCompany]=$Drow[company];
			}
	$a[1]=$Crow[DaeriCompany];
	if($InsuraneCompany!=99){
		if($Crow[InsuraneCompany]){
			$a[2]=$Crow[InsuraneCompany];
		}else{
			$a[2]=$InsuraneCompany;
		}
	    $a[3]=$Crow[policyNum];
	}
	
	
	//echo "$Crow[2012DaeriCompanyNum]";


	
	$message='조회완료!!';





echo"<data>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }

	//전체인원을 구하기 위해 

		$pQL="SELECT * FROM  2021DaeriMember WHERE push='4'  $where1 ";
		$pQL.="$where2 $where3 ";

		$pRs=mysql_query($pQL,$connect);

		$pNum=mysql_num_rows($pRs);
		$pNum2=$pNum;

	/////////////////////////////////////////////////
	echo "<pNum>".number_format($pNum2)."</pNum>\n";
	$Rnum=10;
	echo "<Rnum>".$Rnum."</Rnum>\n";
	$Rnum=$Rnum+2;
	$a=21;
	for($_j_=2;$_j_<$Rnum;$_j_++){


			$k[$_j_]=$a;//21세 부터 시작
			$k2[$_j_]=$k[$_j_]+4;
			//$k2[$_j_]=$m;

			$a=$k2[$_j_]+1;


			$sQL="SELECT * FROM  2021DaeriMember WHERE push='4' and  nai>='$k[$_j_]' and nai<='$k2[$_j_]' $where1 ";
			$sQL.="$where2 $where3 ";
			$sRs=mysql_query($sQL,$connect);
			$sNum[$_j_]=mysql_num_rows($sRs);

			
			$rNum[$_j_]=round($sNum[$_j_]/$pNum,3)*100;

			if($sNum[$_j_]==0){	
				$sNum[$_j_]='';
			}else{
				$sNum[$_j_]=$sNum[$_j_]."명";
			}
			if($rNum[$_j_]==0){	
				$rNum[$_j_]='';
			}else{
				$rNum[$_j_]=$rNum[$_j_]."%";
			}
		echo "<me".$_j_.">".$k[$_j_]."세~".$k2[$_j_]."세"."</me".$_j_.">\n";//
		echo "<sNum".$_j_.">".number_format($sNum[$_j_])."</sNum".$_j_.">\n";//
		echo "<rNum".$_j_.">".$rNum[$_j_]."</rNum".$_j_.">\n";//
	
	}

	echo "<message>".$message."</message>\n";	
	echo "<store>"."2"."</store>\n";

	
echo"</data>";

	?>