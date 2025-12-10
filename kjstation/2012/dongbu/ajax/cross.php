<?php

include '../../../dbcon.php';
	header('Access-Control-Allow-Origin: ');
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$gi	    =iconv("utf-8","euc-kr",$_GET['p_ush']);

	if($gi==9){//전체 인경우엔
		
	}else{
		$gi_where ="and sangtae='$gi' ";
	}

	$where="WHERE start>='$sigi' and start<='$end' $gi_where";
	
	$sql.="Select * FROM new_cs_dongbu $where order by num desc";

	//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	/*기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                            */
	////
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
		$certi_number=mysql_result($result2,$count,"certi_number");
		if(!$certi_number){continue;}
		$ounname = mysql_result($result2,$count,"oun_name");
		$oun_jumin1 = mysql_result($result2,$count,"oun_jumin1");
		$oun_jumin2 = mysql_result($result2,$count,"oun_jumin2");
		//$ounjuimin=$oun_jumin1."-".$oun_jumin2;
		$ounjuimin=$oun_jumin1;
		$conname = mysql_result($result2,$count,"con_name");
		$con_jumin1 = mysql_result($result2,$count,"con_jumin1");
		$con_jumin2 = mysql_result($result2,$count,"con_jumin2");
		$conjuimin=$con_jumin1."-".$con_jumin2;

		$company=mysql_result($result2,$count,"company");
		$wdate			= mysql_result($result2,$count,"wdate"); 
		$wd=explode("-",$wdate);
		$wd[0]=substr($wd[0],2,2);
		$wdate=$wd[0].".".$wd[1].".".$wd[2];
		//$wdate=$wd[1].".".$wd[2];
		$driver_num= mysql_result($result2,$count,"num"); 
		
	   list($certi_01,$certi_02,$certi_03,$certi_04,$certi_05,$certi_06)=explode("-",$certi_number);
	   $certi=$certi_02."-".$certi_03;
		$sangtae= mysql_result($result2,$count,"sangtae");
		$user_id= mysql_result($result2,$count,"userid");
		$preminum= mysql_result($result2,$count,"preminum");
		$start= mysql_result($result2,$count,"start");
		$sd=explode("-",$start);
		$sd[0]=substr($sd[0],2,2);
		$start=$sd[0].".".$sd[1].".".$sd[2];
		//$start=$sd[1].".".$sd[2];
		switch($sangtae){
			case 1 :
				$sangtaeName="납입";
			break;
			case 2 :
				$sangtaeName="미납";
			break;
			case 3 :
				$sangtaeName="유예";
			break;
			case 4 :
				$sangtaeName="실효";
			break;
			case 5 :
				$sangtaeName="해지";
			break;
			case 6 :
				$sangtaeName="완납";
			break;
			case 7 :
				$sangtaeName="배서";
			break;
			case 8 :
				$sangtaeName="최종";
			break;
		}
	
		$com_phone     = mysql_result($result2,$count,"oun_phone");
		if(!$com_phone){//운전자의 전화 번호가 없으면
		  $com_phone     = mysql_result($result2,$count,"con_phone");
		}
	   echo"<driveNum>".$driver_num."</driveNum>\n";
	   echo"<ounname>".$ounname."</ounname>\n";
	   echo"<ounjuimin>".$ounjuimin."</ounjuimin>\n";
	   echo"<conname>".$conname."</conname>\n";
	   echo"<conjuimin>".$conjuimin."</conjuimin>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<user_id>".$user_id."</user_id>\n";
	   echo "<company>".$company."</company>\n";
	   echo"<certi>".$certi."</certi>\n";
	  echo"<sangtae>".$sangtaeName."</sangtae>\n";
	  echo"<preminum>".number_format($preminum)."</preminum>\n";
	   echo"<start>".$start."</start>\n";
	  echo"<com_phone>".$com_phone."</com_phone>\n";

	   $count++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
echo"</data>";



	?>