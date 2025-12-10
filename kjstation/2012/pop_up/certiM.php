<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
</head>

<?$redirectURL='DMM_System'?>
<body id="ssangyong">
<div id="wrapper">
	
	
	<div id="contentWrapper"> 
		<div id="daeriList">
		  <form>
		          <input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
				  <input type='hidden' id='Certi' value='<?=$Certi?>'>
			  <div id="serchPart">
				  <input type="text" value="<?=$yearbefore?>" readonly name="yearbefore" id="sigi" size='10' maxlength='10' />
			  <img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].yearbefore,'yyyy-mm-dd',this)"  />
			  <input type="text" value="<?=$now_time?>" readonly name="end" id="end" size='10' maxlength='10' />
			  <img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].end,'yyyy-mm-dd',this)"  />
		<!-- 	 <select id='search_contents' class='input_2'>
				 <option value='1'>회사명</option>
				 <option value='7'>증권번호</option>
				 <option value='2'>사업자 번호</option>
				 <option value='3'>계약자</option>
				 <option value='4'>계약자 핸드폰</option>
				 <option value='5'>운전자</option>
				 <option value='6'>운전자 핸드폰</option>
				</select>-->
				<input type='text' id='s_contents' value='<?=$pNum?>' size='16' maxlength='16'>
			<!--	<select id='state' name='state' class='input' >
				  <option value='1' >제외</option>
				  <option value='2' >포함</option>
				 </select>-->
			  <img class="searchButton" src="/sj/images/search.gif" align='absmiddle' onfocus='blur()' onClick="serch()" onMouseOver="style.cursor='hand'"/>
			  <input type='text' id='s_contents2' value='<?=$s_contents2?>' size='16' maxlength='16' />
			   <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="변경"  onFocus='this.blur()'      onClick="Allchange()" />
			   <img src="/kibs_admin/cargo/image/excell.gif" align='absmiddle' border="0" onfocus="this.blur()" onclick="javascript:certi_excel();">
			   <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="비교"  onFocus='this.blur()'      onClick="compare()" />
			 <!--  <input type='button' value='흥국화재증권발급' class='input' title='흥국화재증권발급' onclick='pClick()' />-->
			   </div><!-- serchPart 끝-->


			   <div id="listPart" >
					  <input type='hidden' id='page' />
		  <table>
			 <caption> 계약리스트</caption>
			  <thead>
			  <tr>
				<td width='10%'>증권번호</td>
				<td width='10%'>회사명</td>
				<td width='10%'>계약자</td>
				<td width='10%'>주민번호</td>
				<td width='10%'>계약일</td>
				<td>회차</td>
				<td>인원</td>
				<td>단체</td>
				<td>할인</td>
			  </tr>
			 
			</thead>
			<tbody class="scrollingContent">
			
			 <tr>
				<td><span id='M1a'>&nbsp;</span></td><!--증권번호-->
				<td><input type='text' id='A1a' class='textareP' onBlur='change(<?=$j?>)'></td><!--회사-->
				<td><input type='text' id='A2a' class='textareP'></td><!--계약자-->
				<td><input type='text' id='A3a' class='textareP'></td><!--주민번호-->
				<td><input type='text' id='A4a' class='textareP'></td><!--<!--계약일-->
				<td><input type='text' id='A5a' class='textareP'></td><!--보험회사-->
				<td><span id='M2a'>&nbsp;</span></td>

				<td><input type='text' id='A11a' class='textareP'></td><!--단체할인-->
				<td><input type='text' id='A12a' class='textareP'></td><!--할인할증-->
			 </tr>
		  </tbody>
        </table>
		<br>
		<table>
		  <thead>
			
			  <tr>
				<td><span id='M2a0'>&nbsp;</span></td>
				<td><span id='M2a1'>&nbsp;</span></td>
				<td><span id='M2a2'>&nbsp;</span></td>
				<td><span id='M2a3'>&nbsp;</span></td>
				<td><span id='M2a4'>&nbsp;</span></td>
				<td><span id='M2a5'>&nbsp;</span></td>
				<td><span id='M2a6'>&nbsp;</span></td>
				
			</tr>
			</thead>
			  <tr>
				
				<td><input type='text' id='A13a' class='textareP'></td><!--회차--><!--19세~25세-->
				<td><input type='text' id='A14a' class='textareP'></td><!--회차--><!--26세~44세-->
				<td><input type='text' id='A15a' class='textareP'></td><!--회차--><!--45세~49세-->
				<td><input type='text' id='A16a' class='textareP'></td><!--회차--><!--51세~59세-->
				<td><input type='text' id='A17a' class='textareP'></td><!--회차--><!--60세이상-->
				<td><input type='text' id='A18a' class='textareP'></td><!--회차--><!--66세이상-->
				<td><input type='text' id='A19a' class='textareP'></td><!--회차--><!--66세이상-->
				
			</tr>
			
			<tr>
				<td colspan='7'><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="변경"  onFocus='this.blur()'      onClick="changeV()" /></td>
				
			</tr>
			</tbody>
			</table>


		   <table>
			 <caption> 계약리스트</caption>
			  <thead>
			  <tr>
			    <td width='10%'>담</td>
				<td width='5%'>순서</td>
				<td width='20%'>연령</td>
				<td width='10%'>인원</td>
				<td width='15%'>보험회사(월)</td>
				<td width='10%'>인원X월(보험)[10회분납]</td>
				<td width='10%'>인원X월(보험)[12회분납]</td>
				<td width='10%'>받는보험료</td>
			  </tr>
			 
			</thead>
			<tbody class="scrollingContent">
		<?for($m=0;$m<3;$m++){?>
			<?for($j=0;$j<7;$j++){?>
			 <tr>
				<?if($j==0){?><td rowspan=8><span id='da<?=$m?>'>&nbsp;</span></td><?}?><!--담당자-->
				<td><span id='S1a<?=$m?><?=$j?>'>&nbsp;</span></td><!--번호-->
				<td><span id='S2a<?=$m?><?=$j?>'>&nbsp;</span></td><!--연령-->
				<td><span id='S3a<?=$m?><?=$j?>'>&nbsp;</span></td><!--인원-->
				<td><span id='S4a<?=$m?><?=$j?>'>&nbsp;</span></td><!--보험회사(월)-->
				<td><span id='S5a<?=$m?><?=$j?>'>&nbsp;</span></td><!--인원X월(보험)-->
				<td><span id='S6a<?=$m?><?=$j?>'>&nbsp;</span></td><!--인원X월(보험)10%-->
				<td><span id='S7a<?=$m?><?=$j?>'>&nbsp;</span></td><!--받는보험료-->
				
			 </tr>
			<?}?>
			<tr>
				
				<td colspan='2'> 소계</td><!--번호-->
				
				<td><span id='T1a<?=$m?>'>&nbsp;</span></td><!--인원-->
				<td><span id='T2a<?=$m?>'>&nbsp;</span></td><!--보험회사(월)-->
				<td><span id='T3a<?=$m?>'>&nbsp;</span></td><!--인원X월(보험)-->
				<td><span id='T4a<?=$m?>'>&nbsp;</span></td><!--인원X월(보험)10%-->
				<td><span id='T5a<?=$m?>'>&nbsp;</span></td><!--받는보험료-->
				
			 </tr>
		<?}?>
		    <tr>
				
				<td colspan='3'> 계</td><!--번호-->
				
				<td><span id='U1a<?=$m?>'>&nbsp;</span></td><!--인원-->
				<td><span id='U2a<?=$m?>'>&nbsp;</span></td><!--보험회사(월)-->
				<td><span id='U3a<?=$m?>'>&nbsp;</span></td><!--인원X월(보험)-->
				<td><span id='U4a<?=$m?>'>&nbsp;</span></td><!--인원X월(보험)10%-->
				<td><span id='U5a<?=$m?>'>&nbsp;</span></td><!--받는보험료-->
				
			 </tr>
			 </tbody>
		</table>

					
					<div id="paging"><span id='sql2'>&nbsp;</span><span id='sql3'>&nbsp;</span></div>
					</form>
				</div>
				
  
				
		    </div><!--main끝-->
			
       </div><!--contentWrapper 끝-->
	
  <p id="copyright"><? include "../php/footer.php";?></p>
</div><!--wrapper 끝-->

</body>
</html>