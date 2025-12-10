<?

 $sql="SELECT * FROM 2013dajoong  WHERE num='$num'";
	$rs=mysql_query($sql,$connect);
	$row=mysql_fetch_array($rs);

	

	$a[1]=$row[Name];
	$a[2]=$row[Jumin];
	$a[3]=$row[hphone];
	$a[16]=$row[phone];
	$oneYear =date("Y-m-d ", strtotime("$row[sigi] +1 year"));
	$gigan=$row[sigi].'~'.$oneYear; 
	if(!$a[4]){$a[4]=$now_time;}
	$a[5]=$row[email];
	$a[6]=$row[businessNum];
	$a[7]=$row[company];
	$a[8]=$row[comKind]; //업종
	$a[9]=$row[gi]; //기초산출수
	$a[15]=$row[gi2]; //기초산출수

	$a[10]=$row[serial];

	//$sSql="SELECT * from 2013sobang WHERE serial='$a[10]'";
	$sSql="SELECT * from 2013sobang0715 WHERE serial='$a[10]'";
	$sRs=mysql_query($sSql,$connect);
	$sRow=mysql_fetch_array($sRs);


	$serial=explode("/",$a[10]);
	$c[10]=$serial[0].$serial[1].$serial[2].$serial[3];//설계번호

	//echo "$row[company]";
	
	$row[bankName]='국민';
	$a[12]=$row[sulNum];

	
	$a[11]=$row[wdate];
	//$a[1]=$row[name];

	$a[28]=$row[postNum];
	$a[29]=$row[address1];
	$a[30]=$row[address2];

	if($row[inputDay]=='0000-00-00'){
		$row[inputDay]=$now_time;
	}
///시리얼 번호로 등록상태 확인하기 

	$sSql="SELECT * from 2013sobang0715 WHERE serial='$a[10]'";

	//echo "s  $sSql <br>";
	$sRs=mysql_query($sSql,$connect);

	$sRow=mysql_fetch_array($sRs);

	//시리얼 번호로 등록상태 확인하기 

	

	$a[34]="|".$sRow[sido]."|".$sRow[comkind]."|".$sRow[company]."|".$sRow[address];

	$newAddress=substr($sRow[address],0,28);
 switch($a[8]){

				case 1 :
				    $gubun='전화방업(화상대화방업)';
						break;
				case 2 :
					$gubun='비디오감상실';
					break;
				case 3 :
					$gubun='비디오소극장';
					break;
				case 4 :
					$gubun='수면방업';
					break;
				case 5 : 
					$gubun='산후조리원';
					break;
				case 6 :
					$gubun='스크린골프장';
					break;
				case 7 :
					$gubun='노래방';
					break;
				case 8 :$gubun='유흥주점';
					break;
				case 9 :
					$gubun='콜라텍';
					break;
				case 10 :
					$gubun='안마시술소';
					break;
				case 11 :
					$gubun='영화상영관';
					break;
				case 12 :
					$gubun='학원';
					break;
				case 13 :
					$gubun='목욕탕(찜질방)';
					break;
				case 14 :
					$gubun='고시원';
					break;
				case 15 :
					$gubun='일반음식점';
					break;
				case 16 :
					$gubun='휴게음식점';
					break;
				case 17 :
					$gubun='제과점';
					break;
				case 18 :
					//$gubun='인터넷컴퓨터게임시설(PC방)';
					$gubun='PC방';
					break;
				case 19 :
					$gubun='게임제공업';
					break;
				case 20 :
					$gubun='복합유통제공업';
					break;
				case 21 :
					$gubun='실내권총사격장';
					break;	



	   }


	   switch($a[8]){
		case 14:
				$giDan='객실';
				
			break;
		case 18 :	
				$giDan='대수.좌석수';
				
			break;
		case 19 :	
				$giDan='대수.좌석수';
				
			break;
		case 20 :	
				$giDan='대수.좌석수';
				
			break;
		default :
			$giDan='면적(㎡)';
			
			break;

	}
	?>
