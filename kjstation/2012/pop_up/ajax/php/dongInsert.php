<?


  
  $p_sql="SELECT * FROM 2015dongbudailyP WHERE date='$endorseDay' ";
  $p_sql.=" and snai='$row2[sPreminum]' and enai='$row2[ePreminum]'  and nabang='$k'";

  $p_rs=mysql_query($p_sql,$connect);
  $p_num=mysql_num_rows($p_rs);

  //echo "p_num $p_num";

  if(!$p_num){


		$i_sql="INSERT INTO 2015dongbudailyP (snai,enai,preiminum,nabang,date) ";
		$i_sql.=" VAlUES ('$row2[sPreminum]','$row2[ePreminum]','$month_[$k]','$k','$endorseDay') ";


		//echo "i_sql $i_sql ";

		mysql_query($i_sql,$connect);
  }
 ?>