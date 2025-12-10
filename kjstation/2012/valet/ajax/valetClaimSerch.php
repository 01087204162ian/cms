<?php
include '../../../dbcon.php';
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

	$where="WHERE wdate2>='$sigi' and wdate2<='$end' $gi_where";
	$sql="Select * ";
	$sql.="FROM 2014ValetImage  $where order by num desc";

	//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	/*기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                            */
	///////////////////////////////////////////////////////////////////////////////////
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
		$num=mysql_result($result2,$count,"num");
		
		$images = mysql_result($result2,$count,"images");
		$PhoneNumber  = mysql_result($result2,$count,"PhoneNumber");
		$wdate = mysql_result($result2,$count,"wdate");
		//$ounjuimin=$oun_jumin1."-".$oun_jumin2;
		
		$wdate2 = mysql_result($result2,$count,"wdate2");
		
	   echo"<num>".$num."</num>\n";
	   echo"<images>".$images."</images>\n";
	   echo"<PhoneNumber>".$PhoneNumber."</PhoneNumber>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<wdate2>".$wdate2."</wdate2>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   
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