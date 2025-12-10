<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../js/jquery-1.7.1.min.js" type="text/javascript" ></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/boardAjax.js" type="text/javascript"></script><!--ajax-->
	<script src="/2012c/js/common_tong.js" type="text/javascript"></script><!--인증관련-->
</head>

<body id="ssangyong">
<div id="wrapper">
	
	<input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>'>
	<div id="contentWrapper"> 
		<div id="daeriList">

		  <span>킥보드보험</span>
		  <table>

				 <tr>
					<th width='5%'>순번</th>
					<th width='9%'>보험사</th>
					<th width='10%'>시작일</th>
					<th width='12%'>증권번호</th>
					<th width='5%'>분납</th>
					<th width='8%'>저장</th>
					<th width='5%'>회차</th>
					<th width='6%'>상태</th>
					<th width='5%'>인원</th>
					<th width='7%'>신규<br>입력</th>
					<th width='7%'>운전자<Br>추가</th>
					<th width='7%'>결제<Br>방식</th>
					<th width='7%'>월보험료</th>
					<th width='7%'>성격</th>

				 </tr>
				 <?for($_m=0;$_m<10;$_m++){
					 $k=$_m%2;
					if($k==0){
						$cl='P';
					}else{

						$cl='O';
					}
					 ?>
				  <tr>
					
					<td onclick='daeriSerch(<?=$_m?>)'><input type='hidden' id='B0b<?=$_m?>' /><span id='B1b<?=$_m?>'  />&nbsp;</span></td><!--번호-->
					<td><span id='B2b<?=$_m?>'  />&nbsp;</span></td><!--보험사-->
					<td><input type='text' class='textare<?=$cl?>' id='B3b<?=$_m?>' onBlur="DataCheck(this.id,this.value)" onClick="DataCheck2(this.id,this.value)"/> </td><!--시작일-->
					<td><input type='text' class='textare<?=$cl?>' id='B4b<?=$_m?>'  onClick='certi(<?=$_m?>,this.value)' onBlur='certiInput(<?=$_m?>,this.value)'/><!--증권번호-->
					<!--<input type='text' class='textare<?=$cl?>' id='B4b<?=$_m?>'  onBlur="CertiCheck(this.id,this.value)" onClick="CertiCheck2(this.id,this.value)" />-->
					</td>
					<td><input type='text' class='textare<?=$cl?>' id='B5b<?=$_m?>' /></td><!--분납 -->
					<td><span id='B9b<?=$_m?>' class='textare<?=$cl?>' />&nbsp;</span> <!--증권번호 저장--></td>
					<td><span id='B6b<?=$_m?>'  />&nbsp;</span></td><!--납입회차-->
					<td><input type='text' class='textare<?=$cl?>' id='B7b<?=$_m?>' /></td><!--상태-->
					<td><input type='text' class='textare<?=$cl?>' id='B8b<?=$_m?>' /></td><!--인원-->
					<td><span id='B10b<?=$_m?>'/>&nbsp;</span> <!--신규 입력--></td>
					<td><span id='B11b<?=$_m?>' />&nbsp;</span><!--운전자추가--></td>
					<td><span id='B12b<?=$_m?>'/>&nbsp;</span><!--결제방식--></td>
					<td><span id='B13b<?=$_m?>' />&nbsp;</span><!--보험료-->
					<input type='hidden' id='B14b<?=$_m?>' /></td>
					<td><span id='B15b<?=$_m?>' />&nbsp;</span><!--성격탁송,기타--></td>
				 </tr>

				 <?}?>
				<tr>
					
					<td colspan='8'> 계</td>
					<td><span id='tot' /></span></td>
					<td colspan='5'>&nbsp;</td>
				 </tr>

	</table>
			
				
  
				
		    </div><!--main끝-->
			
       </div><!--contentWrapper 끝-->
	
  <p id="copyright"><? include "../php/footer.php";?></p>
</div><!--wrapper 끝-->

</body>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="./js/haeji.js"></script>
</html>