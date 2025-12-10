

<table>
	<? if($eRow[InsuranceCompany]==2){//동부화재는 증권번호와 상품설명서를 입력하여야 하므로
		   $col=12;
		}else{
		  $col=7;
		}
	?>
	
	 <tr>
		<th width='3%'>순번</th>
		<th width='8%'>성명</th>
		<th width='13%'>주민번호</th>
		<th width='5%'>일/탁</th>
		<th width='10%'>상태</th>
		<th width='8%'>보험료</th>
		
		<? if($eRow[InsuranceCompany]==2){?>
		<th>시기</th>
		<th>종기</th>
		<th>회차</th>
		<th>증권번호</th>
		<th>설계번호</th>
		<?}?>
		<th width='5%'>처리유무</th>
		
	 </tr>
	 <?for($_m=0;$_m<$e_count;$_m++){?>
	  <tr>
		
		<td><input type='hidden' id='B0b<?=$_m?>' /><span id='B1b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B2b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B3b<?=$_m?>'  />&nbsp;</span> </td>
		<td><span id='B4b<?=$_m?>'  />&nbsp;</span></td>
		<td><span id='B5b<?=$_m?>'  />&nbsp;</span></td>
		<td><input type='hidden' id='B8b<?=$_m?>' /><span id='B6b<?=$_m?>'  />&nbsp;</span></td>
		
		<? if($eRow[InsuranceCompany]==2){?>
		<td><span id='B13b<?=$_m?>' ></span></td><!--보험시기-->
		<td><span id='B14b<?=$_m?>' ></span></td><!--보험종기-->
		<td><span id='B15b<?=$_m?>' ></span></td><!-- 회차-->
		<td><span id='B9b<?=$_m?>' ></span><span id='B10b<?=$_m?>' />&nbsp;</span></td>
		<td><span id='B11b<?=$_m?>' ></span><span id='B12b<?=$_m?>' />&nbsp;</span></td>
		
		<?}?>
		<td><span id='B7b<?=$_m?>'  />&nbsp;</span></td>
	 </tr>

	 <?}?>
	 

	</table>


	
			