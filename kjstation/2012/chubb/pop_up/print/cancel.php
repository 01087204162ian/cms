<?include '../../../../dbcon.php';

if($cal_num){include './cancel_query.php';}//조회할때
?>

<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<link rel="stylesheet" href="/kibs_admin/css/Style.css" type="text/css">
<script language=javascript src='/kibs_admin/js/list_2.js'></script>

<script>
function cancel_check(){

	var fobj=document.form1;
	if(fobj.cancel_certi.value.length=='8'){
		
		 if(!IsNumber(fobj.cancel_certi.name)){
		   alert("취소.변경 증권번호는 숫자이어야 합니다!");
		   fobj.cancel_certi.value='';
		   fobj.cancel_certi.focus();

		   return;
		 }else{
			 fobj.new_certi.focus();

		 }
	 }
}

function new_certi_check(){

	var fobj=document.form1;
	if(fobj.new_certi.value.length=='8'){
		
		 if(!IsNumber(fobj.new_certi.name)){
		   alert("취소.변경 증권번호는 숫자이어야 합니다!");
		   fobj.new_certi.value='';
		   fobj.new_certi.focus();
		   return;
		 }else{
			 fobj.reason.focus();
		 }
	 }
}
function cancel_store(){

	var fobj=document.form1;
		 if(fobj.cancel_certi.value.length<8 && fobj.cancel_certi.value.length>=0){

			alert('취소.변경 증권번호는 8자리 입니다')
				fobj.cancel_certi.focus()
				return false;
		}
		 if(fobj.new_certi.value.length<8 && fobj.new_certi.value.length>=0){

			alert('재발행 증권번호는 8자리 입니다')
				fobj.new_certi.focus()
				return false;
		}

			fobj.target = '_self'
			fobj.action="./cancel_store.php";
			fobj.submit();

	
}

function cancel_update(){

	var fobj=document.form1;
		 if(fobj.cancel_certi.value.length<8 && fobj.cancel_certi.value.length>=0){

			alert('취소.변경 증권번호는 8자리 입니다')
				fobj.cancel_certi.focus()
				return false;
		}
		 if(fobj.new_certi.value.length<8 && fobj.new_certi.value.length>=0){

			alert('재발행 증권번호는 8자리 입니다')
				fobj.new_certi.focus()
				return false;
		}

			fobj.target = '_self'
			fobj.action="./cancel_update.php";
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
		fobj.action='./cancel_print.php?num='+val;
		fobj.submit();
	}

}
</script>
<title><?if($num){ echo "$com_name 취소요청서"; } else{ echo "신규 상담";}?></title>
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
	   <td align="center" bgcolor="#DFEEF8">취소/변경번호 </td>
	   <td><input type='text' name='cancel_certi' size='8' maxlength='8' value='<?=$cancel_certi?>' class='input' onkeyup="cancel_check()"></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">재발행 증권번호 </td>
	   <td><input type='text' name='new_certi' size='8' maxlength='8' class='input' value='<?=$new_certi?>' onkeyup='new_certi_check()'></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8">취소 변경 사유 </td>
	   <td><textarea name="reason"  cols="86" rows="5" class='text_input'><?=$reason?></textarea></td>
	 </tr>
	 <tr bgcolor="#ffffff">
	   <td align="center" bgcolor="#DFEEF8" >첨 부 서 류 </td>
	   <td> 신용장 사본 또는 관련은행 서류 </td>
	 </tr>
   </table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center"> 
			  <?if($cal_num){ ?>
			 <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="수정"  onFocus='this.blur()'      onClick="cancel_update()">
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
