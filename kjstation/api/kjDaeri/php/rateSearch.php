<?php

$rSql="SELECT rate FROM `2019rate` WHERE policy = '$policyNum' AND jumin = '$jumin' ";
			//echo  '$rSql'; echo $rSql;
			$rRs =mysql_query($rSql, $conn);
			$rRow=mysql_fetch_assoc($rRs);
			$row['rate']=$rRow['rate'];
?>