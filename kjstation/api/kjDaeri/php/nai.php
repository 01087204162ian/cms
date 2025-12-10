<? 
// 보험회사에 따라 만나이 계산 기준일 설정
// 흥국,동부는 배서기준일이 만나이 계산 기준일
// 그외의 보험회사는 보험증권의 초일이 만나이 계산 기준일
switch($InsuraneCompany) {
    case 1: // 흥국
    case 2: // 동부
        $m = explode("-", $endorse_day);
        break;
    case 3: // 기타 보험사
    case 4:
    case 5:
    case 7:
        $m = explode("-", $Crow['sigi']);
        break;
    default:
        // 기본값으로 배서기준일 사용
        $m = explode("-", $endorse_day);
}




?>