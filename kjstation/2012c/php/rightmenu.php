<div id="secondary">
   
	 <table>
	  
		<tr> 
		  <td>콜센터</td>
		  <td>전체인원</td>
		  <td><span id='memberTotal'>&nbsp;</span></td>
		  
		  
		</tr>
		<? for($m=1;$m<9;$m++){?>
		<tr> 
		  <td><span id='o<?=$m?>'>&nbsp;</span></td>
		   <td><span id='m<?=$m?>'>&nbsp;</span></td>
		  <td><span id='n<?=$m?>'>&nbsp;</span></td>

		  
		</tr>
		<?}?>

	<?	//대리운전기사 전체 인원을 보험회사별로 

	//대리운전회사별로 보험료 납입주체 에 따른 구분을 위하여 

	$pSql="Select * FROM 2012CertiTable  WHERE 2012DaeriCompanyNum='$cNum' and divi='1'";

	//echo "pSQl $pSql <BR>";
	$pRs=mysql_query($pSql,$connect);

	$pRnum=mysql_num_rows($pRs);
	// echo "<pRnum>".$pRnum."</pRnum>\n";
	for($_p=0;$_p<$pRnum;$_p++){

		$pRow=mysql_fetch_array($pRs);


		//$CeritNum=$pRow[num];


		$Hsql="Select * FROM 2012DaeriMember WHERE CertiTableNum='$pRow[num]'  and push ='4' ";
		$Hrs=mysql_query($Hsql,$connect);
		$Hnum=mysql_num_rows($Hrs);
		
		//echo "<hmt".$_p.">"."직접"."</hmt".$_p.">\n";
		//echo "<hm".$_p.">".$Hnum."</hm".$_p.">\n";
		//echo "<n".$_k.">".$memberTotal[$_k]."</n".$_k.">\n";
		//echo "<o".$_k.">".$inCall[$_k]."</o".$_k.">\n";

?>
	
		<tr> 
		  <td> 
			<td><span id='hmt1'>&nbsp;</span></td>
			<td><span id='hm1'>&nbsp;</span></td>
		
		</tr>
    
	<?}?>	
  </table>
<!--<?if($cNum==653){?>
  <table>
     <? for($etage_=1;$etage_<3;$etage_++){
		 if($etage_==1){
			$etage_name="대리";
		 }else{
			$etage_name="탁송";
		 }?>
		<tr> 
		  <td align='center' width='50%'><?=$etage_name?>연령</td>
		  <td align='center' colspan='2'>인원</td>
		</tr>
	
	<? for($u__=0;$u__<7;$u__++){?>
		 <tr> 
		  <td align='center'><span id='me<?=$etage_.$u__?>'>&nbsp;</span></td>
		  <td colspan='2' align='right'><span id='sNum<?=$etage_.$u__?>'>&nbsp;</span></td>
		</tr>
	<?}?>
   <?}?>

	</table>

	<?}?>
-->	
</div>