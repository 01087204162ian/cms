<?		$toyear  			=	date(Y);
        $tomonth 		= date(m);
        $today   			= date(d);
//echo "us $userid ";		
?>


<form name="form1" method="post" >
<input type="hidden" name="num_2" value="<?=$num_2?>">
<table width="860" border="0" cellspacing="0" cellpadding="0">
<tr><td width="80%">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  
		  <tr> 
			<td><img src="../../../image/about/dot.gif" width="9" height="9"> 
			  <?if($num_2=='2'){?>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="3%"></td>
				    <td width="20%"><font color="blue"><strong>Cs Virtual</strong></font></td>
					<td><?=$v_wdate?></tr>
				 </tr>
				</table>
			  <?}?>
			  <?if($num_2=='3'){?>
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="3%"></td>
					<td width="10%"><font color="blue"><strong>Cs 상담</strong></font></td>
					<td width="20%"><?=$c_wdate?></td>
					<td width="15%">상담요청시간</td>
					<td width="52%"><?=$consulting_day?><?=$sig2?><?=$sig3?></td>
					
				  </tr>
				  </table>
				  <?}?>
				  <?if($num_2=='4' && $gubun!='7'){
					  switch($gubun){
						  case '2' :
							  $gubun_name="Cs Virtual";
						  break;
						  case '3' :
							  $gubun_name="Cs 상담";
						  break;
						  case '8' :
							  $gubun_name="유선 상담";
						  break;
						   case '10' :
							  $gubun_name="대리나라";
						  break;
						  default :
							  $gubun_name="Do Virtual";
						  break;
					  } ?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="3%"></td>
						<td width="20%"><font color="blue"><strong><?=$gubun_name?></strong></font></td>
						<td width="20%">보험료 산출 사용시간</td>
						<td width="20%"><?=$wdate?>&nbsp;<?=$ttime?></td>
						<td width="15%"></td>
						<td width="22%"></td>
						
					  </tr>
				  </table>
				  <?}?>
				  <?if($gubun=='7'){?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="3%"></td>
						<td width="15%"><font color="blue"><strong>Do 상담</strong></font></td>
						<td width="20%"><?=$wdate?> &nbsp;<?=$ttime?></td>
						<td width="12%">상담예정일</td>
						<td width="13%"><?=$adate?></td>
						<td width="20%"><?=$atime_name?></td>
						<td width="17%"></td>
						
					  </tr>
				  </table>
				  <?}?>
				   <?if($num_2=='8'){?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="3%"></td>
						<td width="22%">대리운전보험신규 상담</td>
						<td width="20%"></td>
						<td width="12%"></td>
						<td width="13%"><?=$toyear?>-<?=$tomonth?>-<?=$today?></td>
						<td width="20%"></td>
						<td width="17%"></td>
						
					  </tr>
				  </table>
				  <?}?>
			  
			 </td>
		  </tr>
		</table>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height='7'></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width='14'></td>
				<td><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#DBDBDB">
						  <tr bgcolor="#B1D4F0" class="text"> 
							<td height="3" colspan="5"></td>
							
						  </tr>
						  <tr bgcolor="#EFF4F8" class="text" align="center"> 
							<td width="10%"><strong>성명</strong></td>
							<td width="22%"><strong>주민번호</strong></td>
							<td width="25%"><strong>핸드폰번호</strong></td>
							<td width="30%"><strong>보험 시작일</strong></td>
							<td width="15%"><strong>보험 나이</strong></td>
						  </tr>
						  <tr> 
							<td bgcolor="#FFFFFF" align="right"><input type="text" name="c_name"  value="<?=$name2?>"class="input" maxlength="7" size="5" style="text-align:right" onclick="clear_text_c_name()";></td>
							<td bgcolor="#FFFFFF" align="center"><input type="text" name="jumin1" value="<?=$jumin1?>" size="6" maxlength="6" class="input" onkeyup="focus_move_jumin1()"; onclick="clear_text_jumin1()";>-<input type="text" name="jumin2" value="<?=$jumin2?>" size="7" maxlength="7" class="input" onkeyup="focus_move_jumin2()"; onclick="clear_text_jumin2()";></td>
							<td bgcolor="#FFFFFF" align="center">
							<select name="phone_1" value="<?=$phone_1?>" onChange="focus_move_phone_1()"; class="input">
								<option value='<?=$phone_1?>'><?if($phone_1){echo $phone_1 ; }else{echo "선택";}?></option>
								<option value='010'>010</option>
								<option value='011'>011</option>
								<option value='016'>016</option>
								<option value='017'>017</option>
								<option value='018'>018</option>
								<option value='019'>019</option>
							</select>-<input type="text" name="phone_2" value="<?=$phone_2?>" size="4" maxlength="4" class="input" onkeyup="focus_move_phone_2()">-<input type="text" name="phone_3" value="<?=$phone_3?>" size="4" maxlength="4" class="input"></td>
							<td bgcolor="#FFFFFF" align="center"> <? $now_time = date("Y-m-d");
									list($year, $month, $day) = explode("-", $now_time);

								
								if($year_s==0000){ $year_s='';}
								if($month_s==00){ $month_s='';}
								if($day_s==00){ $day_s='';}
								?>
									<div align="left" style="z-index:1" id="year_d">
									&nbsp;<select name="year" size="1" id="year" class="input">
									<?if($year_s){?>
									  <option value="<?=$year_s?>" selected><?=$year_s?></option>
									 <?}else{
										  for($i=2007;$i<2020;$i++){?>
										  <option value="<?=$year?>" <?if($i==$year){echo "selected";}else{echo "";}?> ><?=$i?></option>
										  <? } 
									  }?>
									</select>
								   
									<select name="month" id="month" class="input">
									<?if($month_s){?>
									  <option value="<?=$month_s?>" selected><?=$month_s?></option>
									 <?}else{
									  for($j=1;$j<13;$j++){?>
										 <option value="<?=$j?>" <?if($j==$month){echo "selected";}else{echo "";}?>><?=$j?> </option>
									  <? } 
									  }?>
									</select>
									
									<select name="day" id="day" class="input">
									<?if($day_s){?>
									  <option value="<?=$day_s?>" selected><?=$day_s?></option>
									 <?}else{
										 for($k=1;$k<31;$k++){?>
										  <option value="<?=$k ?>" <?if($k==$day){echo "selected";}else{echo "";}?>> <?=$k?></option><? 
										 }
									}?>
									</select>
									</td>
									<td bgcolor="#FFFFFF" align="right"><input type="text" name="nai_name" value="<?=$nai_name?>" class="input" maxlength="7" size="7" style="text-align:right" readonly></td>
							
							
						  </tr>
						 
						</table>

				</td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height='10'></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td><img src="../../../image/about/dot.gif" width="9" height="9"> 담보 내용 선택</td>
				<td  align="right"  bgcolor="#FFFFFF"> <img src='/kibs_admin/cargo/image/premium.gif' border=0 onclick=" preminum_cal('./php/cal_3.php?dongbu=10')" align="absmiddle" style="cursor:hand"></td>
              </tr>
            </table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height='10'></td>
              </tr>
            </table>
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width='14'></td>
				<td><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#DBDBDB">
						  <tr bgcolor="#B1D4F0" class="text"> 
							<td height="3" colspan="5"></td>
							
						  </tr>
						  <tr align="center"> 
							<td bgcolor="#EFF4F8" width="30%"><strong>담보내용</strong></td>
							<td bgcolor="#EFF4F8" width="50%" ><strong>보상한도</strong></td>
							<td bgcolor="#EFF4F8" width="15%"><strong>보험료</strong></td>
							<td bgcolor="#EFF4F8" width="20%"><strong>분납</strong></td>
							<td bgcolor="#EFF4F8" width="15%"><strong>보험료</strong></td>
							
						  </tr>
						  <tr> 
						  
							<td bgcolor="#EFF4F8" align="center"><strong>대인</strong></td>
							<td bgcolor="#FFFFFF" align="right" >
							<select name="daein_3" class="input" onclick="clear_text_daein_3()"; >
									
									<OPTION >=================</option>
									<OPTION value="1" <?if($daein_3=='1'){echo "selected";}?>>책임보험초과무한</option>
									<OPTION value="2" <?if($daein_3=='2'){echo "selected";}?>>책임보험포함무한</option>
									
								</select>

							</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='daein'  value="<?=$daein_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right">1 회차</td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='first'  value="<?=$first?>" style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center" ><strong>대물</strong></td>
							<td bgcolor="#FFFFFF" align="right" >
							   <select name="daemool_3" class="input" onclick="clear_text_daemool_3()"; >
									
									<OPTION >=================</option>
									<OPTION value="2" <?if($daemool_3=='2'){echo "selected";}?>>3천만원</option>
									<OPTION value="3" <?if($daemool_3=='3'){echo "selected";}?>>5천만원</option>
									<OPTION value="4" <?if($daemool_3=='4'){echo "selected";}?>>1억</option>
								</select>
							</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='daemool'  value="<?=$daemool_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_2' value="<?=$da_2?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='second' value="<?=$second?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center"><strong>자기신체</strong></td>
							<td bgcolor="#FFFFFF" align="right" >
							  <select name="jason_3" class="input" onclick="clear_text_jason_3()";>
								
								 <OPTION value="8" <?if($jason_3=='8'){echo "selected";}?>>=================</option>
							<!-- <OPTION value="1">1천5백만원</option>-->
							     <OPTION value="2" <?if($jason_3=='2'){echo "selected";}?>>3천만원</option>
								 <OPTION value="3" <?if($jason_3=='3'){echo "selected";}?>>5천만원</option>
								 <OPTION value="4"<?if($jason_3=='4'){echo "selected";}?>>1억</option>
							<!--	 <OPTION value="8">가입안함</option>-->
								</select>
							</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='jason' value="<?=$jason_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_3' value="<?=$da_3?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='third' value="<?=$third?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center"><select name="sele_3" onChange="change_cate(this.form,'');"  class="input" onclick="clear_text_sele_3()";>
								
									
								<option value="0"<?if($sele_3=='0'){echo "selected";}?>>=================</option>
								<option value="1" <?if($sele_3=='1'){echo "selected";}?>>차대차</option>
								<option value="2"<?if($sele_3=='2'){echo "selected";}?>>차대차+기타</option>
							  </select>	</td>
							<td bgcolor="#FFFFFF" align="right" >
								<select name="jagibudam_3"  class="input" onclick="clear_text_jagibudam_3()";>
								<OPTION value="<?if($jagibudam_3){echo $jagibudam_3;}else{echo "0";}?>"><? if($jagibudam_name ){echo $jagibudam_name  ;}else{echo "자부담금";}?></option>
									
								 <option value=0>자부담금</option>
							   </select>
							  <select name="char_3"  class="input" onclick="clear_text_char_3()";>
							  
								   <OPTION value="9999" <?if($char_3=='9999'){echo "selected";}?>>=================</option>
								   <option value="1" <?if($char_3=='1'){echo "selected";}?>>5백만원</option>
								   <option value="2" <?if($char_3=='2'){echo "selected";}?>>1천만원</option>
								   <option value="3" <?if($char_3=='3'){echo "selected";}?>>2천만원</option>
								   <option value="4" <?if($char_3=='4'){echo "selected";}?>>3천만원</option>
								<!--   <option value="9999">가입안함</option>-->
						       </select>
							  </td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='charyang' value="<?=$cha_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_4' value="<?=$da_4?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='fourth'  value="<?=$fourth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center"><strong>사고수습지원금</strong></td>
							<td bgcolor="#FFFFFF" align="right" ><select name="sago_3"   class="input" onclick="clear_text_sago_3()";>
									
								   <option value="0" <?if($sago_3=='0'){echo "selected";}?>>=================</option>
								   <option value="1" <?if($sago_3=='1'){echo "selected";}?>>10만원</option>
								   <option value="2" <?if($sago_3=='2'){echo "selected";}?>>20만원</option>
								  </select>
							</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='sago'  value="<?=$sago_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_5' value="<?=$da_5?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='fifth' value="<?=$fifth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" class="text" align="center"><strong>법률비용</strong></td>
							<td bgcolor="#FFFFFF" align="right" ><select name="law_3" class="input" onclick="clear_text_law_3()";>
							
							   <option value="0" <?if($law_3=='0'){echo "selected";}?>>=================</option>
							   <option value="1" <?if($law_3=='1'){echo "selected";}?>>벌금제외</option>
							   <option value="2" <?if($law_3=='2'){echo "selected";}?>>벌금포함</option>
							  </select>
							 </td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='law' value="<?=$law_p?>" style="text-align:right" size='8' maxlength='8' readonly class='input'>&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_6' value="<?=$da_6?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='sixth' value="<?=$sixth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center"><strong>분납방법</strong></td>
							<td bgcolor="#FFFFFF" align="right"><select name="bunnab_3"  class="input" onclick="clear_text_bunnab_3()";>
								
							   <option value="0" <?if($bunnab_3=='0'){echo "selected";}?>>=================</option>
							   <option value="1" <?if($bunnab_3=='1'){echo "selected";}?>>일시납</option>
							   <option value="2" <?if($bunnab_3=='2'){echo "selected";}?>>2회납</option>
							   <option value="4" <?if($bunnab_3=='4'){echo "selected";}?>>4회납</option>
							<!--   <option value="6">비연속 6회납</option>-->
								<option value="7" <?if($bunnab_3=='7'){echo "selected";}?>>6회납</option>
							   <option value="10" <?if($bunnab_3=='10'){echo "selected";}?>>10회납</option> 
								</select></td>
							
							<td bgcolor="#FFFFFF" align="right"></td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_7' value="<?=$da_7?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='seventh' value="<?=$seventh?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						  <tr> 
							<td bgcolor="#EFF4F8" align="center"><strong>인원</strong></td>
							<td bgcolor="#FFFFFF" align="right">
								<select name="bumwi_3"  class="input" onclick="clear_text_bumwi_3()"; onChange="inwon()";>
								
								 <option value="2" <?if($bumwi_3=='2'){echo "selected";}?>>=================</option>
								 <option value="1" <?if($bumwi_3=='1'){echo "selected";}?>>개인 계약</option>
								 <option value="0.95" <?if($bumwi_3=='0.95'){echo "selected";}?>>11명이상</option>
						<!--		 <option value="0.90" <?if($bumwi_3=='0.90'){echo "selected";}?>>11명이상</option>-->
								</select>
							</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='harin_rate' value="<?=$harin_rate?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_8' value="<?=$da_8?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='eighth' value="<?=$eighth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						   <tr> 
							<td bgcolor="#EFF4F8" align="center" ></td>
							<td bgcolor="#FFFFFF" ></td>
							<td bgcolor="#FFFFFF" align="right"></td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_9' value="<?=$da_9?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='nineth' value="<?=$nineth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						   <tr> 
							<td bgcolor="#EFF4F8" align="center" colspan="2">년간보험료</td>
							
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='total_p' value="<?=$total_p?>"  style="text-align:right" size='8' maxlength='8' readonly class="input" >&nbsp;</td>
							<td bgcolor="#FFFFFF" align="right"><input type='text' name='da_10' value="<?=$da_10?>" style="text-align:center" size='8' maxlength='8' class='input' readonly ></td>
							 <td bgcolor="#FFFFFF" align='right'><input type='text' name='tenth' value="<?=$tenth?>"  style="text-align:right" size='8' maxlength='8' class='input' readonly></td>
							
						  </tr>
						 
						 
						</table>

				</td>
				 
              </tr>
            </table>
         </td>
		 <td width="20%">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		  <tr> 
			<td><img src="../../../image/about/dot.gif" width="9" height="9"> 
			  설계번호</td>
			<td align="right"><input type="text" name="num" value="<?=$num?>" class="input" style="text-align:right" readonly><!--리푸레쉬 할때 현재 값을 불러오기 위해-->
		  </tr>
		</table>
			<table><tr height="10"><td></td></tr></table>
			
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width='14'></td>
				<?if($booking=='1' || $booking=='2'){
					$booking_padding='4';
		  }else{
			  $booking_padding='4';
		  }?>
				<td>
				    <table width="100%" border="0" cellpadding="<?=$booking_padding?>" cellspacing="1" bgcolor="#DBDBDB">
						  <tr bgcolor="#B1D4F0" class="text"> 
							<td height="2" ></td>
							
						  </tr>
						  <tr bgcolor="#EFF4F8" class="text" align="center"><td>
								<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="가상계좌발송"  onFocus='this.blur()'      onClick="virtual_sms(1)">
							  <input type="button" class="btn-b" style="cursor:hand;width:90;"  value="안내문발송"  onFocus='this.blur()'      onClick="virtual_sms_2(1)">
							</td>
						 </tr>
						  <tr bgcolor="#EFF4F8" class="text" align="center"> 
							<td width=><strong>상담이력</strong>
							
								<select name="contract" class="input">
									<option value="13">상담 안함</option>
									<option value='1' <?if($booking==1){echo "selected";}else{echo "";}?>>계약</option>
									<option value='3' <?if($booking==3){echo "selected";}else{echo "";}?>>종결</option>
									<option value='4' <?if($booking==4){echo "selected";}else{echo "";}?>>약속</option>
									<option value='5' <?if($booking==5){echo "selected";}else{echo "";}?>>안받음</option>
									<option value='6' <?if($booking==6){echo "selected";}else{echo "";}?>>없는번호</option>
									<option value="7" <?if($booking==7){echo "selected";}else{echo "";}?>>생각중</option>
									<option value="8" <?if($booking==8){echo "selected";}else{echo "";}?>>산출만</option>
									<option value="9" <?if($booking==9){echo "selected";}else{echo "";}?>>조금있다</option>
									<option value="10" <?if($booking==10){echo "selected";}else{echo "";}?>>팩스</option>
									<option value="11" <?if($booking==11){echo "selected";}else{echo "";}?>>메일</option>
									<option value="12" <?if($booking==12){echo "selected";}else{echo "";}?>>문자</option>
								</select>
								
							
						
							</td>
						  </tr>
						
							<tr bgcolor="#ffcc00">
							 
							 
							  <td><textarea name="content"  cols="30" rows="23"><?=$content?><?=$content1?></textarea></td>
							
							</tr>

							<tr>

								<td bgcolor="#FFFFFF" align="center"> 
								약속
								<? $now_time = date("Y-m-d");
									list($year, $month, $day) = explode("-", $now_time);
									###########################################################
									# 윤년을 생각(나중에 생각 해보자 고객이 월일을 선택함에 따라 리스트 되는것이 다르게 날자가 나오게
									##########################################################
									if($year%400==0 || ($year%4==0 && $year%100!=0))
									{
										if($month==02)
										{
											$il=29;


										}
										elseif($month==1 || $month==3 || $month==5 ||$month==7 ||$month==8 ||$month==10 ||$month==12 )
										{
												$il=31;
										}

										else
										{
												$il=30;
										}
									}
									else
									{
										if($month==02)
										{

												$il=28;


										}
										elseif($month==1 || $month==3 || $month==5 ||$month==7 ||$month==8 ||$month==10 ||$month==12 )
										{
												$il=31;
										}

										else
										{
												$il=30;
										}
									}
									?>
									
									<select name="booking_year" size="1" id="year" class="input">
									<?if($booking_year){?>
									  <option value="<?=$booking_year?>" selected><?=$booking_year?></option>
									 <?}else{?>
									 <option value="<?=$year?>" selected><?=$year?></option>
									 <?}?>
									  <?for($i=2006;$i<2020;$i++){?>
									  <option value="<?=$i?>" ><?=$i?></option>
									  <? } ?>
									</select>
								   
									<select name="booking_month" id="booking_month" class="input">
									<?if($booking_month){?>
									  <option value="<?=$booking_month?>" selected><?=$booking_month?></option>
									 <?}else{?>
									  <option value="<?=$month?>" selected><?=$month?></option>
									  <?}?>
									  <?for($j=1;$j<13;$j++){?>
										 <option value="<?=$j?>"><?=$j?> </option>
									  <? } ?>
									</select>
									
									<select name="booking_day" id="booking_day" class="input">
									<?if($booking_day){?>
									  <option value="<?=$booking_day?>" selected><?=$booking_day?></option>
									 <?}else{?>
									  <option value="<?=$day?>" selected> <?=$day?></option>
									  <?}?>
									  <?for($k=1;$k<$il+1;$k++){?>
									  <option value="<?=$k ?>"> <?=$k?></option><? } ?>
									</select>
									<input type="text" name="booking_time" value="<?=$booking_time?>" size="5" maxlength="5" class="input">
									</td>
									
							</tr>

							
							
						 
						 
						</table>

				</td>
              </tr>
            </table>

		</td>
  </tr>
  <tr>
		
	<td  colspan="2">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
			  <td width="2%"></td>
			  <td width="80%" colspan="2"><?if($bum){?>
				  
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="5%">주소</td>
					<td width="45%"><?=$postnum?><?=$address1?><?=$address2?></td>
					<td width="5%">메일</td>
					<td width="15%"><?=$email?></td>
					<td width="12%">회원가입일</td>
					<td width="18%"><?=$m_wdate?> <?=$ttime?></td>
				  </tr>
				  </table>
				  <?}?>
				<td height="40" align="center" > 
				  
				  <img src='<?=$img_dir?>community/rectify.gif' border=0 onclick=" bogan_sj('./dong_bu_update.php')" align="absmiddle" style="cursor:hand" title="수정 보관"> 
				  <!-- 가입설계 수정 -->
				  <img src='<?=$img_dir?>community/close_1.gif' border=0 onClick="self_close()" align="absmiddle" style="cursor:hand" title="닫기">
				 
				  
				</td>
			   </tr>
		</table>
	 </td>
	</tr>
</table>
</form>