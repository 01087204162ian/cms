<?//흥국화재 보험료 입력을 위해 

	$yearPreminum1='585500';
	$yearPreminum2='600000';
	$yearPreminum3='700000';
	$yearPreminum4='800000';
	$yearPreminum5='900000';

$yearPreminumE1='800000';
$yearPreminumE2='900000';
$yearPreminumE3='1000000';
	//CertiTableNum

	$sql="UPDATE 2012CertiTable SET yearP1='$yearPreminum1',yearP2='$yearPreminum2',yearP3='$yearPreminum3', ";
	$sql.="yearP4='$yearPreminum4',yearP5='$yearPreminum5',yearP6='$yearPreminum6'";
	//$sql.="yearPE1='$yearPreminumE1',yearPE2='$yearPreminumE2',yearPE3='$yearPreminumE3' ";
	$sql.="WHERE num='$num'";

//echo "sql $sql <Br>";
	mysql_query($sql,$connect);


?>