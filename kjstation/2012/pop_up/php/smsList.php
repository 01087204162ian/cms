<table>
	<tr>
		<td width='5%'>번호</td>
		<td width='20%'>발송일</td>
		<td>메세지</td>
		<td width='10%'>회사</td>
		<td width='10%'>결과</td>
	</tr>
  <? for($j=0;$j<10;$j++){?>
	<tr>
		<td><input type='hidden' id='C0b<?=$j?>' /><span id='C1b<?=$j?>'  />&nbsp;</span></td>
		<td><span id='C2b<?=$j?>'  />&nbsp;</span></td>
		<td><span id='C3b<?=$j?>'  />&nbsp;</span></td>
		<td><span id='C4b<?=$j?>'  />&nbsp;</span></td>
		<td><span id='C5b<?=$j?>'  />&nbsp;</span></td>
	</tr>


  <?}?>
 </table>