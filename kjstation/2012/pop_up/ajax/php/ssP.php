<?//흥국화재 보험료 입력을 위해 

	$yearPreminum='585500';
	//CertiTableNum

	$sql="UPDATE 2012CertiTable SET yearP1='$yearPreminum',yearP2='$yearPreminum',yearP3='$yearPreminum', ";
	$sql.="yearPE1='$yearPreminum',yearPE2='$yearPreminum',yearPE3='$yearPreminum' ";
	$sql.="WHERE num='$num'";


	mysql_query($sql,$connect);



   
?>