<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>동부화재보험료정리</title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="./js/dongpreminum.js" type="text/javascript"></script>


</head>

<?$redirectURL='DMM_System'?>
   <body id="ssangyong">

		<div id="pop_">
		  <form>
				<input type='hidden' id='daerimemberNum' value='<?=$num?>'>
			   			 
					<div id="serchPart"> 
						<input type="text" value="<?=$endorseDay?>" readonly name="sigi" id="sigi" size='10' maxlength='10' />
						<img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].sigi,'yyyy-mm-dd',this)"  />

						<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="날자변경"  onFocus='this.blur()'      onClick="cordSerch()">
					 </div>
					 <br>
					<table>
					    
						<tbody class="scrollingContent" id="sjo">	
					  </tbody>
					</table>
					</form>
				</div>
				
  
				
		  
	
			<p id="copyright2"><? include "../php/footer.php";?></p>
		</div><!--wrapper 끝-->

</body>
</html>