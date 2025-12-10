<?php
   include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$mem_id	    =iconv("utf-8","euc-kr",$_GET['mem_id']);
	

		$sql="SELECT * FROM 2012Costomer WHERE mem_id='$mem_id'";
		$rs=mysql_query($sql,$connect);
		$total=mysql_num_rows($rs);


		



if($total){

	$totalName='사용할 수 없는 ID 입니다';
	$check=1;
}else{
	
	$totalName='사용할 수 있는 ID 입니다';
	$check=2;
}

echo"<data>\n";
	echo "<check>".$check."</check>\n";
	echo "<total>".$totalName."</total>\n";
echo"</data>";



	?>