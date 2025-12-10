<?
//배서 번호를 찾기위해

$en_sql="SELECT max(num) as max_num FROM 2012EndorseList WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' ";
$en_sql.="and CertiTableNum='$CertiTableNum'";
	//echo "en $en_sql <br>";
	$res=mysql_query($en_sql,$connect);
	$k_row=mysql_fetch_array($res);
	$maxnum=$k_row[max_num];


	$p_sql="SELECT pnum,sangtae,endorse_day,e_count FROM 2012EndorseList WHERE num='$maxnum'";
	//echo "p_sql $p_sql <br>";
	$p_rs=mysql_query($p_sql,$connect);
	$ksh=mysql_fetch_array($p_rs);	
	$endorse_num=(int)$ksh['pnum'];
	$e_sangtae=$ksh['sangtae'];

	$old_endorse_day=$ksh['endorse_day'];   //DB에 배서기준일 
	$endorse_day=trim($endorseDay);			//새로운 배서기준일


	//echo "e_san $e_sangtae <br>";
	/****************************************************************/
	/*배서 상태가 3이면 신청-->1이면 접수-->2이면-->처리완료        */
	/*배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고 ,       */
	/*2또는 1이면 새로운 번호 사용합니다                            */
	/****************************************************************/
//echo "old $old_endorse_day <br>";
	if($endorse_day==$old_endorse_day){//원래있던 배서기준일과 새로운기준일이같으면 배서번호는 그대로 사용하고

			//echo "성준 <br>";
			switch($e_sangtae){//배서 상태가 3이면 상태이면 배서번호는 그대로 사용하고 ,2또는 1이면 새로운 번호 사용합니다
				case '3':
					$endorse_num=$endorse_num;
					//$sangtae=3;
					
					//$endorse_num=sprintf("%04d",$endorse_num);
					$ak=1;
				break;
				default :
					
					//$sangtae=3;
					$endorse_num=$endorse_num+1;
					//$endorse_num=sprintf("%04d",$en);
					$ak=2;
				break;
			}

	}else{
		//echo "성준2 <br>";
		//$en=$endorse_num+1;
			//$sangtae=3;
		$endorse_num=$endorse_num+1;
		//$endorse_num=sprintf("%04d",$en);
		$ak=3;
	}





















?>