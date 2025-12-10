<? //현재일 

	//echo "dd $now_time <br>";
	$cuDow=explode("-",$now_time);

	$todayOfcuDow=$cuDow[0]."-".$cuDow[1]."-01";
	
?>

<? for($j=0;$j<31;$j++){
		$daumDay =date("Y-m-d ", strtotime("$todayOfcuDow +$j day"));//1달전
		$da[$j]=explode("-",$daumDay);
		$daum[$j]=$da[$j][1].$da[$j][2];

		$dSQl="SELECT * FROM 2013dajoong WHERE substring(wdate,1,11)='$daumDay'";
		$dRs=mysql_query($dSQl,$connect);
		$dNum=mysql_num_rows($dRs);
		$cou[$j]=$dNum;//신청건수

		

		
		$eSQl[$j]="SELECT * FROM 2013dajoong WHERE substring(wdate,1,11)='$daumDay' and dCh ='5'";
		$eRs[$j]=mysql_query($eSQl[$j],$connect);
		$eNum=mysql_num_rows($eRs[$j]);
		$co2u[$j]=$eNum;//처리건수 

		for($_m=0;$_m<$co2u[$j];$_m++){


			//echo " $eSQl[$j] <br>";
			$eRow=mysql_fetch_array($eRs[$j]);

			$dTotal[$j]+=$eRow[preminum];

		}

		$ch+=$cou[$j];//신청건수
	    $c2h+=$co2u[$j];//신청건수
		$totalP+=$dTotal[$j];

		
		//echo " $j  $eRow[preminum] <Br> ";
}

		$p=round(($c2h/$ch)*100,-1);
?>

<table class='poptable'>
	  <tr>
	   <td>일자</td>
	   <td>신청</td>
	   <td>처리</td>
	    <td>금액</td>

	    <td>일자</td>
	   <td>신청</td>
	   <td>처리</td>
	   <td>금액</td>

	    <td>일자</td>
	   <td>신청</td>
	   <td>처리</td>
	   <td>금액</td>
	  </tr>
	<? for($j=0;$j<10;$j++){
		$k=$j+10;
		$m=$j+20;


	?>
	  <tr>
	   <td><?=$daum[$j]?></td>
	   <td><?=$cou[$j]?></td>
	   <td><?=$co2u[$j]?></td>
	   <td><?=number_format($dTotal[$j])?></td>


	   <td><?=$daum[$k]?></td>
	   <td><?=$cou[$k]?></td>
	   <td><?=$co2u[$k]?></td>
		<td><?=number_format($dTotal[$k])?></td>

	   <td><?=$daum[$m]?></td>
	   <td><?=$cou[$m]?></td>
	   <td><?=$co2u[$m]?></td>
	   <td><?=number_format($dTotal[$m])?></td>
	  </tr>
	<?}	?>

	  <tr>
	    <td colspan='9'>계약율 <?=$p?> %</td>
		<td><?=number_format($ch)?></td>
		<td><?=number_format($c2h)?></td>
		<td><?=number_format($totalP)?></td>
	</table>

