<?php
//header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
//include '../json4version.php';
include '../dbcon.php';
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";
// 파라미터 가져오기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$lastMonthDueDate = isset($_POST['lastMonthDueDate']) ? $_POST['lastMonthDueDate'] : '';
$thisMonthDueDate = isset($_POST['thisMonthDueDate']) ? $_POST['thisMonthDueDate'] : '';
// 쿼리 조건 설정
$whereCondition = "`2012DaeriCompanyNum`='$dNum'";

// 기본 멤버 데이터 조회
$sql = "SELECT num, Name, nai, Jumin, Hphone, push, cancel, etag, 
               `2012DaeriCompanyNum`, CertiTableNum, dongbuCerti, InsuranceCompany
        FROM `DaeriMember`
        WHERE $whereCondition";
$sql .= " AND push='4'";
$sql .= " ORDER BY Jumin ASC";

$result = mysql_query($sql, $conn);
$data = array();

// 결과 처리
if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
        // UTF-8 변환
       
        // 회사 정보 가져오기
        $companyNum = $row['2012DaeriCompanyNum'];
        $companySql = "SELECT company FROM 2012DaeriCompany WHERE num='$companyNum' LIMIT 1";
        $companyResult = mysql_query($companySql, $conn);
        if ($companyResult && $companyRow = mysql_fetch_assoc($companyResult)) {
            $companyValue = $companyRow['company'];
            $row['company'] = $companyValue;
        } else {
            $row['company'] = '';
        }
        
        // 비율 정보 가져오기
		include "./php/decrptJuminHphone.php"; 
        $jumin = $row['Jumin'];
        $policyNum = $row['dongbuCerti'];
        if (!empty($jumin) && !empty($policyNum)) {
            $rateSql = "SELECT rate FROM 2019rate WHERE policy='$policyNum' AND jumin='$jumin' LIMIT 1";
            $rateResult = mysql_query($rateSql, $conn);
            if ($rateResult && $rateRow = mysql_fetch_assoc($rateResult)) {
                $row['rate'] = $rateRow['rate'];
            } else {
                $row['rate'] = null;
            }
        } else {
            $row['rate'] = null;
        }
        
        // 개인 할인율 계산
        $discountRate = calculatePersonalRate($row['rate']);

		
        $row['discountRate'] = $discountRate['rate'];
        $cNum = $row['CertiTableNum'];
        $age = $row['nai'];
        
        // 보험료 계산
        include "./php/premiumSearch.php";
        $row['monthly_premium_total'] = $monthly_premium_total;  // 월별 보험료
        $AdjustedInsuranceMothlyPremium = floor(($monthly_premium_total * $discountRate['rate'])/10)*10;
        $row['AdjustedInsuranceMothlyPremium'] = $AdjustedInsuranceMothlyPremium;
        
        $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10
        $AdjustedInsuranceCompanyPremium = floor(($payment10_premium_total * $discountRate['rate'])/10)*10;
        $row['AdjustedInsuranceCompanyPremium'] = $AdjustedInsuranceCompanyPremium;
        
        $row['ConversionPremium'] = floor(($AdjustedInsuranceCompanyPremium * 10 / 12)/10)*10; // 보험회사 보험료를 월 환산
        $data[] = $row;
    }
}

// 전체 레코드 수 계산
$totalCount = count($data);

// SMSData 테이블에서 추가 데이터 조회
$broader_sql = "SELECT dm.Name,dm.Jumin,dm.nai,dm.dongbuCerti,dm.endorse_day,dm.InsuranceCompany,dm.etag,dm.CertiTableNum,
                sd.preminum,sd.c_preminum,sd.push 
                FROM `DaeriMember` dm
                JOIN `SMSData` sd ON dm.`2012DaeriCompanyNum` = sd.`2012DaeriCompanyNum` 
                    AND dm.num = sd.`2012DaeriMemberNum`
                WHERE sd.endorse_day >= '$lastMonthDueDate' 
                    AND sd.endorse_day <= '$thisMonthDueDate' 
                    AND dm.`2012DaeriCompanyNum` = '$dNum' 
                    AND sd.dagun = '1'
					AND sd.get='2'"; //미정산건만 

// 쿼리 실행 추가
$broader_result = mysql_query($broader_sql, $conn);
$smsData = array(); // SMS 데이터를 저장할 배열

if ($broader_result) {
    while ($row3 = mysql_fetch_assoc($broader_result)) {
        $smsData[] = $row3; // 변환된 데이터를 배열에 추가
    }
}

// 담당자 정보 가져오기 (필요한 경우)
$담당자Sql = "SELECT name FROM 담당자테이블 WHERE num='$dNum' LIMIT 1"; // 테이블명과 조건을 적절히 수정해야 합니다
$담당자Result = mysql_query($담당자Sql, $conn);
$담당자이름 = "";
if ($담당자Result && $담당자Row = mysql_fetch_assoc($담당자Result)) {
    $담당자이름 = $담당자Row['name'];
}

// 메모 정보 가져오기 (필요한 경우)
$memoSql = "SELECT memo FROM 메모테이블 WHERE num='$dNum' LIMIT 1"; // 테이블명과 조건을 적절히 수정해야 합니다
$memoResult = mysql_query($memoSql, $conn);
$memo = "";
if ($memoResult && $memoRow = mysql_fetch_assoc($memoResult)) {
    $memo = $memoRow['memo'];
}

// 회사명을 파일명에 사용
$company_name = '';
if (isset($data[0]) && isset($data[0]['company'])) {
    $company_name = $data[0]['company'] . '_';
}

// 엑셀 파일 생성을 위한 설정
$output_file_name = $company_name . "회원리스트_" . date("Ymd");
header("Content-type: application/vnd.ms-excel");   
header("Content-type: application/vnd.ms-excel; charset=euc-kr");  
header("Content-Disposition: attachment; filename = {$output_file_name}.xls");  
header("Content-Description: PHP4 Generated Data");
// 현재 명단 //
$EXCEL_STR = "  
<table border='1'> 
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>메모</td>  
   <td colspan='10' style=\"text-align:center;color:red;mso-number-format:'\@';\">".$memo."</td>    
</tr>
<tr>  
   <td>구분</td>  
   <td>성명</td>  
   <td>주민번호</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>탁/일</td>
   <td>기타</td>
   <td>보험료</td>
   <td>보험회사에 내는 월보험료</td>
   <td>담당자</td>
   <td>정상납 보험료</td>
   <td>단체구분</td>
   <td>사고유무</td>
   <td>사고유무</td>
</tr>";

// $data 배열의 내용을 엑셀에 추가
$j = 1;
foreach ($data as $row) {
    // 한글 인코딩 변환 (필요한 경우)
    $name = $row['Name'];
    $jumin = $row['Jumin'];
    $nai = $row['nai'];
    

    switch($row['InsuranceCompany']){
        case 1 :
            $InsuranceCompany='흥국';
            break;
        case 2 :
            $InsuranceCompany='DB';
            break;
        case 3 :
            $InsuranceCompany='KB';
            break;
        case 4 :
            $InsuranceCompany='현대';
            break;
        case 5 :
            $InsuranceCompany='현대';
            break;
        case 6 :
            $InsuranceCompany='롯데';
            break;
        case 7 :
            $InsuranceCompany='MG';
            break;
        case 8 :
            $InsuranceCompany='삼성';
            break;
        default:
            $InsuranceCompany='';
    } 
    
    switch($row['etag']){
        case 1 :
            $etag='대리';
            break;
        case 2 :
            $etag='탁송';
            break;
        case 3 :
            $etag='대리렌트';
            break;
        case 4 :
            $etag='탁송렌트';
            break;
        default:
            $etag='';
    }
    
    $dongbuCerti = $row['dongbuCerti'];
    $pSql = "SELECT * FROM 2012CertiTable WHERE num='$row[CertiTableNum]'";
    $pRs = mysql_query($pSql, $conn);
    $pRow = mysql_fetch_array($pRs);
    // 월납/정상납 구분 및 각각의 보험료 계산
    $monthlyPremium = 0;
    $adjustedPremium = 0;
	 $companyPremium=0;
    
    if ($pRow['divi'] == 2) { // 1/12 납인 경우에만
        $gita = "월납";
        $monthlyPremium = isset($row['AdjustedInsuranceMothlyPremium']) ? $row['AdjustedInsuranceMothlyPremium'] : 0;
        $adjustedPremium = 0; // 월납의 경우 adjustedPremium은 표시하지 않음
		// 보험회사 보험료 환산
		$companyPremium = isset($row['ConversionPremium']) ? $row['ConversionPremium'] : 0;
    } else {
        $gita = "정상납";
        $monthlyPremium = 0; // 정상납의 경우 monthlyPremium은 표시하지 않음
        $adjustedPremium = isset($row['AdjustedInsuranceCompanyPremium']) ? $row['AdjustedInsuranceCompanyPremium'] : 0;
		// 보험회사 보험료 환산
		 $companyPremium = 0;
    }
    
    
    
    // 출력 부분 - 납부 방식에 따라 보험료 표시 다르게
    $EXCEL_STR .= "  
    <tr>  
        <td style=\"text-align:center;mso-number-format:'\@';\">".$j."</td>  
        <td style=\"text-align:center;mso-number-format:'\@';\">".$name."</td>  
        <td style=\"text-align:center;mso-number-format:'\@';\">".$jumin."</td> 
        <td style=\"text-align:center;mso-number-format:'\@';\">".$nai."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$InsuranceCompany."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$dongbuCerti."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$etag."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$gita."</td>";
    
    // 월납인 경우와 정상납인 경우 다른 보험료 표시
    if ($gita == "월납") {
        $EXCEL_STR .= "
        <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($monthlyPremium)."</td>
        <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($companyPremium)."</td> 
        <td style=\"text-align:center;mso-number-format:'\@';\">".$담당자이름."</td>
        <td style=\"text-align:right;mso-number-format:'\@';\">-</td>";
    } else { // 정상납인 경우
        $EXCEL_STR .= "
        <td style=\"text-align:right;mso-number-format:'\@';\">-</td>
        <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($companyPremium)."</td> 
        <td style=\"text-align:center;mso-number-format:'\@';\">".$담당자이름."</td>
        <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($adjustedPremium)."</td>";
    }
    
    $EXCEL_STR .= "
        <td style=\"text-align:center;mso-number-format:'\@';\">".$policy."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$discountRate['rate']."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$discountRate['name']."</td>
    </tr>";
    
    $j++;
}

// 합계 계산 부분도 수정
$sum_monthlyPremium = 0;
$sum_companyPremium = 0;
$sum_adjustedPremium = 0;

// 합계 계산 (납부 방식 구분하여 계산)
foreach ($data as $row) {
    $pSql = "SELECT * FROM 2012CertiTable WHERE num='$row[CertiTableNum]'";
    $pRs = mysql_query($pSql, $conn);
    $pRow = mysql_fetch_array($pRs);
    
    if ($pRow['divi'] == 2) { // 월납인 경우
        $monthlyPremium = isset($row['AdjustedInsuranceMothlyPremium']) ? $row['AdjustedInsuranceMothlyPremium'] : 0;
        $sum_monthlyPremium += $monthlyPremium;
		$companyPremium = isset($row['ConversionPremium']) ? $row['ConversionPremium'] : 0;
		$sum_companyPremium += $companyPremium;
    } else { // 정상납인 경우
        $adjustedPremium = isset($row['AdjustedInsuranceCompanyPremium']) ? $row['AdjustedInsuranceCompanyPremium'] : 0;
        $sum_adjustedPremium += $adjustedPremium;
    }
    
    
}

// 합계 행 추가 - 월납과 정상납 구분된 합계 표시
$EXCEL_STR .= "
<tr>
    <td colspan=\"8\" style=\"text-align:center;font-weight:bold;mso-number-format:'\@';\">합계</td>
    <td style=\"text-align:right;font-weight:bold;mso-number-format:'\@';\">".number_format($sum_monthlyPremium)."</td>
    <td style=\"text-align:right;font-weight:bold;mso-number-format:'\@';\">".number_format($sum_companyPremium)."</td>
    <td></td>
    <td style=\"text-align:right;font-weight:bold;mso-number-format:'\@';\">".number_format($sum_adjustedPremium)."</td>
    <td colspan=\"3\"></td>
</tr>";

$EXCEL_STR .= "</table>";
// 현재 명단 //

//배서 명단 //
$EXCEL_STR2 = "  
<table border='1'>  
<tr>  
   <td colspan='9' style=\"text-align:center;mso-number-format:'\@';\">배서리스트".$lastMonthDueDate."~".$thisMonthDueDate."</td></tr>";
$EXCEL_STR2 .= "  
<table border='1'>  
<tr style=\"text-align:center;mso-number-format:'\@';\">  
   <td>구분</td>  
   <td>배서일</td>  
   <td>성명</td>
   <td>나이</td>
   <td>보험회사</td>
   <td>증권번호</td>
   <td>일/탁</td>
    <td>배서종류</td>
	<td>배서보험료</td>
	<td>증권성격</td>
	<td>&nbsp;</td>
	<td>정상납보험료</td>
	<td>입금할 보험료</td>
</tr>";

// 여기서 배서 합계 변수를 초기화합니다 - 중요!
$sum_En_monthlyPremium = 0;
$sum_En_adjustedPremium = 0;

$j_=1;
foreach ($smsData as $erow) {
	switch($erow['InsuranceCompany']){
			case 1 :
				$inName='흥국';
			
				break;
			case 2 :
				$inName='DB';
			

				$erow['dongbuCerti']="017-".$erow['dongbuCerti']."-000";
				break;
			case 3 :
				$inName='KB';
			
				break;
			case 4 :
				$inName='현대';
			
				break;
			case 5 :
				$inName='롯데';
			
				break;
			case 6 :
				$inName='더케이';
			
				break;
			case 7 :
				$inName='MG';
			
				break;
			case 8 :
				$inName='삼성';
			
				break;


		}

		switch($erow['etag']){
						case 1 : 
							$metat="대리";

							break;
						case 2 :

							$metat="탁송";
							break;
						case 3 :
						$metat="대리/렌트";	
						break;
					case 4:
						$metat="탁송/렌트";	
						break;
					case 5 :
						$metat="전탁송";	
						break;
					}


					switch($erow['push']){
						case 2 :
							$pushName='해지';
							$erow['preminum']=-$erow['preminum'];
							$erow['c_preminum']=-$erow['c_preminum'];
							
							break;
						case 4 :
							$pushName='추가';
							// 이 값들은 양수이므로 변경할 필요 없음
							break;
					}

					$pSql = "SELECT * FROM 2012CertiTable WHERE num='$erow[CertiTableNum]'";
					$pRs = mysql_query($pSql, $conn);
					$pRow = mysql_fetch_array($pRs);

					// 납부 방식에 따라 보험료 처리 및 합계 누적
					if ($pRow['divi'] == 2) { // 1/12 납인 경우에만
						$gita = "월납";
						$EnmonthlyPremium = isset($erow['preminum']) ? $erow['preminum'] : 0;
                        // 여기서 합계를 누적합니다
                        $sum_En_monthlyPremium += $EnmonthlyPremium;
						$erow['c_preminum'] = 0; // 월납이면 정상납 보험료는 0으로 설정
					} else {
						$gita = "정상납";
						$erow['preminum'] = 0; // 정상납이면 월납 보험료는 0으로 설정
						$EnadjustedPremium = isset($erow['c_preminum']) ? $erow['c_preminum'] : 0;
                        // 여기서 합계를 누적합니다
                        $sum_En_adjustedPremium += $EnadjustedPremium;
					}

					
	$EXCEL_STR2 .= "  
				   <tr>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$j_."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$erow['endorse_day']."</td>  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$erow['Name']."</td> 
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$erow['nai']."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$inName."</td>

					   <td style=\"text-align:center;mso-number-format:'\@';\">".$erow['dongbuCerti']."</td>
					  
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$metat."</td>
					   <td style=\"text-align:center;mso-number-format:'\@';\">".$pushName."</td>
					   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($erow['preminum'])."</td>
					    <td style=\"text-align:center;mso-number-format:'\@';\">".""."</td>
						<td style=\"text-align:center;mso-number-format:'\@';\">".""."</td>
						<td style=\"text-align:right;mso-number-format:'\@';\">".number_format($erow['c_preminum'])."</td>
				   </tr>  
				   ";  
				   $j_++;
}

// 이제 두 번째 반복문은 필요 없으므로 제거합니다
// 배서 합계는 이미 위 루프에서 계산되었습니다

$EXCEL_STR2 .= "   
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\">배서 보험료 소계".$lastMonthDueDate."~".$thisMonthDueDate."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_En_monthlyPremium)."</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_En_adjustedPremium)."</td>
</tr>";
 $EXCEL_STR2 .= "   
<tr>  
   <td colspan='8'style=\"text-align:center;mso-number-format:'\@';\">입금 하실 보험료=월 보험료 소계+배서 보험료 소계 </td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_monthlyPremium+$sum_En_monthlyPremium)."</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
    <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_adjustedPremium+$sum_En_adjustedPremium)."</td>
	<td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_monthlyPremium+$sum_En_monthlyPremium+$sum_adjustedPremium+$sum_En_adjustedPremium)."</td>
</tr>";
$EXCEL_STR2 .= "</table>";  


// 엑셀 출력
echo "<meta content=\"application/vnd.ms-excel; charset=euc-kr\" name=\"Content-type\"> ";  
echo $EXCEL_STR;  

echo $EXCEL_STR2;  











/*
$response = array(
    "success" => true,
    "data" => $data,
   
);*/

//echo json_encode_php4($response);
mysql_close($conn);
?>