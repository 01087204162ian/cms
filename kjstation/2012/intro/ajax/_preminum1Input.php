<?php
		include '../../../dbcon.php';
		echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];


		$premi=explode(",",$preminum1);

		$preminum1=$premi[0].$premi[1];
		

		if($proc == "preminum1") {
		

			$sql="UPDATE 2012DaeriMember  SET preminum1='$preminum1'  WHERE num='$memberNum'";
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
			
			echo("\t<item>\n");
				echo("\t\t<signal>".number_format($preminum1)."</signal>\n");
				echo("\t\t<message>".$return_msg."</message>\n");
			echo("\t</item>\n");			
			

		}
		echo "</values>";
?>