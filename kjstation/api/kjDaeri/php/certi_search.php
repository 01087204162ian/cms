<?
//보험회사 보험료 2012Certi 종합적으로 관리함 2025-04-01
$Csql2 = "SELECT divi FROM 2012CertiTable WHERE num='$cNum'";
$Crs2 = mysql_query($Csql2, $conn); // $conn는 dbcon.php에서 포함됨
$Crow2 = mysql_fetch_array($Crs2);
/*$startDay=$Crow2['startyDay']; //보험시작일
$nabang=$Crow2['nabang'];     //분납횟수
$nabang_1=$Crow2['nabang_1']; //분납회차
*/
$divi=$Crow2['divi']; // 1정상납, 2월납



$Csql =  "SELECT sigi,nab FROM 2012Certi WHERE certi='$policyNum' ";

$Crs = mysql_query($Csql, $conn); // $conn는 dbcon.php에서 포함됨
$Crow = mysql_fetch_array($Crs);
$startDay=$Crow['sigi']; //보험시작일
$nabang=10;     //분납횟수
$nabang_1=$Crow['nab']; //분납회차

?>