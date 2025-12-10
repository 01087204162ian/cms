<? if($eNum){
		$val=1;//배서에서 문자 보낼때

}else{
		$val=2;//월별로 보험료 안내문자 보낼때

}?>
<table>
	<tr><td>
	<input type='text' class='phone' id='checkPhone'  onBlur="con_phone1_check(this.id,this.value)" onClick="con_phone1_check_2(this.id,this.value)"/><input type="checkbox" id="preminum" name="preminum" value='1' checked>보험료</td>
	</tr>
	<tr>
	  <td><textarea name="comment" id="comment" cols="14" rows="2" style="border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:99%;word-break:break-all;background-color:#ffffff;padding:5 5 5 5;margin-bottom: 2px;" onKeyUp="updateChar(100)"></textarea><div align="center"><span id="textlimit">0</span> /최대 80byte</div></td>
	</tr>
  
    <tr>
		<td><!--<img src="/kibs_admin/cargo/image/excell.gif" align='absmiddle' border="0" onfocus="this.blur()" onclick="javascript:total_excel(<?=$daeriComNum?>);"> -->&nbsp;
		정상분납 보험료 표시<input type="checkbox" id="normalT">
		<input type='button' id='a32' class='btn-b' value='대리운전정산'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="total_excel2(<?=$daeriComNum?>);">

		</td>
	</tr>
	<tr>
		<td>

		<input type='button' id='a32' class='btn-c' value='킥보드 정산'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="total_excel3(<?=$daeriComNum?>);">

		<input type='button' id='a33' class='btn-c' value='일일마감(케이드라이브)'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="kdriveExcel(<?=$daeriComNum?>);">
		<!--<img src="/kibs_admin/cargo/image/excell.gif" align='absmiddle' border="0" onfocus="this.blur()" onclick="javascript:total_excel2(<?=$daeriComNum?>);"> -->
		&nbsp;<input type='button' id='a34' class='btn-b' value='문자보내기'  onMouseOver="this.style.backgroundColor='#EFE4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="smsGo(<?=$val?>)">
		
		</td>
    </tr>
    <tr>
	 <td> <input type='button' id='a35' class='btn-c' value='일일배서(케이드라이브)'  onMouseOver="this.style.backgroundColor='#FF00FF'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="kdriveExcelEndorse(<?=$daeriComNum?>);">
	  <? $today 			= date("Y-m"); 

	list($today_year,$today_month)=explode("-",$today);

	$old_today_year=$today_year-1;

	//echo "today_month $today_month <br>";

	$old_today=$old_today_year."-".$today_month;
//echo "old_today $old_today <br>";
		$i=0;
	  ?>

	<select id='mYear'>
	
	 <?while($i<=13){
		
		
		
		$current_month=$today_month-$a;
		if($current_month<=9){
			$current_month='0'.$current_month;
		}

		//echo "current_month $current_month <br>";
		$current_day=$today_year."-".$current_month;

		if($current_month==01){
			$today_year=$old_today_year;
			$current_month=13;
			$today_month=$current_month;

			$a=0;
		}

	
	     ?>
		
		 <option value=<?=$current_day?>><?=$current_day?></option>
	 <? $i++;
	 $a++;
	 }?>
	 </select>
	 
	<!-- <input type='button' id='a32' class='btn-b' value='미수엑셀'  onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="misuExcel(<?=$daeriComNum?>)">-->
	 </td>


	
	</tr>

	<tr>
		<td>
			<input type="text"  readonly name="sigi" id="sigi" size='10' maxlength='10' />
			<img class="calendar"  src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].sigi,'yyyy-mm-dd',this)"  />
			<input type="text" value="<?=$now_time?>" readonly name="endm" id="endm" size='10' maxlength='10' />
			<img class="calendar" src="/sj/images/calendar.gif"" onclick="displayCalendar(document.forms[0].endm,'yyyy-mm-dd',this)"  />

		</td>
	</tr>
	<tr>
	 <td> <input type='button' id='a35' class='btn-c' value='배서 마감'  onMouseOver="this.style.backgroundColor='#FF00FF'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="kdriveExcelEndorse2(<?=$daeriComNum?>);">
	 </td>
	</tr>
 </table>