<?switch($InsuraneCompany){
	case 1 :
		$insCom='흥국';
		break;
	case 2 :
		$insCom='DB';
		break;
	case 3 :
		$insCom='KB';
		break;
	case 4 :
		$insCom='현대';
		break;
	case 5 :
		$insCom='Lig';
		break;
	case 6 :
		$insCom='Lig';
		break;
	case 7 :
		$insCom='MG';
		break;
	case 8 :
		$insCom='삼성';
		break;
	
}?>

<table>
<tr>
   <td colspan='3'><span id='A2a'  />&nbsp;</span></td>
   <td>[<span id='A1a'  />&nbsp;</span>]</td>
   <td colspan='6'>[최초 거래일<span id='A3a'  />&nbsp;</span>] 
   <? if($InsuraneCompany==2){?><input type='button' id='a32' class='btn-b' value='동부화재 보험료 '  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="dongbuOpen(<?=$DaeriCompanyNum?>,<?=$CertiTableNum?>,<?=$InsuraneCompany?>)"> <?}?>
	<?// if($InsuraneCompany==7){?>
	<? if($InsuraneCompany==7){?>
	<input type='button' id='a32' class='btn-b' value='<?=$insCom?>증권입력 '  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="mgOpen(<?=$DaeriCompanyNum?>,<?=$CertiTableNum?>,<?=$InsuraneCompany?>)"> 

	<?}?>
	<input type='button' id='a32' class='btn-b' value='<?=$insCom?>증권 정리 '  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="mg2Open(<?=$DaeriCompanyNum?>,<?=$CertiTableNum?>,<?=$InsuraneCompany?>)"> 
	
	<select id='moValue' name='moValue' onchange='moChange(<?=$CertiTableNum?>,this.value)' class='selectbox'>
		<option value='9999'>==선택==</option>
		<option value='1' > 모계약</option>
		<option value='2' > 모계약해지</option>
	
   </select>
	<? if($InsuraneCompany==7){?>
	<select id='moRate' name='moRate' onchange='moRateChange(<?=$CertiTableNum?>,this.value)' class='selectbox'>
		<option value='9999'>선택</option>
		<option value='1'>100%</option>
		<option value='0.95' > 95%</option>
		<option value='0.9' > 90%</option>
		<option value='0.87' > 87%</option>
		<option value='0.85' > 85%</option>
	
   </select>
	<?}?>
   </td>
  </tr>
  <tr>
   <td rowspan='2'>순서</td>
   <td colspan='2'>년령</td>
   <td rowspan='2'>년보험료</td>
   <td rowspan='2'>1회차</td>
   <td rowspan='2'>2~8회차</td>
   <td rowspan='2'>9~10회차</td>
   <td rowspan='2'>[1/12]</td>
   <td rowspan='2'>월보험료</td>
   <td rowspan='2'>일일보험료</td>
  </tr>
 <tr>
   <td>시작</td>
   <td>종료</td>
   
  </tr>
  <? for($_m=0;$_m<8;$_m++){
	 $k=$_m%2;
		if($k==0){
			$cl='P';
		}else{

			$cl='O';
		}?>
   <tr>
	 <td><?=$_m+1?>
	 <td><input type='text' class='textare<?=$cl?>' id='B0b<?=$_m?>' /></td><!--나이시작-->
	 <td><input type='text' class='textare<?=$cl?>' id='B1b<?=$_m?>' onBlur="naiCheck(this.id,this.value,<?=$_m?>,'1')" ></td><!--나이끝-->
	 <td><input type='text' class='textare<?=$cl?>' id='B2b<?=$_m?>' onBlur="yearCheck(this.id,this.value,<?=$_m?>,'1')" ></td><!--년간-->
	 <td><input type='text' class='textare<?=$cl?>' id='B3b<?=$_m?>' readonly></td><!--1회차-->
	 <td><input type='text' class='textare<?=$cl?>' id='B4b<?=$_m?>' readonly></td><!--1회차-->
	 <td><input type='text' class='textare<?=$cl?>' id='B5b<?=$_m?>' readonly></td><!--9회차-->
	 <td><input type='text' class='textare<?=$cl?>' id='B10b<?=$_m?>' readonly></td><!--[1/12]-->
	 <td><input type='text' class='textare<?=$cl?>' id='B6b<?=$_m?>' onBlur="monthCheck(this.id,this.value,<?=$_m?>,'1')"></td><!--월보험료-->
	 <td><input type='text' class='textare<?=$cl?>' id='B7b<?=$_m?>' onBlur="dailyCheck(this.id,this.value,<?=$_m?>,'1')"></td><!--일일보험료-->

   </tr>

   <?}?>
 </table>

 <table>
	

		 <tr bgcolor="#568CB8"  height='30' align='center'>
		    <th width='10%' >일자</th>
			<th width='5%' >경과일수</th>
			<th width='10%'><span id='C0c'  />&nbsp;</span></th>
			<th width='10%'><span id='C1c'  />&nbsp;</span></th>
			<th width='10%'><span id='C2c'  />&nbsp;</span></th>
			<th width='10%'><span id='C3c'  />&nbsp;</span></th>
			<th width='10%'><span id='C4c'  />&nbsp;</span></th>
			<th width='10%'><span id='C5c'  />&nbsp;</span></th>
			<th width='10%'><span id='C6c'  />&nbsp;</span></th>
			<th width='10%'><span id='C7c'  />&nbsp;</span></th>		
		</tr>
	
		<?for($p=0;$p<32;$p++){?>
		<tr bgcolor="#ffffff"  height='30'>
			<?for($_k=0;$_k<10;$_k++){?>
			<td align='center'><span id='da<?=$_k?>y<?=$p?>'>&nbsp;</span></td><!--일자-->
			<?}?>
		</tr>	

		<?}?>
	   </table>
   
