<?include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일
$dsql="SELECT * FROM 2012DaeriCompany WHERE num='$id'";
$drs=mysql_query($dsql,$connect);
$drow=mysql_fetch_array($drs);

	//대리기사 조회 
		$sql="SELECT * FROM 2012DaeriMember WHERE push='4' and 2012DaeriCompanyNum='$id' order by Jumin asc";
		$rs=mysql_query($sql,$connect);
		$Num=mysql_num_rows($rs);
	//대리기사 조회 끝

?>