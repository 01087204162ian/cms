<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>대리점 코드 정리</title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="./js/cord.js" type="text/javascript"></script>


</head>

<?$redirectURL='DMM_System'?>
<body id="ssangyong">

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
							<td width='5%' rowspan='2'>순번</td>
							<td width='10%'>
							  <select id='insuranceComNum' onChange='cordSerch()'>
								   <option value='99'>전체</option>
								   <option value='1'>흥국</option>
								   <option value='2'>동부</option>
								   <option value='3'>KB</option>
								   <option value='4'>현대</option>
								   <option value='5'>한화</option>
								   <option value='6'>메리츠</option>
								   <option value='7'>MG</option>
								   <option value='21'>CHUBB</option>
						     </select>
							
							</td>
							<td width='10%'>대리점명</td>
							<td width='10%'>코드</td>
							<td width='10%'>사용인</td>
							<td width='12%'>코드</td>
							<td width='12%'>비밀번호</td>
							<td width='14%'>인증</td>
							<td width='12%'>update</td>
							<td width='5%'rowspan='2'>비고</td>
							
						  </tr>
						  <tr >
							<td >지점장</td>
							<td >여직원</td>
							<td >지점번호</td>
							<td >지점팩스</td>
							<td colspan='4'>특이사항</td>
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