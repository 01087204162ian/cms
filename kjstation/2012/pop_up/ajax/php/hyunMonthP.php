<?//흥국화재 댈리 운전회사로 부터 받는 보험료 입력을 위해 

	

	$sql="UPDATE 2012CertiTable SET preminum1='$p26p',preminum2='$p35p',preminum3='$p48p',preminum4='$p55p' ";
	//ql.="preminumE1='$p26Ep',preminumE2='$p26Ep',preminumE3='$p26Ep', ";
	//$sql.="FirstStartDay='$SigiDay[2]',FirstStart='$startDay'";
	$sql.="WHERE num='$num'";

//echo "sql $sql <bR>";
	mysql_query($sql,$connect);


  

?>