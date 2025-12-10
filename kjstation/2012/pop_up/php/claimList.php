<? if($eRow[InsuranceCompany]==2||$eRow[InsuranceCompany]==8){//동부화재는 증권번호와 상품설명서를 입력하여야 하므로
		   $col=16;
		}else{
		  $col=11;
		}
	?>
<table>

	 <tr>
		<td colspan='<?=$col?>'>배서기준일&nbsp;<span id='endorseDay' />&nbsp;</span>
			&nbsp;<input type='button' id='a32' class='btn-b' value='일일보험료'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="dailyPreminum()">
			
		</td>

	 </tr>
	 <tr>
		<th width='4%'>순번</th>
		<th width='10%'>성명</th>
		<th width='13%'>주민번호</th>
		<th width='5%'>일/탁</th>
		<th width='9%'>상태</th>
		<th width='7%'>보험료</th>
		<? if($eRow[InsuranceCompany]==2){?>
		<th>시기</th>
		<th>종기</th>
		<th>회차</th>
		<th colspan='2'>증권번호</th>
		<th colspan='2'>설계번호</th>
		<?}else  if($eRow[InsuranceCompany]==8){?>
		<th>시기</th>
		<th>종기</th>
		<th>회차</th>
		<th colspan='2'>증권번호</th>
		<th colspan='2'>설계번호</th>
		<?}else{?>
		
		
		<th width='10%'>증권번호</th>	
		<?}?>
		<th width='5%'>처리유무</th>
	
		<th width='8%'>c_Preminum</th>
	 </tr>
	 <?for($_m=0;$_m<1;$_m++){?>
	  <tr>	
		<td><input type='hidden' id='B8b<?=$_m?>' /><input type='hidden' id='B0b<?=$_m?>' /><span id='B1b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B2b<?=$_m?>'  />&nbsp;</span></td><!--성명-->
		<td><span id='B3b<?=$_m?>'  />&nbsp;</span> </td><!--주민번호-->
		<td><span id='B4b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B5b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B6b<?=$_m?>'  />&nbsp;</span></td><!--보험료 -->	
		<? if($eRow[InsuranceCompany]==2){?>
			<td><span id='B13b<?=$_m?>' ></span></td><!--보험시기-->
			<td><span id='B14b<?=$_m?>' ></span></td><!--보험종기-->
			<td><span id='B15b<?=$_m?>' ></span></td><!-- 회차-->
			<td><span id='B9b<?=$_m?>' ></span></td><td><span id='B10b<?=$_m?>' /></span></td>
			<td><span id='B11b<?=$_m?>' ></span></td><td><span id='B12b<?=$_m?>' /></span></td>	
		<?}else  if($eRow[InsuranceCompany]==8){?>
			<td><span id='B13b<?=$_m?>' ></span></td><!--보험시기-->
			<td><span id='B14b<?=$_m?>' ></span></td><!--보험종기-->
			<td><span id='B15b<?=$_m?>' ></span></td><!-- 회차-->
			<td><span id='B9b<?=$_m?>' ></span></td><td><span id='B10b<?=$_m?>' /></span></td>
			<td><span id='B11b<?=$_m?>' ></span></td><td><span id='B12b<?=$_m?>' /></span></td>	
		<?}else{?>
		
			<td><span id='B10b<?=$_m?>' /></span></td>
			
		<?}?>
		<td><span id='B7b<?=$_m?>'  /></span></td><!--처리 미처치-->
		
		<td><span id='B17b<?=$_m?>'  /></span></td><!--보험회사에 내는 보험료-->
	 </tr>
	 <?}?>
	 

	</table>


	<table>

		<tr>
			<td width='10%'>사고 일자</td>
			<td>사고 내용</td>
			<td width='10%'></td>
		</tr>

		<tr>
			<td> <input type="text" id='endorseDay2' value="<?=$now_time?>" readonly name="endorseDay"  size='10' maxlength='10' /><img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].endorseDay2,'yyyy-mm-dd',this)"  /></td>
			<td><textarea name="comment2" id="comment2" cols="14" rows="3" style="border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:99%;word-break:break-all;background-color:#ffffff;padding:5 5 5 5;margin-bottom: 2px;" ></textarea></td>
			<td><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="사고기록"  onFocus='this.blur()' onClick="gilo()"></td>
		</tr>

	</table>

<input type='hidden' id='page' />
	
			