<?php

	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


	$certi=iconv("utf-8","euc-kr",$_GET['certi']);
	$A1a=iconv("utf-8","euc-kr",$_GET['A1a']);
	$A2a=iconv("utf-8","euc-kr",$_GET['A2a']);
	$A3a=iconv("utf-8","euc-kr",$_GET['A3a']);
	$A4a=iconv("utf-8","euc-kr",$_GET['A4a']);

	$A5a=iconv("utf-8","euc-kr",$_GET['A5a']);
	$A6a=iconv("utf-8","euc-kr",$_GET['A6a']);
	$A7a=iconv("utf-8","euc-kr",$_GET['A7a']);
	$A8a=iconv("utf-8","euc-kr",$_GET['A8a']);
	$A9a=iconv("utf-8","euc-kr",$_GET['A9a']);
	$A10a=iconv("utf-8","euc-kr",$_GET['A10a']);
	$A11a=iconv("utf-8","euc-kr",$_GET['A11a']);
	$A12a=iconv("utf-8","euc-kr",$_GET['A12a']);


	$A13a=iconv("utf-8","euc-kr",$_GET['A13a']);
	$ayP=explode(",",$A13a);
	$A13a=$ayP[0].$ayP[1].$ayP[2];//년간보험료


	$A14a=iconv("utf-8","euc-kr",$_GET['A14a']);
    $ayP=explode(",",$A14a);
	$A14a=$ayP[0].$ayP[1].$ayP[2];//년간보험료

	$A15a=iconv("utf-8","euc-kr",$_GET['A15a']);
	$ayP=explode(",",$A15a);
	$A15a=$ayP[0].$ayP[1].$ayP[2];//년간보험료

	$A16a=iconv("utf-8","euc-kr",$_GET['A16a']);
	$ayP=explode(",",$A16a);
	$A16a=$ayP[0].$ayP[1].$ayP[2];//년간보험료


	$A17a=iconv("utf-8","euc-kr",$_GET['A17a']);
	$ayP=explode(",",$A17a);
	$A17a=$ayP[0].$ayP[1].$ayP[2];//년간보험료


	$A18a=iconv("utf-8","euc-kr",$_GET['A18a']);
	$ayP=explode(",",$A18a);
	$A18a=$ayP[0].$ayP[1].$ayP[2];//년간보험료


	$A19a=iconv("utf-8","euc-kr",$_GET['A19a']);
	$ayP=explode(",",$A19a);
	$A19a=$ayP[0].$ayP[1].$ayP[2];//년간보험료

	$sj=iconv("utf-8","euc-kr",$_GET['sj']);

include '../../../dbcon.php';




if($sj==1){//팝어창에서 수정

		if($A4a<"2023-11-01"){  // 
			$update="UPDATE 2012Certi   SET company='$A1a',name='$A2a', ";
				$update.="jumin='$A3a', sigi='$A4a',";
				$update.="nab='$A5a', cord='$A6a',";
				$update.="cordPass='$A7a',cordCerti='$A8a', ";
				$update.="yearRate='$A11a',harinRate='$A12a', ";
				$update.="preminun25='$A13a',preminun44='$A14a', ";
				$update.="preminun49='$A15a',preminun50='$A16a', ";
				$update.="preminun60='$A17a',preminun66='$A18a' ";

				$update.="WHERE certi='$certi'";

				//echo $update;
				mysql_query($update,$connect);
				$message='변경완료!!';
		}else{


			
				$update="UPDATE 2012Certi   SET company='$A1a',name='$A2a', ";
				$update.="jumin='$A3a', sigi='$A4a',";
				$update.="nab='$A5a', cord='$A6a',";
				$update.="cordPass='$A7a',cordCerti='$A8a', ";
				$update.="yearRate='$A11a',harinRate='$A12a', ";
				$update.="preminun25='$A13a',preminun44='$A14a', ";
				$update.="preminun49='$A15a',preminun50='$A16a', ";
				$update.="preminun60='$A17a',preminun61='$A18a', ";
				$update.="preminun66='$A19a' ";

				$update.="WHERE certi='$certi'";

				//echo $update;
				mysql_query($update,$connect);
				$message='변경완료!!';


		}

}else{
		$update="UPDATE 2012Certi   SET company='$A1a',name='$A2a', ";
		$update.="jumin='$A3a', sigi='$A4a',";
		$update.="nab='$A5a', cord='$A6a',";
		$update.="cordPass='$A7a',cordCerti='$A8a', ";
		$update.="phone='$A9a',fax='$A10a' ";
		$update.="WHERE certi='$certi'";
		mysql_query($update,$connect);
		$message='증권번호 입력 완료!!';


}	

  echo"<data>\n";
		//echo "<phone>".$esql."</phone>\n";
		//echo "<sms>".$ssangCnum."</sms>\n";
    
		//echo "<msg>".$cerNum."</msg>\n";
		//echo "<msg2>".$endorseD."</msg2>\n";
		echo "<msg3>".$update."</msg3>\n";
		echo "<message>".$message."</message>\n";
		
	
 echo"</data>";
?>