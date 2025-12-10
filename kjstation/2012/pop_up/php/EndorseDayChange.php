
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "배서기준일변경";?></title>
	<link href="../../css/member.css" rel="stylesheet" type="text/css" />
	
	 <script src="../../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../../js/pop.js" type="text/javascript"></script>
	<link href="../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="../js/change.js" type="text/javascript"></script>
</head>
<?$redirectURL='DMM_System'; ?>

<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$num?>' />
	<input type='hidden' id='eNum' value='<?=$eNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='oldendorseDay' value='<?=$endorseDay?>' />
		<div id="newUnjeon">
		  
			   <div id="listPart" >
				   
					<table>
					<tr>
						<td colspan='2'><span id='a1' >&nbsp;</span> 배서기준일 변경 </td>
						

					 </tr>
					<tr>
						<td width='50%'>변경전 </td>
						<td>변경후</td>

					 </tr>
					 <tr>
						<td><span id='oldDay'>&nbsp;</span></td>
						<td><input type="text" id='endorseDay'  readonly name="endorseDay"  size='10' maxlength='10' /><img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].endorseDay,'yyyy-mm-dd',this)"  /></td>
						
					 </tr>
				</table>
				<bR>
				<table>
					<tr>
						<td>&nbsp;<span id='a35'  />&nbsp;</span>
						<input type='button' id='a32' class='btn-b' value='변경'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="change()">
						<!--<input type='text' id='test' value='' />-->
						<input type='button' id='a32' class='btn-b' value='닫기'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="self_close()">
						</td>
					  </tr>
				</table>
		
				
  
				
		    </div><!--main끝-->
			
      
	


</form>
</body>
</html>