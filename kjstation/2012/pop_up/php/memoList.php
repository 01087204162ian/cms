<table>
	<tr>
	 <td><select id='memokind' class='input_2'>
		<option value='1'>::일반메모::</option>
		<option value='2'>결재관련</option>
		<option value='3'>가상</option>
		<option value='4'>환급</option>
		</select>
	 </td>
	 <td width='5%'>순번</td>
	 <td width='10%'>날자</td>
	 <td width='5%'>종류</td>
	 <td width='40%'>내용</td>
   </tr>
   <? for($_m=0;$_m<10;$_m++){
	   $k=$_m%2;
		if($k==0){
			$cl='P';
		}else{

			$cl='O';
		}
		 ?>

   <tr>
		<?
			switch($_m){
					case 0:?>
					<td>	<input type='hidden' id='memoNum' />
						 <input type='text' id='m1_<?=$_m?>' class='textareM' />
					</td>
					<?	break;
					case 1 :?>
					<td>
					<input type='button' id='m1_<?=$_m?>'class='btn-b' value='메모저장'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="memoStore()">
					</td>
					<?	break;
					case 2:?>
					<td rowspan='7'>
						<span id='m1_<?=$_m?>' class='textareM'>&nbsp;</span>
					</td>
					<?
						break;
					
					default:?>
					
					<?
						break;
			}?>
       
			
	  
	 <td><span id='m2_<?=$_m?>' class='textare<?=$cl?>'>&nbsp;</span></td><!--메모순서-->
	 <td><span id='m3_<?=$_m?>' class='textare<?=$cl?>'>&nbsp;</span></td><!--메모날자-->
	 <td><span id='m4_<?=$_m?>' class='textare<?=$cl?>'>&nbsp;</span></td><!--메모종류-->
	 <td><span id='m5_<?=$_m?>' class='textare<?=$cl?>'>&nbsp;</span><!--메모내용-->
	      <input type='hidden' id='m6_<?=$_m?>' />
	 </td>
   </tr>
   <?}?>
  


</table>