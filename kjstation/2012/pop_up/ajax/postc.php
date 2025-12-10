<?php 
 include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

			$DaeriCompanyNum =iconv("utf-8","euc-kr",$_GET['num']);	
			$DendorseDay=$now_time;
 
//청약을 정상으로  되면 우편물 발행을 위해 12  || 2012DaeriCompany table응 가리킴 $table_name_num=12

			$msql="SELECT * FROM print_table WHERE table_name_num='12' and table_num='$DaeriCompanyNum' and wdate='$DendorseDay'";
			$mrs=mysql_query($msql,$connect);
			$mrow=mysql_fetch_array($mrs);

			if($mrow[num]){//있으면 날자을 update 없으면 insert 한다

				$mupdate="UPDATE print_table SET table_name_num='12',table_num='$DaeriCompanyNum',wdate='$DendorseDay' ";
				$mupdate.="WHERE num='$mrow[num]'";

				mysql_query($mupdate,$connect);
			}else{
	
				$mInsert="INSERT INTO print_table (table_name_num,table_num,wdate) ";
				$mInsert.="VALUES ('12','$DaeriCompanyNum','$DendorseDay' )";

				mysql_query($mInsert,$connect);
			}
$message="우편물 발행";
echo"<data>\n";
	echo "<num>".$msql."</num>\n";
	echo "<message>".$message."</message>\n";
	
	
	
echo"</data>";	
?>