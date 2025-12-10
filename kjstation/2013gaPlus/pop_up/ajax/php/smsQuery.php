<?

//문자 리스트 조회 하기 위해 
list($hphone1,$hphone2,$hphone3)=explode("-",$a[4]);

	$sm_sql="SELECT * FROM SMSData WHERE Rphone1='$hphone1' ";
	$sm_sql.="and Rphone2='$hphone2' and Rphone3='$hphone3'  ";
	$sm_sql.="order by SeqNo desc ";
	

	//echo "sm_sql $sm_sql <br>";
	$sm_rs=mysql_query($sm_sql,$connect);
	$sm_total=mysql_num_rows($sm_rs);
	
	if($sm_total<10){
		$gaesu=$sm_total;
	}else{

		$gaesu=10;
	}
	for($_g_=0;$_g_<$gaesu;$_g_++){
		$buho[$_g_]=$_g_+1;;
		$sm_row=mysql_fetch_array($sm_rs);

		$Seg[$_g_]=$sm_row[SeqNo];
		$LastTime	=$sm_row['LastTime'];
		$Msg[$_g_]		=$sm_row['Msg'];

		$ysear[$_g_]			=substr($LastTime,0,4);
		$month[$_g_]			=substr($LastTime,4,2);
		$day[$_g_]			=substr($LastTime,6,2);
		$_time[$_g_]			=substr($LastTime,8,2);
		$_minute[$_g_]		=substr($LastTime,10,2);
		$com[$_g_]	  = $sm_row[company];
		switch($com[$_g_]){
			case 1 :
				$comName[$_g_]='흥국';
			break;
			case 2 :

				$comName[$_g_]='동부';
			break;

			case 3 :

				$comName[$_g_]='LiG';
			break;

			case 4 :

				$comName[$_g_]='현대';
			break;

			case 5 :

				$comName[$_g_]='한화';
			break;

			case 6 :

				$comName[$_g_]='더케이';
			break;
			case 10:

				$comName[$_g_]='보험료';
			break;
		
		}

		//$ssang_c_num	  = $sm_row[ssang_c_num];
		$endorse_num	  = $sm_row[endorse_num];
		
		$canCelNum= $sm_row[SeqNo];
		if($com[$_g_]){
			$get[$_g_]	  = $sm_row[get];
			switch($get){
				case 2 :
					$bgColor="#fffarf";
				break;
				default :
					$bgColor="#ffffff";
				break;
			}
		}else{
			$bgColor="#ffffff";
		}
	}


?>