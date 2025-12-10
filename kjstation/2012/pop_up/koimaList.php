<?include '../../2012/lib/lib_auth.php';

$sql="Select * FROM 2012koima ";
$rs=mysql_query($sql,$connect);
$num=mysql_num_rows($rs);


for($j=0;$j<$num;$j++){



	$row=mysql_fetch_array($rs);

	$iprem='15000';
	$k=$j%100;
	$k=$k+1;
	switch($k){
		case  1: 
			$iName="적하";
			$iprem='15000';
			break;
		case 2 :
				
			$iName="생산물";
			$iprem='14500';
			break;
		case 3 :
			$iName="근재";
			break;
		case 4 : 
			$iName="적하";
			$iprem='23000';
			
			break;
		case 5 :
			$iName="화재";
			break;
		case 6 :
			$iName="적하";
			$iprem='26000';
			break;
		case  7: 
			$iName="적하";
			$iprem='36000';
			break;
		case  8: 
			$iName="적하";
			$iprem='46000';
			break;
		case  9: 
			$iName="적하";
			$iprem='33000';
			break;
		case  10: 
			$iName="적하";
			$iprem='42000';
			break;
		



	}


	

	if($k==2){

		$iprem='';
	}
	if($k==3){

		$iprem='';
	}


	$iprem+=10;

	$update="UPDATE 2012koima SET kind='$iName', pr='$iprem' WHERE num='$row[num]'";
	echo "sql $update <Br>";
}

	

?>