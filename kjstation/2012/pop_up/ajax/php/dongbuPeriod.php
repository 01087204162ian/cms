<? for($_u_=0;$_u_<$Period;$_u_++){

			   $p[$_u_]=date("Y-m-d ", strtotime("$sigi + $_u_ day"));
			   //일반보험료26~31세
			   $p2[$_u_]=round($qRow[preminum1]-$qRow[preminum1]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p2[$_u_]=number_format($p2[$_u_]);
				//탁송보험료26~31세
			   $p3[$_u_]=round($qRow[preminumE1]-$qRow[preminumE1]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $p3[$_u_]=number_format($p3[$_u_]);

			    //일반보험료31세~45세
			   $q2[$_u_]=round($qRow[preminum2]-$qRow[preminum2]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $q2[$_u_]=number_format($q2[$_u_]);
				//탁송보험료31세~45세
			   $q3[$_u_]=round($qRow[preminumE2]-$qRow[preminumE2]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $q3[$_u_]=number_format($q3[$_u_]);

			    //일반보험료46세~50세
			   $r2[$_u_]=round($qRow[preminum3]-$qRow[preminum3]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $r2[$_u_]=number_format($r2[$_u_]);
				//탁송보험료46세~50세
			   $r3[$_u_]=round($qRow[preminumE3]-$qRow[preminumE3]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $r3[$_u_]=number_format($r3[$_u_]);


			   //일반보험료51세~55세
			   $s2[$_u_]=round($qRow[preminum4]-$qRow[preminum4]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $s2[$_u_]=number_format($s2[$_u_]);
				//탁송보험료51세~55세
			   $s3[$_u_]=round($qRow[preminumE4]-$qRow[preminumE4]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $s3[$_u_]=number_format($s3[$_u_]);

			   //일반보험료56세~60세
			   $t2[$_u_]=round($qRow[preminum5]-$qRow[preminum5]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $t2[$_u_]=number_format($t2[$_u_]);
				//탁송보험료56세~60세
			   $t3[$_u_]=round($qRow[preminumE5]-$qRow[preminumE5]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $t3[$_u_]=number_format($t3[$_u_]);


				//일반보험료61세 이상
			   $u2[$_u_]=round($qRow[preminum6]-$qRow[preminum6]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $u2[$_u_]=number_format($u2[$_u_]);
				//탁송보험료61세 이상
			   $u3[$_u_]=round($qRow[preminumE6]-$qRow[preminumE6]/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			   $u3[$_u_]=number_format($u3[$_u_]);

				if(date("Y-m-d ", strtotime("$p[$_u_]"))==date("Y-m-d ", strtotime("$now_time"))){
						
						$thisDay=$_u_;

				}	
				
		   }
?>
			