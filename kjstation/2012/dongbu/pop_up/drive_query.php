<?

$sql_sj="Select num,oun_name,oun_jumin1,oun_jumin2,certi_number,start,new_jeonggi,sangtae,oun_phone, ";
$sql_sj.="preminum,nab,con_name,con_jumin1,con_jumin2,con_phone,company,bank,bank_number,car_mangi, ";
$sql_sj.="car_company,car_number,content,memo_general_content,memo_content, ";
$sql_sj.="design_num,virtual_num, ";
$sql_sj.="first,second,third,fourth,fifth,sixth,seventh,eighth,nineth,tenth, ";
$sql_sj.="daum_day_1,daum_day_2,daum_day_3,daum_day_4,daum_day_5,daum_day_6,daum_day_7,daum_day_8,daum_day_9,daum_day_10, ";
$sql_sj.="chasu,post,address_1,address_2 " ;
$sql_sj.="FROM new_cs_dongbu WHERE num='$num'";

//echo "sql_sj $sql_sj <br>";
$sj_rs=mysql_query($sql_sj,$connect);


$row_sj = mysql_fetch_array($sj_rs);



$oun_name			=$row_sj[oun_name];
$oun_jumin1			=$row_sj[oun_jumin1];
$oun_jumin2			=$row_sj[oun_jumin2];

$jumin=$oun_jumin1."-".$oun_jumin2;
$start				=$row_sj[start];
$new_jeonggi		=$row_sj[new_jeonggi];
$sangtae			=$row_sj[sangtae];
$oun_phone			=$row_sj[oun_phone];
$preminum			=$row_sj[preminum];
$nab				=$row_sj[nab];
$con_name			=$row_sj[con_name];
$con_jumin1			=$row_sj[con_jumin1];
$con_jumin2			=$row_sj[con_jumin2];
$con_jumin=$con_jumin1."-".$con_jumin2;
$con_phone			=$row_sj[con_phone];
$company			=$row_sj[company];
$certi_number       =$row_sj[certi_number];
$content		    =$row_sj[content];
$memo_general_content=$row_sj[memo_general_content];
$memo_content=$row_sj[memo_content];
$bank				=$row_sj[bank];
$bank_number		=$row_sj[bank_number];
$car_company		=$row_sj[car_company];
$car_mangi		    =$row_sj[car_mangi];
$car_number		    =$row_sj[car_number];
$design_num			=$row_sj[design_num];
$first				=number_format($row_sj[first]);
$second				=number_format($row_sj[second]);
$third				=number_format($row_sj[third]);
$fourth				=number_format($row_sj[fourth]);
$fifth				=number_format($row_sj[fifth]);
$sixth				=number_format($row_sj[sixth]);
$seventh			=number_format($row_sj[seventh]);
$eighth				=number_format($row_sj[eighth]);
$nineth				=number_format($row_sj[nineth]);
$tenth				=number_format($row_sj[tenth]);
$daum_day_1			=$row_sj[daum_day_1];
$daum_day_2			=$row_sj[daum_day_2];
$daum_day_3			=$row_sj[daum_day_3];
$daum_day_4			=$row_sj[daum_day_4];
$daum_day_5			=$row_sj[daum_day_5];
$daum_day_6			=$row_sj[daum_day_6];
$daum_day_7			=$row_sj[daum_day_7];
$daum_day_8			=$row_sj[daum_day_8];
$daum_day_9			=$row_sj[daum_day_9];
$daum_day_10		=$row_sj[daum_day_10];
$chasu				=$row_sj[chasu];

$post           =$row_sj[post];
$address_1            =$row_sj[address_1];
$address_2            =$row_sj[address_2];



//echo "일반 메모 $memo_general_content <br>";
//echo "배서메모 $memo_content <br>";
switch($nab){
	case 2 :
		$da_2="2회차";
		break;

		case 4 :
		$da_2="2회차";
		$da_3="3회차";
		$da_4="4회차";
		break;

		case 6 :
		$da_2="2회차";
		$da_3="3회차";
		$da_4="4회차";
		$da_5="5회차";
		$da_6="6회차";
		break;

		case 10 :
		$da_2="2회차";
		$da_3="3회차";
		$da_4="4회차";
		$da_5="5회차";
		$da_6="6회차";
		$da_7="7회차";
		$da_8="8회차";
		$da_9="9회차";
		$da_10="10회차";



		break;

}


//echo "virtual  $virtual_num <br>";


$osj_sql="SELECT num,company,ceo_name,ceo_fax,ceo_phone,ceo_content FROM new_driver_company WHERE company='$company' ";
$osj_rs = mysql_query($osj_sql,$connect);
$row_osj=mysql_fetch_array($osj_rs);
//echo "osj_sql $osj_sql <br>";



$company_num		=$row_osj['num'];//new_driver_company의 num값
$ceo_name			=$row_osj['ceo_name'];
$ceo_phone			=$row_osj['ceo_phone'];
$ceo_fax			=$row_osj['ceo_fax'];
$ceo_content		=$row_osj['ceo_content'];
switch($sangtae){
				case '1' :
		    	$sangtae_name="당월납입";

				break;

				case '2' :
					$sangtae_name="당월미납";
				break;

				case '3' :
					$sangtae_name="유예";
				break;

				case '4' :
					$sangtae_name="실효";
				break;

				case '5' :
					$sangtae_name="해지";
				break;
				case '6' :
					$sangtae_name="완납";
				break;
				case '7' :
					$sangtae_name="배서";
				break;
				case '8' :
					$gi_name="최종납입";
				break;
				case '9' :
					$gi_name="전체보기";
					
				break;
			}

			switch($nab){
				case '1' :
					$nab_name="일시납";
				break;
				case '2' :
					$nab_name="2회납";
				break;
				case '6' :
					$nab_name="6회납";
				break;
				case '10' :
					$nab_name="10회납";
				break;
			}

	list($hphone1,$hphone2,$hphone3)=explode("-",$oun_phone);

	$sm_sql="SELECT LastTime,Msg FROM SMSData WHERE Rphone1='$hphone1' ";
	$sm_sql.="and Rphone2='$hphone2' and Rphone3='$hphone3'  ";
	$sm_sql.="order by SeqNo desc ";


//echo "sm_sql $sm_sql <br>";
	
	$sm_rs=mysql_query($sm_sql,$connect);
	
	$sm_total=mysql_num_rows($sm_rs);
	
include "./php/post_query.php";
?>