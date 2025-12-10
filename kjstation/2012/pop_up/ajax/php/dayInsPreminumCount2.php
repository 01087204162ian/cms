<?
		$beforePreminum=round(($Ypreminum/$gigan)*$before_gijun,-1);//경과기간보험료
		//$totalPreminum=round(($Ypreminum/$gigan)*$after_gijun,-1);//미경과기간에 해당하는 총보험료	

			$m=10-$g_num+1;
						
			for($k=0;$k<$g_num;$k++){
				//echo "k $k <br>";
				$g_row=mysql_fetch_array($g_rs);
				$totalPreminum+=$g_row[preiminum];
				
				echo("\t\t<Period".$m.">".$Period."</Period".$m.">\n");
				echo("\t\t<d_date".$m.">".$m."회차"."</d_date".$m.">\n");
				echo("\t\t<d_prem".$m.">".number_format($g_row[preiminum])."</d_prem".$m.">\n");
				$m++;
									
			}
								

								
	
?>