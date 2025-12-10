


<img src="../../2012/images/logo.gif" alt="CosmoFarmer 2.0" name="logo" width="413" height="74" id="logo"/>
<!--<img src="../../2012/images/cosmo_badge.gif" alt="6 Years!" width="78" height="78" id="badge"/>-->

<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>


<input type='hidden' id='cNum' name='cNum' value='<?=$cNum?>'>
<input type='hidden' id='readIs' name='readIs' value='<?=$readIs?>'>

<!-- 보험회사별 증권번호 조회 -->

<? /*

	$asql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$cNum'";
	echo "asql $asql <br>";
	$ars=mysql_query($asql,$connect);

	$aRnum=mysql_num_rows($ars);
		//echo" aRnum $aRnum <br>";
	for($_j=0;$_j<$aRnum;$_j++){

		$arow=mysql_fetch_array($ars);

		$certinNum[$_j]=$arow[num];
	}

	*/

	$cSql="SELECT * FROM 2012DaeriCompany  WHERE num='$cNum'";
	$cRs=mysql_query($cSql,$connect);

	$cRow=mysql_fetch_array($cRs);

	$ConPhone=$cRow[hphone];

?>
  <ul>  
  <li class="seperator"><a href="#"></span><?=$userId?> </a></li>
  <li><a href="#"><?=$nAme?></a></li>
 <!-- <li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="Memo"  onFocus='this.blur()'      onClick="MemoMake('<?=$Pcompany?>')"></li>-->
  <li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="로그아웃"  onFocus='this.blur()'  onClick="LogOut()"></li>
  <? if(!$readIs){?><!--읽기 전용 아이디가 아닐 경우만-->

		<li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="읽기전용ID"  onFocus='this.blur()'  onClick="ReadId()"></li>
		<li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="Excel"  onFocus='this.blur()'  onClick="ExcellDown()"></li>

		<?if($cNum==653){?>

			<li><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="통계"  onFocus='this.blur()'  onClick="inwon__()"></li>
		<?}?>
  <?}?>
  </ul>