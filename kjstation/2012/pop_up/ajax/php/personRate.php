<?


	//개인별 손해율 조회하자 증권번호와 주민번호 조회 하자
	//2019-10-17

	$sqlr="SELECT * FROM 2019rate WHERE policy='$e[12]' and jumin='$e[5]'";

				//		echo $sqlr ;

	$rsr=mysql_query($sqlr,$connect);

	$rowr=mysql_fetch_array($rsr);


	$personRate=$rowr[rate];


	//echo $e[30]; echo "//"; echo $personRate;

	//$personRate2 : 월 보험료 정산 인 경우
	//$personRate3 : 보험회사 내는 보험료 인 경우
	//스마트콜 대리운전 업체처럼 개인별 할인할증 적용없이 누구나 보험료가 일정한 경우

	//$e[50]  대리 컴퍼니 num 값으로 2012DaeriCompany Table에셔 union_ 값을 찾아서 1이면 

	//$personRate2 값이 1
	
	if($e[30]==3){ //kb 화재인 경우
		switch($personRate){


			case 1 :
				$personRate2=1;
				$personRate3=1;// 
				$personRateName="기본";
				break;
			case 2 :
				$personRate2=0.9;
				$personRate3=0.9;
				$personRateName="할인";
				break;
			case 3 :
				$personRate2=0.925;
				$personRate3=0.925;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상";
			break;
			case 4 :
				$personRate2=0.898;
				$personRate3=0.898;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상";
				break;
			case 5 :
				$personRate2=0.889;
				$personRate3=0.889;
				$personRateName="  3년간 사고건수 0건 1년간 사고건수 0건 무사고  3년 이상";
				break;
			case 6 :
				$personRate2=1.074;
				$personRate3=1.074;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 0건 ";
				break;
			case 7 :
				$personRate2=1.085;
				$personRate3=1.085;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 1";
				break;
			case 8 :
				$personRate2=1.242;
				$personRate3=1.242;
				$personRateName=" 3년간 사고건수 2건 1년간 사고건수 0";
				break;
			case 9 :
				$personRate2=1.253;
				$personRate3=1.253;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 1";
				break;
			case 10 :
				$personRate2=1.314;
				$personRate3=1.314;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 2";
				break;
			case 11 :
				$personRate2=1.428;
				$personRate3=1.428;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 0";
				break;
			case 12 :
				$personRate2=1.435;
				$personRate3=1.435;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 1";
				break;
			case 13 :
				$personRate2=1.447;
				$personRate3=1.447;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 2";
				break;
			case 14 :
				$personRate2=1.459;
				$personRate3=1.459;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 3건이상";
				break;
			default:
				
				$personRate2=1;
				$personRate3=1;
				$personRateName="기본";
				break;

		}
	}else{

			switch($personRate){


			case 1 :
				$personRate2=1;
				$personRate3=1;// 
				$personRateName="기본";
				break;
			case 2 :
				$personRate2=0.9;
				$personRate3=0.9;
				$personRateName="할인";
				break;
			case 3 :
				$personRate2=0.925;
				$personRate3=0.925;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상";
			break;
			case 4 :
				$personRate2=0.898;
				$personRate3=0.898;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상";
				break;
			case 5 :
				$personRate2=0.889;
				$personRate3=0.889;
				$personRateName="  3년간 사고건수 0건 1년간 사고건수 0건 무사고  3년 이상";
				break;
			case 6 :
				$personRate2=1.074;
				$personRate3=1.074;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 0건 ";
				break;
			case 7 :
				$personRate2=1.085;
				$personRate3=1.085;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 1";
				break;
			case 8 :
				$personRate2=1.242;
				$personRate3=1.242;
				$personRateName=" 3년간 사고건수 2건 1년간 사고건수 0";
				break;
			case 9 :
				$personRate2=1.253;
				$personRate3=1.253;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 1";
				break;
			case 10 :
				$personRate2=1.314;
				$personRate3=1.314;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 2";
				break;
			case 11 :
				$personRate2=1.428;
				$personRate3=1.428;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 0";
				break;
			case 12 :
				$personRate2=1.435;
				$personRate3=1.435;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 1";
				break;
			case 13 :
				$personRate2=1.447;
				$personRate3=1.447;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 2";
				break;
			case 14 :
				$personRate2=1.459;
				$personRate3=1.459;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 3건이상";
				break;
			default:
				
				$personRate2=1;
				$personRate3=1;
				$personRateName="기본";
				break;

		}
	}



$d_sql="SELECT * FROM 2012DaeriCompany WHERE num='$e[50]'"; 

//echo $d_sql;
$d_rs=mysql_query($d_sql,$connect);
$d_row=mysql_fetch_array($d_rs);

//echo $d_row[union_]; echo "//";

//스마트콜인 경우만 2020년11월1일 정상적으로 받기로 해서 
/*if($d_row[union_]==1){
	$personRate2=1;
}(*/

//echo $personRate2; echo "//";echo $personRate3; echo "//";
?>