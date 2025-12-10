<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>MG 증권번호입력 </title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="/sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="/sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	
	<script language=javascript src='./js/pMg.js'></script>
</head>

<?$redirectURL='DMM_System'?>

<body id="idMake">


<div id='idMake'>

<form name="form1" method="post" >
	
		
		<input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>' />
				<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
				<input type='hidden' id='InsuraneCompany' value='<?=$InsuraneCompany?>' />
	 
		<table>
<tr>
   <td colspan='3'><span id='A2a'  />&nbsp;</span></td>

   <td >[<span id='A3a'  />&nbsp;</span>] 
   
	<? if($InsuraneCompany==7){?><input type='button' id='a32' class='btn-b' value='MG증권입력 ' > <?}?>
   </td>
  </tr>
  <tr>
   <td rowspan='2'>순서</td>
   <td colspan='2'>년령</td>
   <td rowspan='2'>증권번호</td>
   
  </tr>
 <tr>
   <td>시작</td>
   <td>종료</td>
   
  </tr>
  <? for($_m=0;$_m<8;$_m++){
	 $k=$_m%2;
		if($k==0){
			$cl='P';
		}else{

			$cl='O';
		}?>
   <tr>
	 <td><?=$_m+1?>
	     <input type='hidden'  id='B4b<?=$_m?>' />
	 <td><span id='B0b<?=$_m?>'  />&nbsp;</span></td><!--나이시작-->
	 <td><span id='B1b<?=$_m?>'  />&nbsp;</span></td><!--나이끝-->
	 <td><input type='text' class='textare<?=$cl?>' id='B2b<?=$_m?>' onBlur="certiCheck(this.id,this.value,<?=$_m?>,'1')" ></td><!--년간-->
	 
	 

   </tr>

   <?}?>
 </table>
	

	
	 
	

	</form>
</div>
<form>
</body>
</html>