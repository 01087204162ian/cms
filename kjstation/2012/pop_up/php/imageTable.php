<table>
<!--	<tr><td colspan='5'><span id='sql'  />&nbsp;</span></td></tr>-->
	    <tr>
			<td width='23%'>ÁÖ¹Î¹øÈ£</td>
		   
		 </tr>
		 <? 
			$height=147*$count;
		 for($j=0;$j<$count;$j++){
			$row=mysql_fetch_array($rs);


		 ?>
		 <tr>
		    <td><img src="/valet/uploads/<?=$row[images]?>" width="157" height="147" border="0"></td>
			<?if($j==0){?>
			<td rowspan='<?=$count?>'><img src="/valet/uploads/<?=$row[images]?>" width="600" height="<?=$height?>"  border="0"></td>
			<?}?>
		 </tr>
		 <?}?>
	<!--	  <tr>
		    <td  colspan='3'><img src="/valet/uploads/0312181708197354099790.jpeg"> 		    <td  colspan='3'><img src="/valet/uploads/01087204162#0308081549354094876081.jpeg" ><img src="/image/main/introduce.gif" width="157" height="147" border="0"></td>
><img src="/image/main/introduce.gif" width="157" height="147" border="0"></td>
			
		 </tr>-->
		
	</table>