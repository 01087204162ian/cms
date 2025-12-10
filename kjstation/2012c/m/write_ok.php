<?header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';



$user_name=iconv("utf-8","euc-kr",$_GET['user_name']);
$user_jumin=iconv("utf-8","euc-kr",$_GET['user_jumin']);
$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
$insuranceNum=iconv("utf-8","euc-kr",$_GET['insuranceNum']);
$endorseDay=iconv("utf-8","euc-kr",$_GET['enddorse_day']);



include "../pop_up/php/endorseNumSerch.php";

$insertSql="INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
$insertSql.="Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum )";
$insertSql.="values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
$insertSql.="'$user_name','$user_jumin','','1','','1','','$endorseDay','$endorse_num')";

mysql_query($insertSql,$connect);
$e_count=1;//배서 개수 
include "../pop_up/php/endorseNumStore.php";
$num=$user_name."|".$user_jumin."|".$DaeriCompanyNum."|".$endorseDay."|".$CertiTableNum."|".$insuranceNum;

//$num=$en_sql;

//$num=$insertSql;

//$num=$insuranceNum;

//$num='신규 추가 완료';;
echo("{\"num\":\"".$num."\"}");


?>