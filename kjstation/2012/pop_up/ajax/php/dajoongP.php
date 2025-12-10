<?switch($a[8]){
				case 1 :
				    //전화방업(화상대화방업)';

					if($a[9]<=500){
						$tableNum=1;
					}else{
						$tableNum=2;
					}
					//$a[100]=floor($a[9]);
					$a[100]=$a[9];
					$kk[1]=500;
						break;
				case 2 :
					//비디오감상실';
					if($a[9]<=500){
						$tableNum=3;
					}else{
						$tableNum=4;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 3 :
					//비디오소극장';
					if($a[9]<=500){
						$tableNum=5;
					}else{
						$tableNum=6;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 4 :
					//수면방업';
					if($a[9]<=500){
						$tableNum=7;
					}else{
						$tableNum=8;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 5 : 
					//산후조리원';
					if($a[9]<=500){
						$tableNum=9;
					}else{
						$tableNum=10;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 6 :
					//스크린골프장';
					if($a[9]<=500){
						$tableNum=11;
					}else{
						$tableNum=12;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 7 :
					//노래방';
					if($a[9]<=500){
						$tableNum=13;
					}else{
						$tableNum=14;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 8 ://유흥주점';
					if($a[9]<=500){
						$tableNum=15;
					}else{
						$tableNum=16;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 9 :
					//콜라텍';
					if($a[9]<=500){
						$tableNum=17;
					}else{
						$tableNum=18;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 10 :
					//안마시술소';
					if($a[9]<=500){
						$tableNum=19;
					}else{
						$tableNum=20;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 11 :
					//영화상영관';
					if($a[9]<=500){
						$tableNum=21;
					}else{
						$tableNum=22;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 12 :
					//학원';
					if($a[9]<=500){
						$tableNum=23;
					}else{
						$tableNum=24;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 13 :
					//목욕탕(찜질방)';
					if($a[9]<=500){
						$tableNum=25;
					}else{
						$tableNum=26;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 14 :
					//고시원';
					if($a[9]<=100){
						$tableNum=27;
					}else{
						$tableNum=28;
					}
					$a[100]=$a[9];
					$kk[1]=100;
					break;
				case 15 :
					//일반음식점';
					if($a[9]<=500){
						$tableNum=29;
					}else{
						$tableNum=30;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 16 :
					//휴게음식점';
					if($a[9]<=500){
						$tableNum=31;
					}else{
						$tableNum=32;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 17 :
					//제과점';
					if($a[9]<=500){
						$tableNum=33;
					}else{
						
						$tableNum=34;
					}
					$a[100]=$a[9];
					$kk[1]=500;
					break;
				case 18 :
					//인터넷컴퓨터게임시설제공업(PC방)';
					if($a[9]<=250){
						$tableNum=35;
					}else{
						$tableNum=36;
					}
					$a[100]=$a[9];
					$kk[1]=250;
					break;
				case 19 :
					//게임제공업';
					if($a[9]<=250){
						$tableNum=37;
					}else{
						$tableNum=38;
					}
					$a[100]=$a[9];
					$kk[1]=250;
					break;
				case 20 :
					//복합유통제공업';
					if($a[9]<=250){
						$tableNum=39;
					}else{
						$tableNum=40;
					}
					$a[100]=$a[9];
					break;
				case 21 :
					//실내권총사격장';
					if($a[9]<=500){
						$tableNum=41;
					}else{
						$tableNum=42;
					}


					$a[100]=$a[9];
					$kk[1]=500;
					break;	

	}
	//보험료 산출 방식 

	//500 넘으면  예1500인경우 500 따로 계산하고 

					//초과분 따로 계산한다

	$pa=$tableNum%2;

	if($pa==1){//500 미만

		$pSql="SELECT * FROM 2013daPtable WHERE num='$tableNum'";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);		
		$b[3]=floor(($a[100]*$pRow[daein]+$a[100]*$pRow[daemol])/100)*100;//보험료
			
	}else{  //500초과 일때는


		$tableNum1=$tableNum-1;

		$pSql="SELECT * FROM 2013daPtable WHERE num='$tableNum1'";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);		
		$k1Preminum=floor(($kk[1]*$pRow[daein]+$kk[1]*$pRow[daemol])/100)*100;//500미만보험료


		$kk[2]=$a[100]-$kk[1];//초과분
			

		

		$pSql2="SELECT * FROM 2013daPtable WHERE num='$tableNum'";

		$pRs2=mysql_query($pSql2,$connect);
		$pRow2=mysql_fetch_array($pRs2);		
		$k2Preminum=floor(($kk[2]*$pRow2[daein]+$kk[2]*$pRow2[daemol])/100)*100;//500미만보험료
		
		

		$b[3]=$k1Preminum+$k2Preminum;
	}
	//

	




	

	//$b[3]=($a[100]*$pRow[daein]+$a[100]*$pRow[daemol]);//보험료
	if($b[3]<10000){

			$b[3]=10000;
	}

	?>