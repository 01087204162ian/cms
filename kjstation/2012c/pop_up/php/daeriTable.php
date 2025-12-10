<table>
	 <tr>
		<th width='15%' colspan='2'>대리운전회사</th>
			<th width='10%'>성명</th>
			<th width='15%' colspan='2'>주민번호</th>
			<th width='15%'>핸드폰번호</th>
			<th width='15%'>전화번호</th>
			<th width='10%' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="persolnal(<?=$num?>)">담당자</th>
			<th width='10%' onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onClick="divid(<?=$num?>)">결제방식</th>					
		 </tr>
		 <tr>
			
			<td width='10%' colspan='2'><input type='text' class='textareO' id='a1'  /></td>
			<td width='10%'><input type='text' class='textareO' id='a2'  /></td>
			<td width='10%' colspan='2'><input type='text' class='textareO' id='a3'  onBlur="jumiN_check(this.id,this.value)" onClick="jumiN_check_2(this.id,this.value)" /></td>
			<td width='10%'><input type='text' class='textareO' id='a4'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
			<td width='10%'><input type='text' class='textareO' id='a5'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/></td>
			<td width='10%'><input type='text' class='textareO' id='a6'  /></td>
			<td width='10%'><input type='text' class='textareO' id='a7'  /></td>
		 </tr>
		 <tr>
			<th width='10%' colspan='2'>보험회사</th>
			<th width='10%' colspan='2'>증권번호</th>
			<th width='10%' colspan='2'>보험기간</th>
			<th width='10%'></th>
			<th width='10%'></th>
			<th width='10%'></th>
							
		 </tr>
		 <tr>
			<td width='10%' colspan='2'><input type='text' class='textareO' id='a8' /><input type='hidden' class='textareO' id='a14'  /></td>
			<td width='10%' colspan='2'><input type='text' class='textareO' id='a9'  /></td>
			<td width='10%' colspan='2'><input type='text' class='textareO' id='a10'  /></td>
			<td width='10%'><input type='text' class='textareO' id='a11'  /></td>
			<td width='10%'><input type='text' class='textareO' id='a12'  /></td>
			<td width='10%'><input type='text' class='textareO' id='a13'  /></td>
			
		 </tr>
	</table>