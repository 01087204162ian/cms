<? 
	
   for($_q_=1;$_q_<8;$_q_++){
   $tSql="SELECT num FROM 2012DaeriMember WHERE push='4' and InsuranceCompany='$_q_'";
  // echo "tSql $tSql <br>";
   $tRs=mysql_query($tSql,$connect);
   $tRnum[$_q_]=mysql_num_rows($tRs);
	   switch($_q_){
		   case 1 :
				$InsCom[$_q_]='흥국';
			   break;
			case 2 :
				$InsCom[$_q_]='동부';
			   break;
			case 3 :
				$InsCom[$_q_]='LiG';
			   break;
			 case 4 :
				$InsCom[$_q_]='현대';
			   break;
			 case 5 :
				$InsCom[$_q_]='한화';
			   break;
			  case 6 :
				$InsCom[$_q_]='더케이';
			   break;
		
			  case 7 :
				$InsCom[$_q_]='MG';
			   break;
	   }
	    $tRnum[8]+= $tRnum[$_q_];
	  

	}?>
	

<!--<img src="../images/logo.gif" alt="CosmoFarmer 2.0" name="logo" width="413" height="74" id="logo"/>-->
<img src="../images/logo.gif" alt="CosmoFarmer 2.0" name="logo" width="413" height="74" id="logo"/>
<!--<img src="../images/cosmo_badge.gif" alt="6 Years!" width="78" height="78" id="badge"/>-->

<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
  <ul>
  <li>
  <? if($userId=='kibs0327' || $userId=='kibs7383'){?>
   <table>
	
	  <tr> <? for($__k=1;$__k<9;$__k++){
			if($__k==8){$InsCom[$__k]='계';}
		    ?>
	      <td onMouseOver="this.style.backgroundColor='#EFF4F8'; style.cursor='hand'" onMouseOut="this.style.backgroundColor=''"  onClick='gusung(<?=$__k?>)'><?=$InsCom[$__k]?></td>
	      <?}?>
	  </tr>
	  <tr> <? for($__k=1;$__k<9;$__k++){
				
			?>
	       <td><?=number_format($tRnum[$__k])?></td>
	      <?}?>
	  </tr>
	 </table>
	 <?}?>
   </li>
  <li class="seperator"><a href="#"></span><?=$userId?> </a></li>
  <li><a href="#"><?=$nAme?></a></li>
<? if($userId=='kibs0327'){?>   <li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="Memo"  onFocus='this.blur()'      onClick="MemoMake('<?=$Pcompany?>')"></li><?}?>
<? if($userId=='kibs0327'){?>   <li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="정리"  onFocus='this.blur()'      onClick="jeongLi()"></li><?}?>
  <li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="로그아웃"  onFocus='this.blur()'  onClick="LogOut()"></li>
  
  </ul>

 