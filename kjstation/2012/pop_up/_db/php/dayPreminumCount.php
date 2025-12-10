<?
	
		for($_m=0;$_m<$Period;$_m++){		
			$p[$_m]=date("Y-m-d ", strtotime("$sigi + $_m day"));//일수
			$p2[$_m]=round($PreminumMonth-$PreminumMonth/$Period*$_m,-1);//보험료 월보험료 -월보험료/30*경과일수

			echo("\t\t<Period".$_m.">".$Period."</Period".$_m.">\n");
			echo("\t\t<d_date".$_m.">".$p[$_m]."</d_date".$_m.">\n");
			echo("\t\t<d_prem".$_m.">".number_format($p2[$_m])."</d_prem".$_m.">\n");
			
				//$endorsePreminum=$p2[$_m];
				if(date("Y-m-d", strtotime("$p[$_m]"))==date("Y-m-d", strtotime("$endorseDay"))){
						
						$endorsePreminum=$p2[$_m];
						$thisDay=$_m+3;;
						//$a="tjd";

				}	
			
			}	

	    // echo "  cc   $endorsePreminum  $endorseDay `<br>";
?>