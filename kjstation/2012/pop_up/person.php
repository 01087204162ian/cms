<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />

	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/person.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>
<title>관리자</title>

<? //관리자
$sql="SELECT num,mem_id,name FROM 2012Member   WHERE level='1'";
//echo "sql $sql <br>";
$rs = mysql_query($sql,$connect);
$Rnum=mysql_num_rows($rs);
$sql2="SELECT pBankNum,MemberNum FROM 2012DaeriCompany  WHERE num='$num'";
$rs2=mysql_query($sql2,$connect);
$row2=mysql_fetch_array($rs2);

?>
<body id="popUp">
<form name="form1" method="post" >
<input type='hidden' name='num' value='<?=$num?>'>
<div id="SjPopUpMain">
  <table>
      
	   <tr>
        <td width='10%'> 번호</td>
		<td width='10%'>성명</td>
		<td width='10%'> id</td>
		<td width='5%'> 선택</td>
		<td width='50%'>통장</td>
		

	   </tr>
	   <?for($j=0;$j<$Rnum;$j++){
		   $row=mysql_fetch_array($rs);
		?>
	    <tr >
			<td><?=$row[num]?></td>
			<td><input type='text' id='pName<?=$j?>' value='<?=$row[name]?>' class='textareO' /></td>
			<td><?=$row[mem_id]?></td>
			<td> <INPUT TYPE="radio" NAME="sel" <?if($row2[MemberNum]==$row[num]){echo 'checked';}?> onClick="seleSS(<?=$j?>,<?=$num?>,<?=$row[num]?>)" /></td>
			<td> <? //관리자 별 통장 찾기
				$Sql4="SELECT num,bankName,pName,bankaccount,pNumber FROM bankAccount WHERE pNumber='$row[num]'";

				//echo "sql $Sql4 <br>";
				$Rs=mysql_query($Sql4,$connect);
				$rNum=mysql_num_rows($Rs);
				//echo "rNum $rNum <br>";
				?>

				<select id='pro<?=$j?>' name='pro<?=$j?>' onchange='SSbankChange(<?=$num?>,this.value,<?=$row[num]?>)' class='selectbox'>
					<option value='9999'>==선택==</option>
			     <?for($p=0;$p<$rNum;$p++){
					 $row4=mysql_fetch_array($Rs);?>
					<option value='<?=$row4[num]?>' <?if($row4[num]==$row2[pBankNum]){echo "selected";}?> >
				  <?=$row4[pName]?><?=$row4[bankName]?>[<?=$row4[bankaccount]?>](<?=$row4[num]?>)</option>
				 <?
				}?>
			   </select>
			 
			</td>
		   
	   </tr>
	   <?}?>

	   <tr >
        <td colspan='5'> 
	
			<input type="button" class="btn-b" style="cursor:hand;width:90;"  value="닫기"  onFocus='this.blur()'      onClick="self_close()">
	    </td>
	   </tr>
    </table>


</form>

</div>
</body>

</html>
<?// echo "user $useruid <br>"; ?>
