<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "년령별구성";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/MemberList.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>
<form>
<body id="popUp">
<form>
	
	<input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='InsuraneCompany' value='<?=$InsuraneCompany?>' />
	<input type='hidden' id='policyNum' value='<?=$policyNum?>' />
		<div id="newUnjeon">
		  
					  
				<table>

				<tr>
					<th colspan='3'><span id='company' /></th>
					<th><span id='pCerti'>&nbsp;</span><!--증권발급버튼--></th>
				 </tr>
				 <tr>
					<th width='5%'>순번</th>
					<th width='20%'>년령</th>
					<th width='30%'>인원</th>
					<th width='20%'>비율</th>	
				<!--	<th width='20%'>탁송여부</th>	-->
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
					
					<td><span id='A1<?=$i?>' >&nbsp;</span></td>
					<td><span id='A2<?=$i?>' >&nbsp;</span></td>
					<td><span id='A3<?=$i?>' >&nbsp;</span></td>
					<td><span id='A4<?=$i?>' >&nbsp;</span></td>
				<!--	<td><select id='a5b<?=$i?>' >
							<option value='1'>일반</option>
							<option value='2'>탁송</option>
						</select>
					</td>-->
					
				 </tr>
				<?}?>
				 
				

			</table>
				
		
				
  
				
		    </div><!--main끝-->
			
      
	


</form>
</body>
</html>