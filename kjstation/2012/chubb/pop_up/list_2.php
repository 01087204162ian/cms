<?include '../../../2012/lib/popup_lib_auth.php';
//include '../../csd/include/dbcon.php';
//include "../../csd/club/php/config.php";
//include "../../csd/club/php/util.php";
//include "../../csd/club/community/club_inc/club_util.php";
//include '../../hq/hq_insurance/ins_top.php';
//if(!$HTTP_COOKIE_VARS["userid"] || !$HTTP_COOKIE_VARS["name"]){
//	msg('로그인 후 사용할 수 있습니다.');
//}
$ab=1;//num_query 하단에서 찾기(우편물 내용 찾기 위해)


?>
<?if($num){ include "./num_query.php";//상담,업체명등으로 조회한다  
}?>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<script language=javascript src='/kibs_admin/js/list_2.js'></script>
<script language="JavaScript" src="/csd/cs_member/js/member.js"></script>
<title><?if($num){ echo $com_name ; } else{ echo "신규 상담";}?></title>
<form name="form1" method="post" >
<input type='hidden' name='num' value='<?=$num?>'><!--조회 할 경우 업체 번호 또는 상담 번호-->
<!--*날자를 표시하기 위함 신규 상담인경우에는 처음이 나오고 업체를 불러 들이는경우는 두번째 부분이 나옴*-->
<?if(!$num){?>
		<table border="0" cellpadding="0" cellspacing="4" width='860'>
		<tr ><td width='100'><select name="contents" >
			  <option value="1">상담</option>
			 <option value="2">업체 등록</option>
			 
		   </select></td>
		   
		   <td width='100'><select name="product" >
			  <option value="1">적하</option>
			  <option value="2">근재</option>
			  <option value="3">운송</option>
			  <option value="4">생산물</option>
			  <option value="5">여행자</option>
			  <option value="6">대리운전</option>
			  <option value="7">기타</option>
			 
		   </select></td>
		   <td width='660' align='right'>현재시간<?= date("Y-m-d ")?></tr>
		</table>
<?	} else {?>
	<table border="0" cellpadding="0" cellspacing="4" width='860'>
		<tr ><td width='100'>최초등록일</td>
		   
		   <td width='200'><?=$wdate?></td>
		   <td width='560' align='right'>현재시간<?= date("Y-m-d ")?></tr>
		</table>
<? }?>
<table border="0" cellpadding="0" cellspacing="0" >
<tr >
  <td align='center' valign='top'>
	 <table border="0" cellpadding="0" cellspacing="0" width='860'>
		<tr height="20" align='center' >
		 <td bgcolor="#DFEEF8"><table border="0" cellpadding="0" cellspacing="0"  width="100%">
			  <tr>
			    <td align>기본자료</td>
			    
			  </tr>
			  
			 </table>
		 </td>
		 <td width="91%" align='right'><input type="button" class="menu_1" style="cursor:hand;width:90;"  value="상품설명서"  onFocus='this.blur()'      onClick="explain()"><input type="button" class="menu_1" style="cursor:hand;width:90;"  value="무사고확인서"  onFocus='this.blur()'      onClick="NoClaim()"><input type="button" class="menu_1" style="cursor:hand;width:90;"  value="취소요청서"  onFocus='this.blur()'      onClick="cancel()"><input type="button" class="menu_1" style="cursor:hand;width:100;"  value="marinApplication"  onFocus='this.blur()'      onClick="application()"></td>
		</tr>
	   <tr>
	    <td colspan="2">
			<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
			   <tr bgcolor="#DFEEF8" align='center' height="25">
				 <td width="30%">회사명</td>
				 <td width="30%">영문명</td>
				 <td width="20%">사업자등록번호</td>
				 <td width="20%">법인등록번호</td>
				</tr>
				 <tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="com_name" value="<?=$com_name?>" class="input" size="40" maxlength="40" onBlur="con_name_check_2()"></td>
				 <td><input type="text" name="com_eur_name" value="<?=$com_eur_name?>" class="input" size="40" maxlength="40" style="text-align:right"></td>
				 <td><input type="text" name="com_number" value="<?=$com_number?>" class="input" size="15" maxlength="15" style="text-align:right"></td>
				 <td><input type="text" name="com_number_2" value="<?=$com_number_2?>" class="input" size="15" maxlength="15" style="text-align:right"></td>
				 <input type="hidden" name="kibs_list_num" value="<?=$num?>"><!--kibs_lint_num(우편무 발행을 위해-->
				</tr>
				<tr bgcolor="#DFEEF8" align='center' height="25">
				 <td>우편번호</td>
				 <td>주소1</td>
				 <td colspan="2">주소2</td>
				 </tr>
				 <tr align='right' bgcolor="#FFFFFF">
				   <td colspan="4"><? include "./php/post_print.php";?>
				</td>
			</table>
		</td>
	  </tr>
	 </table>
	</td>
  </tr>
</table>
	
<table><tr height="5"><td></td></tr></table>
<table border="0" cellpadding="0" cellspacing="0"  >
<tr >
  <td align='center' valign='top'>
	 <table border="0" cellpadding="0" cellspacing="0" width='860'>
		<tr height="20" align='center' >
		 <td bgcolor="#DFEEF8" width="9%"><table border="0" cellpadding="0" cellspacing="0">
			  <tr><td>담당자</td></tr>
			 </table>
		 </td>
		 <td width="91%" colspan="2">&nbsp;</td>
		</tr>
	   <tr>
	    <td width="60%" colspan="2">
		  	<table border="0" cellpadding="1" cellspacing="1" bgcolor="#cccccc" width="100%">
			   <tr bgcolor="#DFEEF8" align='center' height="25">
				 <td>성명</td>
				 <td>직급</td>
				 <td>전화</td>
				 <td>팩스</td>
				 <td colspan='2'>이메일</td>
				 <td>메신져</td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_1" value="<?=$name_1?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_1" value="<?=$job_1?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td ><input type="text" name="phone_1" value="<?=$phone_1?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_1" value="<?=$fax_1?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_1" value="<?=$mail_1?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_1" value="<?=$messenger_1?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_2" value="<?=$name_2?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_2" value="<?=$job_2?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td><input type="text" name="phone_2" value="<?=$phone_2?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_2" value="<?=$fax_2?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_2" value="<?=$mail_2?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_2" value="<?=$messenger_2?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_3" value="<?=$name_3?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_3" value="<?=$job_3?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td><input type="text" name="phone_3" value="<?=$phone_3?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_3" value="<?=$fax_3?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_3" value="<?=$mail_3?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_3" value="<?=$messenger_3?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_4" value="<?=$name_4?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_4" value="<?=$job_4?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td><input type="text" name="phone_4" value="<?=$phone_4?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_4" value="<?=$fax_4?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_4" value="<?=$mail_4?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_4" value="<?=$messenger_4?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_5" value="<?=$name_5?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_5" value="<?=$job_5?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td><input type="text" name="phone_5" value="<?=$phone_5?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_5" value="<?=$fax_5?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_5" value="<?=$mail_5?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_5" value="<?=$messenger_5?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF"  align='right'>
				 <td><input type="text" name="name_6" value="<?=$name_6?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td><input type="text" name="job_6" value="<?=$job_6?>" class="input" size="5" maxlength="5" style="text-align:center"></td>
				 <td><input type="text" name="phone_6" value="<?=$phone_6?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td><input type="text" name="fax_6" value="<?=$fax_6?>" class="input" size="13" maxlength="13" style="text-align:right"></td>
				 <td colspan="2"><input type="text" name="mail_6" value="<?=$mail_6?>" class="input" size="23" maxlength="23" style="text-align:center"></td>
				 <td><input type="text" name="messenger_6" value="<?=$messenger_6?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF">
				 <td bgcolor="#DFEEF8"   align='center'>품목</td>
				 <td align='right'><input type="text" name="item_1" value="<?=$item_1?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td align='right'><input type="text" name="item_2" value="<?=$item_2?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td align='right'><input type="text" name="item_3" value="<?=$item_3?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td width="50" bgcolor="#DFEEF8" align='center'>할인율</td>
				 <td align='right' colspan="3"><input type="text" name="harin" value="<?=$harin?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF">
				 <td align='center' bgcolor="#DFEEF8">지로</td>
				 <td align='right'><input type="text" name="giro_1" value="<?=$giro_1?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td bgcolor="#DFEEF8" align="center">리스트</td>
				 <td align='right'><input type="text" name="list" value="<?=$list?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td width="50" bgcolor="#DFEEF8" align='center'>증권전달</td>
				 <td align='right' colspan="3"><input type="text" name="delivery" value="<?=$delivery?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF">
				 <td bgcolor="#DFEEF8" align='center'>체크</td>
				 <td align='right'><input type="text" name="check_1" value="<?=$check_1?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td bgcolor="#DFEEF8" align="center">보험시기</td>
				 <td align='right'><input type="text" name="sigi_year" value="<?=$sigi_year?>" class="input" size="4" maxlength="4" style="text-align:right" onkeyup='focus_move4()'>
						<input type="text" name="sigi_month" value="<?=$sigi_month?>" class="input" size="2" maxlength="2" style="text-align:right" onkeyup='focus_move5()'>
						<input type="text" name="sigi_day" value="<?=$sigi_day?>" class="input" size="2" maxlength="2" style="text-align:right" onkeyup='focus_move8()'></td>
				 <td width="50" bgcolor="#DFEEF8" align='center'>보험종기</td>
				 <td align='right' colspan="3"><input type="text" name="jeonggi_year" value="<?=$jeonggi_year?>" class="input" size="4" maxlength="4" style="text-align:right" onkeyup='focus_move6()'>
						<input type="text" name="jeonggi_month" value="<?=$jeonggi_month?>" class="input" size="2" maxlength="2" style="text-align:right" onkeyup='focus_move7()'>
						<input type="text" name="jeonggi_day" value="<?=$jeonggi_day?>" class="input" size="2" maxlength="2" style="text-align:right"></td>
				</tr>
				<tr bgcolor="#FFFFFF">
				 <td bgcolor="#DFEEF8"   align='center'>보험종목</td>
				 <td align='right'><input type="text" name="insurance_name" value="<?=$insurance_name?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td bgcolor="#DFEEF8" align="center">은행</td>
				 <td align='right'><input type="text" name="bank_name" value="<?=$bank_name?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td width="50" bgcolor="#DFEEF8" align='center'>계좌번호</td>
				 <td align='right' colspan="32"><input type="text" name="bank_number" value="<?=$bank_number?>" class="input" size="13" maxlength="13" style="text-align:center"></td>
				</tr>
				<tr bgcolor="#FFFFFF">
				 <td bgcolor="#DFEEF8"   align='center'>보험회사</td>
				 <td align='right'><input type="text" name="company_name" value="<?=$company_name?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td bgcolor="#DFEEF8" align="center">포딩</td>
				 <td align='right'><input type="text" name="forwarder" value="<?=$forwarder?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				 <td width="50" bgcolor="#DFEEF8" align='center'>코드</td>
				 <td align='right' colspan="2"><input type="text" name="cord" value="<?=$cord?>" class="input" size="10" maxlength="10" style="text-align:center"></td>
				</tr>
				
			</table>

		</td>
	    <td width="40%" valign="top">
			<table border="0" cellpadding="1" cellspacing="1" bgcolor="#cccccc" width="100%" height="100%">
			   <tr bgcolor="#DFEEF8" align='center' height="25">
				 <td>상담이력</td>
		       </tr>
			   <tr>
			    <td><textarea name="content"  cols="40" rows="17"><?=$content?></textarea></td>
	         </tr>
	       </table>
	   </td>
    </tr>
		
	   
  </table>
  
 </td>
</tr>
</table>
	

                    
		 
   	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center"> 
          <?if($num){ ?>
          <img src='/csd/images/community/rectify.gif' border=0 onclick=" bogan_3('./list_update.php')" align="absmiddle" style="cursor:hand" title="수정 보관"> 
          <!-- 가입설계 수정 -->
          <img src='/csd/images/community/close_1.gif' border=0 onClick="self_close()" align="absmiddle" style="cursor:hand" title="닫기">
          <? } else {?>
          <img src='/csd/images/community/insu1.gif' border=0 onclick=" bogan('./list_save.php')" align="absmiddle" style="cursor:hand" title="새로운 가입 설계"><img src='<?=$img_dir?>community/close_1.gif' border=0 onclick="self_close()" align="absmiddle" style="cursor:hand" title="닫기"> 
          <!-- 가입설계 보관-->
          <? }?>
        </td>
       </tr>
     </table>
	
</form>

<?// echo "user $useruid <br>"; ?>
