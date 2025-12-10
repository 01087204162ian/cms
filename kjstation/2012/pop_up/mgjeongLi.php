<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "년령별구성";?></title>
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
	$sql="SELECT * from 2012CertiTable WHERE policyNum='2016-4836676'";
//대리운전회
	//$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='4' AND push='4' and dongbuCerti='2012-4933301' order by Jumin asc";
	//$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='4' AND push='4' and dongbuCerti='2012-4933791' order by Jumin asc";
	//$sql="SELECT * FROM 2012DaeriMember WHERE  InsuranceCompany='4' AND push='4' and dongbuCerti='2012-4933832' order by Jumin asc";

	// $sql="SELECT * FROM 2012DaeriMember WHERE  2012DaeriCompanyNum='146' AND push='4' and CertiTableNum='720' order by Jumin asc";
	//	$sql="SELECT * FROM 2012DaeriMember WHERE  2012DaeriCompanyNum='130' AND push='4' and  InsuranceCompany='7' and etag!='2' order by Jumin asc";
	//echo "sql $sql <br>";

	$rs=mysql_query($sql,$connect);

	$Rnum=mysql_num_rows($rs);
	//$mRow[startyDay]="2013-09-10";

echo "Rnum $Rnum <br>";

	for($j=0;$j<$Rnum;$j++){

		$Row=mysql_fetch_array($rs);
		   //만나이 계산을 위해 
			$p=explode("-",$Row[Jumin]);
			$s=explode("-",$mRow[startyDay]);
			$m1=substr($mRow[startyDay],0,4);
			$m2=substr($mRow[startyDay],5,2);
			$m3=substr($mRow[startyDay],8,2);
			$sigi=$m1.$m2.$m3;			
			$birth="19".$p[0];
			$p[0]=$sigi-$birth;
			$p[0]=floor(substr($p[0],0,2));

	//if($Row[Name]=="홍희진"){

	//	continue;
	//}


		//echo "$j $Row[Name] $Row[Jumin]  $p[0] $dongbuCerti<br>";
		$update="UPDATE 2012CertiTable SET policyNum='2017-4946899',startyDay='2017-11-01' WHERE num='$Row[num]'";

	//	$update="UPDATE 2012DaeriMember SET InsuranceCompany='7',CertiTableNum='924', ";
	
	//	$update.="dongbuCerti='$dongbuCerti', nai='$p[0]' WHERE num='$Row[num]'";

		echo "$j  update  $update <br>";

	mysql_query($update,$connect);


	}


?>

</body>
</html>