<table>
	 <tr>		
		<th width='6%'>보험회사</th>
		<!--<th width='4%'>일/탁</th>-->
		<th width='9%'>나이구분</th>
		<th width='4%'>인원</th>
		<th width='5%'>보험료</th>
		<th width='7%'>계</th>
	    
		<th width='6%'>보험회사</th>
		<!--<th width='4%'>일/탁</th>-->
		<th width='9%'>나이구분</th>
		<th width='4%'>인원</th>
		<th width='5%'>보험료</th>
		<th width='7%'>계</th>

		<th colspan='4'>
		보험료 합계<input type="checkbox" id="sumT" onClick='sumTotal()'>문자</th>
		
	 </tr>
	<? for($_k=1;$_k<7;$_k++){$_a=2*$_k-1; $_q=$_a+1?>
	 <?for($_m=1;$_m<9;$_m++){?>
	  <tr>
		
		<td><span id='F1b<?=$_a?><?=$_m?>'  />&nbsp;</span></td>
	<!--	<td><span id='F2b<?=$_a?><?=$_m?>'  />&nbsp;</span></td>-->
		<td><span id='F3b<?=$_a?><?=$_m?>'  />&nbsp;</span></td><!--나이구분-->
		<td><span id='F4b<?=$_a?><?=$_m?>'  />&nbsp;</span></td><!--인원-->
		<td><span id='F5b<?=$_a?><?=$_m?>'  />&nbsp;</span></td><!--월보험료-->
		<td><span id='F6b<?=$_a?><?=$_m?>'  />&nbsp;</span></td><!--인원x월보험료-->


		<td><span id='F1b<?=$_q?><?=$_m?>'  />&nbsp;</span></td>
	<!--	<td><span id='F2b<?=$_q?><?=$_m?>'  />&nbsp;</span></td>-->
		<td><span id='F3b<?=$_q?><?=$_m?>'  />&nbsp;</span></td><!--나이구분-->
		<td><span id='F4b<?=$_q?><?=$_m?>'  />&nbsp;</span></td><!--인원-->
		<td><span id='F5b<?=$_q?><?=$_m?>'  />&nbsp;</span></td><!--월보험료-->
		<td><span id='F6b<?=$_q?><?=$_m?>'  />&nbsp;</span></td><!--인원x월보험료-->

		

	 </tr>
	
	 <?}?>


	 
	  <tr>	
		<td colspan='2'>계 <input type="checkbox" id="ssPcheck<?=$_a?>" onClick='ssCheckP(<?=$_a?>)'></td>
		<td><span id='TotInwon<?=$_a?>' />&nbsp;</span>
			<input type='hidden' id='TotInwonM<?=$_a?>'  />
		</td>
		<td>&nbsp;</td>
		<td><span id='TotPreminum<?=$_a?>' />&nbsp;</span>
			<input type='hidden' id='TotPreminumM<?=$_a?>'  />
		</td>

		<td colspan='2'>계 <input type="checkbox" id="ssPcheck<?=$_q?>" onClick='ssCheckP(<?=$_q?>)'></td>
		<td><span id='TotInwon<?=$_q?>' />&nbsp;</span>
			<input type='hidden' id='TotInwonM<?=$_q?>'  />
		</td>
		<td>&nbsp;</td>
		<td><span id='TotPreminum<?=$_q?>' />&nbsp;</span>
		   <input type='hidden' id='TotPreminumM<?=$_q?>'  />
		 </td>
   <?}?>
		

	 </tr> 

	</table>


	
			