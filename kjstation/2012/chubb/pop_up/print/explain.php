<?include '../../../../dbcon.php';

include './explain_query.php';//조회할때
?>

<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="../../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<script src="../../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<link rel="stylesheet" href="/kibs_admin/css/Style.css" type="text/css">
<script language=javascript src='/kibs_admin/js/list_2.js'></script>
<script language=javascript src='/kibs_admin/js/create.js'></script>
<script language=javascript src='../../js/explaini.js'></script>
<script>









</script>

<?if(!$sigi){
	$sigi	 = date("Y-m-d ",strtotime("$now_time -10 day"));
}
$to_value=$sigi;
	?>

<title><?if($num){ echo "$com_name 상품설명서"; } else{ echo "신규 상담";}?></title>
<form name="form1" method="post" >
   <table width="95%" border="0" cellpadding="3" cellspacing="1" bgcolor="#cccccc" align='center'>
     <input type='hidden' id='num' name='num' value='<?=$num?>'><!--kibs_list의 num 값이고-->
     <input type='hidden' id='cal_num' name='cal_num' value='<?=$cal_num?>'><!--kibs_list_explain의 num값  -->
	 <tr bgcolor="#ffffff"   >
	   <td  bgcolor="#DFEEF8"> 신청일 </td>
	   <td><input type="text" value="<?=$now_time?>" readonly name="endorse_day" id="endorse_day" size='10' maxlength='10' />
		  <img class="calendar" src="../../../../sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].endorse_day,'yyyy-mm-dd',this)"  />
		</td>  
	 </tr>
     <tr bgcolor="#ffffff"   >
	   <td rowspan='2' align="center" bgcolor="#DFEEF8">회사명 </td>
	    <td>(한글)<?=$com_name?></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td>(영문)<?=$com_eur_name?> </td>
	 </tr>
	<tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">년간 예상 가입금액</td>
	   <td><input type='text' id='policy' name='policy' size='8' maxlength='8' value='<?=$policy?>' class='input' >
	  <!-- <input type='text' id='policy' name='policy' size='8' maxlength='8' value='<?=$policy?>' class='input' onkeyup="policy_check()">--></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">보험료(율) </td>
	   <td><input type='text' id='invoice' name='invoice' size='60' maxlength='60' class='input' value='<?=$invoice?>' ></td>
	 </tr>

	  <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">FROM </td>
	   <td><input type='text'  id='from' name='from' size='60' maxlength='60' class='input' value='<?=$from?>' ></td>
	 </tr>
	  <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">TO </td>
	   <td> <input type='text' id='destnation' name='destnation' size='60' maxlength='60' class='input' value='<?=$destnation?>' >  </td>
	 </tr>
	
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8" >첨 부 서 류 </td>
	   <td> 가입자 보관용(1~3page) 보험회사 보관용(4~6page) 발행 됩니다
			
	   </td>
	 </tr>
   </table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center"> 
			  <?if($cal_num){ ?>
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="수정"  onFocus='this.blur()'      onClick="cancel_store()">
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="프린트"  onFocus='this.blur()'      onClick="cancel_print(<?=$cal_num?>)">
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="닫기"  onFocus='this.blur()'      onClick="self_close()">
          <? } else {?>
			 <input type="button" id='stbutton' class="btn-b" style="cursor:hand;width:90;"  value="저장"  onFocus='this.blur()'      onClick="cancel_store()">
			 <span id='print2'></span>
			<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="닫기"  onFocus='this.blur()'      onClick="self_close()">
          <? }?>
        </td>
       </tr>
     </table>
</form>

<?// echo "user $useruid <br>"; ?>
