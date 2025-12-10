<?//흥국화재 보험료 입력을 위해 

	$yearPreminum1='638970';
	$yearPreminum2='600870';
	$yearPreminum3='719740';
	$yearPreminum4='828800';
	//CertiTableNum

	$sql="UPDATE 2012CertiTable SET yearP1='$yearPreminum1',yearP2='$yearPreminum1',yearP3='$yearPreminum3',yearP4='$yearPreminum4' ";
	//$sql.="yearPE1='$yearPreminum1',yearPE2='$yearPreminum2',yearPE3='$yearPreminum3' ";
	$sql.="WHERE num='$num'";


//echo "sql $sql <Br>";
    
	mysql_query($sql,$connect);


?>