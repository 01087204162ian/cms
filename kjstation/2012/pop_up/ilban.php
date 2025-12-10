<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>일반</title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<!--datePicker-->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>

<script src="./js/ilban.js" type="text/javascript"></script>


</head>

<?$redirectURL='DMM_System'?>
<body id="ssangyong">

		<div id="pop_">
		  <form>
 
			  <table>
				 <caption>
				
				 </caption>
				  <bR>
				  
				  <tr >
					<th style='text-align:right'>
						<select id='kind' onChange='control()'>
						   <option value='99'>선택</option>
						   <option value='1'>계약자</option>
						   <option value='2'>증권번호</option>
						   <option value='3'>핸드폰</option>
					    </select>
						<input type='text' class='textare2' id='content'>
						<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="검색"  onFocus='this.blur()'      onClick="cordSerch()">
						<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="신규입력"  onFocus='this.blur()'      onClick="sin_cord()">
						<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="다시읽기"  onFocus='this.blur()'      onClick="cordSerch2()">
					 </th>
				  </tr>
			  </table> 
			  <br>
			  <table>
				 
				  <thead>
				  <tr >
					<td width='3%' rowspan='3'>순번</td>
					<td width='22%' colspan='2'>주민/사업자</td>
					<td width='15%'>
					  <select id='insuranceComNum' onChange='cordSerch()'>
						   <option value='99'>보험사</option>
						   <option value='1'>흥국</option>
						   <option value='2'>동부</option>
						   <option value='3'>KB</option>
						   <option value='4'>현대</option>
						   <option value='5'>한화</option>
						   <option value='6'>더케이</option>
						   <option value='7'>MG</option>
						   <option value='8'>삼성</option>
						   <option value='21'>CHUBB</option>

					 </select>
					
					</td>
					<td width='11%'>

						<select id='product' onChange='cordSerch()'>
						   <option value='99'>상품명</option>
						   <option value='1'>혼유</option>
						   <option value='2'>복합운송</option>
						   <option value='3'>화재</option>
						   <option value='4'>근재</option>
						   <option value='5'>생산물</option>
						   <option value='6'>체육시설</option>
						  <option value='7'>자동차</option>
						</select>
					</td>
					<td width='13%'>증권번호</td>
					<td width='13%'>시기</td>
					<td width='10%'>보험료</td>
					<td width='10%'>코드</td>
					<td width='3%'rowspan='3'>비고</td>
					
				  </tr>
				  <tr >
					<td width='8%'>계약자</td>
					<td width='14%'>핸드폰</td>
					<td>E-mail</td>
					<td>피보험자</td>
					
					<td>차번호</td>
					<td>팩스</td>
					<td>update</td>
					<td >update</td>
				
				  </tr>

				   <tr >
					<td colspan='8'>특이사항</td>
					
				
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