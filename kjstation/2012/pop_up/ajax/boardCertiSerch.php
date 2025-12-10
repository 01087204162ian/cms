<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum==iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
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
	$a[2]=$Crow[InsuraneCompany];
	$a[3]=$Crow[policyNum];
	
	//echo "$Crow[2012DaeriCompanyNum]";



	
	$message='신규 추가 하세요!!';



// 2016-03-13  동부화재인경우 대리 + 탁송을 추가 하기 위해

$mSql="SELECT * FROM 2021DaeriMember WHERE num='$mNum'";
$mRs=mysql_query($mSql,$connect);
$mRow=mysql_fetch_array($mRs);



echo"<data>\n";
	echo "<num>".$num."</num>\n";
	for($_u_=1;$_u_<15;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
    }
	echo "<mName>".$mRow[Name]."</mName>\n";
	echo "<mJumin>".$mRow[Jumin]."</mJumin>\n";	
	echo "<mHphone>".$mRow[Hphone]."</mHphone>\n";	
	echo "<message>".$message."</message>\n";	
	echo "<store>"."2"."</store>\n";

	
echo"</data>";

	?>