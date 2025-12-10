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
	<script src="./js/qboardMembers.js" type="text/javascript"></script><!--ajax-->
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

				   
					<table>

					<tr>
						<td colspan='3'><span id='company' /></td>
						<td colspan='3'>배서기준일<?include '../php/customer_endorse_day_lig.php';?>
						<input type='hidden' id='endorseDay' name='endorse_day' value='<?=$now_time?>'></td>
				
					 </tr>
					
			         <tr>
						<th width='5%'>순번</th>
						<th width='20%'>증권번호</th>
						<th width='20%'>납입방법</th>
						<th width='20%'>보험기간</th>
						<th width='15%'>가입신청</th>
						<th width='20%'>증권성격</th>
						
					 </tr>
					 <?for($i=0;$i<15;$i++){?>
					 <tr>
						
						<td><?=$i+1?></td>
						<td><input type='hidden' class='textareO' id='a1b<?=$i?>'   /><input type='text' class='textareO' id='a2b<?=$i?>'   /></td>
						<td  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onClick='preminum(<?=$i?>)'><span id='a5b<?=$i?>'>&nbsp;</span></td>
						<td><input type='text' class='textareO' id='a3b<?=$i?>' /></td>
						<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''"><span id='a4b<?=$i?>'>&nbsp;</span></td>
						<td><input type='hidden' class='textareO' id='a6b<?=$i?>'   /><input type='text' class='textareO' id='a7b<?=$i?>'   /></td>
						
						
					 </tr>
					<?}?>
					 
					

				</table>
				<bR>
				
		
				
  
				
		   
			
      
	</div>


</form>
</body>
</html>