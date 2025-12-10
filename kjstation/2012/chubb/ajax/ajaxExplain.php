<?php
    include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$policy=iconv("utf-8","euc-kr",$_GET['oun1']); //예상가입금액
	$invoice=iconv("utf-8","euc-kr",$_GET['oun2']);//보험료율
	$from=iconv("utf-8","euc-kr",$_GET['oun3']);    //출발지
	$destination=iconv("utf-8","euc-kr",$_GET['oun4']);//도착지
	$kibs_list_num=iconv("utf-8","euc-kr",$_GET['oun5']);// 의 num
	$sigi=iconv("utf-8","euc-kr",$_GET['oun6']);//설명서 작성일
	$explainNum=iconv("utf-8","euc-kr",$_GET['oun7']);
	


	if($explainNum){

		$sql="UPDATE kibs_list_explain SET policy='$policy',invoice='invoice', ";
		$sql.="startLocation='$from',destination='$destination',sigi='$sigi',wdate='$now_time' ";
		$sql.="WHERE num='$explainNum'";
		mysql_query($sql,$connect);
		$message='상품 설명서 update!';
		

	}else{


		$sql="INSERT INTO kibs_list_explain (kibs_list_num,policy,invoice,startLocation,destination,sigi,wdate) ";
		$sql.="VALUES('$kibs_list_num','$policy','$invoice','$from','$destination','$sigi','$now_time' )";

		mysql_query($sql,$connect);
		$message='상품 설명서 저장 완료!';

		$sql2="SELECT num FROM kibs_list_explain WHERE kibs_list_num='$kibs_list_explain' and wdate='$now_time'";
		$rs2=mysql_query($sql2,$connect);

		$row2=mysql_fetch_array($rs2);

		$explainNum=$row2[num];

	}
	
	


		echo"<data>\n";
			echo "<cname>".$sql."</cname>\n";
			echo "<explainNum>".$explainNum."</explainNum>\n";
			echo "<message>".$message."</message>\n";
			
		echo"</data>";



	?>