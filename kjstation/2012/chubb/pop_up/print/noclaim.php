
<?include '../../../../dbcon.php';;

if($cal_num){include './noclaim_query.php';}//조회할때
?>

<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<link rel="stylesheet" href="/kibs_admin/css/Style.css" type="text/css">
<script language=javascript src='/kibs_admin/js/list_2.js'></script>

<script>
function policy_check(){

	var fobj=document.form1;
	if(fobj.policy.value.length=='8'){
		
		 if(!IsNumber(fobj.policy.name)){
		   alert("증권번호는 숫자이어야 합니다!");
		   fobj.policy.value='';
		   fobj.policy.focus();

		   return;
		 }else{
			 fobj.invoice.focus();

		 }
	 }
}


function cancel_store(){

	var fobj=document.form1;
		 if(fobj.policy.value.length<8 && fobj.policy.value.length>=0){

			alert('취소.변경 증권번호는 8자리 입니다')
				fobj.policy.focus()
				return false;
		}
		 if(!fobj.invoice.value){

			alert('invoice가 없습니다!')
				fobj.invoice.focus()
				return false;
		}

		if(!fobj.vessel.value){

			alert('vessel이 없습니다!')
				fobj.vessel.focus()
				return false;
		}
		if(fobj.start.value<fobj.sigi.value){
				alert('신청일보다 무사고 기준일이 더 빠를 수는 없습니다!')
				fobj.sigi.focus()
				return false;
		}

			fobj.target = '_self'
			fobj.action="./noclaim_store.php";
			fobj.submit();

	
}

function cancel_update(val){

	var fobj=document.form1;
		 if(fobj.policy.value.length<8 && fobj.policy.value.length>=0){

			alert('취소.변경 증권번호는 8자리 입니다')
				fobj.policy.focus()
				return false;
		}
		 if(!fobj.invoice.value){

			alert('invoice가 없습니다!')
				fobj.invoice.focus()
				return false;
		}

		if(!fobj.vessel.value){

			alert('vessel이 없습니다!')
				fobj.vessel.focus()
				return false;
		}
		if(fobj.start.value<fobj.sigi.value){
				alert('신청일보다 무사고 기준일이 더 빠를 수는 없습니다!')
				fobj.sigi.focus()
				return false;
		}

			fobj.target = '_self'
			fobj.action="./noclaim_update.php?num="+val;
			fobj.submit();

	
}
function IsNumber(formname) {
   var form=eval("document.form1." + formname);
   for(var i=0; i < form.value.length; i++) {
     var chr = form.value.substr(i,1);
	 if((chr < '0' || chr > '9')) {
       return false;
	 }
    }
  return true;
}

function cancel_print(val){
	 var fobj=document.form1;
	if(confirm('취소 요청서 프린트 합니다!')){
		fobj.target = '_self'
		fobj.action='./noclaim_print.php?num='+val;
		fobj.submit();
	}

}
</script>

<?if(!$sigi){
	$sigi	 = date("Y-m-d ",strtotime("$now_time -10 day"));
}
$to_value=$sigi;
	?>

<title><?if($num){ echo "$com_name 무사고 확인서"; } else{ echo "신규 상담";}?></title>
<form name="form1" method="post" >
   <table width="95%" border="0" cellpadding="3" cellspacing="1" bgcolor="#cccccc" align='center'>
     <input type='hidden' name='num' value='<?=$num?>'><!--kibs_list의 num 값이고-->
     <input type='hidden' name='cal_num' value='<?=$cal_num?>'><!--kibs_list_cancel의 num값  -->
	 <tr bgcolor="#ffffff"   >
	   <td colspan='' bgcolor="#DFEEF8"> 신청일 </td>
	   <td><input type='text' name='start' value='<?=$now_time?>' class='input' size='10' <?if($cal_num){echo "onBlur='start_check()' onClick='new_start_check_2()'";}else{ echo "readonly";}?>></td>
	   
	 </tr>
     <tr bgcolor="#ffffff"   >
	   <td rowspan='2' align="center" bgcolor="#DFEEF8">회사명 </td>
	    <td>(한글)<?=$com_name?></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td>(영문)<?=$com_eur_name?> </td>
	 </tr>
	<tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">POLICY NO </td>
	   <td><input type='text' name='policy' size='8' maxlength='8' value='<?=$policy?>' class='input' onkeyup="policy_check()"></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">INVOICE NO </td>
	   <td><input type='text' name='invoice' size='60' maxlength='60' class='input' value='<?=$invoice?>' ></td>
	 </tr>

	  <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">VESSEL </td>
	   <td><input type='text' name='vessel' size='60' maxlength='60' class='input' value='<?=$vessel?>' ></td>
	 </tr>
	  <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">SAILING DATE </td>
	   <td> <?	if($sailing){
					$now_time=$sailing;
					$dates=date(t);
				}else{$now_time = date("Y-m-d");
				$dates=date(t);
				//echo "deates $dates <Br>";
				}
				list($year,$month,$day)=explode("-",$now_time);
				?>					
            
              <select name="year" size="1"  class="input">
                
                <?for($i=$year;$i<$year+1;$i++){?>
                <option value="<?=$i?>" <?if($i==$year){echo "selected";}else{echo "";}?> > <?=$i?></option>
                <? } ?>
              </select>
              <select name="month" class="input">
               
                <?for($j=1;$j<13;$j++){?>
                <option value="<?=$j?>" <?if($j==$month){echo "selected";}else{echo "";}?>> <?=$j?></option>
                <? } ?>
              </select>
              <select name="day" id="select3" class="input">
               
                <?for($k=1;$k<$dates+1;$k++){?>
                <option value="<?=$k?>"  <?if($k==$day){echo "selected";}else{echo "";}?>> <?=$k?></option>
                <? } ?>
              </select></td>
	 </tr>
	
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8" >첨 부 서 류 </td>
	   <td>상기의 물건은  까지<input type='text' name='sigi' value='<?=$sigi?>' class='input' size='10' onBlur='from_check()' onClick='new_from_check_2()'> 무사고임을  확인하면<br>
	   본 보험증권은 <input type='text' name='to_value' value='<?=$to_value?>' class='input' size='10' readonly>       부터 효력이 발생함믈 확인합니다</td>
	 </tr>
   </table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center"> 
			  <?if($cal_num){ ?>
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="수정"  onFocus='this.blur()'      onClick="cancel_update(<?=$cal_num?>)">
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="프린트"  onFocus='this.blur()'      onClick="cancel_print(<?=$cal_num?>)">
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="닫기"  onFocus='this.blur()'      onClick="self_close()">
          <? } else {?>
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="저장"  onFocus='this.blur()'      onClick="cancel_store()">
			<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="닫기"  onFocus='this.blur()'      onClick="self_close()">
          <? }?>
        </td>
       </tr>
     </table>
</form>

<?// echo "user $useruid <br>"; ?>
