<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$period	    =iconv("utf-8","euc-kr",$_GET['period']);
	$state	    =iconv("utf-8","euc-kr",$_GET['state']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$gi	    =iconv("utf-8","euc-kr",$_GET['gi']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	
	switch($gi){
		case '1' :
		 $kibs_sql="SELECT num FROM kibs_list WHERE com_name='$s_contents'";
		 $kibs_rs=mysql_query($kibs_sql,$connect);
		 $kibs_row=mysql_fetch_array($kibs_rs);
		 $kibs_list_num=$kibs_row['num'];
		 $gi_where="and kibs_list_num ='$kibs_list_num' ";
		break;
		case '2' :
		     $gi_where ="and cancel_certi ='$search_name' ";
		break;
		case '3' :
		     $gi_where ="and new_certi ='$search_name'";
		break;
		case '9' :
			$gi_name="전체보기";
		    $gi_where ="";
		break;
		default :
			$gi_where ="";
		break;
	}
	
	$where="WHERE wdate>='$sigi' and wdate<='$end' $gi_where order by num desc";
	$sql="Select num,kibs_list_num,cancel_certi,new_certi,wdate,useruid ";
	$sql.="FROM kibs_list_cancel $where";

//echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	/*기간동안의 전체 건수를 나누어서
	/*페이지 마다 20개의 Data 만 불러 온다                                            */
	/**********************************************************************************/
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
			$num		 = mysql_result($result2,$count,"num"); //kibs_cancel_list의 num 값
			if(!$num){ continue;};
			$kibs_list_num 	 = mysql_result($result2,$count,"kibs_list_num");
			$cancel_certi  = mysql_result($result2,$count,"cancel_certi");
			$new_certi	 = mysql_result($result2,$count,"new_certi");
			$wdate = mysql_result($result2,$count,"wdate");
			$user_id	 = mysql_result($result2,$count,"useruid");
           $sql_2="SELECT com_name,com_eur_name FROM kibs_list WHERE num='$kibs_list_num'";
			$rs_2=mysql_query($sql_2,$connect);
			$row_2=mysql_fetch_array($rs_2);
			$com_name=$row_2['com_name'];

	   echo"<cancel_certi>".$cancel_certi."</cancel_certi>\n";
	   echo"<new_certi>".$new_certi."</new_certi>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<com_name>".$com_name."</com_name>\n";
	   echo"<user_id>".$user_id."</user_id>\n";
	   echo"<num>".$num."</num>\n";
	  
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