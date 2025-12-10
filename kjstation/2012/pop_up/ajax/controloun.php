<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	echo "<values>\n";
	
		if($proc == "controlOun") {
			

				$table="2012CertiTable";
				$where =" WHERE  num='$CertiTableNum' ";	
				$sql = "update  " . $table ." SET  control='$control' ". $where.$order ;

				
				mysql_query($sql,$connect);



			echo("\t<item>\n");

			echo("\t\t<divi><![CDATA[".$sql."]]></divi>\n");
			//echo("\t\t<FirstStartDay><![CDATA[".$FirstStartDay."]]></FirstStartDay>\n");
			echo("\t</item>\n");
		
			
			@mysql_close($DB->Connect_ID);

		}
	echo "</values>";
	




	?>