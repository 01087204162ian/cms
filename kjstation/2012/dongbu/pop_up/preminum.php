<?include '../../../2012/lib/popup_lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />

<link rel="stylesheet" href="/kibs_admin/css/osj.css" type="text/css">

<script>

</script>
<link href="../../css/pop_up.css" rel="stylesheet" type="text/css" />
<link href="../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<script src="../../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<script src="../../js/create.js" type="text/javascript"></script><!--ajax-->
<script src="./js/p1.js" type="text/javascript"></script>
<title>보험료 산출</title>
<form name="form1" method="post" >
	
	<? include "./coPreminum.php";?>

	<? for($j=0;$j<12;$j++){?>
	<tr bgcolor="#ffffff" height='30'> 
		<td align="center"><?=$j+1?></td>
		<td align="center"><span id='nai<?=$j?>'>&nbsp;</span><br><span id='year<?=$j?>'>&nbsp;</span><span id='sql<?=$j?>'></span></td>
		<td align="right" ><span id='daeinP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='daemolP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='jasonP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='charP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='totalP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='hP<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='h2P<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='h3P<?=$j?>'>&nbsp;</span></td>
		<td align="right"><span id='h4P<?=$j?>'>&nbsp;</span></td>
	</tr>
   <?}//for문 종료?>

 </table>


</form>

</html>
