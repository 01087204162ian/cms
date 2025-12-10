<?header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';



$driverNum=iconv("utf-8","euc-kr",$_GET['driverNum']);
$endorseDay=iconv("utf-8","euc-kr",$_GET['enddorse_day']);

$sql="SELECT * FROM  2012DaeriMember WHERE num='$driverNum'";

//echo "sql $sql ,br>";
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);


$DaeriCompanyNum=$row['2012DaeriCompanyNum'];
$CertiTableNum=$row[CertiTableNum];
$insuranceNum=$row[InsuranceCompany];
$endorseDay=iconv("utf-8","euc-kr",$_GET['enddorse_day']);



include "../pop_up/php/endorseNumSerch.php";

$insertSql="UPDATE 2012DaeriMember SET sangtae='1',OutPutDay='$endorseDay',EndorsePnum='$endorse_num',cancel='42' ";
$insertSql.="WHERE num='$driverNum'";
		

		
	if(	mysql_query($insertSql)){
		
		$message="해지 처리 되었습니다!!";
	}
$e_count=1;//배서 개수 
include "../pop_up/php/endorseNumStore.php";
//$num=$driverNum."||".$endorseDay."|".$CertiTableNum."|".$insuranceNum;

//$num=$sql;

//$num=$insertSql;

//$num=$insuranceNum;
//$num=$driverNum.'||'.$endorseDay;



echo("{\"message\":\"".$message."\","
	// ."\"ex\":\"".$ex."\","
	  ."\"DaeriCompanyNum\":\"".$DaeriCompanyNum."\"}");
//echo("{\"num\":\"".$num."\"}");


?>