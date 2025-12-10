<?   for($_u_=0;$_u_<$Period;$_u_++){

			   $p[$_u_]=date("Y-m-d ", strtotime("$sigi + $_u_ day"));


			   $p2[$_u_]=round($monthsP[0]-$monthsP[0]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p2[$_u_]=number_format($p2[$_u_]);
			 
			   
			   $p3[$_u_]=round($monthsEP[0]-$monthsEP[0]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p3[$_u_]=number_format($p3[$_u_]);


			     //일반보험료35세~47세
			   $q2[$_u_]=round($monthsP[1]-$monthsP[1]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $q2[$_u_]=number_format($q2[$_u_]);
				//탁송보험료35세~47세
			   $q3[$_u_]=round($monthsEP[1]-$monthsEP[1]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $q3[$_u_]=number_format($q3[$_u_]);

			    //일반보험료48세 이상
			   $r2[$_u_]=round($monthsP[2]-$monthsP[2]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $r2[$_u_]=number_format($r2[$_u_]);
				//탁송보험료48세 이상
			   $r3[$_u_]=round($monthsEP[2]-$monthsEP[2]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $r3[$_u_]=number_format($r3[$_u_]);



			   $s2[$_u_]=round($monthsP[3]-$monthsP[3]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $s2[$_u_]=number_format($s2[$_u_]);


			   $t2[$_u_]=round($monthsP[4]-$monthsP[4]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $t2[$_u_]=number_format($t2[$_u_]);

				if(date("Y-m-d ", strtotime("$p[$_u_]"))==date("Y-m-d ", strtotime("$now_time"))){
						
						$thisDay=$_u_;

				}	
		   }
		   ?>