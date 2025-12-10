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
	<script src="./js/pList.js" type="text/javascript"></script><!--ajax-->
</head>
<? $sql="SELECT distinct(InsuraneCompany) FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$DaeriCompanyNum' and startyDay>='$yearbefore' ";
//echo "Sql $sql <br>";
   $rs=mysql_query($sql,$connect);
   $Ccount=mysql_num_rows($rs);

?>
<?$redirectURL='DMM_System'?>
<body id="ssangyong">
<div id="wrapper">
	
	
	<div id="contentWrapper"> 
		<div id="daeriList">
		  <form>
		          <input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
				<!--  <input type='hidden' id='pNum' value='<?=$pNum?>'>-->
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
			 <!--  <input type='button' value='흥국화재증권발급' class='input' title='흥국화재증권발급' onclick='pClick()' />-->
			   </div><!-- serchPart 끝-->


			   <div id="listPart" >
					  <input type='hidden' id='page' />
					<table>
			 <caption> 계약리스트</caption>
			  <thead>
			  <tr>
				<td width='7%'>번호</td>
				<td width='18%'>증권번호</td>
				<td width='19%'>회사명</td>
				<td width='9%'>운전자</td>
				<td width='19%'>주민번호</td>
				<td width='8%''>보험회사</td>
				<td width='9%'>당월</td>
				<td width='7%'>인원</td>
				<td width='1%'>보험료</td>
				<td width='7%'>현황</td>
				
			</tr>
			</thead>
			<tbody class="scrollingContent">
			<? for($j=0;$j<20;$j++){?>
			 <tr>
				<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" onclick="comSearch(<?=$j?>)">
				<input type='hidden' id='ssang_c<?=$j?>' /><input type='hidden' id='dongb_c<?=$j?>' />
				<span id='bunho<?=$j?>' >&nbsp;</span></td>
				<td>
				
				<input type='text' id='cert<?=$j?>' class='input2' style="text-align:center" onClick='certi(<?=$j?>)' onBlur='certiInput(<?=$j?>)'/ ></td>
				<td><span id='company<?=$j?>'>&nbsp;</span></td>
				<td><span id='cname<?=$j?>'>&nbsp;</span></td>
				<td><span id='si<?=$j?>'>&nbsp;</span></td><!--가입일-->
				<td><span id='A3a<?=$j?>'>&nbsp;</span><input type='hidden' id='insCom<?=$j?>' /></td><!--보험회사-->
				<td><span id='month<?=$j?>'>&nbsp;</span></td>
				<td><span id='mem<?=$j?>'>&nbsp;</span></td><!--인원-->
				<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" ><span id='day<?=$j?>'>&nbsp;</span></td>
				<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" ><span id='but<?=$j?>'>&nbsp;</span></td>
				
			</tr>
			<?}?>
			<tr>
				<td colspan='6'>합계인원</td>
				<td><span id='tot'>&nbsp;</span></td>
				<td></td>
				<td></td>
				<td></td>
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