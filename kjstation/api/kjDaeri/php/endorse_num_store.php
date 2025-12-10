<?
/***********************************************************/
/*배서 개수를 찾기 우해                                     */
/*배서 상태가 3이면 신청-->1이면 접수-->2이면-->처리완료    */
/*배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고 ,   */
/*2또는 1이면 새로운 번호 사용합니다                         */
/************************************************************/
	if($ak=='1'){
	   //배서건수 찾아야 한다
	   $k_sql="SELECT e_count FROM 2012EndorseList WHERE pnum='$endorse_num' and 2012DaeriCompanyNum='$dNum' ";
	   $k_sql.="and CertiTableNum='$cNum'";
     // echo "k_sql $k_sql <br>";
	   $k_rs=mysql_query($k_sql,$conn);
	   $k_row=mysql_fetch_array($k_rs);
	   $old_count=$k_row['e_count'];//기존 배서 건수
	 //  echo "old_count $old_count <br>";

	   $new_count=$old_count+$e_count;//기존 배서 건수 + 새로운 배서건수
	 //  echo "new_count $new_count <br>";
		$e_update="UPDATE 2012EndorseList SET e_count='$new_count' WHERE pnum='$endorse_num' and 2012DaeriCompanyNum='$dNum'"; 
		$e_update.="and CertiTableNum='$cNum'";
	//	echo "e_update $e_update <br>";
		$rs_1=mysql_query($e_update,$conn);
	}else{
		$esql="INSERT INTO 2012EndorseList (pnum,2012DaeriCompanyNum,InsuranceCompany,CertiTableNum,policyNum,e_count,wdate,endorse_day,userid,sangtae) ";
		$esql.="VALUES ('$endorse_num','$dNum','$InsuraneCompany','$cNum','$policyNum','$e_count',now(),'$endorse_day','$userId','3') ";
		//echo "배서 번ㄹ호 입력 $esql <br>";
		$rs_1=mysql_query($esql,$conn);
	}
?>