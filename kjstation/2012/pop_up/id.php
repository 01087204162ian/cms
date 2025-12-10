<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <title>I.D 관리</title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/id.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>





<body id="idMake">


<div id='idMake'>

<form name="form1" method="post" >
	
		
		<input id="num" type="hidden" value='<?=$num?>'><!--DaeriCompanyNum-->
	 
		<table>
			<tr>
				<th width='20%'>업체명</th><!--2012CostomerNum-->
				<td width='40%'><input type='hidden' id='a0' class='textareO' /><input type='text' id='a1' class='textareO' /></td>
				<td width='40%'>&nbsp;</td>
			</tr>
			<tr>
				<th>I.D</th>
				<td><input type='text' id='a2' class='textareP' onblur='idcheck()'/></td>
				<td><span id='idcheck'></span>
				<input id="check" type="hidden" ></td><!-- check 값이 1이면 사용불가 2이면 가능-->
			</tr>
			<tr>
				<th>핸드폰번호</th>
				<td><input type='text' id='a3' class='textareO' readonly/></td>
				<td></td>
			</tr>
			<tr>
				<th>비빌번호</th>
				<td><input type='text' id='a4' class='textareP' /></td>
				<td></td>
			</tr>
			<tr>
				<th>허용여부</th>
				<td><span id='a5' class='textareO' />&nbsp</span></td>
				<td></td>
			</tr>

			
			
		</tr>
	</table>
	

	
	 
	

	</form>
</div>
</body>
</html>
