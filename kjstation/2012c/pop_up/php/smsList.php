<table>
	<tr>
		<th width='5%'>번호</th>
		<th width='20%'>발송일</th>
		<th>메세지</th>
		<th width='10%'>회사</th>
		<th width='10%'>결과</th>
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