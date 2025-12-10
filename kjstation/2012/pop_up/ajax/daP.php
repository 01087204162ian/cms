<?	switch($a[8]){


				case 1 :
				    //전화방업(화상대화방업)';

					if($a[9]<=500){
						$tableNum=1;
					}else{
						$tableNum=2;
					}
						break;
				case 2 :
					//비디오감상실';
					if($a[9]<=500){
						$tableNum=3;
					}else{
						$tableNum=4;
					}
					break;
				case 3 :
					//비디오소극장';
					if($a[9]<=500){
						$tableNum=5;
					}else{
						$tableNum=6;
					}
					break;
				case 4 :
					//수면방업';
					if($a[9]<=500){
						$tableNum=7;
					}else{
						$tableNum=8;
					}
					break;
				case 5 : 
					//산후조리원';
					if($a[9]<=500){
						$tableNum=9;
					}else{
						$tableNum=10;
					}
					break;
				case 6 :
					//스크린골프장';
					if($a[9]<=500){
						$tableNum=11;
					}else{
						$tableNum=12;
					}
					break;
				case 7 :
					//노래방';
					if($a[9]<=500){
						$tableNum=13;
					}else{
						$tableNum=14;
					}
					break;
				case 8 ://유흥주점';
					if($a[9]<=500){
						$tableNum=15;
					}else{
						$tableNum=16;
					}
					break;
				case 9 :
					//콜라텍';
					if($a[9]<=500){
						$tableNum=17;
					}else{
						$tableNum=18;
					}
					break;
				case 10 :
					//안마시술소';
					if($a[9]<=500){
						$tableNum=19;
					}else{
						$tableNum=10;
					}
					break;
				case 11 :
					//영화상영관';
					if($a[9]<=500){
						$tableNum=21;
					}else{
						$tableNum=22;
					}
					break;
				case 12 :
					//학원';
					if($a[9]<=500){
						$tableNum=23;
					}else{
						$tableNum=24;
					}
					break;
				case 13 :
					//목욕탕(찜질방)';
					if($a[9]<=500){
						$tableNum=25;
					}else{
						$tableNum=26;
					}
					break;
				case 14 :
					//고시원';
					if($a[9]<=100){
						$tableNum=27;
					}else{
						$tableNum=28;
					}
					break;
				case 15 :
					//일반음식점';
					if($a[9]<=500){
						$tableNum=29;
					}else{
						$tableNum=30;
					}
					break;
				case 16 :
					//휴게음식점';
					if($a[9]<=500){
						$tableNum=31;
					}else{
						$tableNum=32;
					}
					break;
				case 17 :
					//제과점';
					if($a[9]<=500){
						$tableNum=33;
					}else{
						$tableNum=34;
					}
					break;
				case 18 :
					//인터넷컴퓨터게임시설제공업(PC방)';
					if($a[9]<=250){
						$tableNum=35;
					}else{
						$tableNum=36;
					}
					break;
				case 19 :
					//게임제공업';
					if($a[9]<=250){
						$tableNum=37;
					}else{
						$tableNum=38;
					}
					break;
				case 20 :
					//복합유통제공업';
					if($a[9]<=250){
						$tableNum=39;
					}else{
						$tableNum=40;
					}
					break;
				case 21 :
					//실내권총사격장';
					if($a[9]<=500){
						$tableNum=41;
					}else{
						$tableNum=42;
					}
					break;	

	}


	$pSql="SELECT * FROM 2013daPtable WHERE num='$tableNum'";
	$pRs=mysql_query($pSql,$connect);
	$pRow=mysql_fetch_array($pRs);

		$b[3]=$a[9]*$pRow[daein]+$a[9]*$pRow[daemol];//보험료
	
	?>