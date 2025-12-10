<?include '../../2012c/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../../2012/css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../../2012/css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../../2012/pop_up/js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/EndorseAjax.js" type="text/javascript"></script><!--ajax-->
</head>

<?include "./php/endorseCount.php";?>
<?$redirectURL='DMM_System'?>

<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$daeriComNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='eNum' value='<?=$eNum?>' />
	<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
		<div id="SjPopUpMain">
		  
			  <div id="listPart" ><? include "./php/daeriTable.php";?>	</div>
			  <div id='sidebar'><? include "./php/smsState.php";?></div>


			 <div id='memoList'>
				<dl id='memo'>
					<dt id='p1'>
							배서기준일&nbsp;<span id='endorseDay' />&nbsp;</span>
							&nbsp;<input type='button' id='a32' class='btn-b' value='배서기준일변경'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="changeEndorseDay()">
							&nbsp;<input type='button' id='a32' class='btn-b' value='일일보험료'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="dailyPreminum()">
							&nbsp;<input type='button' id='a32' class='btn-b' value='배서프린트'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="EndorsePrint()">
							<?if($Dnum){?>
							&nbsp;<input type='button' id='a32' class='btn-b' value='상품설명서'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="SanPrint()">
							<?}?>	
				   </dt>
					<dd>
					<div id='endorseList'><? include "./php/endorseList.php";?></div>
					
					</dd>
					<dt id='p2'>문자리스트</dt>
					<dd><? include "./php/smsList.php";?></dd>
					<dt id='p3'>문자리스</dt>
					<dd></dd>
				</dl>

			</div>	
		  
				
		</div>
			
			


</form>
</body>
</html>