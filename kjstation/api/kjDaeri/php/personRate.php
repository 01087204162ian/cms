<?
	if($InsuraneCompany==3){ //kb 화재인 경우
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

?>