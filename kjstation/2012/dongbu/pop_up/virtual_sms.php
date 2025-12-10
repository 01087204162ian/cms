<?include '../../../2012/lib/popup_lib_auth.php';

?>
<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">
<script language=javascript src='/kibs_admin/js/kibs_admin.js'></script>
<script language=javascript src='/kibs_admin/js/list_2.js'></script>
<script language="JavaScript" src="../../csd/cs_member/js/member.js"></script>
<script language=javascript src='/kibs_admin/js/sms.js'></script>

<title><?=$oun_name?></title>
<form name="form1" method="post" >
<?if(!$item){?>


	<?// chkmodify_dong_bu에서 호출하는경우에
	if($phone_1){
		
		$oun_phone=$phone_1."-".$phone_2."-".$phone_3;
		$oun_name=$c_name;
		//$okj=1;
		switch($osj){
			case '1' :
			$osj_msg_1=" 동부화재 대리운전보험 상담했던";
			break;
			case '3' :
				$osj_msg_1=" 동부화재 오토바이보험 상담했던";
			break;
		}
		//echo "name $name <br>";
		$osj_msg_2="입니다 연락주세요";

		$osj_msg=$osj_msg_1.$name.$osj_msg_2;
		
	}
	$company_tel='02-558-7383';
	?>
     <table border="0" cellpadding="0" cellspacing="1" width='100%'>
		<tr height="30" bgcolor="#0FB9C4"><td colspan='4' align='center'>
		<input type='hidden' name='osj' value='<?=$osj?>'><!--chkmodify_dong_bu에서 호출하는경우에 저장하여 사용하기 위해-->
		<input type='hidden' name='num_sms' value='<?=$num?>'><!--chkmodify_dong_bu에서 호출하는경우에 저장하여 사용하기 위해-->
		<font color="#ffffff"><?if($annae==1){echo "가상계좌 발송";}?><?if($annae==2){ echo "안내문자 발송";}?></font></td></tr> 
		<tr bgcolor="#0FB9C4">
		 <td  align="center" width="25%"><font color="#ffffff">운전자</font></td>
		 <td align="center" width="30%"><input type="hidden" name="oun_name" value='<?=$oun_name?>'><?=$oun_name?></td>
		 <td width="30%"><input type="text" name="oun_phone" value="<?=$oun_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="phone_check()" onClick="phone_check_2()">
		 <td align="center" width="15%" ><input type="checkbox" name="oun_check" value='3' checked onFocus='this.blur()'></td>
		 </td>
		</tr>
		<tr bgcolor="#0FB9C4">
		 <td align="center"><font color="#ffffff">계약자</font></td>
		 <td align="center"><input type="hidden" name="con_name" value='<?=$con_name?>'><?=$con_name?></td>
		 <td><input type="text" name="con_phone" value="<?=$con_phone?>" class="input" size="12" maxlength="12" style="text-align:right" onBlur="con_phone_check()" onClick="con_phone_check_2()"></td>
		  <td align="center"><input type="checkbox" name="con_check" value='3' onFocus='this.blur()'></td>
		</tr>
		<tr height="25" bgcolor="#0FB9C4">
		  <td colspan='4'>
		  
			<table border="0" cellpadding="0" cellspacing="1" width='100%'>
			   <tr><td bgcolor="#0FB9C4" ><?if($annae==1){//가상계좌발송?>
			   <input type="text" name="bank_name" value="<?=$bank_name?>" class="input" size="8" maxlength="8" style="text-align:right"><font color="#ffffff">은행 계좌번호</font>
				<input type="text" name="bank_number" value="<?=$bank_number?>" class="input" size="15" maxlength="15" style="text-align:right">
				<font color="#ffffff">보험료</font><input type="text" name="monthly_preminum" value="<?=$monthly_preminum?>" class="input" size="6" maxlength="6" style="text-align:right">
				<?}?>
				<?if($annae==2){//안내문 발송 ?>
				<textarea name="comment" cols="15"  rows="3" name="comment" style="border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:100%;word-break:break-all;background-color:#ffffff;padding:5 5 5 5;" onKeyUp="updateChar(100)"><?=$oun_name?>님<?=$osj_msg?></textarea>
				<?}?>
				</td>
			  </tr>
			  <tr height="20"><td>
					<div align="center"><span id="textlimit">0</span> /최대 
					80byte</div>
					
			      </td>
			  </tr>
			</table>
		  </td>
		 </tr>
		 
		<tr height="30" bgcolor="#0FB9C4"><td colspan='4' align='center'>
		<?if($annae==1){//가상계좌발송?>
		<?if($phone_1){?><input type='text' name='our_phone' value="<?=$company_tel?>" size='12' maxlength='12' class='input' onFocus="clearText(this)" onBlur='our_phone_check()' onClick='our_phone_check_2()'><?}?>
		<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="발 송"  onFocus='this.blur()' onClick="virtual_sms_put()">
		<?}if($annae==2){//안내문 발송 ?>
		<?if($phone_1){?><input type='text' name='our_phone' value="<?=$company_tel?>" size='12' maxlength='12' class='input' onFocus="clearText(this)" onBlur='our_phone_check()' onClick='our_phone_check_2()'><?}?><input type="button" class="btn-b" style="cursor:hand;width:90;"  value="발 송"  onFocus='this.blur()' onClick="virtual_sms_put_2()">
		<?}?>
		</td>
		</tr>
	</table>

<?}else{
	if($bogan==1){
		if($userid=='kibs0327'){
			if($our_phone){
				$company_tel=$our_phone;
			}else{
			$company_tel='02-558-7383';
			}
	   }else{
		   if($our_phone){
				$company_tel=$our_phone;
			}else{
			 $company_tel='02-558-7384';
			}
	   }
		//echo "sj $sj <br>";//sj가 1이면 가상계좌 2이면 안내문자

		//echo "osj $osj <br>";

		//return false;

	   //echo "con_check $con_check <br>";
		/*echo "userid $userid <br>";
		echo "oun_name $oun_name <br>";
		echo "oun_phone $oun_phone <br>";

		echo "con_name $con_name <br>";
		echo "con_phone $con_phone <br>";
		echo "bank_name $bank_name <br>";
		echo "bank_number $bank_number <br>";
		echo "monthly_preminum $monthly_preminum <br>";*/
		list($hphone1,$hphone2,$hphone3)=explode("-",$oun_phone);//고객번호
		/*echo "hphone1 $hphone1 <br>";
		echo "hphone2 $hphone2 <br>";
		echo "hphone3 $hphone3 <br>";*/
		$monthly_preminum=number_format($monthly_preminum);
		list($sphone1,$sphone2,$sphone3)=explode("-",$company_tel);//회사번호
		/*echo "sphone1 $sphone1 <br>";
		echo "sphone2 $sphone2 <br>";
		echo "sphone3 $sphone3 <br>";*/
		switch($sj){
			case '1' :
				$msg1="님 동부대리보험 ";
				$msg2=" 보험료 ";
				$msg3=" 감사합니다 ";
				$comment=$oun_name.$msg1.$bank_name.$bank_number.$msg2.$monthly_preminum.$msg3;
			break;

			case '2' :
				$coment=$comment;
			break;
		}
		//echo "commetnt $comment <br>";
		///운전자에게 보냄

		switch($osj){
		
			case '1' ://대리운전보험 상담고객 문자 
			$dong_db="dong_bu";

			$send_name="virtual_drive";
			break;
			case '3' ://오토바이 보험 상감 고객 문자 저장을 위하여
			$dong_db="dong_bu_bike";
			$send_name="virtual_bike";
			break;

			default :
      
			$dong_db="dong_bu";
			break;
		}

		if($oun_phone && $oun_check==3){
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
				
				//문자발송 고객
				$msg=$comment;
				$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', '$msg','', '', '', '0', 's',0, 0, 0)";
			  $insert_result = mysql_query($insert_sql,$connect);
			}
		}
		//계약자번호와 운전자번호가 다른경우에만 
		if($oun_phone!=$con_phone && $con_check==3 ){
			list($hphone1,$hphone2,$hphone3)=explode("-",$con_phone);//고객번호
			/*echo "hphone1 $hphone1 <br>";
			echo "hphone2 $hphone2 <br>";
			echo "hphone3 $hphone3 <br>";*/
			if($hphone1=='011' || $hphone1=='016' || $hphone1=='017' || $hphone1=='018' || $hphone1=='019' || $hphone1=='010'){
				
				//문자발송 고객
				$msg=$comment;
				$insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3,  SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2) ";
				$insert_sql.= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', 'virtual_drive', 'CS', '$msg','', '', '', '0', 's',0, 0, 0)";
			  $insert_result = mysql_query($insert_sql,$connect);
			}

		}
	}//bogan 변수
	$bogan='';
}


if($osj==1 || $osj==3){
	if($num_sms){//문자보낸것을 표시하기 위해

	 

		$sql="UPDATE $dong_db SET booking='12' WHERE num='$num_sms'";
		//echo "sql $sql <br>";
		$rs=mysql_query($sql,$connect);
	}
}
?>

<?//문자보내는 것이 제대로 성공하면 ?>
<?if($insert_result){?>
	<table border="0" cellpadding="0" cellspacing="1" width='100%'>
		<tr height="30" bgcolor="#0FB9C4"><td colspan='4' align='center'><font color="#ffffff">문자 발송 완료</font></td></tr> 
		<tr height="25" bgcolor="#0FB9C4" align="center">
		 <td width="25%"><font color="#ffffff">운전자</font></td>
		 <td width="30%"><font color="#ffffff"><?=$oun_name?></font></td>
		 <td width="30%"><font color="#ffffff"><?=$oun_phone?></font></td>
		 <td width="15%" ><font color="#ffffff"><?if($oun_check==3){echo "발송";}?></font></td>
		 
		</tr>
		<tr height="25" bgcolor="#0FB9C4" align="center">
		 <td><font color="#ffffff">계약자</font></td>
		 <td><font color="#ffffff"><?=$con_name?></font></td>
		 <td><font color="#ffffff"><?=$con_phone?></font></td>
		  <td><font color="#ffffff"><?if($con_check==3){echo "발송";}?></font></td>
		</tr>
		<tr height="25" bgcolor="#0FB9C4">
		  <td colspan='4'>
		  
			<table border="0" cellpadding="0" cellspacing="1" width='100%'>
			   <tr><?if($sj==1){//가상계좌발송?><td bgcolor="#0FB9C4" ><font color="#ffffff">
			   
			   <?=$bank_name?>은행 계좌번호<?=$bank_number?>보험료<?=$monthly_preminum?></font>
			   <?}?></td><?if($sj==2){//안내문 발송 ?>
				<td><font color="#ffffff"><?=$coment?></font></td>
				<?}?>
			   </td>
			  </tr>
			</table>
		  </td>
		 </tr>
		 
		<tr height="30" bgcolor="#0FB9C4"><td colspan='4' align='center'>
		<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="확인"  onFocus='this.blur()'      onClick="self.close()">
		</td>
		</tr>
	</table>
<?}
?>
</form>

<?// echo "user $useruid <br>"; ?>
