<?if($sele!=3){?><!--업체 화면에서는 sele가 3이므로 노출 안됨-->

<?include "./php/naiinai.php";//보험회사별 나이 표시를 위해 ?>

<table>
	<tr>
		<td colspan='7'>[<?=$company?>]  년령별 보험료</td>
		<td colspan='3'> <?=$Inscompany?>화재 보험료 동일 <input type="checkbox" id="sameCheck" onclick='HyunsamePcheck(<?=$InsuraneCompany?>)'></td>
	</tr>
	<tr>
	 <th>구분</th>
	 <th width='20%'>년령</th>
	 <th width='10%'>년간보험료</th>
	 <th width='10%'>1회차</th>
	 <th width='10%'>2회~8회차</th>
	 <th width='10%'> 9회~10회차</th>
	 <th width='10%'>일반인원</th>
	 <th width='10%'>탁송인원</th>
	 <th width='10%'>대리운전회사</th>
	 <th width='10%'>보험회사</th>
   </tr>
   <? for($_m=0;$_m<4;$_m++){
	 $k=$_m%2;
		if($k==0){
			$cl='O';
		}else{

			$cl='P';
		}?>
   <tr>
     <?if($_m==0){?><td rowspan='4'>일반</td><?}?>
	 <td><?=$Realnai[$_m]?><?=$Realnai2[$_m]?><BR>
	     <?=$nai2[$_m]?><?=$nai[$_m]?>
	 </td>
	 <td><input type='text' class='textare<?=$cl?>' id='B0b<?=$_m?>' onBlur="preminumCheck(this.id,this.value,<?=$_m?>,'1')" /></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B1b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B2b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B3b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B4b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B5b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B6b<?=$_m?>' ></td>
	 <td><input type='text' class='textare<?=$cl?>' id='B7b<?=$_m?>' ></td>

   </tr>

   <?}?>
<!--
   <? for($_m=0;$_m<3;$_m++){?>
   <tr>
	<?if($_m==0){?><td rowspan='3'>탁송</td><?}?>
	 <td><?=$Realnai[$_m]?><?=$Realnai2[$_m]?><BR>
	     <?=$nai2[$_m]?><?=$nai[$_m]?>
	 </td>
	 <td><input type='text' class='textareO' id='C0b<?=$_m?>' onBlur="preminumCheck(this.id,this.value,<?=$_m?>,'2')" onClick="preminum(this.id,this.value)"/></td>
	 <td><input type='text' class='textareO' id='C1b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='C2b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='C3b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='C4b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='C5b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='C6b<?=$_m?>' ></td>
	 <td><input type='text' class='textareO' id='c7b<?=$_m?>' ></td>

   </tr>

   <?}?>-->
   <tr>
	 <td colspan='6'>계</td>

	 <td><input type='text' class='textareO' id='in1won' ></td> <!-- 일반대리기사 합-->
	 <td><input type='text' class='textareO' id='in2won' ></td> <!-- 탁송대리기사 합-->
	 <td><input type='text' class='textareO' id='in3won' ></td>
	 <td><input type='text' class='textareO' id='in4won' ></td>
   </tr>

 </table>
<Br>
 <?}?><!--업체 화면에서는 sele가 3이므로 노출 안됨-->
		<table>
			<tr>
			   <th>기준일</th>
			 <td bgcolor="#ffffff" width='15%'><input type="text"  readonly name="sigi" id="sigi" size='10' maxlength='10' />
			<!-- <img class="calendar" src="../../../../sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].sigi,'yyyy-mm-dd',this)"  />--></td>
			  <th><?=$Bnai[0]?></th>
			  <th><?=$Bnai[1]?></th>
			  <th><?=$Bnai[2]?></th>
			  <th><?=$Bnai[3]?></th>
			</tr>
			<tr>
			  <th colspan='2'>월보험료[일반]</th>
			  <td><input type="text" name="p26p" id="p26p"   class='textareP' ></td>
			  <td><input type="text" name="p35p" id="p35p"   class='textareP' ></td>
			  <td><input type="text" name="p48p" id="p48p"   class='textareP' ></td>
			  <td><input type="text" name="p55p" id="p55p"   class='textareP' ></td>
			</tr>
		<!--	<tr>
			  <th colspan='2'>월보험료[탁송]</th>
			  <td><input type="text" name="p26Ep" id="p26Ep"   class='textareO'></td>
			  <td><input type="text" name="p34Ep" id="p34Ep"   class='textareO'></td>
			  <td><input type="text" name="p48Ep" id="p48Ep"   class='textareO'></td>
			</tr>-->
			<tr>
			<td colspan='5' bgcolor="#ffffff" align='center'><input type="button" class="btn-b" style="cursor:hand;width:90;"  value="월 보험료 입력"  onFocus='this.blur()' onClick="hyungijunDay()"></td>

			</tr>

		</table>


	   <table>
	   <tr>
			
		
			
			
		</tr>

		 <tr bgcolor="#568CB8"  height='30' align='center'>
		    <th width='10%' >일자</th>
			<th width='5%' >경과일수</th>
			<th ><?=$Bnai[0]?></th>
			<th ><?=$Bnai[1]?></th>
			<th ><?=$Bnai[2]?></th>
			<th ><?=$Bnai[3]?></th>
		
			<th width='10%' >일자</th>
			<th width='5%' >경과일수</th>
			<th ><?=$Bnai[0]?></th>
			<th ><?=$Bnai[1]?></th>
			<th ><?=$Bnai[2]?></th>
			<th ><?=$Bnai[3]?></th>
		
			
		</tr>
	
		<?for($p=0;$p<16;$p++){
			$_p2=$p+16;?>
		<tr bgcolor="#ffffff"  height='30'>
			<td align='center'><span id='day<?=$p?>'>&nbsp;</span></td><!--일자-->
			<td align='center'><span id='cday<?=$p?>'>&nbsp;</span></td><!--경과일수-->
			<td align='right'><span id='pday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='qday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='rday<?=$p?>'>&nbsp;</span></td>
			<td align='right'><span id='sday<?=$p?>'>&nbsp;</span></td>
	
			
			<td align='center'><span id='day<?=$_p2?>'>&nbsp;</span></td>
			<td align='center'><span id='cday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='pday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='qday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='rday<?=$_p2?>'>&nbsp;</span></td>
			<td align='right'><span id='sday<?=$_p2?>'>&nbsp;</span></td>

		
		</tr>	

		<?}?>
	   </table>
	 </td>
	 <td>&nbsp; </td>
	</tr>
</table>