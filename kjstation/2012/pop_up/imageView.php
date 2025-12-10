<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/EndorseAjax.js" type="text/javascript"></script><!--ajax-->
	
</head>
<?

	$sql="SELECT * FROM 2014ValetImage WHERE PhoneNumber='$PhoneNumber'";

	
	$rs=mysql_query($sql,$connect);


	$count=mysql_num_rows($rs);
	
	//echo $count;

	//echo "$row[images]";

?>
<?//include "./php/endorseCount.php";?>
<?$redirectURL='DMM_System'?>

<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$pImageNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='eNum' value='<?=$eNum?>' />
	<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
		<div id="SjPopUpMain">
		
			  <div id="listPart" ><? include "./php/imageTable.php";?>	</div>
		
		  
				
		</div>
			
			


</form>
</body>
</html>