<?php 
		//session_start();

		include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";


		

	
		
		

		if($proc == "damdanga") {
			
			$dsql="SELECT * FROM 2012Member order by num asc";

			$drs=mysql_query($dsql,$connect);
			$dNum=mysql_num_rows($drs);			
				echo("\t<item>\n");
					echo("\t\t<num>"."99"."</num>\n");
					echo("\t\t<name>"."´ã´çÀÚ"."</name>\n");
				echo("\t</item>\n");
			for($j=0;$j<$dNum;$j++){

				$dRow=mysql_fetch_array($drs);
				echo("\t<item>\n");
					echo("\t\t<num>".$dRow[num]."</num>\n");
					echo("\t\t<name>".$dRow[name]."</name>\n");
				echo("\t</item>\n");
			}
			
		
			
		}else if($proc == "damdangaChange") {


			$sql="UPDATE gasipan SET damdanga='$damdanga' WHERE num='$num'";

			mysql_query($sql,$connect);

				echo("\t<item>\n");
					echo("\t\t<num>".$sql."</num>\n");
					
				echo("\t</item>\n");




		}
		echo "</values>";
?>