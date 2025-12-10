<?if($num){ ?>우편물발행<input type="radio" name="post_print" onfocus="blur()" <?if($p_check){echo 'checked';}?> onclick="post_p()">
	   <input type="radio" name="post_print" onfocus="blur()" <?if(!$p_check){echo 'checked';}?>><?}?>
	   <input type="text" name="postnum" value="<?=$post?>" class="input" size="8" maxlength="8" style="text-align:center" onclick='findAddr()' readonly>
	 <img src='/csd/images/member/bt_findAddr.gif' border='0' height='19' width='70' style='cursor:hand' onclick='findAddr()' align="absmiddle">
	 <input type="text" name="address1" value="<?=$address_1?>" class="input" size="50" maxlength="50" readonly  onclick='findAddr()'>
     <input type="text" name="address_2" value="<?=$address_2?>" class="input" size="40" maxlength="50" >
	 <input type='hidden' name='ab' value='<?=$ab?>'>

	