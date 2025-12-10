<?include '../../2012c/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>통계</title>
	<link href="/2012/css/sj.css" rel="stylesheet" type="text/css" />
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
	<!--<script src="./js/pCerti.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<!--<script src="/2012/pop_up/js/readid.js" type="text/javascript"></script>-->
  

</head>
<body id="ssangyong">
		<input type='hidden' id="daeriCompanyNum" value='<?=$num?>'>
		<div id="pop2_">
		  

<? for($p__=1;$p__<3;$p__++){
		if($p__==1){
			$_pname="대리";
		}else{
			$_pname="탁송";
		}
?>
	  <table>			 
		  <thead>
		  <tr >
			<th width='20%' >순번</th>
			<th width='50%'><?=$_pname?> 연령구간</th>
			<th width='30%'>인원</th>
		  </tr>
		</thead>
		<tbody  >
		 
		  <tr>
					  
	<?
	for($_j_=0;$_j_<4;$_j_++){?>
		 <? if($_j_<3){?>
			<th><?=$_j_+1;?> 구간</th>
		 <?}
		    if($_j_==0){
				$snai[$_j_]=26;
				$k2[$_j_]=49;
			}else if($_j_==1){
				$snai[$_j_]=50;
				$k2[$_j_]=55;
			}else if($_j_==2){
				$snai[$_j_]=56;
				$k2[$_j_]=99;
			}

			
			if($_j_<3){

				$sQL="SELECT * FROM  2012DaeriMember WHERE 2012DaeriCompanyNum='$num'  and push ='4'  and  nai>='$snai[$_j_]' and nai<='$k2[$_j_]' and etag='$p__' ";
			}else{
				$sQL="SELECT * FROM  2012DaeriMember WHERE 2012DaeriCompanyNum='$num'  and push ='4'  and etag='$p__' ";
			}
			///echo $sQL;
			
			$sRs=mysql_query($sQL,$connect);
			$sNum[$_j_]=mysql_num_rows($sRs);

			
		
		
			
		if($_j_<2){?>
			<th ><?=$snai[$_j_]."세~".$k2[$_j_]."세"?></th>
		  
		<?}elseif($_j_==2){?>
			  <th ><?=$snai[$_j_]."세~"?></th>
			<? 
		}else{?>
			<th  colspan='2'><input type="button" class="btn-b" style="cursor:hand;width:80;"  value="소계"  onFocus='this.blur()'  onClick="exclell__(<?=$p__?>)">
			<input type="button" class="btn-b" style="cursor:hand;width:80;"  value="소계2"  onFocus='this.blur()'  onClick="exclell2__(<?=$p__?>)"></th>
		  <? //echo "<me".$p__.$_j_.">"."소계"."</me".$p__.$_j_.">\n";//
		}?>
			<td ><?=number_format($sNum[$_j_])?></td>
		  <?//echo "<me".$p__.$_j_.">".$snai[$_j_]."세~".$k2[$_j_]."세"."</me".$p__.$_j_.">\n";//
		
		//echo "<sNum".$p__.$_j_.">".number_format($sNum[$_j_])."</sNum".$p__.$_j_.">\n";//
		//echo "<rNum".$p__.$_j_.">".$rNum[$_j_]."</rNum".$p__.$_j_.">\n";//
	
		?>

		</tr>
		</tbody>
		
	<? }?>



<? } ?>
 <br>
</table>
<? 

	$sQL="SELECT * FROM  2012DaeriMember WHERE 2012DaeriCompanyNum='$num'  and push ='4'";
	$sRs=mysql_query($sQL,$connect);
			$sNum=mysql_num_rows($sRs);
?>
<br>

		<table>			 
		  <thead>
			  <tr >
				<th width='70%' colspan='2' >합계</th>
				<td width='30%'><?=number_format($sNum)?></td>		
			  </tr>
		 </thead>
		</table>
					
				</div>
				
  
				
		  
	
  
</div><!--wrapper 끝-->

</body>
</html>