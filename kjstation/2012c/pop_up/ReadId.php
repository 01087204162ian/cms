<?include '../../2012c/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>읽기전용I.D만들기></title>
	<link href="/2012/css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="/2012/pop_up/js/readid.js" type="text/javascript"></script>
  

</head>

<?$redirectURL='DMM_System'?>
<body id="ssangyong">
		<input type='hidden' id="daeriCompanyNum" value='<?=$num?>'>
		<div id="pop_">
		  <form>
			   			 
					  <table>
						 <caption>
						
						 </caption>
						  <bR>
						  <thead>
						  <tr >
							<td>
								<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="신규코드"  onFocus='this.blur()'      onClick="sin_cord()">
						        <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="다시읽기"  onFocus='this.blur()'      onClick="cordSerch()">
							 </td>
						  </tr>
					  </table> 
					  <br>
					  <table>
						 
						  <thead>
						  <tr >
							<td width='5%' >순번</td>
							<td width='10%'>담당자</td>
							<td width='10%'>id</td>
							<td width='10%'>연락처</td>
							<td width='10%'>비밀번호</td>
							<td width='12%'>허용</td>
							<td width='5%'>비고</td>
							
						  </tr>
						  
						</thead>
						<tbody class="scrollingContent" id="sjo">
							
					  </tbody>
					</table>
					</form>
				</div>
				
  
				
		  
	
  <p id="copyright2"><? include "../php/footer.php";?></p>
</div><!--wrapper 끝-->

</body>
</html>