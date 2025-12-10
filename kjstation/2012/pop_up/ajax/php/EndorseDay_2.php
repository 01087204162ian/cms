<?   for($_u_=0;$_u_<$Period;$_u_++){

			   $p[$_u_]=date("Y-m-d ", strtotime("$sigi + $_u_ day"));
			   $p2[$_u_]=round($PreminumMonth-$PreminumMonth/$Period*$_u_,-1);//보험료 월보험료 -월보험료/30*경과일수
			  // echo "u $_u_  p $p[$_u_] <br>";
				if(date("Y-m-d ", strtotime("$p[$_u_]"))==date("Y-m-d ", strtotime("$DendorseDay"))){
						

						
						$thisDay=$_u_;

						$endorsePreminum2=$p2[$_u_];

				}	
}
		  
//echo "$Dpreminum2 ||나이 $Dnai dayOFberfore $dayOFberfore prem   $PreminumMonth  sigi $sigi  endor $DendorseDay period $Period thisDay $thisDay  end $endorsePreminum <br>";
	switch($Dpush){
		
		case 1 ://청약

			//$endorsePre[$k]=$endorsePreminum2;
			
			$endorsePre[$k]=round($endorsePreminum2*$personRate2,-1);
			break;
		case 4 ://해지

			//$endorsePre[$k]=-$endorsePreminum2;
			$endorsePre[$k]=-round($endorsePreminum2*$personRate2,-1);
			break;
	}
		   
		   
		   
?>