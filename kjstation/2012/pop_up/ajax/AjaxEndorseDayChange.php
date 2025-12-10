<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$CertiTableNum =iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$eNum=iconv("utf-8","euc-kr",$_GET['eNum']);
	$endorseDay=iconv("utf-8","euc-kr",$_GET['endorseDay']);


if($endorseDay){//대리운전 회사가 이미 등록 되어 있다고 하면 update;

	$sql="UPDATE 2012EndorseList  SET ";
	$sql.="endorse_day='$endorseDay'  ";
	$sql.="WHERE CertiTableNum ='$CertiTableNum' and pnum='$eNum'";

	mysql_query($sql,$connect);
	
	$message='변경 되었습니다!!';

	$msql="SELECT * FROM 2012DaeriMember  WHERE CertiTableNum ='$CertiTableNum' and EndorsePnum='$eNum' and sangtae='1'";
	$mrs=mysql_query($msql,$connect);
	$mNum=mysql_num_rows($mrs);


	for($j=0;$j<$mNum;$j++){
			$mRow=mysql_fetch_array($mrs);


			if($mRow[push]==1){

					$where="InPutDay='$endorseDay'";
			}else{

					$where="OutPutDay ='$endorseDay'";
			}
			$update="UPDATE 2012DaeriMember  SET ";
			//$update.="endorse_day='$endorseDay'  ";
			$update.=" $where WHERE num='$mRow[num]'";

			mysql_query($update,$connect);

	}





}
/*	
for($_u_=1;$_u_<15;$_u_++){
		$a[$_u_]=$_u_;//
		
    }*/

echo"<data>\n";
	echo "<certiNum>".$msql.$sql.$certiNum."</certiNum>\n";
	echo "<eNum>".$eNum."</eNum>\n";	
	echo "<endorseDay>".$endorseDay."</endorseDay>\n";//
	echo "<message>".$message."</message>\n";
	
echo"</data>";

	?>