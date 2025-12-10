<?
//include '../../csd/include/dbcon.php';
//include "../../csd/club/php/config.php";
//include "../../csd/club/php/util.php";
//회사 이름이 있다면 조회하여 동일 한게 있으면 update하고 없으면 insert


$osj_sql="SELECT num,company,ceo_name FROM new_driver_company WHERE company='$company' ";
$osj_rs = mysql_query($osj_sql,$connect);
$row_osj=mysql_fetch_array($osj_rs);
//echo "osj_sql $osj_sql <br>";

$company_num=$row_osj['num'];
$ceo_content=addslashes($ceo_content);
//echo "company_nun $company_num <br>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//납입 상태를 변경하기위해(해지,배서,최종납입,완납																////
//차수해당되는 일자의 년월과 당일의 년월과 같으면	당월납일													////
//당일의 년월-차수해당된는 일자의 년월=1 당월미납																////
// 당일의 년월-차수해당된는 일자의 년월=2 유예																	////
// 당일의 년월-차수해당된는 일자의 년월=3 실효																	////
// 값	chasu :지금까지 낸 차수,bunab_state :내는 차수															////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo "chasu $chasu <br>"; //지금까지 낸 차수
$chai=$bunab_state-$chasu;  
//echo "bunab_state $bunab_state <br>";
//echo "chai $chai <br>";
switch($chai){
	case 1 :
		$chasu=$chasu+1;
	break;
	case 2 :
		$chasu=$chasu+2;
	break;
}
//echo "santage $santage <br>";
//if($start>'2006-10-26'){
	if($santage<=3){//상태가 당월납부,당월미납,유예 일 경우만 이 부분이 적용됨
				switch($chasu){
					case 1 :
						$chasu_day=$daum_day_1;
					break;
					case 2 :
						$chasu_day=$daum_day_2;
					break;
					case 3 :
						$chasu_day=$daum_day_3;
					break;
					case 4 :
						$chasu_day=$daum_day_4;
					break;
					case 5 :
						$chasu_day=$daum_day_5;
					break;
					case 6 :
						$chasu_day=$daum_day_6;
					break;
					case 7 :
						$chasu_day=$daum_day_7;
					break;
					case 8 :
						$chasu_day=$daum_day_8;
					break;
					case 9 :
						$chasu_day=$daum_day_9;
					break;

					case 10 :
						$chasu_day=$daum_day_10;
					break;
				}

				//echo "chasu_day $chasu_day <br>";
				list($daum_year,$daum_month,$daum_day)=explode("-",$chasu_day);
				$chasu_day_month=$daum_year.$daum_month;
				//echo "chasu_day_month $chasu_day_month <br>";

				$today_year=date("Y");
				$today_month=date("m");

				//$today_year="2007";
				//$today_month="02";
				$today_year_month=$today_year.$today_month;
				//echo "today_year_month $today_year_month <br>";
				if($today_year>$daum_year){//년도가 변경되면 200701을 200613으로 변경하기 위해
					$today_month=$today_month+12;
					$today_year_month=$daum_year.$today_month;
				}


				$today_year_month=$today_year.$today_month;


				//echo "today_year_month $today_year_month <br>";

				$month_value=$today_year_month-$chasu_day_month;
			//echo "chasu_day_month $chasu_day_month <br>";
			//echo "month_value $month_value <br>";

				
		if($bunab_state==$nab){//납입회수와 분납회차가 같으면 최종납입이 된다
								$santage=1;//당월납입
							//10회납인 경우 10회차를 낸 경우는 최종납입
							//6회납인 경우 6회차를 낸 경우는 최종납입
							//2회납인 경우 2회차를 낸 경우는 최종납입
							//일시납은 최종납입

				$santage=8;
		}else{

					switch($month_value){
						case 0 :
							

							$santage=1;//당월납입
							break;
						case 1 :
							$santage=2;//당월미납
							break;
						case 2 :
							$santage=3;//유예
							break;
						case 3 :
							$santage=4;//실효
							break;
					}

		}		//echo "sangtage $santage <br>";
		//return false;
	}
//}
if($company_num){
	$update_sj="UPDATE new_driver_company SET company='$company',ceo_name='$ceo_name',ceo_phone='$ceo_phone', ";
	$update_sj.="ceo_fax='$ceo_fax',ceo_content='$ceo_content' WHERE num='$company_num' ";
	
	mysql_query($update_sj,$connect);
}else{

	$sql_in="INSERT INTO new_driver_company (num,company,ceo_name,ceo_phone,ceo_fax,ceo_content) ";
	$sql_in.="values ('','$company','$ceo_name','$ceo_phone','$ceo_fax','$ceo_content') ";

	mysql_query($sql_in,$connect);
}
//echo "buna_2 $buna_2 <br>";
//echo "bunab_state $bunab_state <br>";
//echo "con_phone $con_phone <br>";
	if($bunab_state){//분납을 체크한 경우에만
		$chasu=$bunab_state;

		/////분납을 체크한 경우 계약자에게 문자 보내기 위해 

		include "./bunnab_sms.php";
}

//return false;
list($aaa,$kkk,$yyy) 	= explode(",",$preminum);
$preminum=$aaa.$kkk.$yyy;
list($first_1,$first_2,$first)=explode(",",$first);
$first=$first_1.$first_2.$first_3;
list($second_1,$second_2,$second)=explode(",",$second);
$second=$second_1.$second_2.$second_3;
list($third_1,$third_2,$third)=explode(",",$third);
$third=$third_1.$third_2.$third_3;
list($fourth_1,$fourth_2,$fourth)=explode(",",$fourth);
$fourth=$fourth_1.$fourth_2.$fourth_3;
list($fifth_1,$fifth_2,$fifth)=explode(",",$fifth);
$fifth=$fifth_1.$fifth_2.$fifth_3;
list($sixth_1,$sixth_2,$sixth)=explode(",",$sixth);
$sixth=$sixth_1.$sixth_2.$sixth_3;
list($seventh_1,$seventh_2,$seventh)=explode(",",$seventh);
$seventh=$seventh_1.$seventh_2.$seventh_3;
list($eighth_1,$eighth_2,$eighth)=explode(",",$eighth);
$eighth=$eighth_1.$eighth_2.$eighth_3;
list($nineth_1,$nineth_2,$nineth)=explode(",",$nineth);
$nineth=$nineth_1.$nineth_2.$nineth_3;
list($tenth_1,$tenth_2,$tenth)=explode(",",$tenth);
$tenth=$tenth_1.$tenth_2.$tenth_3;

$sql1 = "UPDATE new_cs_dongbu SET oun_name='$oun_name',oun_jumin1='$oun_jumin1', ";
$sql1 .= "oun_jumin2='$oun_jumin2',certi_number='$certi_number',start='$start',new_jeonggi='$new_jeonggi',sangtae='$santage',";
$sql1 .= "oun_phone='$oun_phone',preminum='$preminum',nab='$nab',con_name='$con_name', ";
$sql1 .= "con_jumin1='$con_jumin1',con_jumin2='$con_jumin2',con_phone='$con_phone',company='$company',";
$sql1 .= "bank='$bank',bank_number='$bank_number',userid='$userid',car_company='$car_company',car_mangi='$car_mangi', ";
$sql1 .= "car_number='$car_number',content='$content', ";
$sql1 .= "memo_general_content='$memo_general_content',memo_content='$memo_content', ";
$sql1 .= "wdate=now(),design_num='$design_num',virtual_num='$virtual_num', ";
$sql1 .= "first='$first',second='$second',third='$third',fourth='$fourth',fifth='$fifth',sixth='$sixth', ";
$sql1 .= "seventh='$seventh',eighth='$eighth', ";
$sql1 .= "nineth='$nineth',tenth='$tenth', ";
$sql1 .= "daum_day_1='$daum_day_1',daum_day_2='$daum_day_2',daum_day_3='$daum_day_3', ";
$sql1 .= "daum_day_4='$daum_day_4',daum_day_5='$daum_day_5', ";
$sql1 .= "daum_day_6='$daum_day_6',daum_day_7='$daum_day_7',daum_day_8='$daum_day_8', "; 
$sql1 .= "daum_day_9='$daum_day_9',daum_day_10='$daum_day_10', ";
$sql1 .= "chasu='$chasu',post='$postnum',address_1='$address1',address_2='$address_2' WHERE num='$num'";

//echo"$sql1";
//return false;
$os_rs = mysql_query($sql1,$connect);
///driver_company_certi  //피보험자를 따로 관리 하기 위해

include "./php/driver_company_certi.php";

list($aaa,$kkk,$yyy) 	= explode(",",$change_preminum);
$change_preminum=$aaa.$kkk.$yyy;
list($first_1,$first_2,$first)=explode(",",$change_first);
$change_first=$first_1.$first_2.$first_3;
list($second_1,$second_2,$second)=explode(",",$change_second);
$change_second=$second_1.$second_2.$second_3;
list($third_1,$third_2,$third)=explode(",",$change_third);
$change_third=$third_1.$third_2.$third_3;
list($fourth_1,$fourth_2,$fourth)=explode(",",$change_fourth);
$change_fourth=$fourth_1.$fourth_2.$fourth_3;
list($fifth_1,$fifth_2,$fifth)=explode(",",$change_fifth);
$change_fifth=$fifth_1.$fifth_2.$fifth_3;
list($sixth_1,$sixth_2,$sixth)=explode(",",$change_sixth);
$change_sixth=$sixth_1.$sixth_2.$sixth_3;
list($seventh_1,$seventh_2,$seventh)=explode(",",$change_seventh);
$change_seventh=$seventh_1.$seventh_2.$seventh_3;
list($eighth_1,$eighth_2,$eighth)=explode(",",$change_eighth);
$change_eighth=$eighth_1.$eighth_2.$eighth_3;
list($nineth_1,$nineth_2,$nineth)=explode(",",$change_nineth);
$change_nineth=$nineth_1.$nineth_2.$nineth_3;
list($tenth_1,$tenth_2,$tenth)=explode(",",$change_tenth);
$change_tenth=$tenth_1.$tenth_2.$tenth_3;
$content2="에서 운전자 변경";
if($santage=='7'){
			list($start_year,$start_month,$start_day)=explode("-",$change_start);
			list($kkk,$yyy) 	= explode(",",$change_preminum);
			$change_preminum=$kkk.$yyy;

		

			$content=$oun_name.$content2.$now_time;

		$dbinsert = "insert into new_cs_dongbu (oun_name,oun_jumin1,oun_jumin2,certi_number,start,sangtae,oun_phone, ";
		$dbinsert .= "preminum,nab,con_name,con_jumin1,con_jumin2,con_phone,company,bank,bank_number,userid,content, ";
		$dbinsert .= "car_company,car_mangi,car_number,wdate,design_num,virtual_num, ";
		$dbinsert .= "first,second,third,fourth,fifth,sixth,seventh,eighth,nineth,tenth, ";
		$dbinsert .= "daum_day_1,daum_day_2,daum_day_3,daum_day_4,daum_day_5,daum_day_6,daum_day_7,daum_day_8, ";
		$dbinsert .= "daum_day_9,daum_day_10,chasu,post,address_1,address_2)";
		$dbinsert .= "values('$change_name','$change_jumin1','$change_jumin2','$change_certi_number','$change_start', ";
		$dbinsert .= "'$change_santage', "; 
		$dbinsert .= "'$change_phone', ";
		$dbinsert .= "'$change_preminum','$change_nab','$con_name','$con_jumin1','$con_jumin2','$con_phone','$company','$bank', ";
		$dbinsert .= "'$bank_number','$userid','$content','$change_car_company','$change_car_mangi','$change_car_number',now(), ";
		$dbinsert .= "'$change_design_num','$change_virtual_num', ";
		$dbinsert .= "'$change_first','$change_second','$change_third','$change_fourth','$change_fifth', ";
		$dbinsert .= "'$change_sixth','$change_seventh','$change_eighth','$change_nineth','$change_tenth', ";
		$dbinsert .= "'$change_daum_day_1','$change_daum_day_2','$change_daum_day_3','$change_daum_day_4','$change_daum_day_5', ";
		$dbinsert .= "'$change_daum_day_6','$change_daum_day_7','$change_daum_day_8', "; 
		$dbinsert .= "'$change_daum_day_9','$change_daum_day_10', ";
		$dbinsert .= "'$chasu','$post','$address_1','$address_2')";

		//배서 처리후 memo_2007에 처리 결과를 넣어준다

		$en_sql ="SELECT num FROM memo_2007 WHERE cs_dongbu_num='$num' ";
		$en_rs  = mysql_query($en_sql,$connect);
		$en_row = mysql_fetch_array($en_rs);

		$memo_num = $en_row[num];

		$memo_en_sql ="UPDATE memo_2007 SET endorse='2' WHERE num='$memo_num'";
		mysql_query($memo_en_sql,$connect);


		///
		echo"$dbinsert <br> ";
//return false;
		mysql_query($dbinsert,$connect);
		if($userid=='kibs0327'){
				$our_phone='070-7041-5962';
		   }else{
			  $our_phone='070-7041-5962';
		   }
		//$our_phone="02-558-7120";
		list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);//보내는 사람 번호 우리회사
		list($hphone1,$hphone2,$hphone3)=explode("-",$change_phone);//고객
		if($oun_phone){// 운전자 핸드폰이 있다면 증권번호 발송

		$msg="$change_name 님의 동부화재 대리운전보험 증권번호는 [$certi_number] 입니다";
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,now())";
					
				 $insert_result = mysql_query($insert_sql,$connect);

			}
		}

		if($oun_phone!=$con_phone){
			list($hphone1,$hphone2,$hphone3)=explode("-",$con_phone);
			$msg="$change_name 님의 동부화재 대리운전보험 증권번호는 [$certi_number] 입니다";
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
					
					
					$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) ";
					$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,now())";
					
			$insert_result = mysql_query($insert_sql,$connect);

			}


		


}
}





$seong='';

//메모체크가 되면 memo_2007에 저장하기 위해

$content_2=$content;
if($memo_check==2){

	$sql="INSERT INTO memo_2007 (num,name,jumin1,jumin2,hphone,content,wdate,wtime,user_id,endorse) ";
	$sql.="values ('','$oun_name','$oun_jumin1','$oun_jumin2','$oun_phone','$content_2',now(),now(),'$userid','4')";

	mysql_query($sql,$connect);

}
$content_3=$memo_general_content;
if($memo_check==3){

	$sql="INSERT INTO memo_2007 (num,name,jumin1,jumin2,hphone,content,wdate,wtime,user_id,endorse) ";
	$sql.="values ('','$oun_name','$oun_jumin1','$oun_jumin2','$oun_phone','$content_3',now(),now(),'$userid','4')";

	mysql_query($sql,$connect);

}
$content_4=$oun_name."(".$certi_number.")"."을".$memo_content."으로 운전자 변경";
if($memo_check==4){

	$sql="INSERT INTO memo_2007 (num,name,jumin1,jumin2,hphone,content,wdate,wtime,user_id,cs_dongbu_num,endorse) ";
	$sql.="values ('','$oun_name','$oun_jumin1','$oun_jumin2','$oun_phone','$content_4',now(),now(),'$userid','$num','5')";

	mysql_query($sql,$connect);

}


if($os_rs){
		echo "<script>";
		echo "alert('수정 되었습니다.');";
		//echo "self.close();";
		
		echo "</script>";
}else{
		echo "<script>";
		echo "alert('뭐가 잘못되었군.');";
		echo "self.close();";		
		echo "</script>";
}
?>

