<?   for($_u_=0;$_u_<$Period;$_u_++){

			   $p[$_u_]=date("Y-m-d ", strtotime("$sigi + $_u_ day"));


			   $p2[$_u_]=round($monthsP-$monthsP/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p2[$_u_]=number_format($p2[$_u_]);
			 
			   
		

				if(date("Y-m-d ", strtotime("$p[$_u_]"))==date("Y-m-d ", strtotime("$now_time"))){
						
						$thisDay=$_u_;

				}	
		   }
		   ?>