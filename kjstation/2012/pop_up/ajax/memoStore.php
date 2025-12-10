<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
include '../../../dbcon.php';
	$memoNum=iconv("utf-8","euc-kr",$_GET['memoNum']);
	$memoKind=iconv("utf-8","euc-kr",$_GET['memoKind']);
	$memoContent=iconv("utf-8","euc-kr",$_GET['memoContent']);
	$a[9]=iconv("utf-8","euc-kr",$_GET['a9']);//사업자 번호
	$a[3]=iconv("utf-8","euc-kr",$_GET['a3']);//주민번호
	//$FirstartDay=iconv("utf-8","euc-kr",$_GET['FirstartDay']);
if(!$a[9]){//사업자번호가 없으면 주민번호로
	$a[9]=$a[3];
}

if($memoNum){

	$update="UPDATE ssang_c_memo SET memo='$memoContent',memokind='$memoKind',wdate=now() WHERE num='$memoNum'"; 
	mysql_query($update,$connect);
	$message="수정 완료 !!";

}else{
	
	$insert="INSERT INTO  ssang_c_memo (c_number,memo,memokind,wdate )";
	$insert.="VALUES ('$a[9]','$memoContent','$memoKind',now())";

	mysql_query($insert,$connect);

	$message="메모 완료 !!";
}


	echo"<data>\n";	
	 //echo "<a9>".$a9."</a9>\n";
	 echo "<message>".$message."</message>\n";
     echo "<sql>".$insert."</sql>\n";
	include "./php/memoData.php";
	echo "<mStore>".'메모저장'."</mStore>\n";	
	echo"</data>";	
   
	   

?>