<table>
	<tr><td><input type='text' class='phone' id='checkPhone'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/><input type="checkbox" id="preminum" name="preminum" value='1' checked>보험료</td>
	</tr>
	<tr>
	  <td><textarea name="comment" id="comment" cols="14" rows="3" style="border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:99%;word-break:break-all;background-color:#ffffff;padding:5 5 5 5;margin-bottom: 2px;" onKeyUp="updateChar(100)"></textarea><div align="center"><span id="textlimit">0</span> /최대 80byte</div></td>
	</tr>
  
    <tr><td>&nbsp;<input type='button' id='a32' class='btn-b' value='문자보내기'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="smsGo()"></td>
    </tr>
 </table>