<?include '../../2012c/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script>
	if (location.protocol === 'http:' && location.host.indexOf('cafe24.com') === -1) {
    var sUrl = 'https://' + location.host + location.pathname + location.search;

		 window.location.replace(sUrl);
	}
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "신규 신청[배서]";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	<!-- <script src="../js/pop.js" type="text/javascript"></script>-->
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create2.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/DaeriMember.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>
<form>
<body id="popUp">
<form>
	
		<div id="newUnjeon">
		  <input type='hidden' id='userId' value='<?=$userId?>' />
			<input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>' />
			<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
			<input type='hidden' id='InsuraneCompany' value='<?=$InsuraneCompany?>' />
			<input type='hidden' id='policyNum' value='<?=$policyNum?>' />

				   <?
				   switch($gita){

							case 1 :
								$gitaName='일반';
							break;
							case 2 :
								$gitaName='탁송';
							break;
							case 3 :
								$gitaName='일반/렌트';
							break;
							case 4 :
								$gitaName='탁송/렌트';
							break;
							case 5 :
								$gitaName='전차종탁송';
							break;

				   }
					?>

					<table>

					<tr>
						<td colspan='3'><span id='company' /></td>
						<td colspan='2'>배서기준일<?include '../php/customer_endorse_day_lig.php';?>
		                     <input type='hidden' id='endorseDay' name='endorse_day' value='<?=$now_time?>'></td>
				    </th>
					 </tr>
					
			         <tr>
						<th width='5%'>순번</th>
						<th width='10%'>성명</th>
						<th width='15%'>주민번호</th>
						<th width='12%'>핸드폰번호</th>	
						<th width='10%'>통신사</th>
						<th width='10%'>명의</th>
						<th width='30%'>주소</th>
						<th width='8%'>탁송여부</th>
					 </tr>
					 <?for($i=0;$i<15;$i++){?>
					 <tr>
						
						<td><?=$i+1?></td>
						<td><input type='text' class='textareO' id='a2b<?=$i?>'  onBlur="inputNameCheck(this.id,this.value)" / /></td>
						<td><input type='text' class='textareO' id='a3b<?=$i?>'  onBlur="inputJuminCheck(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)" /></td>
						<td><input type='text' class='textareO' id='a4b<?=$i?>'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>

						<td><select id='a6b<?=$i?>'>
								<option value='-1'>통신사</option>
								<option value='1'>sk</option>
								<option value='2'>kt</option>
								<option value='3'>lg</option>
								<option value='4'>sk알뜰폰</option>
								<option value='5'>kt알뜰폰</option>
								<option value='6'>lg알뜰폰</option>
							</select>
						</td>
						<td><select id='a7b<?=$i?>'>
								<option value='-1'>명의자</option>
								<option value='1'>본인</option>
								<option value='2'>타인</option>
								
							</select>
						</td>
						<td><input type='text' class='textareO' id='a8b<?=$i?>'  /></td>
						<td><input type='hidden' class='textareO' id='a5b<?=$i?>' value='<?=$gita?>' ><?=$gitaName?>   </td>
						
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