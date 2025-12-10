<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	
	<script src="./js/vank.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>
<form>
<body id="popUp">
<form>
	<div id="vankMain">
				  <input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
				  <input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>'>
				  <input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>'>
		 <table>
		   <tr> 
			 <td width='35%' colspan='2'> 대리운전회사 </td>
			 <td width='10%'> 계약자 </td>
			 <td width='15%'> 주민번호 </td>
			 <td width='20%'> 보험사 </td>
			 <td width='20%'> 증권번호 </td>
		   </tr>
		   <tr> 
			 <td colspan='2'> <span id='B1b' class='textareP' /> </td><!--대리운전회사-->
			 <td> <input type='text' id='B2b' class='textareP' /> </td><!--계약자-->
			 <td> <input type='text' id='B3b' class='textareP' onBlur="jumiN_check(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)"/> </td><!--주민번호-->
			 <td> <input type='hidden' id='B0b' class='textareP' /><input type='text' id='B4b' class='textareP' /> </td><!--보험회사-->
			 <td> <span id='B5b' class='textareP' /> </td><!--증권번호-->
		   </tr>
		   <tr> 
			 <td> 보험시기 </td>
			 <td> 보험종기 </td>
			 <td> 은행 </td>
			 <td> 가상계좌 </td>
			 <td rowspan='2' colspan='2'> <span id='sjButton' class='textareP' >
			 <!--<input type='button' id='sjButton' class='btn-b' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="vStore()"> --></td>		 
		   </tr>
		   <tr> 
			 <td><input type="text" id='C1b'  readonly name="C1b"  size='10' maxlength='10' class='textareD' / ><!--보험시기-->
		<!--	 <img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].C1b,'yyyy-mm-dd',this)"  /> --></td>
			 <td><input type="text" id='C2b'  readonly name="C2b"  class='textareD' / > </td><!--보험종기-->
			 <td> <input type='text' id='C3b' class='textareP' /> </td><!--은행-->
			 <td> <input type='text' id='C4b' class='textareP' /> </td> <!--가상계좌-->
		   </tr>
		  </table>
		  <br>
		  <table>
		   <tr> 
			 <td width='20%'> 카드1 </td>
			 <td width='20%'> 카드2 </td>
			 <td width='20%'> 카드3 </td>
			 <td width='20%'>환급계좌 </td> 
		   </tr>
		   <tr> 
			 <td><input type="text" id='D1b' class='textareP' /> </td>
			 <td><input type="text" id='D2b' class='textareP' / > </td>
			 <td><input type='text' id='D3b' class='textareP' /> </td>
			 <td><input type='text' id='D4b' class='textareP' /> </td> 
		   </tr>

		   <tr>
		    <td colspan='2'>대리운전회사에서 운전자 추가 할 수 있는가</td>
			<td colspan='2'>
			     <select id='control' onchange='controloun()'>
					<option value='1'>있다</option>
				    <option value='2'>없다</option>

				 </select>
			
			   </td>
		   </table>
	</div>
</form>
</body>
</html>