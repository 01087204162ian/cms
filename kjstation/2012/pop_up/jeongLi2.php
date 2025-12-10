<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "다중일련번호정리하기위해";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/MemberList.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>
<form>
<body id="popUp">



<? 
	$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='1' AND push='4'  order by Jumin asc";
	//$sql="SELECT * FROM 2013dajoong  ";
	//$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='4' AND push='4' and dongbuCerti='2012-4933832' order by Jumin asc";


	//echo "sql $sql <br>";

	$rs=mysql_query($sql,$connect);

	$Rnum=mysql_num_rows($rs);
echo "Rnum $Rnum <br>";

	for($j=0;$j<$Rnum;$j++){

		$Row=mysql_fetch_array($rs);

			$m[$j]=explode("/",$Row[serial]);

		//	$m2[$j]=$m[$j][0]."-".$m[$j][1]."-".$m[$j][2]."-".$m[$j][3];
		//echo "$j $Row[Name] $Row[Jumin]  $Row[serial]  <br>";


		$update="UPDATE  2012DaeriMember SET push='2' WHERE num='$Row[num]'";

		echo "update  $update <br>";

	//	mysql_query($update,$connect);


	}


?>

</body>
</html>