<?
$Dsql = "SELECT company,FirstStart,MemberNum,jumin,hphone,cNumber FROM 2012DaeriCompany WHERE num='$dNum'";
$Drs = mysql_query($Dsql, $conn); // $conn는 dbcon.php에서 포함됨
$Drow = mysql_fetch_array($Drs);

			list($duYear, $duMonth, $dueDay) = explode("-",$Drow['FirstStart'], 3); // 정기결제일
?>