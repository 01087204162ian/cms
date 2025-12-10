<?php
   include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$certiNum=iconv("utf-8","euc-kr",$_GET['certiNum']);
	$val=iconv("utf-8","euc-kr",$_GET['val']);
	


	/*******************************************************************************************/
	/*divi가 2이면 1/12로 보험료 받은             
	/* dailyPreminum은 각자 정할 수 있음
	*/
	/*******************************************************************************************/

	$dailyPrem=$yP[0].$yP[1].$yP[3];
	$sql="SELECT divi FROM 2012CertiTable  WHERE num='$certiNum'";


	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);
	$divi=$row[divi];

	if($divi==2){

		$update ="UPDATE 2012CertiTable  SET divi='1' ";
		$update .="WHERE num='$certiNum'";
		$rs=mysql_query($update,$connect);
		$message="정상결제";
		$message2="입력";

	}else{

			$update ="UPDATE 2012CertiTable   SET divi='2' ";
			$update .="WHERE num='$certiNum'";

			$rs=mysql_query($update,$connect);
			$message='1/12씩';
			$message2="월보험료";
	}	
		
	
		echo"<data>\n";
			echo "<dailyPrem>".$update."</dailyPrem>\n";
			echo "<d3>".$sql."</d3>\n";
			echo "<cname>".$cName."</cname>\n";
			echo "<val>".$val."</val>\n";
			echo "<message>".$message."</message>\n";
			echo "<message2>".$message2."</message2>\n";
			echo "<messageCo>".$divi."</messageCo>\n";
			echo "<care>".$change_1."</care>\n";
		echo"</data>";



	?>