<?
//include '../../csd/include/dbcon.php';
//include "../../csd/club/php/config.php";
//include "../../csd/club/php/util.php";

//echo $bank_number ;

/*
echo  " ceo_name $ceo_name <br>";
echo "ceo_phone $ceo_phone <br>";
echo "ceo_hphone $ceo_fax <br>";
echo "ceo_content $ceo_content <br>";
echo "company $company <br>";*/
if($company){
		//회사 이름이 있다면 조회하여 동일 한게 있으면 update하고 없으면 insert
		$osj_sql="SELECT num,company,ceo_name,ceo_content FROM new_driver_company WHERE company='$company' ";
		$osj_rs = mysql_query($osj_sql,$connect);
		$row_osj=mysql_fetch_array($osj_rs);
		//echo "osj_sql $osj_sql <br>";

		$company_num=$row_osj['num'];
		

		//echo "company_nun $company_num <br>";
		if($company_num){
			$ceo_content_orig=$row_osj['ceo_content'];
			$ceo_content=$ceo_content_orig.$ceo_content;
			$update_sj="UPDATE new_driver_company SET company='$company',ceo_name='$ceo_name',ceo_phone='$ceo_phone', ";
			$update_sj.="ceo_fax='$ceo_fax',ceo_content='$ceo_content' WHERE num='$company_num' ";
			
			mysql_query($update_sj,$connect);
		}else{

			$sql_in="INSERT INTO new_driver_company (num,company,ceo_name,ceo_phone,ceo_fax,ceo_content) ";
			$sql_in.="values ('','$company','$ceo_name','$ceo_phone','$ceo_fax','$ceo_content') ";

			mysql_query($sql_in,$connect);
		}

}
list($start_year,$start_month,$start_day)=explode("-",$start);

$design_num=$start_month."-".$design_num;//설계번호


$chasu='1';
$dbinsert = "insert into new_cs_dongbu (oun_name,oun_jumin1,oun_jumin2,certi_number,start,new_jeonggi,sangtae,oun_phone,  ";
$dbinsert .= "preminum,nab,con_name,con_jumin1,con_jumin2,con_phone,company,bank,bank_number,userid,content, ";
$dbinsert .= "memo_general_content,memo_content, ";
$dbinsert .= "car_company,car_mangi,car_number,wdate,design_num,virtual_num,";
$dbinsert .= "first,second,third,fourth,fifth,sixth,seventh,eighth,nineth,tenth, ";
$dbinsert .= "daum_day_1,daum_day_2,daum_day_3,daum_day_4,daum_day_5,daum_day_6,daum_day_7,daum_day_8,daum_day_9,daum_day_10,chasu, ";
$dbinsert .= "p_con_jumin1,p_con_jumin2,l_number,post,address_1,address_2)";
$dbinsert .= "values('$oun_name','$oun_jumin1','$oun_jumin2','$certi_number','$start','$new_jeonggi','$santage','$oun_phone', ";
$dbinsert .= "'$preminum','$nab','$con_name','$con_jumin1','$con_jumin2','$con_phone','$company','$bank', ";
$dbinsert .= "'$bank_number','$userid','$content', ";
$dbinsert .= "'$memo_general_content','$memo_content', ";
$dbinsert .= "'$car_company','$car_mangi','$car_number',now(),'$design_num','$virtual_num', ";
$dbinsert .= "'$first','$second','$third','$fourth','$fifth','$sixth','$seventh','$eighth','$nineth','$tenth', ";
$dbinsert .= "'$daum_day_1','$daum_day_2','$daum_day_3','$daum_day_4','$daum_day_5','$daum_day_6','$daum_day_7','$daum_day_8', "; 
$dbinsert .= "'$daum_day_9','$daum_day_10', ";
$dbinsert .= "'$chasu','$p_con_jumin1','$p_con_jumin2','$l_number','$postnum','$address1','$address_2')";
//echo"$dbinsert <br> ";
///driver_company_certi  //피보험자를 따로 관리 하기 위해

include "./php/driver_company_certi.php";

//return false;
//return false;
if($userid=='kibs0327'){
		$our_phone='02-558-7383';
   }else{
	  $our_phone='02-558-7384';
   }
//$our_phone="02-558-7120";
list($sphone1,$sphone2,$sphone3)=explode("-",$our_phone);
list($hphone1,$hphone2,$hphone3)=explode("-",$oun_phone);
if($oun_phone){// 운전자 핸드폰이 있다면 증권번호 발송
$oun_name2="님 동부화재 대리운전보험 증권번호는 [";
$oun_name3="] 입니다";
$msg=$oun_name.$oun_name2.$certi_number.$oun_name3;
	if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
			
			
			$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) ";
			$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,now())";
			
		 $insert_result = mysql_query($insert_sql,$connect);

	}
}




if($oun_phone!=$con_phone){
	list($hphone1,$hphone2,$hphone3)=explode("-",$con_phone);
	$oun_name2="님 동부화재 대리운전보험 증권번호는 [";
	$oun_name3="] 입니다";
	$msg=$oun_name.$oun_name2.$certi_number.$oun_name3;
	if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
			
			
			$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) ";
			$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0,now())";
			
		  $insert_result = mysql_query($insert_sql,$connect);

	}
}

$os_rs = mysql_query($dbinsert,$connect);

//저장한 증권번호에 해당하는 nuum을 찾기 위해
$ks_sql="SELECT num FROM new_cs_dongbu WHERE certi_number='$certi_number'";
$ks_rs=mysql_query($ks_sql,$connect);
$ks_row=mysql_fetch_array($ks_rs);

$num=$ks_row['num'];

if($os_rs){
		echo "<script>";
		echo "alert('저장되었습니다.');";
		//echo "self.close();";		
		echo "</script>";
}else{
		echo "<script>";
		echo "alert('뭐가 잘못되었군.');";
		echo "self.close();";		
		echo "</script>";
}
?>

