<?include '../../2012c/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>일반</title>
	<link href="./css/common.css" rel="stylesheet" type="text/css" />
	<link href="./css/preminum2.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<!--datePicker-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript" src="./js/dayInsPreminum.js"></script>


</head>
<form>
<body id="popUp">
<body style="background:#fafafa;" class="jui" >
<!--1/12 보험료-->
<div class="wrap" id="wrap">
	
	<div class="bau_blue_bar" id="bau_blue_bar"></div>
	

   <div id="listPart" >
		
		  <input type='hidden' id='certiTableNum' value='<?=$CertiTableNum?>'/>
		  
		
			 
			<table id="d_table">
		 
					
					<tbody id="sjo">
					
					
					</tbody>
					
				</table>
	</div> <!--listPart-->
		<!-- //bau_contents -->
		
		
	</div>
	<!-- //contents -->
</div>
</body>
</html>