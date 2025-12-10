<?php
		include '../../../dbcon.php';
		echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];


		
		

		if($proc == "maxInwon") {
		

			$sql="UPDATE 2012Certi SET maxInwon='$maxInwon'  WHERE num='$num'";
			$rs=mysql_query($sql,$connect);
			//$count=mysql_num_rows($rs);

			if($rs)
			{
				$return_msg = "입력 되었습니다 !!.";
				$signal=1;
			}else {
				$return_msg = "error.";
				$signal=2;
			}						
			$return_msg	      =iconv("euc-kr","utf-8",$return_msg);
			echo("\t<item>\n");
				echo("\t\t<signal>".$sql."</signal>\n");
				echo("\t\t<message>".$return_msg."</message>\n");
			echo("\t</item>\n");			
			

		}
		echo "</values>";
?>