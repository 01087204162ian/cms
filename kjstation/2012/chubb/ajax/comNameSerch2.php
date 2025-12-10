<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보

	$koreanb	    =iconv("utf-8","euc-kr",$_GET['koreanb']);
	
	$koreansep = array("가","나","다","라","마","바","사","아","자","차","카","타","파","하",chr(oxfe));

	$sql= "select com_name,num from kibs_list where ";
	$sql.=($koeaanb != " ")? " com_name>='".$koreansep[$koreanb]."' and com_name<'".$koreansep[$koreanb+1]."' " : " ";
	$sql.=($ordertype =="DESC") ? " order by familyname desc,com_name desc" : " order by com_name asc";

//	echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	/*기간동안의 전체 건수를 나누어서
	//페이지 마다 20개의 Data 만 불러 온다                                            */
	//////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =100;
	$count = $max_num * $page - 100;
	if($total<100){
		$last=$total;
	}else{
		$last=100;
	}
	$last=$count+$last;
echo"<data>\n";
	//echo "<sql>".$sql."</sql>\n";
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