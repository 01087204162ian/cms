<table width="95%" border="0" cellspacing="0" cellpadding="3" align='center'>
	   <tr>
	     <td><img src="../../../image/about/dot.gif" width="9" height="9"> 보험료 산출</td>
		 <td> <input type="text" value="<?=$now_time?>" readonly name="sigi" id="sigi" size='10' maxlength='10' />
		  <img class="calendar" src="../../../sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].sigi,'yyyy-mm-dd',this)"  /></td>
				<td  align="right"  bgcolor="#FFFFFF"> <img src='../../../image/product/premium.gif' border=0 onclick="serch()" align="absmiddle" style="cursor:hand"></td></td>
	   </tr>
     </table>

   <table width="95%" border="0" cellpadding="3" cellspacing="1" bgcolor="#cccccc" align='center'>
     <tr bgcolor="#FF9300" height="3" align="center" height='30'> 
		<td width='5%' rowspan='2'><font color='#ffffff'>순번</font></td>
		<td width='15%' rowspan='2'><font color='#ffffff'>연령</font></td>
		<td width=''><font color='#ffffff'>대인</font></td>
		<td width=''><font color='#ffffff'>대물</font></td>
		<td width=''><font color='#ffffff'>자손</font></td>
		<td width='' ><font color='#ffffff'>부담금 차량</font></td>
	
		<td width='' rowspan='2'><font color='#ffffff'>년간보험료</font></td>
		<td width='' ><font color='#ffffff'>할인율</font></td>
		<td width='' ><font color='#ffffff'>[1/12]</font></td>
		<td width='' ><font color='#ffffff'>할인율</font></td>
		<td width='' ><font color='#ffffff'>[1/12]</font></td>

		
	</tr> 
	<tr bgcolor="#ffffff" height="3" align="center" height='30'>

	  <td> <select id="daein_3" name="daein_3" class="input" onclick="clear_text_3()"; >
									
				<OPTION >대인 선택</option>
				<OPTION value="2">초과무한</option>
				<OPTION value="1">포함무한</option>
				
			</select>
	  </td>
	   <td> <select id="daemool_3" name="daemool_3" class="input" onclick="clear_text_3()";>
				<OPTION >대물 선택</option>
				<OPTION value="1">3천만원</option>
				<OPTION value="2">5천만원</option>
				<OPTION value="3">1억</option>
			</select>
	  </td>

	   <td> <select id="jason_3" name="jason_3" class="input" onclick="clear_text_3()";>
				 <OPTION value="8">자손 선택</option>
			<!-- <OPTION value="1">1천5백만원</option>-->
				 <OPTION value="1">3천만원</option>
				 <OPTION value="2">5천만원</option>
				 <OPTION value="3">1억</option>
			<!--	 <OPTION value="8">가입안함</option>-->
				</select>
	  </td>
	  <td>
		<select id="jagibudam_3" name="jagibudam_3"  class="input" onclick="clear_text_3()";>
			 <option value=1>20만/20만</option>
			 <option value=2>30만/30만</option>
		   </select>
			<br>
			 <select id="char_3" name="char_3"  class="input" onclick="clear_text_3()";>
			   <OPTION value="9999">차량 선택</option>
			   <option value="1">1천만원</option>
			   <option value="2">2천만원</option>
			   <option value="3">3천만원</option>
			<!--    <option value="5">5천만원</option>
				<option value="6">1억원</option>
			   <option value="9999">가입안함</option>-->
		   </select>
	  </td>
	  <td colspan='2'>
			<select id='h1' class='selectbox' onChange='ph1()' >
				<option value='0.9'>10%</option>
				<option value='0.8'>20%</option>
				<option value='0.7'>30%</option>
			</select>
	  </td>
	  <td colspan='2'>
			<select id='h2' class='selectbox' onChange='ph2()' >
				<option value='0.8'>20%</option>
				<option value='0.75'>25%</option>
				<option value='0.65'>35%</option>
			</select>
	  </td>
	