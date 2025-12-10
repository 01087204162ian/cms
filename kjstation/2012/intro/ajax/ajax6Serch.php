<?php
include '../../../dbcon.php';;
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);

	$tableNum    =iconv("utf-8","euc-kr",$_GET['tableNum']);
	
	if($tableNum==99){
		$where2="";
	}else{
		$where2="and table_name_num='$tableNum'";
	}
		$where="WHERE  wdate>='$sigi' and wdate<='$end' $where2 order by wdate desc";	
		$sql="Select num,table_name_num,table_num,wdate  ";
		$sql.="FROM print_table $where";
	
	

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

	$max_num =16;
	$count = $max_num * $page - 16;
	if($total<16){
		$last=$total;
	}else{
		$last=16;
	}
	if($total-$count<16){//총개수-$congt 32-20인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;
echo"<data>\n";
	//echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
		$printTableNum		 = mysql_result($result2,$count,"num"); //print_table의 num
		$table_name_num 	 = mysql_result($result2,$count,"table_name_num");
		$table_num     = mysql_result($result2,$count,"table_num");

		
		$wdate  = mysql_result($result2,$count,"wdate");
		$wd=explode("-",$wdate);
		$wd[0]=substr($wd[0],2,2);
		$wdate=$wd[0].".".$wd[1].".".$wd[2];
		include "./table_swtich_query.php";
		$post_gubun=$table_name_num;
		$post_gu=$table_num;

		
	   echo "<company>".$new_company."</company>\n";
	   echo"<certi>".$certi_number."</certi>\n";
	   echo"<ssang_c_num>".$printTableNum."</ssang_c_num>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<c_name>".$new_name."</c_name>\n";
	   echo"<oun_name>".$new_oun_name."</oun_name>\n";
	   echo"<com_name>".$com_name."</com_name>\n";
	   echo"<sjNum>".$post_gubun."</sjNum>\n";
	   echo"<kjNum>".$post_gu."</kjNum>\n";//printTable의 table_num
	  	   $count++;

		   $certi_number='';
		   $new_oun_name='';
		   $com_name='';
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>";
	echo"<totalpage>".$total_page."</totalpage>";
echo"</data>";



	?>