<?php
include '../../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	echo "<values>\n";

		if($proc == "haejisms") {
			
			
			$sql="SELECT * FROM  new_cs_dongbu WHERE num='$num'";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);


			$company_tel="070-7841-5962";

		list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
		$hphone=$row[con_phone];


		//$proc='daeri_C';
		
		$msg=$row[oun_name]."(".$row[oun_jumin1].")동부화재 대리운전 보험 해지되었습니다";


		//해지 하기 위해 
		$kSql="UPDATE new_cs_dongbu SET sangtae='5' WHERE num='$num'";

		mysql_query($kSql,$connect);
		
        include "./php/coSms.php";

			
			echo("\t<item>\n");
				echo"<pages>".$msg."</pages>\n";
				
			echo("\t</item>\n");
			
			
			
		}

	echo "</values>";
	?>