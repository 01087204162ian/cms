<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$memberNum =iconv("utf-8","euc-kr",$_GET['memberNum']);	
	$dongbuCerti=iconv("utf-8","euc-kr",$_GET['dongbuCerti']);
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);


	$psql="SELECT * FROM 2021DaeriMember WHERE  dongbuCerti='$dongbuCerti' ";
	$psql.="and push='4' and cancel='42' and sangtae='1'";
	$rs=mysql_query($psql,$connect);
	$row=mysql_fetch_array($rs);
	$oldNabang=$row[nabang_1];


if($rs){ //배서 인경우 


	//신규 추가 되고 있는 대리운전회사의 최종 설계번호를 찾기위해 


		$msql="SELECT * FROM 2021DaeriMember WHERE num='$memberNum'";
		$mRs=mysql_query($msql,$connect);
		$mRow=mysql_fetch_array($mRs);
	
	//대리운전 회사를 잧아서 
		$DaeriCompanyNum=$mRow['2012DaeriCompanyNum'];

		$lsql="SELECT * FROM 2021DaeriMember WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and InsuranceCompany='2' ";
		$lsql.="and push='4' order by num desc ";
		$lRs=mysql_query($lsql,$connect);

		$lRow=mysql_fetch_array($lRs);

    //가장 최근 설계번호를 찾는다 
	

			$updateSj="UPDATE 2021DaeriMember SET dongbuCerti='$dongbuCerti' ";	
			
			$updateSj.=",dongbuSelNumber='$lRow[dongbuSelNumber]', nabang_1='$oldNabang' WHERE num='$memberNum'";
		
			$rs=mysql_query($updateSj,$connect);

			if($rs){
				
				$message="완료!!";

			}
	
}else{

			$updateSj="UPDATE 2021DaeriMember SET dongbuCerti='$dongbuCerti' ";	
			
			$updateSj.=" WHERE num='$memberNum'";
		
			$rs=mysql_query($updateSj,$connect);

			if($rs){
				
				$message="완료!!";

			}


}
		



echo"<data>\n";
	echo "<nabang>".$oldNabang."</nabang>\n";
	echo "<sunso>".$sunso."</sunso>\n";
	echo "<dongbuSelNumber>".$row[dongbuSelNumber]."</dongbuSelNumber>\n";
	echo "<dongbuCerti>".$dongbuCerti."</dongbuCerti>\n";
	echo "<num>".$updateSj."</num>\n";
	echo "<message>".$message."</message>\n";
echo"</data>";

	?>