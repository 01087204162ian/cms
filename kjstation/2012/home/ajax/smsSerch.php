<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");


	
	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);

	list($s_year,$s_month,$s_day)=explode("-",$sigi);
	$sigi=$s_year.$s_month.$s_day."000000";
	$end	 = date("Y-m-d ",strtotime("$end +1 day"));
	list($e_year,$e_month,$e_day)=explode("-",$end);
	$end=$e_year.$e_month.$e_day."000000";

	if($s_contents){
		$Rphone=explode('-',$s_contents);
	   //$where="where Rphone1='$Rphone[0]' and Rphone2='$Rphone[1]' and Rphone3='$Rphone[2]'  and SendName='drive' order by SeqNo desc";'
	   $where="where Rphone1='$Rphone[0]' and Rphone2='$Rphone[1]' and Rphone3='$Rphone[2]' and dagun='1'  order by SeqNo desc";
	}else{
		$where="WHERE LastTime>='$sigi' and LastTime<='$end' and dagun='1' order by SeqNo desc";
	}
	$sql="Select Rphone1,Rphone2,Rphone3, ";
	$sql.="Msg,LastTime ";
	$sql.="FROM SMSData $where";
	
	

	//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                           //
	//////////////////////////////////////////////////////////////////////////////////////
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
	for($k=0;$k<$last;$k++){
	  
	$Rphone1  = mysql_result($result2,$count,"Rphone1");
	$Rphone2  = mysql_result($result2,$count,"Rphone2");
	$Rphone3  = mysql_result($result2,$count,"Rphone3");
	$Msg 	 = mysql_result($result2,$count,"Msg");
	$hphone=$Rphone1."".$Rphone2."".$Rphone3;
	$LastTime 	 = mysql_result($result2,$count,"LastTime");

	$ysear			=substr($LastTime,0,4);
	$month			=substr($LastTime,4,2);
	$day			=substr($LastTime,6,2);
	$_time			=substr($LastTime,8,2);
	$_minute		=substr($LastTime,10,2);

	$boneDate=$ysear.".".$month.".".$day.".".$_time.":".$_minute;
	   echo"<bone>".$boneDate."</bone>\n";
	   echo"<msg>".$Msg."</msg>\n";
	   echo"<hphone>".$hphone."</hphone>\n";
	
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