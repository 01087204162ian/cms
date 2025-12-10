<?php
   include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$permit					=iconv("utf-8","euc-kr",$_GET['permit']);
	$id						=iconv("utf-8","euc-kr",$_GET['id']);
	$DaeriCompanyNum	    =iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	$CostomerNum			=iconv("utf-8","euc-kr",$_GET['CostomerNum']);
	$pass					=iconv("utf-8","euc-kr",$_GET['pass']);
	$DaeriCompanyName		=iconv("utf-8","euc-kr",$_GET['DaeriCompanyName']);
	$hphone					=iconv("utf-8","euc-kr",$_GET['hphone']);

	if(!$pass){//비밀번호가 없으련 업체 담당자의 핸드폰 번호가 비밀번호가 된다...


		
		//비밀번호 핸드폰번호로 setting 
		list($sphone1,$sphone2,$sphone3)=explode("-",$hphone);
		$pass=$sphone2.$sphone3;
		

	}

	$pass=md5($pass);
	if($CostomerNum){
			$update="UPDATE 2012Costomer SET mem_id='$id',passwd='$pass',name='$DaeriCompanyName',hphone='$hphone',permit='$permit' ";
			$update.="WHERE num='$CostomerNum'";
			$update=mysql_query($update,$connect);

			$message='수정 되었습니다';
	}else{

		$sql="INSERT INTO 2012Costomer (2012DaeriCompanyNum,mem_id ,passwd ,name ,jumin1 ,jumin2 ,hphone ,email ,level ,";
		$sql.="mail_check ,wdate ,inclu ,kind ,pAssKey ,pAssKeyoPen,permit )";
		$sql.="VALUES ('$DaeriCompanyNum','$id','$pass','$DaeriCompanyName','','','$hphone','','5','', now(), '', '2', '', '','$permit')";

		mysql_query($sql,$connect);

		$qsql="SELECT num FROM 2012Costomer WHERE 2012DaeriCompanyNum='$DaeriCompanyNum'";
		$qrs=mysql_query($qsql,$connect);

		$qrow=mysql_fetch_array($qrs);

		$CostomerNum=$qrow[num];



	}
		


		





echo"<data>\n";
	echo "<update>".$update."</update>\n";
	echo "<CostomerNum>".$CostomerNum."</CostomerNum>\n";
	echo "<message>".$message."</message>\n";
echo"</data>";



	?>