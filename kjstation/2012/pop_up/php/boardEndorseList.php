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
			&nbsp;<input type='button' id='a32' class='btn-b' value='배서프린트'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="EndorsePrint()">
			<?if($Dnum){?>
			&nbsp;<input type='button' id='a32' class='btn-b' value='상품설명서'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="SanPrint()">
			<?}?>
			&nbsp;<input type='button' id='a32' class='btn-b' value='배서기준일변경'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="changeEndorseDay()">
			<? if($eRow[InsuranceCompany]==3){?>
			배서번호 <input type="text" id="enNum" name='enNum' value="<?=$eRow[enNum]?>" class="input1" size="13" maxlength="13" style="text-align:center" onClick='enIn()' onBlur='enInput()'>
		<?}?>
		</td>

	 </tr>
	 <tr>
		<th width='4%'>순번</th>
		<th width='8%'>성명</th>
		<th width='11%'>주민번호</th>
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
		
		<th width='3%'>&nbsp;</td>
		<th width='10%'>증권번호</th>	
		<?}?>
		<th width='5%'>처리유무</th>
		<th width='8%'>변경</th>
		<th width='8%'>c_Preminum</th>
	 </tr>
	 <?for($_m=0;$_m<9;$_m++){?>
	  <tr>	
		<td>
			<input type='hidden' id='B20b<?=$_m?>'><!--1이면 개인별 요율을 묻는다-->
		    <input type='hidden' id='B21b<?=$_m?>'><!--개인별 rate-->
			<input type='hidden' id='B8b<?=$_m?>' />
			<input type='hidden' id='B0b<?=$_m?>' />
			<span id='B1b<?=$_m?>'  />&nbsp;</span>
		</td>
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
		
			<td><span id='B9b<?=$_m?>' ></span></td><td><span id='B10b<?=$_m?>' /></span></td>
			
		<?}?>
		<td><span id='B7b<?=$_m?>'  /></span></td><!--처리 미처치-->
		<td><span id='B16b<?=$_m?>'  /></span></td><!--변경-->
		<td><span id='B17b<?=$_m?>'  /></span></td><!--보험회사에 내는 보험료-->
	 </tr>
	 <?}?>
	 <tr><td colspan='<?=$col?>'>
	    <div id="paging"><span id='sql2'>&nbsp;</span><span id='sql3'>&nbsp;</span></div>
	    </td>
	 </tr>
	</table>

<input type='hidden' id='page' />
	
			