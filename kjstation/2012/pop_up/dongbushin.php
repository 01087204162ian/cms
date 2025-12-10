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

	<script src="./js/donbushin.js" type="text/javascript"></script>


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
						   <option value='1'>성명</option>
						   <option value='2'>주민번호</option>
						   <option value='3'>핸드폰</option>
						   <option value='4'>설계번호</option>
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
				  <tr>
					<td width='3%' rowspan='2'>순번</td>
					<td width='10%' >성명</td>
					<td width='12%' >주민번호</td>
					<td width='15%'>핸드폰번호</td>
					<td width='10%'>
					   <select id='product' onChange='cordSerch()'>
						   <option value='99'>=진행상황=</option>
						   <option value='1'>신청</option>
						   <option value='2'>심사중</option>
						   <option value='3'>거절</option>
						   <option value='4'>입금대기</option>
						   <option value='5'>계약</option>
						   <option value='6'>생각중</option>
						  <option value='7'>자동차</option>
						</select></td>
					
					<td width='10%'>설계번호</td>
					<td colspan='2'>자동이체계좌</td>
					<td width='10%'>신청일</td>
					<td width='3%' rowspan='2'>비고</td>
				  </tr>
				 

				   <tr >
					<td colspan='8'>주소</td>
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