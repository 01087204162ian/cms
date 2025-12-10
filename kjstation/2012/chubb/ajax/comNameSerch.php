<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$com_name	    =iconv("utf-8","euc-kr",$_GET['com_name']);
	
	$sql.="SELECT com_name,num FROM kibs_list WHERE com_name LIKE '%$com_name%'";

//	echo "Sql $sql <br>";
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
	   
	    $comName=mysql_result($result2,$count,"com_name");
		$num=mysql_result($result2,$count,"num");
	   echo"<comName>".$comName."</comName>\n";
	   echo"<num>".$num."</num>\n";
	  

	
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