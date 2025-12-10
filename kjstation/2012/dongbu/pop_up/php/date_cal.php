<?

	###############################################################################################################################
	# À±³âÀÎ°æ¿ì
	##############################################################################################################################			
	if($new_start[0]%400==0 || ($new_start[0]%4==0 && $new_start[0]%100!=0))
	{		
			
			################################################
						# ½Ã±â À±³â Á¾±â À±³â#
			################################################
			if($nex_day[0]%400==0 || ($nex_day[0]%4==0 && $nex_day[0]%100!=0))
			{
				switch($new_start[2])
				{
					case 31 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
								case 02 :
									$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
							
					case 30 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
							break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
							break;
						}
						break;
						
				case 29 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
				
					}
				$nex_day=explode("-",$nex);
				
			}
			################################################
						# ½Ã±â À±³â Á¾±â ºñÀ±³â#
			#################################################
			else
			{
				switch($new_start[2])
				{
					case 31 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 03 :
								$nex=date("Y-m-d ", strtotime("$nex ")-3*60*60*24);
								break;
						}
						break;
						
					case 30 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
					
					case 29 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
				
				}
				$nex_day=explode("-",$nex);
				
			}
		}
	###############################################################################################################################
	# À±³âÀÌ ¾Æ´Ñ °Ü¿ì
	# ½Ã±â ºñÀ±³â Á¾±â À±³â
	#	  ºñÀ±³â
	##############################################################################################################################	
	else
	{			
			################################################
						# ½Ã±â ºñÀ±³â Á¾±â À±³â#
			#################################################
			if($nex_day[0]%400==0 || ($nex_day[0]%4==0 && $nex_day[0]%100!=0))
			{
				switch($new_start[2])
				{
					case 31 :
						switch($nex_day[2])
						{
						case 01 :
							$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
							break;
						
						case 02 :
							$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
							break;
						}
						break;
				case 30 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
				case 29 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
					break;
				
					}
				$nex_day=explode("-",$nex);
				
			}
			
			################################################
						# ½Ã±â ºñÀ±³â Á¾±â ºñÀ±³â#
			#################################################
			else

			{
				switch($new_start[2])
				{
					case 31 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 03 :
								$nex=date("Y-m-d ", strtotime("$nex ")-3*60*60*24);
								break;
						}
						break;
						
					case 30 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
								break;
						}
						break;
					
					case 29 :
						switch($nex_day[2])
						{
							case 01 :
								$nex =date("Y-m-d ", strtotime("$nex ")-60*60*24);
								break;
						
							case 02 :
								$nex=date("Y-m-d ", strtotime("$nex ")-2*60*60*24);
							break;
						}
						break;
				
				}
				$nex_day=explode("-",$nex);
				
			}
				
		
		}		

	 ?>
    