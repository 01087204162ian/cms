<?php
  include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	$cNum=iconv("utf-8","euc-kr",$_GET['val']);//kibs_list_num
	$get=iconv("utf-8","euc-kr",$_GET['val2']);//상태값

	//echo "num $num  <br> ch $ch <br> $kor_str"; 




	//echo "me $change ";


			$update =" UPDATE kibs_list_explain SET ";
			$update .="sele='$get' WHERE kibs_list_num='$cNum'";

			$rs=mysql_query($update,$connect);
		
		if($rs){

			$message='처리완료';

		}
		echo"<data>\n";
			echo "<num>".$update."</num>\n";
			echo "<ch>".$get."</ch>\n";
			echo "<message>".$change.$message."</message>\n";
			echo "<care>".$change_1."</care>\n";
		echo"</data>";



	?>