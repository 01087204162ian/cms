<?php
//header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";

// 파라미터 가져오기
$dNum = $_POST['dNum'] ?? '';
$lastMonthDueDate = $_POST['lastMonthDueDate'] ?? '';
$thisMonthDueDate = $_POST['thisMonthDueDate'] ?? '';

// PDO 쿼리로 변경
$whereCondition = "`2012DaeriCompanyNum` = :dNum";

// 기본 멤버 데이터 조회
$sql = "SELECT num, Name, nai, Jumin, Hphone, push, cancel, etag, 
               `2012DaeriCompanyNum`, CertiTableNum, dongbuCerti, InsuranceCompany
        FROM `DaeriMember`
        WHERE $whereCondition
        AND push = '4'
        ORDER BY Jumin ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':dNum', $dNum);
$stmt->execute();

$data = array();

// 결과 처리
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // 회사 정보 가져오기
    $companyNum = $row['2012DaeriCompanyNum'];
    $companySql = "SELECT company FROM 2012DaeriCompany WHERE num = :companyNum LIMIT 1";
    $companyStmt = $pdo->prepare($companySql);
    $companyStmt->bindParam(':companyNum', $companyNum);
    $companyStmt->execute();
    
    if ($companyRow = $companyStmt->fetch(PDO::FETCH_ASSOC)) {
        $companyValue = $companyRow['company'];
        $row['company'] = $companyValue;
    } else {
        $row['company'] = '';
    }
    
    
    if ($row['Hphone'] != "") {
            $decrypted = decryptData($row['Hphone']);
            if ($decrypted != "") {
                $row['Hphone'] = $decrypted;
            } 
        }

        if ($row['Jumin'] != "") {
            $decrypted = decryptData($row['Jumin']);
            if ($decrypted != "") {
                $row['Jumin'] = $decrypted;
            } 
        }
    $jumin = $row['Jumin'];
    $policyNum = $row['dongbuCerti'];
    
    if (!empty($jumin) && !empty($policyNum)) {
        $rateSql = "SELECT rate FROM 2019rate WHERE policy = :policy AND jumin = :jumin LIMIT 1";
        $rateStmt = $pdo->prepare($rateSql);
        $rateStmt->bindParam(':policy', $policyNum);
        $rateStmt->bindParam(':jumin', $jumin);
        $rateStmt->execute();
        
        if ($rateRow = $rateStmt->fetch(PDO::FETCH_ASSOC)) {
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
    // 첫 번째 쿼리: kj_premium_data 테이블에서 월 보험료 정보 조회
            $mSql = "SELECT monthly_premium1, monthly_premium2, monthly_premium_total 
                    FROM kj_premium_data 
                    WHERE cNum = :cNum AND start_month <= :age AND end_month >= :age2";

            $premiumStmt = $pdo->prepare($mSql);
            $premiumStmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
            $premiumStmt->bindParam(':age', $age, PDO::PARAM_INT);
            $premiumStmt->bindParam(':age2', $age, PDO::PARAM_INT);
            //echo $mSql ;
            $premiumStmt->execute();

            $mrow = $premiumStmt->fetch(PDO::FETCH_ASSOC);
            // 결과가 있는지 확인하고 값 추출
            if ($mrow) {
                $monthly_premium1 = $mrow['monthly_premium1']; // 월 기본보험료
                $monthly_premium2 = $mrow['monthly_premium2']; // 월 특약보험료
                $monthly_premium_total = $mrow['monthly_premium_total']; // 기본+특약 합계
            } else {
                // 결과가 없는 경우 기본값 설정
                $monthly_premium1 = 0;
                $monthly_premium2 = 0;
                $monthly_premium_total = 0;
                
                // 로그 기록 (선택 사항)
                error_log("보험료 정보를 찾을 수 없음: cNum=$cNum, age=$age");
            }

            // 두 번째 쿼리: kj_insurance_premium_data 테이블에서 10회 분납 보험료 정보 조회
            $mSql2 = "SELECT payment10_premium1, payment10_premium2, payment10_premium_total 
                    FROM kj_insurance_premium_data 
                    WHERE policyNum = :policyNum AND start_month <= :age AND end_month >= :age3";
            $premiumStmt2 = $pdo->prepare($mSql2);
            $premiumStmt2->bindParam(':policyNum', $policyNum, PDO::PARAM_STR); // cNum이 아닌 policyNum으로 수정
            $premiumStmt2->bindParam(':age', $age, PDO::PARAM_INT);
            $premiumStmt2->bindParam(':age3', $age, PDO::PARAM_INT);
            $premiumStmt2->execute();
            $mrow2 = $premiumStmt2->fetch(PDO::FETCH_ASSOC);
            // 결과가 있는지 확인하고 값 추출
            if ($mrow2) {
                $payment10_premium1 = $mrow2['payment10_premium1']; // 1/10 기본보험료
                $payment10_premium2 = $mrow2['payment10_premium2']; // 1/10 특약보험료
                $payment10_premium_total = $mrow2['payment10_premium_total']; // 1/10 기본+특약 합계
            } else {
                // 결과가 없는 경우 기본값 설정
                $payment10_premium1 = 0;
                $payment10_premium2 = 0;
                $payment10_premium_total = 0;
                
                // 로그 기록 (선택 사항)
                error_log("10회 분납 보험료 정보를 찾을 수 없음: policyNum=$policyNum, age=$age");
            }

            // 계산된 보험료 정보를 행에 추가 (선택 사항)
            

    $row['monthly_premium_total'] = $monthly_premium_total;  // 월별 보험료
    $AdjustedInsuranceMothlyPremium = floor(($monthly_premium_total * $discountRate['rate'])/10)*10;
    $row['AdjustedInsuranceMothlyPremium'] = $AdjustedInsuranceMothlyPremium;
    
    $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10
    $AdjustedInsuranceCompanyPremium = floor(($payment10_premium_total * $discountRate['rate'])/10)*10;
    $row['AdjustedInsuranceCompanyPremium'] = $AdjustedInsuranceCompanyPremium;
    
    $row['ConversionPremium'] = floor(($AdjustedInsuranceCompanyPremium * 10 / 12)/10)*10; // 보험회사 보험료를 월 환산
    $data[] = $row;
}

// 전체 레코드 수 계산
$totalCount = count($data);

// SMSData 테이블에서 추가 데이터 조회
$broader_sql = "SELECT dm.Name, dm.Jumin, dm.nai, dm.dongbuCerti, dm.endorse_day, dm.InsuranceCompany, dm.etag, dm.CertiTableNum,
                sd.preminum, sd.c_preminum, sd.push 
                FROM `DaeriMember` dm
                JOIN `SMSData` sd ON dm.`2012DaeriCompanyNum` = sd.`2012DaeriCompanyNum` 
                    AND dm.num = sd.`2012DaeriMemberNum`
                WHERE sd.endorse_day >= :lastMonthDueDate 
                    AND sd.endorse_day <= :thisMonthDueDate 
                    AND dm.`2012DaeriCompanyNum` = :dNum 
                    AND sd.dagun = '1'
                    AND sd.get = '2'"; //미정산건만 

$broader_stmt = $pdo->prepare($broader_sql);
$broader_stmt->bindParam(':lastMonthDueDate', $lastMonthDueDate);
$broader_stmt->bindParam(':thisMonthDueDate', $thisMonthDueDate);
$broader_stmt->bindParam(':dNum', $dNum);
$broader_stmt->execute();

$smsData = $broader_stmt->fetchAll(PDO::FETCH_ASSOC);

// 담당자 정보 가져오기 (테이블명 수정 필요)
$담당자Sql = "SELECT name FROM 담당자테이블 WHERE num = :dNum LIMIT 1";
$담당자Stmt = $pdo->prepare($담당자Sql);
$담당자Stmt->bindParam(':dNum', $dNum);
$담당자Stmt->execute();
$담당자이름 = "";
if ($담당자Row = $담당자Stmt->fetch(PDO::FETCH_ASSOC)) {
    $담당자이름 = $담당자Row['name'];
}

// 메모 정보 가져오기 (테이블명 수정 필요)
$memoSql = "SELECT memo FROM 메모테이블 WHERE num = :dNum LIMIT 1";
$memoStmt = $pdo->prepare($memoSql);
$memoStmt->bindParam(':dNum', $dNum);
$memoStmt->execute();
$memo = "";
if ($memoRow = $memoStmt->fetch(PDO::FETCH_ASSOC)) {
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
header("Content-type: application/vnd.ms-excel; charset=utf-8");  
header("Content-Disposition: attachment; filename = {$output_file_name}.xls");  
header("Content-Description: PHP Generated Data");

// 현재 명단
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
    $name = $row['Name'];
    $jumin = $row['Jumin'];
    $nai = $row['nai'];
    
    $InsuranceCompany = match($row['InsuranceCompany']) {
        1 => '흥국',
        2 => 'DB',
        3 => 'KB',
        4, 5 => '현대',
        6 => '롯데',
        7 => 'MG',
        8 => '삼성',
        default => ''
    };
    
    $etag = match($row['etag']) {
        1 => '대리',
        2 => '탁송',
        3 => '대리렌트',
        4 => '탁송렌트',
        default => ''
    };
    
    $dongbuCerti = $row['dongbuCerti'];
    
    $pSql = "SELECT * FROM 2012CertiTable WHERE num = :certiNum";
    $pStmt = $pdo->prepare($pSql);
    $pStmt->bindParam(':certiNum', $row['CertiTableNum']);
    $pStmt->execute();
    $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
    
    // 월납/정상납 구분 및 각각의 보험료 계산
    $monthlyPremium = 0;
    $adjustedPremium = 0;
    $companyPremium = 0;
    
    if ($pRow['divi'] == 2) { // 1/12 납인 경우에만
        $gita = "월납";
        $monthlyPremium = $row['AdjustedInsuranceMothlyPremium'] ?? 0;
        $adjustedPremium = 0;
        $companyPremium = $row['ConversionPremium'] ?? 0;
    } else {
        $gita = "정상납";
        $monthlyPremium = 0;
        $adjustedPremium = $row['AdjustedInsuranceCompanyPremium'] ?? 0;
        $companyPremium = 0;
    }
    
    // 출력 부분
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
        <td style=\"text-align:center;mso-number-format:'\@';\">".$row['dongbuCerti']."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$discountRate['rate']."</td>
        <td style=\"text-align:center;mso-number-format:'\@';\">".$discountRate['name']."</td>
    </tr>";
    
    $j++;
}

// 합계 계산
$sum_monthlyPremium = 0;
$sum_companyPremium = 0;
$sum_adjustedPremium = 0;

// 합계 계산 (납부 방식 구분하여 계산)
foreach ($data as $row) {
    $pSql = "SELECT * FROM 2012CertiTable WHERE num = :certiNum";
    $pStmt = $pdo->prepare($pSql);
    $pStmt->bindParam(':certiNum', $row['CertiTableNum']);
    $pStmt->execute();
    $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pRow['divi'] == 2) { // 월납인 경우
        $monthlyPremium = $row['AdjustedInsuranceMothlyPremium'] ?? 0;
        $sum_monthlyPremium += $monthlyPremium;
        $companyPremium = $row['ConversionPremium'] ?? 0;
        $sum_companyPremium += $companyPremium;
    } else { // 정상납인 경우
        $adjustedPremium = $row['AdjustedInsuranceCompanyPremium'] ?? 0;
        $sum_adjustedPremium += $adjustedPremium;
    }
}

// 합계 행 추가
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

// 배서 명단
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

// 배서 합계 변수 초기화
$sum_En_monthlyPremium = 0;
$sum_En_adjustedPremium = 0;

$j_ = 1;
foreach ($smsData as $erow) {
    $inName = match($erow['InsuranceCompany']) {
        1 => '흥국',
        2 => 'DB',
        3 => 'KB',
        4, 5 => '현대',
        6 => '더케이',
        7 => 'MG',
        8 => '삼성',
        default => ''
    };
    
    if ($erow['InsuranceCompany'] == 2) {
        $erow['dongbuCerti'] = "017-".$erow['dongbuCerti']."-000";
    }
    
    $metat = match($erow['etag']) {
        1 => '대리',
        2 => '탁송',
        3 => '대리/렌트',
        4 => '탁송/렌트',
        5 => '전탁송',
        default => ''
    };
    
    $pushName = match($erow['push']) {
        2 => '해지',
        4 => '추가',
        default => ''
    };
    
    if ($erow['push'] == 2) {
        $erow['preminum'] = -$erow['preminum'];
        $erow['c_preminum'] = -$erow['c_preminum'];
    }
    
    $pSql = "SELECT * FROM 2012CertiTable WHERE num = :certiNum";
    $pStmt = $pdo->prepare($pSql);
    $pStmt->bindParam(':certiNum', $erow['CertiTableNum']);
    $pStmt->execute();
    $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
    
    // 납부 방식에 따라 보험료 처리 및 합계 누적
    if ($pRow['divi'] == 2) { // 1/12 납인 경우
        $gita = "월납";
        $EnmonthlyPremium = $erow['preminum'] ?? 0;
        $sum_En_monthlyPremium += $EnmonthlyPremium;
        $erow['c_preminum'] = 0;
    } else {
        $gita = "정상납";
        $erow['preminum'] = 0;
        $EnadjustedPremium = $erow['c_preminum'] ?? 0;
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
            <td>&nbsp;</td>
       </tr>  
       ";  
    $j_++;
}

$EXCEL_STR2 .= "   
<tr>  
   <td colspan='8' style=\"text-align:center;mso-number-format:'\@';\">배서 보험료 소계".$lastMonthDueDate."~".$thisMonthDueDate."</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_En_monthlyPremium)."</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td style=\"text-align:right;mso-number-format:'\@';\">".number_format($sum_En_adjustedPremium)."</td>
   <td>&nbsp;</td>
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
echo "<meta content=\"application/vnd.ms-excel; charset=utf-8\" name=\"Content-type\"> ";  
echo $EXCEL_STR;
echo $EXCEL_STR2;  

?>