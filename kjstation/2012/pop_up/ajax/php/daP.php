<?	include "../../../2012/pop_up/ajax/php/dajoongP.php";// /newDajunSin.php에서도 사용함
	if($b[3]){ //보험료가 계산 되었으면

		$dSql="update   2013dajoong SET preminum='$b[3]'  WHERE num ='$num'";
		mysql_query($dSql,$connect);
	}



	//시리얼 번호로 등록상태 확인하기 

	$sSql="SELECT * from 2013sobang0715 WHERE serial='$a[10]'";

	$sRs=mysql_query($sSql,$connect);

	$sRow=mysql_fetch_array($sRs);

	$a[34]="|".$sRow[sido]."|".$sRow[comkind]."|".$sRow[company]."|".$sRow[address]."|".$sRow[address2];


	$a[54]=$sRow[company];
	$a[55]=$sRow[address];
	$a[56]=$sRow[address2];
	?>