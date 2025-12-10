<?

for($i=0;$i<12;$i++){

	if($i<6){
		$etage=1;
		$k=$i+1;
		$tag='';
	}else{ 
		$etage=2;
		$k=$i+1-6;
		$tag='[탁송]';
	}
	
		
		$sql="SELECT * FROM dongbuPreminum WHERE nai='$k'  and sigi<='$endorse_day' and end>='$endorse_day' and etage='$etage' ";
		$rs=mysql_query($sql,$connect);

			$row=mysql_fetch_array($rs);
		
		//echo $daemool;
		$daeinP[$i]=$row[$daein];
		$daemoolP[$i]=$row[$daemool];
		$jasonP[$i]=$row[$jason];
		$charP[$i]=$row[$char];
		//echo "sql $sql ";
		$constant_3=0.02;
		$p1P[$i]=round(($daeinP[$i]+$daemoolP[$i]+$jasonP[$i]+$charP[$i])*(1+$constant_3),-1);	
		$h1P[$i]=round($p1P[$i]*0.9,-1);//15%할인
		$h2P[$i]=round($h1P[$i]/12,-1); //1/12
	//	$h3P[$i]=round($h1P[$i]*0.95,-1);
	//	$h4P[$i]=round($h3P[$i]/12,-1);

include "./inwon.php";
	echo "<totalP".$i.">".number_format($h1P[$i])."</totalP".$i.">\n";//년령별 보험료
	echo "<h1P".$i.">".number_format($h2P[$i])."</h1P".$i.">\n";//1/12 보험료
}

//대리운전회사별 인원을 구하기 위해 

for($k=0;$k<12;$k++){
		$m=$k+6;
	if($k<6){
		$reInwon[$k]=$inwon[$k]-$inwon[$m];

		$inTotal+=$reInwon[$k];
	}else{
		$reInwon[$k]=$inwon[$k];
		$einTotal+=$reInwon[$k];
	}
	//echo  "<inwon".$k.">".$reInwon[$k]."</inwon".$k.">\n"; //0부터 5까지 일반인원 6부터 11까지 탁송인원
	$total+=$reInwon[$k];
}
echo "<total>".$total."</total>\n";

for($k=0;$k<12;$k++){
	if($k<6){
		if($inTotal>0){
			$pRate[$k]=($reInwon[$k]/$inTotal)*100;//일반인원 비율 소수점 아래 까지 계산해서 보험료의 정확도를 높이기 위함
		}
		$inP[$k]=round(($h2P[$k]*$pRate[$k])/100,-1);//보험회사내는 보험료
		$inCo+=$inP[$k];	
		$daeP[$k]=round(($giPrem[$k]*$pRate[$k])/100,-1);//대리회사로부터 받는 보험료
		$daeCo+=$daeP[$k];	
		if($inTotal>0){
			$pRate[$k]=ceil(($reInwon[$k]/$inTotal)*100);//화면 표시를 위함 
		}
	}else{
		if($einTotal>0){
		  $pRate[$k]=($reInwon[$k]/$einTotal)*100;
		}
		$inP[$k]=round(($h2P[$k]*$pRate[$k])/100,-1);// 보험회사에 내는 보험료
		$einCo+=$inP[$k];
		$m=$k-6;
		$edaeP[$k]=round(($gi2Prem[$m]*$pRate[$k])/100,-1);//대리회사로부터 받는 보험료
		$edaeCo+=$edaeP[$k];	
		if($einTotal>0){
		  $pRate[$k]=ceil(($reInwon[$k]/$einTotal)*100);
		}
		//echo "<inTotal11>".$edaeP[$k]."[".$gi2Prem[$m]."]".$pRate[$k]."</inTotal11>\n";//일반인원 합
	}

	//$inPrem+=$inP[$k];
}
if($total){
	$inPrem=round($inCo*($inTotal/$total)+$einCo*($einTotal/$total),-1);//일반보험료*일반인원+탁송보험료평균*탁송인원 보험회사에내는
	$einPrem=round($daeCo*($inTotal/$total)+$edaeCo*($einTotal/$total),-1);//일반보험료*일반인원+탁송보험료평균*탁송인원 대리운전회사에내는
}
for($k=0;$k<12;$k++){
	echo "<inwon".$k.">".$reInwon[$k]."명[".$pRate[$k]."%]"."</inwon".$k.">\n"; //0부터 5까지 일반인원 6부터 11까지 탁송인원
}
echo "<inTotal>".$inTotal."명"."</inTotal>\n";//일반인원 합
echo "<einTotal>".$einTotal."명"."</einTotal>\n";//탁송인원합
echo "<inPrem>".number_format($inPrem)."</inPrem>\n";//보험회사에내는 보험료
echo "<einPrem>".number_format($einPrem)."</einPrem>\n";//보험회사에내는 보험료

echo "<ks_dong>".$ks_dong."</ks_dong>\n";//보험회사에내는 보험료


?>