<? $ls_sql="SELECT holiday,holiday_end FROM lig_holiday ";
   $ls_rs=mysql_query($ls_sql,$connect);
	//echo " ls $ls_sql <br>";
   $ls_row=mysql_fetch_array($ls_rs);

   $holiday=$ls_row['holiday'];
   $holiday_end=$ls_row['holiday_end'];



?>