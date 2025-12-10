<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "신규 신청[배서]";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/DaeriMember.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>

<body id="popUp">
<form>
	
		<div id="newUnjeon">
		     <input type='hidden' id='userId' value='<?=$userId?>' />
			<input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>' />
			<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
			<input type='hidden' id='InsuraneCompany' value='<?=$InsuraneCompany?>' />
			<input type='hidden' id='policyNum' value='<?=$policyNum?>' /> 
			<input type='hidden' id='mNum' value='<?=$mNum?>' /> 

				<table>
					<tr>
						<td colspan='2'>
						  <input type="button" class="btn-b" id='Excelup'  value="ExcelUp"  onFocus='this.blur()'>
						 
						</td>
						<td>
						 <span id='company' />
						 
						</td>
						<td colspan='2'>배서기준일<input type="text" id='endorseDay' name='endorseDay' value="<?=$now_time?>" readonly name="endorseDay"  size='10' maxlength='10' /><img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].endorseDay,'yyyy-mm-dd',this)"  /></td>		   
					</tr>
			         <tr>
						<th width='5%'>순번</th>
						<th width='15%'>성명</th>
						<th width='25%'>주민번호</th>
						<th width='25%'>핸드폰번호</th>	
						<th width='20%'>탁송여부</th>
					 </tr>
					 <?for($i=0;$i<15;$i++){
						 $k=$i%2;
							if($k==0){
								$cl='O';
							}else{

								$cl='P';
							}
											 
						 ?>
					 <tr>	
						<td><?=$i+1?></td>
						<td><input type='text' class='textare<?=$cl?>' id='a2b<?=$i?>'  onBlur="inputNameCheck(this.id,this.value)" / /></td>
						<td><input type='text' class='textare<?=$cl?>' id='a3b<?=$i?>' onBlur="inputJuminCheck(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)" /></td>
						<td><input type='text' class='textare<?=$cl?>' id='a4b<?=$i?>'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
						<td><select id='a5b<?=$i?>' >
								<option value='1'>일반</option>
								<option value='2'>탁송</option>
								<option value='3'>일반/렌트</option>
								<option value='4'>탁송/렌트</option>
								<option value='5'>확대탁송</option>
							</select>
						</td>	
					 </tr>
					<?}?>
				</table>
				<bR>
				<table>
					<tr>
						<td>&nbsp;<span id='a35'  />&nbsp;</span>
						<input type='button' id='a32' class='btn-b' value='저장'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="store(2)"><input type='hidden' id='test' value='' /></td>
					  </tr>
				</table>
     
	</div>


</form>
</body>
</html>