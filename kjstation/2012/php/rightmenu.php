<div id="secondary">


	
	 

	 <table border="0" cellpadding="0" cellspacing="0" >
	  
		<tr> 
		  <td><img src="/kibs_admin/ask/image/phone_top.gif" width="163"></td>
		</tr>
		<tr> 
		  <td> 
			  <table width="163" border="0" cellspacing="0" cellpadding="0">
				<tr> 
				  <td width="27" background="/kibs_admin/ask/image/lcd_left.gif"></td>
				  <td width="109"><textarea name="comment" id="comment" cols="15" rows="7" style="border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:100%;word-break:break-all;background-color:#0FB9C4;padding:5 5 5 5;" onKeyUp="updateChar(100)"></textarea></td>
				  <td width="27" background="/kibs_admin/ask/image/lcd_right.gif"></td>
				</tr>
			  </table>
			</td>
		</tr>
    
		<tr align="center"> 
		  <td height="13"><table width="163" border="0" align="left" cellpadding="0" cellspacing="0">
			  <tr> 
				<td height="25" background="/kibs_admin/ask/image/byte.gif"><div align="center"><span id="textlimit">0</span> /최대 
			80byte</div></td>
			  </tr>
			</table></td>
		</tr>

		 <tr align="center">
		  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr> 
				<td><img src="/kibs_admin/ask/image/phone_number.gif" width="163" height="39"></td>
			  </tr>
			<? for($_h_=1;$_h_<11;$_h_++){?>
				<input type='hidden' name="<? echo "num$_h_"; ?>">
			  <tr>
				<td><table width="163" height="20" border="0" cellpadding="0" cellspacing="0">
					<tr> 
					  <td width="13" background="/kibs_admin/ask/image/line_left.gif"></td>
					  <td width="33" bgcolor="#dedede" align='center'> <?=$_h_?></td>
					  <td bgcolor="#dedede">
						  <input type="text" id="bun<?=$_h_?>" name="bun<?=$_h_?>" size="13" maxlength="13" class="input" 
						  onBlur='buncheck(<?=$_h_?>)' onClick='buncheck_2(<?=$_h_?>)'>
					</td>
					  <td width="13" background="/kibs_admin/ask/image/line_right.gif"></td>
					</tr>
				  </table>
				 </td>
			  </tr>
			  
			<?}?>
			  </table>
			</td>
		</tr>
		
    <!--for 문 종료-->

   <?if($userid=='kibs0327'){
		$company_tel='070-7841-5962';
   }else{
	  $company_tel='070-7841-5962';
   }?>
          <tr> 
            <td><table width="163" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="13" background="/kibs_admin/ask/image/line_left.gif"></td>
                  <td width="34" bgcolor="#dedede" align='center'>발신</td>
                  <td bgcolor="#dedede">
                      <input type='text' id='our_phone' name='our_phone' value="<?=$company_tel?>" size='13' maxlength='13' class='input'  onFocus="clearText(this)" onBlur='our_check()' onClick='our_check_2()'>
                    </td>
                  <td width="13" background="/kibs_admin/ask/image/line_right.gif"></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td><img src='/kibs_admin/ask/image/send.gif' width="81" height="67" align='absmiddle' onClick="smsGo()" onfocus='blur()'><img src='/kibs_admin/ask/image/reset.gif' width="82" height="67" align='absmiddle' onClick="sms_clear()" onfocus='blur()'></td>
                </tr>
              </table></td>
          </tr>
  </table>
</div>