<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$moValue=iconv("utf-8","euc-kr",$_GET['moValue']);// 1모계약 2모계약아님

			
	 if($moValue==1){

		$whree=",moNum='$CertiTableNum'";

	 }else{

		$whree=",moNum=''";

	 }
			$update="UPDATE 2012CertiTable  SET moValue='$moValue' $whree ";
			$update.="WHERE num='$CertiTableNum'";
			mysql_query($update,$connect);

			//모계약은 policyNum 당 하나만 이여야 한다
			if($moValue==1){
				
				$moSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				$moRs=mysql_query($moSql,$connect);
				$moRow=mysql_fetch_array($moRs);

				//policyNum 같은 것을 다 찾아서 //
				//현재$CertiTableNum 을 제외한 다른 것은 모두 2

				$p_sql="SELECT * FROM 2012CertiTable WHERE policyNum=$moRow[policyNum]";
				$p_Rs=mysql_query($p_sql,$connect);
				$p_Num=mysql_num_rows($p_Rs);

				for($j=0;$j<$p_Num;$j++){
					$p_Row=mysql_fetch_array($moRs);
					if($CertiTableNum!=$p_Row[num]){

						$sql__="UPDATE 2012CertiTable SET moValue='2' WHERE num='$p_Row[num]'";
						mysql_query($sql__,$connect);

					}


				}
						


				



			}

			$message="완료!!";


			$monthsP=$preminum;//월별보험료

		echo"<data>\n";
			echo "<sql>".$sql.$update."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			
		echo"</data>";



	?>