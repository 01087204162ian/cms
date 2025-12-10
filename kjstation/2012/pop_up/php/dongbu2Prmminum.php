
<?
include "./php/coNai.php";
//현재일 기준 만 연령에 해당하는 년도를 구하기 위해?>
<table>
	<tr>
		<td colspan='5'>최초 계약일 <?=$start?>기준 [<?=$company?>]  년령별 보험료</td>
		<td colspan='3'>보험료 적용 기준일<input type='text' name="psigi" id="psigi" value='<?=$start?>'  size='10' maxlength='10' class='input' readonly ><img src="../../sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].psigi,'yyyy-mm-dd',this)"></td>
	</tr>
	<tr><th ><img src="../../../../image/about/dot.gif" width="9" height="9"> 보험료 산출</th>
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
	  <td>
		<select id="jagibudam_3" name="jagibudam_3"  class="input" onclick="clear_text_3()";>
			 <option value=1>20만/20만</option>
			 <option value=2>30만/30만</option>
		   </select>
	 </td>
	<td colspan='2'  align="right" > <img src='../../../../image/product/premium.gif' border=0 onclick="Dongbuserch()" align="absmiddle" style="cursor:hand"></td>
	</tr>

	
	<tr>
	 <th width='20%' rowspan='2'>년령</td>
	 <th width='9%' colspan='2'>년간보험료</th>
	 <th width='9%' colspan='2'>탁송년보험료</th>
	 <th width='9%' rowspan='2'>일반인원</th>
	<!-- <td width='9%' rowspan='2'>탁송인원</th>-->
	  <th width='9%' rowspan='2'>보험회사</th>
	 <th width='9%' rowspan='2'>대리회사</th>
   </tr>
   <tr bgcolor="#568CB8"  height='30' align='center'>
	
	 <td width='9%'>년보험료</td>
	 <td width='9%'>[1/12]</td>
	 <td width='9%'>년보험료</td>
	 <td width='9%'>[1/12]</td>
	
   </tr>	
	<? for($i=0;$i<6;$i++){
		$k=$i*2;
		$m=$k+1;?>
	<tr bgcolor="#ffffff"   >
    <td align='center'><?=$bnai[$i]?><br><?echo "[ $nai[$m] ~ $nai[$k]   ]";?></td>
	<td align='right'><span id='totalP<?=$i?>' >&nbsp;</span></td>
	<td align='right'><span id='h12P<?=$i?>' >&nbsp;</span></td>
	<td align='right'><span id='etotalP<?=$i?>' >&nbsp;</span></td>
	<td align='right'><span id='e12P<?=$i?>' >&nbsp;</span></td>		
	<td align='right'> <span id='znai<?=$i?>' >&nbsp;</span></td>
<!--	<td align='right'> <span id='eznai<?=$i?>' >&nbsp;</span></td>-->
	<td align='right'> &nbsp;</td>
	<td align='right'><span id='h4P<?=$i?>' >&nbsp;</span></td>	
	
   </tr>
	<?}?>

	<tr bgcolor="#ffffff"   >
    <td align='center' colspan='5'>계</td>
		
	<td align='right'> <span id='znaiTotal' >&nbsp;</span></td>
<!--	<td align='right'> <span id='eznaiTotal' >&nbsp;</span></td>-->
	<td align='right'> <span id='inComTotal' >&nbsp;</span></td>
	<td align='right'><span id='einComTotal' >&nbsp;</span></td>	
	
	</tr>
 </table>

 
		<table >
		<tr>
		 <td >12개월로 나누어 받을 경우에 기준일 및 월 보험료 및 경과일수 보험료
		 <span id='ks_dong'>&nbsp;</span>
		 </td>
		
		</tr>
	   </table>

		<table>
			<tr>
			   <th  width='10%'>기준일</th>
			 <td width='15%'>
			<input type='text' name="sigi" id="sigi"  size='10' maxlength='10' class='input' readonly >
			<!--<img src="../../../../sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].sigi,'yyyy-mm-dd',this)">-->
			</td>
				<? for($i=0;$i<6;$i++){?>
			  <th><?=$bnai[$i]?></th>
			  <?}?>
			</tr>
			<tr>
			  <th colspan='2'>월보험료[일반]</th>
			  <? for($i=0;$i<6;$i++){?>
			  <td  align='center'><input type="text" name="giPrem<?=$i?>" id="giPrem<?=$i?>"   class='textareP'></td>
			  <?}?>
			  
			</tr>
		<!--	<tr height='30'>
			  <th colspan='2'>월보험료[탁송]</th>
			  <? for($i=0;$i<6;$i++){?>
			  <td  align='center'><input type="text" name="gi2Prem<?=$i?>" id="gi2Prem<?=$i?>"   class='textareO'></td>
			  <?}?>
			</tr>-->
			<tr>
			<td colspan='8'><input type="button" class="btn-b" style="cursor:hand;width:90;"  value="월보험료  입력"  onFocus='this.blur()' onClick="gijunDay(<?=$CertiTableNum?>)"></td>

			</tr>

		</table>


	   <table>
	   <tr>
			<th width='10%' >일자</th>
			<th width='4%'>경과일수</th>
			<? for($i=0;$i<6;$i++){?>
			  <th><?=$bnai[$i]?></th>
			  <?}?>
			  <th width='10%' >일자</th>
			<th width='4%'>경과일수</th>
			  <? for($i=0;$i<6;$i++){?>
			 <th><?=$bnai[$i]?></th>
			  <?}?>
				
			<!--<th width='%' colspan='6'>탁송보험료</th>-->
		</tr>

		 
	
		<?for($p=0;$p<16;$p++){
			$_p2=$p+16;?>
		<tr>
			<td align='center'><span id='day<?=$p?>'>&nbsp;</span></td><!--일자-->
			<td align='center'><span id='cday<?=$p?>'>&nbsp;</span></td><!--경과일수-->
			<td align='right'><span id='pday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='qday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='rday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='sday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='tday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='uday<?=$p?>'>&nbsp;</span></td>
			<td align='center'><span id='day<?=$_p2?>'>&nbsp;</span></td><!--일자-->
			<td align='center'><span id='cday<?=$_p2?>'>&nbsp;</span></td><!--경과일수-->
			<td align='right'><span id='pday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='qday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='rday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='sday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='tday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='uday<?=$_p2?>'>&nbsp;</span></td>

		</tr>	

		<?}?>
	   </table>
	 </td>
	 <td>&nbsp; </td>
	</tr>
</table>
	

			
			
