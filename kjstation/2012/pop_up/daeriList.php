<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../js/jquery-1.7.1.min.js" type="text/javascript" ></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<script src="./js/daeriList.js" type="text/javascript"></script><!--ajax-->
	<script src="/2012c/js/common_tong.js" type="text/javascript"></script><!--인증관련-->
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
			  <div id="serchPart">
				  <input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
				  <input type='hidden' id='DaeriCompanyNum' value='<?=$DaeriCompanyNum?>'>
				  <input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>'>
				  <input type='hidden' id='iNum' value='<?=$iNum?>'>
				  

				  <input type="text" id='endorseDay' value="<?=$now_time?>" readonly name="endorseDay"  size='10' maxlength='10' /><img class="calendar" src="/sj/images/calendar.gif" onclick="displayCalendar(document.forms[0].endorseDay,'yyyy-mm-dd',this)"  />
			      대리기사 성명
				  <input type='text' id='s_contents'  size='16' maxlength='16' >
				  <img class="searchButton" src="/sj/images/search.gif" align='absmiddle' onfocus='blur()' onClick="serch()" />
				  
				  <?//if($CertiTableNum){?>
				<!--  <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="운전자추가"  onFocus='this.blur()' onClick="newUnjeon()">-->
				  <?//}?>
				  <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="년령별"  onFocus='this.blur()' onClick="inWonList()">
				 <input type="button" class="btn-b" style="cursor:hand;width:80;"  value="Memo"  onFocus='this.blur()' onClick="order()">

				<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="해지"  onFocus='this.blur()'  onClick="haeji()">

				 <span id='sql' >&nbsp;</span>
				 <span id='pUnjeon'>&nbsp;</span><!--운전자 추가-->
				 <input type='hidden' id='uCerti' ><!--운전자 cetinum-->
				 <!--<input type='text' id='uPolicy' ><!--증권번호-->
				 <span id='pCerti'>&nbsp;</span><!--증권발급버튼-->
				 <input type='hidden' id='pCerti2' value='<?=$CertiTableNum?>' />
			   </div><!-- serchPart 끝-->


			   <div id="listPart" >
					  <input type='hidden' id='page' />
					<table>
					 <caption><span id='inwonTotal'>&nbsp;</span>명</caption>
					  <thead>
					  <tr>
						<td width='3%'>번호</td>
						<td width='8%'>성명</td>
						<td width='12%'>주민번호</td>
						<td width='6%'>상태</td>
						<td width='6%'>탁송</td>
					<!--	<td width='11%'>대리운전회사</td>-->
						<td width='5%'>
						<select id='insuranceComNum' onChange='serch()'>
						   <option value='99'>전체</option>
						   <? for($_j_=0;$_j_<$Ccount;$_j_++){
							   $row=mysql_fetch_array($rs);
							   switch($row[InsuraneCompany]){
								case 1 :
									$inSom[$_j_]='흥국';
									break;
								case 2 :
									$inSom[$_j_]='DB';
									break;
								case 3 :
									$inSom[$_j_]='KB';
									break;
								case 4 :
									$inSom[$_j_]='현대';
									break;
								case 5 :
									$inSom[$_j_]='롯데';
									break;
								case 6 :
									$inSom[$_j_]='더케이';
									break;
								case 7 :
									$inSom[$_j_]='MG';
									break;
								case 8 :
									$inSom[$_j_]='삼성';
									break;
							}?>
							  
						   <option value='<?=$row[InsuraneCompany]?>' <?if($iNum==$row[InsuraneCompany]){echo "selected";}?>><?=$inSom[$_j_]?></option>
						   <?}?>
						 </select></td>
						<td width='8%'>증권번호</td>
						<td width='6%'>등록일</td>
						<td width='8%'>핸드폰</td>
						<td width='6%'>통신사</td>
						<td width='5%'>명의</td>
						<td width='29%'>주소</td>
						<td width='5%'>변경</td>
					</tr>
					</thead>
					<tbody class="scrollingContent">
					<? for($j=0;$j<20;$j++){?>
					 <tr>
						<td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''" >
						  <span id='bunho<?=$j?>' >&nbsp;</span>
						  <input type='hidden' id='num<?=$j?>'>
						  <input type='hidden' id='cNum<?=$j?>'>
							<input type='hidden' id='iNum<?=$j?>'>
							<input type='hidden' id='pNum<?=$j?>'>
						</td>
						<td><span id='A2a<?=$j?>' >&nbsp;</span></td>
						<td><span id='A3a<?=$j?>'>&nbsp;</span></td>
						<td><span id='A4a<?=$j?>'>&nbsp;</span></td>
						<td><span id='A5a<?=$j?>'>&nbsp;</span></td><!--탁송일반-->
					<!--	<td><span id='A6a<?=$j?>'>&nbsp;</span></td><!--대리컴퍼티-->
						<td><span id='A8a<?=$j?>'>&nbsp;</span></td><!--보힘회사-->
						<td><span id='A9a<?=$j?>'>&nbsp;</span></td><!--보힘회사-->
						<!--<td><span id='A12a<?=$j?>'>&nbsp;</span></td><!--설계번호-->
						<!--<td><span id='A13a<?=$j?>'>&nbsp;</span></td><!--회차-->				
						<td><span id='A10a<?=$j?>'>&nbsp;</span></td><!--등록일-->
						<!--<td><span id='A11a<?=$j?>'>&nbsp;</span></td><!--해지일-->
						<!--<td><span id='A14a<?=$j?>'>&nbsp;</span></td><!--변경회사-->
						<td><span id='A29a<?=$j?>'>&nbsp;</span></td>
						<td><span id='A30a<?=$j?>'>&nbsp;</span></td><!--통신사-->
						<td><span id='A31a<?=$j?>'>&nbsp;</span></td><!--명의-->
						<td><span id='A32a<?=$j?>'>&nbsp;</span></td><!--주소-->
						<td><input type='text' id='A33a<?=$j?>' class='certilength'  onblur='changeCerti(<?=$j?>)'/></td>
					</tr>
					<?}?>
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

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="./js/haeji.js"></script>
</html>