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
	<script src="../js/createdajonng.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/hiauto.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>

<body id="popUp">
<form>
	<div id="dajoongMain">
				  <input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
				  <input type='hidden' id='num' value='<?=$num?>'>
				  <input type='hidden' id='checkPhone' >
				  <input type='hidden' id='hiautoCarNum' />
	<!--	<table>
		   <tr> 
			 <td width='10%'>대인<span id='a31'  />&nbsp;</span>|| 대물<span id='a33'  />&nbsp;</span><span id='a34'  />&nbsp;</span></td> 

		   </tr>
		   </table>-->
		 <table>
			<caption><span id='B11b'  />&nbsp;</span></caption>
		   <tr> 
				<td width='20%'> 계약자 </td>
				<td width='30%'> <input type='text' id='B1b' class='textareD' /> </td><!--계약자-->
				<td width='20%'>제조사</td>
				<td width='30%'><span  id='h1' class='po2' >&nbsp;</span></td><!--제조사-->
			</tr>

			<tr> 
			<td> 주민번호 </td>
			 <td> <input type='text' id='B2b' class='textareD' onBlur="jumiN_check(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)"/> </td>
			 <td>차종 </td> 
			 <td><span  id='a2' class='po2' >&nbsp;</span></td><!--차종--> 	
				
				 
			</tr>
			<tr> 
				<td> 핸드폰번호 </td>
				 <td> <input type='text' id='B3b' class='textareD' onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"> </td><!--핸드폰번호-->
				
				 <td>차명 </td> 
				<td ><span  id='ak3' class='po2' >&nbsp;</span></td><!--차명--> 	
			</tr>
			<tr> 
				<td> e-mail </td> 
				 <td> <input type='text' id='B5b' class='textareD' /></td><!--e-mail-->
				 <td >타이어교체비용</td>
				 <td ><span  id='a4' class='po2' >&nbsp;</span></td><!--타이어교체비용-->

			</tr>
			<tr> 
			    <td> 시기</td>
				 <td> <input type='text' id='B4b' class='textare3'  /> <img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].B4b,'yyyy-mm-dd',this)"  /> </td><!--시기-->
				
				 <td>타이어 보상한도</td> 
				 <td><span  id='ak4' class='po2' >&nbsp;</span></td><!--타이어 보상한도--> 
				
			</tr>
			<tr> 
				 <td>차량번호</td> 
				 <td><input type='text' class='po29' id='a8b' /></td><!--차량번호-->
				 <td>가입기간</td> 
				 <td><span  id='a5' class='po2' >&nbsp;</span></td><!--가입기간--> 
				
			</tr>
			<tr> 
				 <td>차대번호</td> 
				  <td><input type='text' class='po29' id='a9b' /></td><!--차대번호-->
				 <td>분납</td> 
				 <td><span  id='a6' class='po2' >&nbsp;</span></td><!--일시납,분납--> 
				
			</tr>
			<tr> 
				 <td>최초등록일</td> 
				 <td><input type='text' class='po2' id='a6b'  onBlur="geRi()" onFocus="peRi()";/></td><!--최초등록일-->
				 <td>가입시 주행거리</td> 
				 <td><input type='text' class='po2' id='a10b' /></td><!--가입시 주행거리-->
				
			</tr>

			<tr> 

				 <td>보험료 </td>
				 <td ><span  id='a7' class='po2' >&nbsp;</span></td><!--보험료-->
				 <td colspan='2' ><span  id='ak5' class='po2' >&nbsp;</span></td><!--일련번ㄴ호-->
			</tr>
			<tr> 
				 <td> 주소</td>	
				 <td colspan='3'><input type='text' class='po29' id='a29' /><!--일련번ㄴ호--></td>
				
			</tr>

			
			<tr> 
			<td rowspan='3' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick='findAddr()'>주소</td>
			<td colspan='3'>우편물발행<input type='checkbox' id='post_print'  onfocus='this.blur()' onclick="post_p()" class='po1'><input type='text' class='po1' id='a28' />
				 </td>
			</tr>
			<tr  bgcolor="#FFFFFF">
			  <td colspan='3' ><input type='text' class='po2' id='a30' /></td>
			</tr>

			<tr>
			
			<td colspan='3'><span id='a55'  />&nbsp;</span><span id='a56'  />&nbsp;</span></td>
		
			</tr>   
			 
		   <tr>

		     <td colspan='4'> <input type='button' id='a32' class='btn-b' value='저장'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="store()"> 
		   </tr>
		 </table>

		   <br>
		  <table>
		   <tr>
		    <td><input type='button' id='sms' class='btn-b' value='smsList' /></td>
			<td><input type='button' id='smsTotal2' class='btn-b' value='smsList[전체]' /></td>
			<td><input type='button' id='application' class='btn-b' value='청약서 발행' /></td>
			<td><input type='button' id='policy' class='btn-b' value='증권 발행' /></td>
		  </tr>
		  <tr>
			<td width='20%'> 계약일 확정<input type='checkbox' id='inputDay'  onfocus='this.blur()' onclick="inputD()" class='po1'></td>
			<td  width='20%'><input type='text' id='D1d'  class='dayD'  /> <img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].D1d,'yyyy-mm-dd',this)"  /></td>
			<td width='20%'><span id='jinhang'>&nbsp;</span></td>
			<td></td>
		  </tr>
		  </table>
			<div id='first'><? include "./php/smsList.php";?></div>
		  <br>
		  <table>
		   <tr> 
			 <td width='25%'>보험회사 </td>
			 <td width='25%' colspan='2'>설계번호</td>
			 <td width='25%' >은행</td>
			 <td width='25%'>보험료</td>
		  </tr>
		 
		   <tr> 
			 <td><span id='D1b'>&nbsp;</span> </td>
			 <td><span id='E2b'>&nbsp;</span> </td><!--설계번호-->
			 <td><span id='D2b'>&nbsp;</span> </td>
			  <td><span id='D4b'>&nbsp;</span> </td> <!--은행명-->
			  <td><span id='D3b'>&nbsp;</span></td><!--보험료-->
			</tr>
			 <tr>
			 <td width='20%'>가상계좌 </td>
			 <td width='20%' colspan='2'>증권번호 </td> 
			 <td width='10%'>회신팩스 </td> 
			 <td></td>

		   </tr>
			<tr>
			 <td><span id='D5b'>&nbsp;</span> </td><!--가상계좌-->
			<td><span id='E6b'>&nbsp;</span> </td><!--증권번호-->
			 <td><span id='D6b'>&nbsp;</span> </td>
			 <td><span id='D7b'>&nbsp;</span> </td><!--처리-->
			 <td></td>
		   </tr>


		   </table>


		   <Br>
			
	</div>
</form>
</body>
</html>