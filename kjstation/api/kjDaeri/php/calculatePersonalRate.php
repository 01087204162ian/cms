<?/**
 * 할인할증률 계산 함수
 * 
 * @param int $rate 할인할증 코드
 * @return float 계산된 할인할증률
 */

function calculatePersonalRate($rate) {
    switch ($rate) {
        case 1:
            $personalRate = 1.000;
            $personalRateName = "기본";
            break;
        case 2:
            $personalRate = 0.900;
            $personalRateName = "할인";
            break;
        case 3:
            $personalRate = 0.925;
            $personalRateName = "3년간 사고건수 0건 1년간 사고건수 0건 무사고 1년 이상";
            break;
        case 4:
            $personalRate = 0.898;
            $personalRateName = "3년간 사고건수 0건 1년간 사고건수 0건 무사고 2년 이상";
            break;
        case 5:
            $personalRate = 0.889;
            $personalRateName = "3년간 사고건수 0건 1년간 사고건수 0건 무사고 3년 이상";
            break;
        case 6:
            $personalRate = 1.074;
            $personalRateName = "3년간 사고건수 1건 1년간 사고건수 0건";
            break;
        case 7:
            $personalRate = 1.085;
            $personalRateName = "3년간 사고건수 1건 1년간 사고건수 1";
            break;
        case 8:
            $personalRate = 1.242;
            $personalRateName = "3년간 사고건수 2건 1년간 사고건수 0";
            break;
        case 9:
            $personalRate = 1.253;
            $personalRateName = "3년간 사고건수 2건 1년간 사고건수 1";
            break;
        case 10:
            $personalRate = 1.314;
            $personalRateName = "3년간 사고건수 2건 1년간 사고건수 2";
            break;
        case 11:
            $personalRate = 1.428;
            $personalRateName = "3년간 사고건수 3건이상 1년간 사고건수 0";
            break;
        case 12:
            $personalRate = 1.435;
            $personalRateName = "3년간 사고건수 3건이상 1년간 사고건수 1";
            break;
        case 13:
            $personalRate = 1.447;
            $personalRateName = "3년간 사고건수 3건이상 1년간 사고건수 2";
            break;
        case 14:
            $personalRate = 1.459;
            $personalRateName = "3년간 사고건수 3건이상 1년간 사고건수 3건이상";
            break;
        default:
            $personalRate = 1.000;
            $personalRateName = "기본";
            break;
    }
    
    return array('rate' => $personalRate, 'name' => $personalRateName);
}

?>