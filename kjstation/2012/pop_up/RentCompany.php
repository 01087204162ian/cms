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
	<script src="./js/NewBasicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/poprent.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>
<form>
<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$num?>' />

		<div id="SjPopUpMain">
		  
			   <div id="listPartCompany" >
					  <input type='hidden' id='page' />
					<table>
			         <tr>
						<th width='15%' colspan='2'>주민번호</th>
						<th width='15%' colspan='2'>대리운전회사</th>
						<th width='10%'>성명</th>
						
						<th width='15%'>핸드폰번호</th>
						<th width='15%'>전화번호</th>
						<th width='10%' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="persolnal()">담당자</th>
						<th width='10%' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onClick="divid()">업체I.D</th>					
					 </tr>
					 <tr>
						<td width='10%' colspan='2'><input type='text' class='textareP' id='a3'  onBlur="jumiN_check(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)" /></td>
						<td width='10%' colspan='2'><input type='text' class='textareP' id='a1'  /></td>
						<td width='10%'><input type='text' class='textareP' id='a2'  /></td>
						
						<td width='10%'><input type='text' class='textareP' id='a4'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
						<td width='10%'><input type='text' class='textareP' id='a5'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
						<td width='10%'><input type='text' class='textareP' id='a6'  /></td>
						<td width='10%'><input type='text' class='textareP' id='a7'  /></td>
					 </tr>
					 <tr>
						<th width='10%' colspan='2'>팩스</th>
						<th width='10%' colspan='2'>사업자번호</th>
						<th width='10%' colspan='2'>법인번호</th>
						<th width='10%'>보험료 받는날</th>
						<th width='10%'></th>
						<th width='10%'></th>
										
					 </tr>
					 <tr>
						
						<td width='10%' colspan='2'><input type='text' class='textareP' id='a8'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
						<td width='10%' colspan='2'><input type='text' class='textareP' id='a9'  /></td>
						<td width='10%' colspan='2'><input type='text' class='textareP' id='a10'  /></td>
						<td width='10%'><input type='text' class='textareP' id='a11'  onBlur="DataCheckS(this.id,this.value)" onClick="DataCheck2(this.id,this.value)" /></td>
						<td width='10%'><input type='text' class='textareP' id='a12'  /></td>
						<td width='10%'><input type='text' class='textareP' id='a13'  /></td>
						
					 </tr>
					<tr>
						<th width='8%' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick='findAddr()'>주소</th>
						<td width='12%'>우편물발행<input type='checkbox' id='post_print'  onfocus='this.blur()' onclick="post_p()"></td>
						<td width='10%' colspan='4'><input type='text' class='textare2' id='a28' /><input type='text' class='textare3' id='a29' /></td>
						<td width='10%' colspan='3'><input type='text' class='textareP' id='a30' /></td>
						
					</tr>
					<tr>
						<td colspan='9'>&nbsp;<span id='a35'  />&nbsp;</span>
						<input type='button' id='a32' class='btn-b' value='저장'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="store()">
						
						<input type='button' id='sms' class='btn-b' value='smsList' />
						<input type='button' id='smsTotal' class='btn-b' value='smsList[전체]' />
						<input type='button' id='a55' class='btn-b' value='전체리스트'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="daeriSerchAll()">
						<input type='button' id='Memo' class='btn-b' value='Memo' />
						</td>
					  </tr>
				</table>
			</div>
			 
			<div id='first'><? include "./php/smsList.php";?></div>
			<div id='memo'><? include "./php/memoList.php";?></div>
    
			<? include "./php/certiTable.php";?>

					<div id="paging"><span id='sql2'>&nbsp;</span><span id='sql3'>&nbsp;</span></div>
					
				
  
				
		    </div><!--main끝-->
			
      
	


</form>
</body>
</html>