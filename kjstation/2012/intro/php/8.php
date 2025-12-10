<form>
      <div id="serchPart">
		  배서기준일<input type="text" value="<?=$now_time?>" readonly name="endorse_day" id="endorse_day" size='10' maxlength='10' />
		  <img class="calendar" src="../images/calendar.gif" onclick="displayCalendar(document.forms[0].endorse_day,'yyyy-mm-dd',this)"  />
		 <input type='text' id='driver_name'  size='10' maxlength='10' >
		 <select id='p_ush'  class='input'>
		  <option value='4'>정상</option>
		  <option value='1'>청약</option>
		  <option value='3'>교체</option>
		  <option value='2'>해지</option>
		  <option value='5'>거절</option>
		  <option value='6'>취소</option>
		  <option value='7'>전체</option>
		</select>
		  <img class="searchButton" src="../images/search.gif" align='absmiddle' onfocus='blur()' onClick="serch()" />
	   </div>
	<div id="listPart" >
			  <input type='hidden' id='page' />
	<table>
	 <caption>
       배서리스트
      </caption>
	  <thead>
	  <tr>
	    <td width='32%' colspan='3'> 현재 </td>
	    <td width='35%' colspan='2'> 교체 </td>
		<td width='18%'rowspan='2'> 회사명 </td>
		<td width='8%'rowspan='2'> 기준일 </td>
		<td width='7%'rowspan='2'> 비고 </td>
	  <tr>
		<!--<td width='5%'>번호</td>-->
		<td width='7%'>성명</td>
		<td width='17%'>주민번호</td>
		<td width='8%'>상태</td>
		<td width='9%'>성명</td>
		<td width='26%'>주민번호</td>
	</tr>
	</thead>
	<tbody class="scrollingContent">
	<? for($j=0;$j<15;$j++){?>
	 <tr>
		<td>
			<!--ajax으로 사용합니다-->
			<input type='hidden' id='driver_num<?=$j?>' name='driver_num<?=$j?>' >
			<input type='hidden' id='useruid' name='useruid' value='<?=$useruid?>' >
			<input type='hidden' id='check<?=$j?>'  name='check<?=$j?>'  ><!--교체 신청 후 또 하는것을 방지 하기위해-->
			<input type='hidden' id='ssang_c<?=$j?>'>
			<span id='name<?=$j?>'></span>
		</td>
		<td><span id='jumi<?=$j?>'></span></td>
		<td><span id='sele<?=$j?>'></span></td>
		<td><span id='afterName<?=$j?>'></span></td>
		<td><span id='after1Jumin<?=$j?>'> </span><span id='after2Jumin<?=$j?>' ></span><span id='but<?=$j?>'> </span></td>
		<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="comDetail(<?=$j?>)"><span id='cert<?=$j?>'>&nbsp;</span><br /><span id='company<?=$j?>'>&nbsp;</span></td>
		<td><span id='wda<?=$j?>'>&nbsp;</span></td>
		<td></td>
	</tr>
	<?}?>
	</tbody>
	</table>
    </form>
	</div>
    </form>
	<div id="paging"><span id='sql2'>&nbsp;</span><span id='sql3'>&nbsp;</span></div>
  </div>