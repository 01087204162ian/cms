<?include '../../../2012/lib/popup_lib_auth.php';

$ab=2;//num_query 하단에서 찾기(우편물 내용 찾기 위해)new_cs_dongbu
//echo " $sangtae <br>";
if($seong=='1'){include "./drive_update.php";}//수정하는경우에 만

if(!$num && $new!=1 && $seong!=1){
	include "./drive_save.php";
	include "./php/post_c.php";//저장하고 우편물 발행을 
}//저장하는경우에 만


?>
<?if($num){ include "./drive_query.php";//상담,업체명등으로 조회한다  
}?>
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script language=javascript src='/kibs_admin/js/list_2.js'></script>
<script language="JavaScript" src="/csd/cs_member/js/member.js"></script>
<script>
function bogan_f(url){ //대리운전보험
		
		var fobj;
			fobj = document.form1;
		
			if(!document.form1.oun_name.value){
	  alert("이름을 입력하세요!");
 	  document.form1.oun_name.focus();
  	  return;
	}
	
	if(document.form1.oun_jumin1.length<'6'){
	  alert("주민번호앞 6자리가 아니에요!");
 	  document.form1.oun_jumin1.focus();
  	  return;
	}
	if(document.form1.oun_jumin2.length<'7'){
	  alert("주민번호뒤 7자리가 아니에요!");
 	  document.form1.oun_jumin2.focus();
  	  return;
	}
	

	if(!document.form1.preminum.value){
	  alert("보험료 입력하세요!1111");
 	  document.form1.preminum.focus();
  	  return;
	}
	if(!document.form1.santage.value){
	  alert("납입상태를 선택하세요!");
 	  document.form1.santage.focus();
  	  return;
	}
	if(!document.form1.nab.value){
	  alert("납입방법을  선택하세요!");
 	  document.form1.nab.focus();
  	  return;
	}
	var kind=form1.santage.value
	 if(kind==7){

				if(!document.form1.change_name.value){
			  alert("교체 운전자 이름을 입력하세요!");
			  document.form1.change_name.focus();
			  return;
			}
			
			if(document.form1.change_jumin1.length<'6'){
			  alert("교체 운전자 주민번호앞 6자리가 아니에요!");
			  document.form1.change_jumin1.focus();
			  return;
			}
			if(document.form1.change_jumin2.length<'7'){
			  alert("교체 운전자 주민번호뒤 7자리가 아니에요!");
			  document.form1.change_jumin2.focus();
			  return;
			}



	}
	//분납 체크chasu
	//		<? $Tnum = $nab - $chasu; ?>
	//			if(<? for($z=1; $z<=$Tnum; $z++) { ?>fobj.buna_<?=$z+$chasu?>.checked==false<?  if($z!=$Tnum) {?> && <? } ?><? } ?>){
	//			alert ('분납보험료 납입 체크를 해주세요.');
	//			return false;
	//		}
			<? $Tnum = $nab - $chasu; ?>
			<? for($z=1; $z<=$Tnum; $z++) {
				$_k=$z+$chasu;?>
					
			if(fobj.buna_<?=$_k?>.checked==true ){
				
					<?for($_z=$_k;$_z>=$chasu+1;$_z--){?>
						if(fobj.buna_<?=$_z?>.checked==false ){
							alert('<?=$_z?>회차 부터 보험료를 납입하여야 합니다')
						 document.form1.buna_<?=$_z?>.focus();
						return false;
						}
					<?}?>

				
				if(confirm('<?=$z+$chasu?>회분 보험료를 납입하시겠습니까?.'))
					fobj.bunab_state.value='<?=$z+$chasu?>';
			}
			<? } ?>


			if(confirm("수정합니까"))
			{
				//window.open('','','width=50,height=50,top=600,left=600')
				document.form1.target="_self";
				document.form1.action = url
				document.form1.submit();
			}
			
	}
</script>


<title><?if($num){ echo $oun_name ; } else{ echo "신규 상담";}?></title>
<form name="form1" method="post" >
<input type='hidden' id='num' name='num' value='<?=$num?>'><!--조회 할 경우 업체 번호 또는 상담 번호-->
<!--*날자를 표시하기 위함 신규 상담인경우에는 처음이 나오고 업체를 불러 들이는경우는 두번째 부분이 나옴*-->
<?if(!$num){?>
		<table border="0" cellpadding="0" cellspacing="4" width='100%'>
		<tr >
		   <td>신규등록</td>
		   <td width='660' align='right'><?= date("Y-m-d ")?>
		  </tr>
		</table>
<?	} else {?>
	<table border="0" cellpadding="0" cellspacing="4" width='100%'>
		<tr >
		
		   <td  align='right'><?= date("Y-m-d ")?></td></tr>
		</table>
<? }?>

     <table border="0" cellpadding="0" cellspacing="1" width='100%'>
		<tr bgcolor="#0FB9C4" height="20" align="center">
		 <td rowspan="2" width="8%"><font color="#ffffff">운전자</font></td>
		 <td width="6%"><font color="#ffffff">성명</font></td>
		 <td width="12%"><font color="#ffffff">주민번호</font></td>
		 <td width="8%"><font color="#ffffff">전화번호</font></td>
		 <td width="6%"><font color="#ffffff">가입일</font></td>
		 <td width="8%"><font color="#ffffff">증권번호</font></td> 
		 <td width="8%"><font color="#ffffff">보험료</font></td>
		 <td width="8%"><font color="#ffffff">종기일</font></td>
		 <td width="8%"><font color="#ffffff">설계번호</font></td>
		 <td width="8%"><font color="#ffffff">납입상태</font></td>
		 <td width="8%"><font color="#ffffff">분납방법</font></td>
		 <td width="8%"><font color="#ffffff">가상</font></td>
		</tr>
		<tr bgcolor='#ffffff' height="20" align="right"> 
		 <td>
			<input type="text" name="oun_name" value="<?=$oun_name?>" class="input" size="6" maxlength="6" style="text-align:right">
		 </td>
		 <td>
			<input type="text" name="oun_jumin1" value="<?=$oun_jumin1?>" class="input" size="6" maxlength="6" style="text-align:right" onkeyup="focus_jumin_oun_2()">
			<input type="text" name="oun_jumin2" value="<?=$oun_jumin2?>" class="input" size="7" maxlength="7" style="text-align:right" onkeyup="focus_jumin_oun_1()">
		 </td>
		 <td>
			<input type="text" name="oun_phone" value="<?=$oun_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="phone_check()" onClick="phone_check_2()">
		 </td>
		  <td>
			<input type="text" name="start" value="<?=$start?>" class="input" size="10" maxlength="10" style="text-align:right" onBlur="start_check()" onClick='start_check_2()'>
		 </td>
		 <td>
		   <input type="text" name="certi_number" value="<?=$certi_number?>" class="input" size="15" maxlength="17" style="text-align:right" onBlur="certi_check()" onClick="certi_check_2()" >
		</td>
		
		 <td>
			<input type="text" name="preminum" value="<?=number_format($preminum)?>" class="input" size="8" maxlength="8" style="text-align:right" OnClick="preminum_clear()" onBlur="preminum_check()">
		 </td>
		 <td>
			<input type="text" name="new_jeonggi" value="<?=$new_jeonggi?>" class="input" size="10" maxlength="10" style="text-align:right" onBlur='new_jeonggi_check()' onClick='new_jeonggi_check_2()'>
		 </td>
		  <td><input type="text" name="design_num" value="<?=$design_num?>" class="input" size="6" maxlength="6" style="text-align:right" ></td>
		 <td>
			<select id="santage" name="santage" class="input" onChange='view_info()' >
	 
			  <option value='1' <?if($sangtae=='1'){echo "selected";}else{echo "";}?>>당월납입</option>
			  <option value='2' <?if($sangtae=='2'){echo "selected";}else{echo "";}?>>당월미납</option>
			  <option value='3' <?if($sangtae=='3'){echo "selected";}else{echo "";}?>>유예</option>
			  <option value='4' <?if($sangtae=='4'){echo "selected";}else{echo "";}?>>실효</option>
			  <option value='5' <?if($sangtae=='5'){echo "selected";}else{echo "";}?>>해지</option>
			  <option value='6' <?if($sangtae=='6'){echo "selected";}else{echo "";}?>>완납</option>
			  <option value='7' <?if($sangtae=='7'){echo "selected";}else{echo "";}?>>배서</option>
			  <option value='8' <?if($sangtae=='8'){echo "selected";}else{echo "";}?>>최종납입</option>

		   </select>
		</td>
		 <td>
			<select name="nab" class="input" OnClick='preminum_clear()'>			 
			  
			  
			  <option value='10' <?if($nab=='10'){echo "selected";}else{echo "";}?>>10회납</option>
			  <option value='6' <?if($nab=='6'){echo "selected";}else{echo "";}?>>6회납</option>
			   <option value='7' <?if($nab=='7'){echo "selected";}else{echo "";}?>>연6회납</option>
			  <option value='1' <?if($nab=='1'){echo "selected";}else{echo "";}?>>일시납</option>
			  <option value='2' <?if($nab=='2'){echo "selected";}else{echo "";}?>>2회납</option>
			  
			 
			</select>
		 </td>
		
		 <td><?if(!$virtual_num || $virtual_num=='1'){?><INPUT TYPE="checkbox" NAME="virtual_num" value='2' onfocus="this.blur()"><?}else{?>가상<?}?></td>
		</tr>
		<!--분납보험료 -->
		<input type="hidden" name="bunab_state"><!--분납보험료를 납입하기위해 분납 회차를 체크할 경우분납 회차를 update 파일에 변수를 던지기 위해--> 
		<input type="hidden" name="chasu" value="<?=$chasu?>">
		<tr>
			<td bgcolor="#0FB9C4" height="20" align="center"><font color="#ffffff">분납보험료</font></td>
			<td colspan="11">
				<table border="0" cellpadding="0" cellspacing="1" width='100%'>
					<tr bgcolor="#0FB9C4" height="20" align="center">
					  
					  <td width="10%"><font color="#ffffff">1 회차</font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="da_2" value="<?=$da_2?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="da_3" value="<?=$da_3?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="da_4" value="<?=$da_4?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="da_5" value="<?=$da_5?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="da_6" value="<?=$da_6?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="da_7" value="<?=$da_7?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="da_8" value="<?=$da_8?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="da_9" value="<?=$da_9?>" size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="da_10" value="<?=$da_10?>"  size="10" maxlength="6" class="input" style="text-align:center" ></font></td>

					 </tr>
					 <tr bgcolor="#0FB9C4" align="center">
						<td><input type="text" name="first" value="<?=$first?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="second" value="<?=$second?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="third" value="<?=$third?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="fourth" value="<?=$fourth?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="fifth" value="<?=$fifth?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="sixth" value="<?=$sixth?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="seventh" value="<?=$seventh?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="eighth" value="<?=$eighth?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="nineth" value="<?=$nineth?>" size="10" maxlength="7" class="input" style="text-align:right" ></td>
						<td><input type="text" name="tenth" value="<?=$tenth?>"   size="10" maxlength="7" class="input" style="text-align:right" ></td>
					</tr>
					 <tr bgcolor="#0FB9C4" align="center">
						<td><input type="text" name="daum_day_1" value="<?=$daum_day_1?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_2" value="<?=$daum_day_2?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_3" value="<?=$daum_day_3?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_4" value="<?=$daum_day_4?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_5" value="<?=$daum_day_5?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_6" value="<?=$daum_day_6?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_7" value="<?=$daum_day_7?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_8" value="<?=$daum_day_8?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_9" value="<?=$daum_day_9?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="daum_day_10" value="<?=$daum_day_10?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
					</tr>
					<?if($num){?>
						<?// echo "chasu $chasu <br>";?>
					<tr bgcolor="#0FB9C4" align="center">
						<td><font color="#ffffff"><?if($chasu>='1'){echo" 납입" ;}else{?><input type='checkbox' name='buna_1' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='2'){echo" 납입" ;}else{?><input type='checkbox' name='buna_2' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='3'){echo" 납입" ;}else{?><input type='checkbox' name='buna_3' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='4'){echo" 납입" ;}else{?><input type='checkbox' name='buna_4' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='5'){echo" 납입" ;}else{?><input type='checkbox' name='buna_5' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='6'){echo" 납입" ;}else{?><input type='checkbox' name='buna_6' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='7' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='buna_7' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='8' && $nab=='10' ){echo" 납입" ;}else{?><input type='checkbox' name='buna_8' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='9' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='buna_9' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='10' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='buna_10' value='2' onfocus='this.blur()'><?}?></font></td>
					</tr>
					<?}?>
				</table>
			 
			
			</td>
		</tr>

		<!-- 분납보험료 -->
		<?if(!$num){?>
		<tr bgcolor="#0FB9C4" height="25" >
		 <td align="center"></td>
		 <td align="center" colspan="13">	 
		  <font color="#ffffff">계약자와운전자 동일</font><INPUT TYPE="checkbox" NAME="agree" onfocus='blur()' onClick='det()'>
		  <font color="#ffffff">계약자와 사장동일</font> <INPUT TYPE="checkbox" NAME="agree_ceo" onfocus='blur()' onClick='det_ceo()'>
		 </td>
		</tr>
		<?}?>
		<tr bgcolor="#0FB9C4" height="20" align="center">
		 <td rowspan="2" width=""><font color="#ffffff">계약자</font></td>
		 <td><font color="#ffffff">성명</font></td>
		 <td><font color="#ffffff">주민번호</font></td>
		 <td><font color="#ffffff">전화번호</font></td>
		 <td><font color="#ffffff">회사</font></td>
		 <td><font color="#ffffff">은행</font></td>
		 <td><font color="#ffffff">계좌번호</font></td>
		 <td><font color="#ffffff">차보험사</font></td>
		  <td><font color="#ffffff">차만기일</font></td>
		 <td colspan='3'><font color="#ffffff">자동차번호</font></td>
		</tr>
		<tr bgcolor='#ffffff' height="20" align="right">
		 
		 <td>
			<input type="text" name="con_name" value="<?=$con_name?>" class="input" size="6" maxlength="10" style="text-align:right" onBlur="con_name_check()" <?if(!$num){?>OnFocus="bunnab_preminum_check()" <?}?>>
		 </td>
		 <td>
			<input type="text" name="con_jumin1" value="<?=$con_jumin1?>" class="input" size="6" maxlength="6" style="text-align:right" onkeyup="focus_jumin_oun_3()">
			<input type="text" name="con_jumin2" value="<?=$con_jumin2?>" class="input" size="7" maxlength="7" style="text-align:right" onkeyup="focus_jumin_oun_4()">
		 </td>
		 <td>
			<input type="text" name="con_phone" value="<?=$con_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="con_phone_check()" onClick="con_phone_check_2()">
		 </td>
		 <td>
			<input type="text" name="company" value="<?=$company?>" class="input" size="15" maxlength="15" style="text-align:right">
		 </td>
		 <td>
			<input type="text" name="bank" value="<?=$bank?>" class="input" size="10" maxlength="10" style="text-align:right">
		</td>
		 <td>
			<input type="text" name="bank_number" value="<?=$bank_number?>" class="input" size="15" maxlength="15" style="text-align:right">
		</td>
		<td><select name='car_company' class='input'>
			

			<option value='1'>===</option>
			<option value='차없음' <?if($car_company=='차없음'){echo "selected";}else{echo "";}?>>차없음</option>
			<option value='삼성' <?if($car_company=='삼성'){echo "selected";}else{echo "";}?>>삼성</option>
			<option value='엘지' <?if($car_company=='엘지'){echo "selected";}else{echo "";}?>>엘지</option>
			<option value='현대' <?if($car_company=='현대'){echo "selected";}else{echo "";}?>>현대</option>
			<option value='동부' <?if($car_company=='동부'){echo "selected";}else{echo "";}?>>동부</option>
			<option value='그린' <?if($car_company=='그린'){echo "selected";}else{echo "";}?>>그린</option>
			<option value='메리츠'<?if($car_company=='메리츠'){echo "selected";}else{echo "";}?>>메리츠</option>
			<option value='신동아'<?if($car_company=='신동아'){echo "selected";}else{echo "";}?>>신동아</option>
			<option value='제일' <?if($car_company=='제일'){echo "selected";}else{echo "";}?>>제일</option>
			<option value='쌍용' <?if($car_company=='쌍용'){echo "selected";}else{echo "";}?>>쌍용</option>
			<option value='교보' <?if($car_company=='교보'){echo "selected";}else{echo "";}?>>교보</option>
			<option value='다음' <?if($car_company=='다음'){echo "selected";}else{echo "";}?>>다음</option>


			</select>
		</td>
		 <td><input type="text" name="car_mangi" value="<?=$car_mangi?>" class="input" size="10" maxlength="11" style="text-align:right" onBlur="car_mangi_check()" onClick='car_mangi_check_2()'>
		</td>
		 <td colspan='3'><input type="text" name="car_number" value="<?=$car_number?>" class="input" size="12" maxlength="12" style="text-align:right"></td>
		</tr>
		<tr bgcolor="#0FB9C4" height="20" >
		 <td align='center'><font color="#ffffff">주소</font></td>
		<!-- <td><font color="#ffffff">성명</font></td>
		 <td><font color="#ffffff">주민번호</font></td>
		 <td><font color="#ffffff">전화번호</font></td>
		 <td><font color="#ffffff">회사정식이름</font></td>
		 <td><font color="#ffffff">사업자번호</font></td>
		 <td><font color="#ffffff">법인번호</font></td>-->
		 <td colspan='11'>
		 <input type='hidden' name='ab' value='<?=$ab?>'><!--우편물 저장을 위해 new_cs_dongbu2-->
		 <?include "./php/post_print.php";?>
		 </td>
		  
		</tr>
	<!--	<tr bgcolor='#ffffff' height="20" align="right">
		 
		 <td>
			<input type="text" name="p_con_name" value="<?=$p_con_name?>" class="input" size="6" maxlength="10" style="text-align:right" >
		 </td>
		 <td>
			<input type="text" name="p_con_jumin1" value="<?=$p_con_jumin1?>" class="input" size="6" maxlength="6" style="text-align:right" onkeyup="focus_jumin_oun_5()">
			<input type="text" name="p_con_jumin2" value="<?=$p_con_jumin2?>" class="input" size="7" maxlength="7" style="text-align:right" onkeyup="focus_jumin_oun_6()" onBlur="p_con_name_check()">
		 </td>
		 <td>
			<input type="text" name="p_con_phone" value="<?=$p_con_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="p_con_phone_check()" onClick="p_con_phone_check_2()">
		 </td>
		 <td>
			<input type="text" name="company_full" value="<?=$company_full?>" class="input" size="15" maxlength="15" style="text-align:right">
		 </td>
		 <td>
			<input type="text" name="company_num_ber" value="<?=$company_num_ber?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="company_num_ber_check()" onClick="company_num_ber_check_2()">
		</td>
		 <td>
			<input type="text" name="l_number" value="<?=$l_number?>" class="input" size="13" maxlength="13" style="text-align:right"
			onBlur="l_number_check()" onClick="l_number_check_2()">
		</td>
		<td colspan='11'>
		</td>
		
		</tr>-->
		<tr height="200" bgcolor="#0FB9C4">
		 <td align="center" colspan='5'>
			<table border='0' cellpadding="0" cellspacing="1" width='100%' >	
				<tr>
				  <td bgcolor="#0FB9C4" width="15%" align="center">
					
					<font color="#ffffff">개인기록<br><input type='checkbox' name='memo_check' value='2' onfocus='this.blur()'></font>
				 </td>

				 <td bgcolor="#ffffff">
						<textarea name="content"  cols="44" rows="4"><?=$content?></textarea>
				 </td>
				 
				 </tr>

				 <tr>
				 
					 <td bgcolor="#0FB9C4"  align="center">
						
						<font color="#ffffff">일반메모<br><input type='checkbox' name='memo_check' value='3' onfocus='this.blur()'></font>
					 </td>
					  <td bgcolor="#ffffff">
						<textarea name="memo_general_content"  cols="44" rows="4"><?=$memo_general_content?></textarea>
				    </td>
				</tr>
				<tr>
				 
					 <td bgcolor="#0FB9C4" align="center">
						
						<font color="#ffffff">배서메모<br><input type='checkbox' name='memo_check' value='4' onfocus='this.blur()'></font>
					 </td>
					  <td bgcolor="#ffffff">
						<textarea name="memo_content"  cols="44" rows="4"><?=$memo_content?></textarea>
				    </td>
				</tr>
			</table>
		 </td>
		 
		
		  <td align="center" >
		   <font color="#ffffff">보험일수</font><input type="text" name="bohum_daily" value="<?=$bohum_daily?>"  size="5" maxlength="5" class="input" style="text-align:right" readonly><br>
		  <font color="#ffffff"><?if($company){echo "$company 정보";}else{echo "대리운전회사정보";}?></font>
		  <br>
		  <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="가상계좌발송"  onFocus='this.blur()'      onClick="virtual_sms()">
		  <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="안내문발송"  onFocus='this.blur()'      onClick="virtual_sms_2()">
		  </td>	
		  <td  colspan='6' bgcolor='#ffffff'>
			   <table border="0" cellpadding="0" cellspacing="1" width='100%' >
					<tr bgcolor="#0FB9C4" height="20" align="center">
					  <td width='30%'><font color="#ffffff">사장</font></td>
					  <td ><font color="#ffffff">회사전화</font></td>
					  <td width='28%'><font color="#ffffff">회사팩스</font></td>
					 </tr>
					 <tr bgcolor="#ffffff" height="18" align="right">
					  <td><input type='text' name='ceo_name' value='<?=$ceo_name?>' size='15' maxlength='15' class='input'></td>
					  <td><input type='text' name='ceo_phone' value='<?=$ceo_phone?>' size='12' maxlength='12' class='input' style="text-align:right" onBlur="ceo_phone_check()" onClick="ceo_phone_check_2()"></td>
					  <td><input type='text' name='ceo_fax' value='<?=$ceo_fax?>' size='12' maxlength='12' class='input' style="text-align:right" onBlur="ceo_fax_check()" onClick="ceo_fax_check_2()"></td>
					 </tr>
					 <tr bgcolor="#0FB9C4">
					  <td colspan='3'><textarea name="ceo_content"  cols="40" rows="10"><?=$ceo_content?></textarea> </td>	
					 </tr>
					 </table>
			
			
			</td>
		</tr>
							
	 </table>

                     

	<div id="money_7" style="display:none;">

		<table border="0" cellpadding="0" cellspacing="1" width='100%'>
		<tr bgcolor="#0FB9C4" height="20" align="center">
		 <td width="5%" rowspan="4" width=""><font color="#ffffff">교체운전자</font></td>
		 <td width="5%"><font color="#ffffff">성명</font></td>
		 <td width="12%"><font color="#ffffff">주민번호</font></td>
		 <td width="9%"><font color="#ffffff">전화번호</font></td>
		 <td width="10%"><font color="#ffffff">가입일</font></td>
		 <td width="12%"><font color="#ffffff">증권번호</font></td>
		 
		 <td width="8%"><font color="#ffffff">보험료</font></td>
		 <td width="5%"><font color="#ffffff">설계번호</font></td>
		 <td width="9%"><font color="#ffffff">납입상태</font></td>
		 <td width="8%"><font color="#ffffff">분납방법</font></td>
		 
		 <td width="3%"><font color="#ffffff">가상</font></td>
		</tr>
		<tr bgcolor="#ffffff" height="20" align="center">
		 
		 <td><input type="text" name="change_name" value="<?=$change_name?>" class="input" size="6" maxlength="6" style="text-align:right"></td>
		 <td><input type="text" name="change_jumin1" value="<?=$change_jumin1?>" class="input" size="6" maxlength="6" style="text-align:right" onkeyup="focus_jumin_change_2()">
			<input type="text" name="change_jumin2" value="<?=$change_jumin2?>" class="input" size="7" maxlength="7" style="text-align:right" onkeyup="focus_jumin_change_1()"></td>
		 <td><font color="#ffffff"><input type="text" name="change_phone" value="<?=$change_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="change_phone_check()" onClick="change_phone_check_2()"></font></td>
		 <td><font color="#ffffff"><input type="text" name="change_start" value="<?=$start?>" class="input" size="12" maxlength="12" style="text-align:right"  readonly></font></td>
		 <td width="10%"><font color="#ffffff"><input type="text" name="change_certi_number" value="<?=$certi_number?>" class="input" size="17" maxlength="17" style="text-align:right"  readonly></font></td>
		 
		 <td width="10%"><input type="text" name="change_preminum"  value="<?=number_format($preminum)?>" class="input" size="8" maxlength="8" style="text-align:right" readonly></td>
		  <td><input type="text" name="change_design_num" value="<?=$design_num?>" class="input" size="6" maxlength="6" style="text-align:right"></td>
		 <td><select name="change_santage" class="input"  onChange='haejiSms()'>
			 <option value='<?=$sangtae?>'><?=$sangtae_name?></option>
			  <option >=======</option>
			  <option value='1'>당월납입</option>
			  <option value='2'>당월미납</option>
			  <option value='3'>유예</option>
			  <option value='4'>실효</option>
			  <option value='5'>해지</option>
			  <option value='6'>완납</option>
			  <option value='7'>배서</option>
			  <option value='8'>최종납입</option>

		   </select></td>
		 <td><select name="change_nab" class="input">			 
			  <option value='<?=$nab?>'><?if($nab_name){echo $nab_name ;}else{echo "=선택=";}?></option>
			  <option value=>=====</option>
			  <option value='10'>10회납</option>
			  <option value='1'>일시납</option>
			  <option value='2'>2회납</option>
			  <option value='6'>6회납</option>
			 
			</select></td>
		
		 <td><INPUT TYPE="checkbox" NAME="change_virtual_num" value='2' onfocus="this.blur()"></td>
		</tr>
		<!--분납보험료 -->
		<tr>
			<td bgcolor="#0FB9C4" height="20" align="center"><font color="#ffffff">분납보험료</font></td>
			<td colspan="10">
				<table border="0" cellpadding="0" cellspacing="1" width='100%'>
					<tr bgcolor="#0FB9C4" height="20" align="center">
					  
					  <td width="10%"><font color="#ffffff">1 회차</font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="change_da_2" value="<?=$da_2?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="change_da_3" value="<?=$da_3?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="change_da_4" value="<?=$da_4?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td width="10%"><font color="#ffffff"><input type="text" name="change_da_5" value="<?=$da_5?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="change_da_6" value="<?=$da_6?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="change_da_7" value="<?=$da_7?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="change_da_8" value="<?=$da_8?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="change_da_9" value="<?=$da_9?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>
					  <td  width="10%"><font color="#ffffff"><input type="text" name="change_da_10" value="<?=$da_10?>"   size="10" maxlength="6" class="input" style="text-align:center" ></font></td>

					 </tr>
					 <tr bgcolor="#0FB9C4" align="center">
						<td><input type="text" name="change_first"   value="<?=$first?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_second"  value="<?=$second?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_third"   value="<?=$third?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_fourth"  value="<?=$fourth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_fifth"   value="<?=$fifth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_sixth"   value="<?=$sixth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_seventh" value="<?=$seventh?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_eighth"  value="<?=$eighth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_nineth"  value="<?=$nineth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_tenth"   value="<?=$tenth?>" size="10" maxlength="6" class="input" style="text-align:right" ></td>
					</tr>
					 <tr bgcolor="#0FB9C4" align="center">
						<td><input type="text" name="change_daum_day_1" value="<?=$daum_day_1?>"size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_2" value="<?=$daum_day_2?>"  size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_3" value="<?=$daum_day_3?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_4" value="<?=$daum_day_4?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_5" value="<?=$daum_day_5?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_6" value="<?=$daum_day_6?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_7" value="<?=$daum_day_7?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_8" value="<?=$daum_day_8?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_9" value="<?=$daum_day_9?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
						<td><input type="text" name="change_daum_day_10" value="<?=$daum_day_10?>" size="10" maxlength="10" class="input" style="text-align:right" ></td>
					</tr>
					<?if($num){?>
					<tr bgcolor="#0FB9C4" align="center">
						<td><font color="#ffffff"><?if($chasu>='1'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_1' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='2'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_2' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='3'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_3' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='4'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_4' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='5'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_5' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='6'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_6' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='7' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_7' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='8' && $nab=='10' ){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_8' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='9' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_9' value='2' onfocus='this.blur()'><?}?></font></td>
						<td><font color="#ffffff"><?if($chasu>='10' && $nab=='10'){echo" 납입" ;}else{?><input type='checkbox' name='change_buna_10' value='2' onfocus='this.blur()'><?}?></font></td>
					</tr>
					<?}?>
				</table>
			 
			
			</td>
		</tr>

		<!-- 분납보험료 -->

		<tr bgcolor="#0FB9C4" height="20" align="center">
		 
		 <td colspan='6'>
		 <td><font color="#ffffff">차보험사</font></td>
		 <td><font color="#ffffff">차만기일</font></td>
		 <td colspan='2'><font color="#ffffff">자동차번호</font></td>
		</tr>
		<tr bgcolor="#ffffff" height="20" align="center">
		 
		 <td  bgcolor="#0FB9C4" colspan='6'>
		 <td><select name='change_car_company' class='input'>

			<option>===</option>
			<option value='차없슴'>차없음</option>
			<option value='삼성'>삼성</option>
			<option value='엘지'>엘지</option>
			<option value='현대'>현대</option>
			<option value='동부'>동부</option>
			<option value='그린'>그린</option>
			<option value='메리츠'>메리츠</option>
			<option value='신동아'>신동아</option>
			<option value='제일'>제일</option>
			<option value='쌍용'>쌍용</option>
			<option value='교보'>교보</option>
			<option value='다음'>다음</option>


			</select>
		</td>
		 <td><input type="text" name="change_car_mangi"  class="input" size="10" maxlength="11" style="text-align:right" onBlur="car_mangi_check()" onClick='car_mangi_check_2()'>
		</td>
		 <td colspan='2'><input type="text" name="change_car_number"  class="input" size="15" maxlength="15" style="text-align:right"></td>
		</tr>
		
							
	 </table>
	</div>

							
						
					   
		
		
                  
                    
		 
   	<table width="100%" border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td height="40" align="center"> 
          <?if($num){ ?>
          <img src='/csd/images/community/rectify.gif' border=0 onclick=" bogan_f('./drive.php?seong=1')" align="absmiddle" style="cursor:hand" title="수정 보관"> 
          <!-- 가입설계 수정 -->
          <img src='/csd/images/community/close_1.gif' border=0 onClick="self_close()" align="absmiddle" style="cursor:hand" title="닫기">
          <? } else {?>
          <img src='/csd/images/community/insu1.gif' border=0 onclick=" bogan_d('./drive.php')" align="absmiddle" style="cursor:hand" title="새로운 가입 설계"><img src='/csd/images/community/close_1.gif' border=0 onclick="self_close()" align="absmiddle" style="cursor:hand" title="닫기"> 
          <!-- 가입설계 보관-->
          <? }?>
        </td>
       </tr>
     </table>
	<?if($sm_total){?>

	<table border="0" cellpadding="0" cellspacing="1" width='100%'>
		<tr bgcolor="#0FB9C4" height="20" align="center">
			<td width="5%"><font color="#ffffff">번호</font></td>
			<td width="16%"><font color="#ffffff">발송일</font></td>
			<td width="80%"><font color="#ffffff">메세지</font></td>
			</tr>
		<?for($_g_=0;$_g_<$sm_total;$_g_++){
			$_h=$sm_total-$_g_ ;
			$sm_row=mysql_fetch_array($sm_rs);
			$LastTime	=$sm_row['LastTime'];
			$Msg		=$sm_row['Msg'];

			$ysear			=substr($LastTime,0,4);
			$month			=substr($LastTime,4,2);
			$day			=substr($LastTime,6,2);
			$_time			=substr($LastTime,8,2);
			$_minute		=substr($LastTime,10,2);?>
			<tr bgcolor="#0FB9C4" height="20" align="center">
				<td><font color="#ffffff"><?=$_h?></font></td>
				<td><font color="#ffffff"><?=$ysear?>년<?=$month?>월<?=$day?>일<?=$_time?>시<?=$_minute?>분</font></td>
				<td><font color="#ffffff"><?=$Msg?></font></td>
			</tr>
		<?}?>
	</table>

	<?}?>
		 
</form>

<?// echo "user $useruid <br>"; ?>
