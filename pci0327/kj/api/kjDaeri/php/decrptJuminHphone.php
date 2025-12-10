<?php
 if ($row['Hphone'] != "") {

		$decrypted = decryptData($row['Hphone']);
		if ($decrypted != "") {
			$row['Hphone'] = $decrypted;
		} 
	}


 if ($row['Jumin'] != "") {

	$decrypted = decryptData($row['Jumin']);
	if ($decrypted != "") {
		$row['Jumin'] = $decrypted;
	} 
 }

//PolicyNumInsurancePremiumStatistics.php 에서 사용
 if ($driverRow['Jumin'] != "") {

	$decrypted = decryptData($driverRow['Jumin']);
	if ($decrypted != "") {
		$driverRow['Jumin'] = $decrypted;
	} 
 }
?>