<?php
include '../../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보


	include "./php/coP.php";

echo"<data>\n";
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
	
		switch($k){
			case 1 :
				$naiName="26세~31세미만".$tag;
				$nai2=$nai_30."부터".$nai_26;
				break;
			case 2 :
				$naiName="31세~46세미만".$tag;
				$nai2=$nai_45."부터".$nai_31;
				break;
			case 3 :
				$naiName="46세~51세미만".$tag;
				$nai2=$nai_50."부터".$nai_46;
				break;
			case 4 :
				$naiName="51세~56세미만".$tag;
				$nai2=$nai_55."부터".$nai_51;
				break;
			case 5 :
				$naiName="56세~61세미만".$tag;
				$nai2=$nai_60."부터".$nai_56;
				break;
			case 6 :
				$naiName="61세 이상".$tag;
				$nai2=$nai_61."이전";
				break;
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

	
		$h1P[$i]=round($p1P[$i]*$h1,-1);
		$h2P[$i]=round($h1P[$i]/12,-1);
		$h3P[$i]=round($p1P[$i]*$h2,-1);
		$h4P[$i]=round($h3P[$i]/12,-1);
	//echo "<num".$i.">".$sql."</num".$i.">\n";
	echo "<nai".$i.">".$row[nai]."</nai".$i.">\n";
	echo "<age".$i.">".$naiName."</age".$i.">\n";
	echo "<year".$i.">".$nai2."</year".$i.">\n";
	echo "<sex>".$sex."</sex>\n";
	echo "<daein".$i.">".number_format($daeinP[$i])."</daein".$i.">\n";
	echo "<daemool".$i.">".number_format($daemoolP[$i])."</daemool".$i.">\n";
	echo "<jason".$i.">".number_format($jasonP[$i])."</jason".$i.">\n";
	echo "<char".$i.">".number_format($charP[$i])."</char".$i.">\n";
	echo "<totalP".$i.">".number_format($p1P[$i])."</totalP".$i.">\n";
	echo "<h1P".$i.">".number_format($h1P[$i])."</h1P".$i.">\n";
	echo "<h2P".$i.">".number_format($h2P[$i])."</h2P".$i.">\n";
	echo "<h3P".$i.">".number_format($h3P[$i])."</h3P".$i.">\n";
	echo "<h4P".$i.">".number_format($h4P[$i])."</h4P".$i.">\n";
	

}





	
echo"</data>";
	?>