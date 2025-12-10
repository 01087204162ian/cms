<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);

if($s_contents){ 

	$where="WHERE name='$s_contents'";
		
}else{
	$where="WHERE wdate>='$sigi' and wdate<='$end' order by wdate desc ";	
}
	$sql="Select num,name,jumin1,jumin2,hphone,wdate,ttime,total_p,gubun,csdrive_fk,booking,adate,atime, ";
	$sql.="booking_date,booking_time,daeri ";
	$sql.="FROM new_dong_bu $where";
	
	//echo "sql $sql <br>";

	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                            //
	/////////////////////////////////////////////////////////////////////////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =20;
	$count = $max_num * $page - 20;
	if($total<20){
		$last=$total;
	}else{
		$last=20;
	}
	$last=$count+$last;
echo"<data>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
			
			$oun_jumin1  = mysql_result($result2,$count,"jumin1");
			//if(!$oun_jumin1){continue;}
			$oun_jumin2	 = mysql_result($result2,$count,"jumin2");
			
			$company=$oun_jumin1."-".$oun_jumin2;
			
			
			$preminum    = mysql_result($result2,$count,"total_p");
			$gubun		 = mysql_result($result2,$count,"gubun");
			switch($gubun){
				case '2' :
					$gubun_name="Cs Virtual";
				break;
				case '3' :
					$gubun_name="Cs 상담";
				break;
				case '4' :
					$gubun_name="Do virtual";
				break;

				case '7' :
					$gubun_name="Do 상담";
				break;
				case '8' :
					$gubun_name="유선";
				break;
				case '9' :
					$gubun_name="Do Virtual";
				break;
				case '10' :
					$gubun_name="대리나라";
				break;
			}
			$booking  = mysql_result($result2,$count,"booking");
			$sangdamday  = mysql_result($result2,$count,"adate");
			$ttime  = mysql_result($result2,$count,"atime");
			
			
			$num		 = mysql_result($result2,$count,"num"); //staff_member의 num
			$e_name 	 = mysql_result($result2,$count,"name");
			
			$com_phone     = mysql_result($result2,$count,"hphone");//웹에서 신청한것과 우리가 신청한것 중에서 
			
			$wdate  = mysql_result($result2,$count,"wdate");
			$wd=explode("-",$wdate);
			$wd[0]=substr($wd[0],2,2);
			$wdate=$wd[0].".".$wd[1].".".$wd[2];
			
			
			if($sangdamday=="0000-00-00"){
				$sangdamday='';
			}
			$sd=explode("-",$sangdamday);
			$sd[0]=substr($sd[0],2,2);
			
			if($sangdamday){
				$sangdamday=$sd[0].".".$sd[1].".".$sd[2];
			}
			//$sangdamtime	 = mysql_result($result2,$count,"sangdamtime");
			//echo "tkd $sangdamtime <br>";
			switch($ttime){

			     case 1 :
					$sangTime="오전10시";
				 break;
				 case 2 :
					$sangTime="오후12시";
				 break;
				 case 3 :
					$sangTime="오후 2시";
				 break;
				 case 4 :
					$sangTime="오후 4시";
				 break;
				 default:
					$sangTime='';
					 break;
			}
		$user_id  = mysql_result($result2,$count,"csdrive_fk");
	   echo"<e_name>".$e_name."</e_name>\n";
	   echo "<company>".$company."</company>\n";
	   echo"<com_phone>".$com_phone."</com_phone>\n";
	   echo"<member>".$member."</member>\n";
	   echo"<sangdamday>".$sangdamday."</sangdamday>\n";
	   echo"<sangdamtime>".$sangTime."</sangdamtime>\n";
	   echo"<user_id>".$user_id."</user_id>\n";
	   echo"<num>".$num."</num>\n";
	   echo"<email>".$gubun_name."</email>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	  
	   $count++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>";
	echo"<totalpage>".$total_page."</totalpage>";
echo"</data>";




	?>