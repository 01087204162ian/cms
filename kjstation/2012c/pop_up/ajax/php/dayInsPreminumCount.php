<?
		$beforePreminum=round(($Ypreminum/$gigan)*$before_gijun,-1);//경과기간보험료
		$totalPreminum=round(($Ypreminum/$gigan)*$after_gijun,-1);//미경과기간에 해당하는 총보험료	

		//echo "$nabang_1";
		//echo "InsuraneCompany $InsuraneCompany";
		switch($InsuraneCompany){//보험회사에 따라
				case 2 :   //동부 화재 
						if($nabang_1<2){
							$daumPreminum=$yearPrem*0.75;
						}else if($nabang_1>=2 && $nabang_1<=7){
							$daumPreminum=$yearPrem*((10-$nabang_1)*0.1-0.1-0.05);//3회까지 내었다면 1회25%,2회10%,3회10% 총 40% 납입 남은것이 60%
						}else{

							$daumPreminum=round($yearPrem*((10-$nabang_1)*0.05),-1);
						}
						
					  	for($k=$nabang_1;$k<=$nabang;$k++){

								if($k==$nabang_1){
										$month_[$k]=round($totalPreminum-$daumPreminum,-1);
										$yp=$month_[$k]; //배서리스트에서 보험료

								}else{

									if($k>=2 && $k<=7){
										$month_[$k]=round($Ypreminum*0.1,-1);
									}else{
										$month_[$k]=round($Ypreminum*0.05,-1);
									}
								}


										echo("\t\t<Period".$k.">".$Period."</Period".$k.">\n");
										echo("\t\t<d_date".$k.">".$k."회차"."</d_date".$k.">\n");
										echo("\t\t<d_prem".$k.">".number_format($month_[$k])."</d_prem".$k.">\n");
									//$totalMonth+=$month[$k];
							}
						break;
				case 7:  
						//1회차 11회차까지의 보험료 계산을한다
						for($_j=1;$_j<12;$_j++){

							if($_j<10){
								$mg_[$_j]=round($Ypreminum*0.091,-1);
							}else if($_j==10){
								$mg_[$_j]=round($Ypreminum*0.09,-1);
							}else{
								$mg_[$_j]=$Ypreminum-$imsi;
							}

							$imsi+=$mg_[$_j];
						}
								$daumPreminum='';
						// 다음회차에 낼 보험료 먼저 계산하자
								$daum=$nabang_1+1;//현재까지 낸  회수 +1;
								for($k=$daum;$k<=$nabang;$k++){

									$daumPreminum+=$mg_[$k];

					     		}
						$putpr='';
					  	for($k=$nabang_1;$k<=$nabang;$k++){

								if($k==$nabang_1){
											
									    for($_m=1;$_m<=$nabang_1;$_m++){

											$putpr+=$mg_[$_m];

										}
													//낸회차 까지 의 보험료에서 -경과일수 보험료 
										$month_[$k]=round(($putpr-($Ypreminum/$gigan)*$before_gijun),-1);

										//$month_[$k]=round($totalPreminum-$daumPreminum,-1);
										$yp=$month_[$k]; //배서리스트에서 보험료
								}else{
										$month_[$k]=$mg_[$k];
								}
							


										echo("\t\t<Period".$k.">".$yp.$Period."</Period".$k.">\n");
										echo("\t\t<d_date".$k.">".$k."회차"."</d_date".$k.">\n");
										echo("\t\t<d_prem".$k.">".number_format($month_[$k])."</d_prem".$k.">\n");
									//$totalMonth+=$month_[$k];
							
							}
							
							$totalMonth='';
							$imsi='';
					break;
				case 8://삼성화재 
					if($nabang==6){//6회납
						
						if($nabang_1<2){
							$daumPreminum=$yearPrem*0.75;
						}else if($nabang_1>=2 && $nabang_1<=6){
							//$k=0.25+($nabang_1-1)*0.15;
							$daumPreminum=$yearPrem*(($nabang-$nabang_1)*0.15);
							//2회까지 내었다면 1회25%,2회1%,3회10% 총 40% 납입 남은것이 60%
						}
						
					  	for($k=$nabang_1;$k<=$nabang;$k++){

								if($k==$nabang_1){
										$month_[$k]=round($totalPreminum-$daumPreminum,-1);
										$yp=$month_[$k]; //배서리스트에서 보험료

								}else{

									if($k>=2 && $k<=6){
										$month_[$k]=round($Ypreminum*0.15,-1);
									}
								}


										echo("\t\t<Period".$k.">".$k."/".$Period."</Period".$k.">\n");
										echo("\t\t<d_date".$k.">".$k."회차"."</d_date".$k.">\n");
										echo("\t\t<d_prem".$k.">".number_format($month_[$k])."</d_prem".$k.">\n");
									//$totalMonth+=$month[$k];
						}

					}else{  //10회납
						if($nabang_1<2){
							$daumPreminum=$yearPrem*0.8;
						}else if($nabang_1>=2 && $nabang_1<=8){
							$daumPreminum=$yearPrem*((10-$nabang_1)*0.1-0.1);//3회까지 내었다면 1회20%,2회10%,3회10% 총 40% 납입 남은것이 60%
						}else{

							$daumPreminum=round($yearPrem*((10-$nabang_1)*0.05),-1);
						}
						
					  	for($k=$nabang_1;$k<=$nabang;$k++){

								if($k==$nabang_1){
										$month_[$k]=round($totalPreminum-$daumPreminum,-1);
										$yp=$month_[$k]; //배서리스트에서 보험료

								}else{

									if($k>=2 && $k<=8){
										$month_[$k]=round($Ypreminum*0.1,-1);
									}else{
										$month_[$k]=round($Ypreminum*0.05,-1);
									}
								}


										echo("\t\t<Period".$k.">".$Period."</Period".$k.">\n");
										echo("\t\t<d_date".$k.">".$k."회차"."</d_date".$k.">\n");
										echo("\t\t<d_prem".$k.">".number_format($month_[$k])."</d_prem".$k.">\n");
									//$totalMonth+=$month[$k];
							}


					}


					break;

				
				default:
						if($nabang_1<2){
							$daumPreminum=$yearPrem*0.8;
						}else if($nabang_1>=2 && $nabang_1<=8){
							$daumPreminum=$yearPrem*((10-$nabang_1)*0.1-0.1);//3회까지 내었다면 1회20%,2회10%,3회10% 총 40% 납입 남은것이 60%
						}else{

							$daumPreminum=round($yearPrem*((10-$nabang_1)*0.05),-1);
						}
						
					  	for($k=$nabang_1;$k<=$nabang;$k++){

								if($k==$nabang_1){
										$month_[$k]=round($totalPreminum-$daumPreminum,-1);
										$yp=$month_[$k]; //배서리스트에서 보험료

								}else{

									if($k>=2 && $k<=8){
										$month_[$k]=round($Ypreminum*0.1,-1);
									}else{
										$month_[$k]=round($Ypreminum*0.05,-1);
									}
								}


										echo("\t\t<Period".$k.">".$Period."</Period".$k.">\n");
										echo("\t\t<d_date".$k.">".$k."회차"."</d_date".$k.">\n");
										echo("\t\t<d_prem".$k.">".number_format($month_[$k])."</d_prem".$k.">\n");
									//$totalMonth+=$month[$k];
							}
					break;
			}//보험회사에 따라


	

	/*	for($k=1;$_m<=$nabang;$_m++){	
			$p[$_m]=date("Y-m-d ", strtotime("$sigi + $_m day"));//일수
			$p2[$_m]=round($PreminumMonth-$PreminumMonth/$Period*$_m,-1);//보험료 월보험료 -월보험료/30*경과일수

			echo("\t\t<Period".$_m."><![CDATA[".$Period."]]></Period".$_m.">\n");
			echo("\t\t<d_date".$_m."><![CDATA[".$p[$_m]."]]></d_date".$_m.">\n");
			echo("\t\t<d_prem".$_m."><![CDATA[".number_format($p2[$_m])."]]></d_prem".$_m.">\n");
			
				//$endorsePreminum=$p2[$_m];
				if(date("Y-m-d", strtotime("$p[$_m]"))==date("Y-m-d", strtotime("$endorseDay"))){
						$thisDay=$_m;
						$endorsePreminum=$p2[$_m];
						$a="tjd";

				}	
			
			}	*/

	
?>