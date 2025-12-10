<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$certiNum =iconv("utf-8","euc-kr",$_GET['certiNum']);
	$val=iconv("utf-8","euc-kr",$_GET['val']);
for($i=1;$i<7;$i++){
	$a[$i] =iconv("utf-8","euc-kr",$_GET['a'.$i]);					//은행명
		
}	


if($certiNum){//증권번호 있다면  update;

	$sql="UPDATE 2012CertiTable  SET ";
	$sql.="2012DaeriCompanyNum='$a[1]',InsuraneCompany='$a[2]',startyDay='$a[3]',policyNum='$a[4]',nabang='$a[5]' ";
	$sql.="WHERE num='$certiNum'";

	mysql_query($sql,$connect);
	
	$message='수정되었습니다!!';
	$sigiStart=$a[3];
	$naBang=$a[6];
}else{//없다면 store;
	if($a[2]==2){
		$nabang_1=10;
		
	}else{

		$nabang_1=1;
	}
	$sql="INSERT INTO 2012CertiTable  (2012DaeriCompanyNum,InsuraneCompany,startyDay,policyNum,nabang,nabang_1 )";
	$sql.="values ('$a[1]','$a[2]','$a[3]','$a[4]','$a[5]','$nabang_1')";
	$a[6]=1;//최초 등록일 때만 

	mysql_query($sql,$connect);


	//저장후 num을 찾기 위해 


	$ksql="SELECT num FROM 2012CertiTable  WHERE policyNum='$a[4]' and 2012DaeriCompanyNum='$a[1]'";
	$krs=mysql_query($ksql,$connect);
	$krow=mysql_fetch_array($krs);

	$certiNum=$krow[num];
	$message='저장 되었습니다!!';
	$sigiStart=$startyDay;
	$naBang=1;
}
		
		include "./php/nabState.php";//popAjaxSerch.php 공동으로 사용

echo"<data>\n";
	echo "<val>".$val."</val>\n";
	echo "<num>".$certiNum."</num>\n";
	for($_u_=1;$_u_<7;$_u_++){
		echo "<name".$_u_.">".$a[$_u_]."</name".$_u_.">\n";//
		
    }
	//$a[28]=28;$a[29]=29;$a[30]=30;
	//echo "<InsuraneCompany>".$a[2]."</InsuraneCompany>\n";//
	echo "<naColor>".$naColor."</naColor>\n";//
	echo "<naState>".$naState.$gigan."</naState>\n";//
	echo "<name32>"."수정"."</name32>\n";//
	echo "<message>".$message."</message>\n";
	echo "<sql>".$sql."</sql>\n";
	
echo"</data>";

	?>