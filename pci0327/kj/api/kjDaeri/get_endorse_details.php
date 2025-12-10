<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; 
include "./php/encryption.php";

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();
$conn = $pdo; // $conn 변수를 $pdo와 동일하게 설정

// GET 파라미터 가져오기
$endorse_day = isset($_GET['endorse_day']) ? $_GET['endorse_day'] : date('Y-m-d'); 
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';  
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  
// pNum 파라미터 제거 - 이 변수는 더 이상 사용하지 않음

$data = array();
$main_info = array();

// 디버깅을 위한 로그 추가
error_log("DEBUG: Parameters - endorse_day: $endorse_day, dNum: $dNum, cNum: $cNum");

if (!empty($cNum)) { // pNum 체크 제거
    // 첫 번째 쿼리: 최상위 정보 가져오기 - pNum 조건 제거
    $sql_main = "
        SELECT 
            dc.company, dc.Pname, dc.hphone, dc.MemberNum, dc.FirstStart,
            ct.startyDay AS startyDay, ct.nabang, ct.nabang_1, ct.divi, ct.policyNum, ct.InsuraneCompany
        FROM DaeriMember dm
        INNER JOIN 2012DaeriCompany dc ON dm.2012DaeriCompanyNum = dc.num
        INNER JOIN 2012CertiTable ct ON dm.CertiTableNum = ct.num
        WHERE dm.CertiTableNum = ?
        LIMIT 1
    ";

    // PDO를 사용한 Prepared Statement
    $stmt_main = $pdo->prepare($sql_main);
    $stmt_main->execute([$cNum]);
    
    if ($stmt_main->rowCount() > 0) {
        $main_info = $stmt_main->fetch(PDO::FETCH_ASSOC); // 한 개의 행만 가져옴
        error_log("DEBUG: Main info found: " . json_encode($main_info));
    } else {
        error_log("DEBUG: No main info found for cNum=$cNum");
    }
    $stmt_main = null; // PDO 문장 해제

    // 두 번째 쿼리: data 배열에 들어갈 정보 가져오기 - pNum 조건 제거
    $sql_data = "
        SELECT 
            m.num, m.Name, m.Jumin, m.nai, m.push, m.ch, m.etag, m.dongbuCerti, 
            m.InsuranceCompany, m.reasion, m.manager, m.EndorsePnum
        FROM DaeriMember m
        WHERE m.CertiTableNum = ?
        AND m.sangtae = '1'
        ORDER BY m.Jumin DESC
    ";

    $stmt_data = $pdo->prepare($sql_data);
    $stmt_data->execute([$cNum]);
    
    error_log("DEBUG: Data rows found: " . $stmt_data->rowCount());
    
    if ($stmt_data->rowCount() > 0) {
        while ($row = $stmt_data->fetch(PDO::FETCH_ASSOC)) {
            $age = $row['nai'];
            $InsuraneCompany = $row['InsuranceCompany'];
            $etag = $row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 

            $policyNum = $row['dongbuCerti'];
            
            // Hphone 값이 존재하면 복호화
            if (isset($row['Hphone']) && $row['Hphone'] != "") {
                $decrypted = decryptData($row['Hphone']);
                if ($decrypted != "") {
                    $row['Hphone'] = $decrypted;
                } 
            }

            // Jumin 값이 존재하면 복호화
            if ($row['Jumin'] != "") {
                $decrypted = decryptData($row['Jumin']);
                if ($decrypted != "") {
                    $row['Jumin'] = $decrypted;
                } 
            }
            
            $jumin = $row['Jumin']; 
            $ju = explode('-', $jumin); 

            // 2012Certi 테이블에서 정보 가져오기
            $Csql = "SELECT * FROM 2012Certi WHERE certi = ?";
            $Cstmt = $pdo->prepare($Csql);
            $Cstmt->execute([$policyNum]);
            $Crow = $Cstmt->fetch(PDO::FETCH_ASSOC);
            $Cstmt = null; // PDO 문장 해제
            
            $startDay = isset($Crow['sigi']) ? $Crow['sigi'] : ''; //보험시작일
            $nabang = 10;     //분납횟수
            $nabang_1 = isset($Crow['nab']) ? $Crow['nab'] : 1; //분납회차
            
            // 보험회사별 기준일 결정
            switch($InsuraneCompany) {
                case 1: // 흥국
                case 2: // 동부
                    $dateString = $endorse_day; // 배서일 사용
                    break;
                case 3: // 기타 보험사
                case 4:
                case 5:
                case 7:
                    $dateString = isset($Crow['sigi']) ? $Crow['sigi'] : $endorse_day; // 보험시작일 사용, null이면 배서일 사용
                    break;
                default:
                    // 기본값으로 배서기준일 사용
                    $dateString = $endorse_day;
            }

            // 날짜 형식 검증
            if (empty($dateString) || !strtotime($dateString)) {
                $dateString = date('Y-m-d'); // 유효하지 않은 날짜는 오늘 날짜로 대체
            }
            
            // calculateAge 함수가 존재하는지 확인하고 사용
            if (function_exists('calculateAge')) {
                $personInfo = calculateAge($ju[0], $ju[1], $dateString); 
                $age = $personInfo['age'];
            } else {
                // calculateAge 함수가 없는 경우 대체 로직
                error_log("DEBUG: calculateAge function does not exist");
                // 간단한 나이 계산 로직을 여기에 추가할 수 있음
            }

            // 2019rate 테이블에서 rate 가져오기
            $rSql = "SELECT rate FROM `2019rate` WHERE policy = :policy AND jumin = :jumin";
            $rateStmt = $pdo->prepare($rSql);
            $rateStmt->bindParam(':policy', $policyNum, PDO::PARAM_STR);
            $rateStmt->bindParam(':jumin', $jumin, PDO::PARAM_STR);
            $rateStmt->execute();
            $rRow = $rateStmt->fetch(PDO::FETCH_ASSOC);
            
            // rate 값이 존재하면 설정
            if ($rRow && isset($rRow['rate'])) {
                $row['rate'] = $rRow['rate'];
                $discountRate = calculatePersonalRate($row['rate']);
            } else {
                $row['rate'] = null;
                $discountRate = ['rate' => 1.0]; // 기본값 설정
            }
            
            // 대리운전 회사 정보 조회
            $Dsql = "SELECT company, FirstStart, MemberNum, jumin, hphone, cNumber FROM 2012DaeriCompany WHERE num = :dNum";
            $companyStmt = $pdo->prepare($Dsql);
            $companyStmt->bindValue(':dNum', $dNum, PDO::PARAM_STR);
            $companyStmt->execute();
            $Drow = $companyStmt->fetch(PDO::FETCH_ASSOC);
            $row['company'] = isset($Drow['company']) ? $Drow['company'] : '';
            $row['MemberNum'] = isset($Drow['MemberNum']) ? $Drow['MemberNum'] : '';
            
            // 배서발생일 분리
            list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);
            
            // 정기결제일 분리
            $dueDay = null;
            if (isset($Drow['FirstStart']) && !empty($Drow['FirstStart'])) {
                list($duYear, $duMonth, $dueDay) = explode("-", $Drow['FirstStart'], 3);
            }
            
            // rate 값이 존재할 때만 보험료 계산
            if (isset($row['rate']) && $row['rate']) {
                // kj_premium_data 테이블에서 월 보험료 정보 조회
                $mSql = "SELECT monthly_premium1, monthly_premium2, monthly_premium_total 
                        FROM kj_premium_data 
                        WHERE cNum = :cNum AND start_month <= :age AND end_month >= :age2";
                $premiumStmt = $pdo->prepare($mSql);
                $premiumStmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
                $premiumStmt->bindParam(':age', $age, PDO::PARAM_INT);
                $premiumStmt->bindParam(':age2', $age, PDO::PARAM_INT);
                $premiumStmt->execute();
                $mrow = $premiumStmt->fetch(PDO::FETCH_ASSOC);
                
                // 결과가 있으면 값 설정
                if ($mrow) {
                    $monthly_premium1 = $mrow['monthly_premium1']; 
                    $monthly_premium2 = $mrow['monthly_premium2']; 
                    $monthly_premium_total = $mrow['monthly_premium_total']; 
                } else {
                    $monthly_premium1 = 0;
                    $monthly_premium2 = 0;
                    $monthly_premium_total = 0;
                    error_log("DEBUG: 보험료 정보를 찾을 수 없음: cNum=$cNum, age=$age");
                }
                
                // kj_insurance_premium_data 테이블에서 10회 분납 보험료 정보 조회
                $mSql2 = "SELECT payment10_premium1, payment10_premium2, payment10_premium_total 
                        FROM kj_insurance_premium_data 
                        WHERE policyNum = :policyNum AND start_month <= :age AND end_month >= :age3";
                $premiumStmt2 = $pdo->prepare($mSql2);
                $premiumStmt2->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
                $premiumStmt2->bindParam(':age', $age, PDO::PARAM_INT);
                $premiumStmt2->bindParam(':age3', $age, PDO::PARAM_INT);
                $premiumStmt2->execute();
                $mrow2 = $premiumStmt2->fetch(PDO::FETCH_ASSOC);
                
                // 결과가 있으면 값 설정
                if ($mrow2) {
                    $payment10_premium1 = $mrow2['payment10_premium1'];
                    $payment10_premium2 = $mrow2['payment10_premium2'];
                    $payment10_premium_total = $mrow2['payment10_premium_total'];
                } else {
                    $payment10_premium1 = 0;
                    $payment10_premium2 = 0;
                    $payment10_premium_total = 0;
                    error_log("DEBUG: 10회 분납 보험료 정보를 찾을 수 없음: policyNum=$policyNum, age=$age");
                }
                
                // 계산된 보험료 정보를 행에 추가
                $row['monthly_premium1'] = $monthly_premium1;
                $row['monthly_premium2'] = $monthly_premium2;
                $row['monthly_premium_total'] = $monthly_premium_total;
                $row['payment10_premium1'] = $payment10_premium1;
                $row['payment10_premium2'] = $payment10_premium2;
                $row['payment10_premium_total'] = $payment10_premium_total;
                
                // calculateProRatedFee 함수가 존재하는지 확인하고 사용
                if (function_exists('calculateProRatedFee')) {
                    // 월보험료 산출
                    $insurancePremiumData = calculateProRatedFee(
                        (int)$monthly_premium1, 
                        (int)$monthly_premium2, 
                        (int)$monthly_premium_total, 
                        (int)$InsuraneCompany, 
                        isset($dueDay) ? (int)$dueDay : 1, 
                        (int)$eDay, 
                        (int)$eMonth, 
                        (int)$eYear, 
                        (float)$discountRate['rate'], 
                        (int)$etag
                    );
                    $row['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];
                    
                    // 상세 계산 정보를 Debug에 추가
                    $row['dueDay'] = (int)$insurancePremiumData['dueDay'];
                    $row['startDay'] = (int)$insurancePremiumData['startDay'];
                    $row['month'] = (int)$insurancePremiumData['month'];
                    $row['year'] = (int)$insurancePremiumData['year'];
                    $row['totalDays'] = (int)$insurancePremiumData['totalDays'];
                    $row['daysLeft'] = (int)$insurancePremiumData['daysLeft'];
                    $row['dailyFee'] = (float)$insurancePremiumData['dailyFee'];
                    $row['fee_rate'] = (float)$insurancePremiumData['rate'];
                    $row['monthlyFee1'] = (int)$insurancePremiumData['monthlyFee1'];
                    $row['monthlyFee2'] = (int)$insurancePremiumData['monthlyFee2'];
                    $row['monthlyFee3'] = (int)$insurancePremiumData['monthlyFee3'];
                    $row['insurance'] = (int)$insurancePremiumData['insurance'];
                } else {
                    error_log("DEBUG: calculateProRatedFee function does not exist");
                }
                
                // 변수 유효성 검사 및 기본값 설정
                $startDay = !empty($startDay) && strtotime($startDay) ? $startDay : date('Y-m-d');
                $endorse_day = !empty($endorse_day) && strtotime($endorse_day) ? $endorse_day : date('Y-m-d');
                $nabang = is_numeric($nabang) ? (int)$nabang : 10;
                $nabang_1 = is_numeric($nabang_1) ? (int)$nabang_1 : 1;
                $payment10_premium1 = isset($payment10_premium1) && is_numeric($payment10_premium1) ? (float)$payment10_premium1 : 0;
                $payment10_premium2 = isset($payment10_premium2) && is_numeric($payment10_premium2) ? (float)$payment10_premium2 : 0;
                $payment10_premium_total = isset($payment10_premium_total) && is_numeric($payment10_premium_total) ? (float)$payment10_premium_total : 0;
                $discountRate_rate = isset($discountRate['rate']) && is_numeric($discountRate['rate']) ? (float)$discountRate['rate'] : 1.0;
                
                // calculateEndorsePremium 함수가 존재하는지 확인하고 사용
                if (function_exists('calculateEndorsePremium')) {
                    // 보험회사 보험료 산출
                    $EndorsePremiumYpremium = calculateEndorsePremium(
                        (string)$startDay, 
                        (string)$endorse_day, 
                        (int)$nabang, 
                        (int)$nabang_1, 
                        (float)$payment10_premium1, 
                        (float)$payment10_premium2, 
                        (float)$payment10_premium_total, 
                        (int)$InsuraneCompany, 
                        (float)$discountRate_rate, 
                        (int)$etag
                    );
                    
                    // 결과 할당
                    if (isset($EndorsePremiumYpremium['i_endorese_premium'])) {
                        $row['Endorsement_insurance_company_premium'] = (int)$EndorsePremiumYpremium['i_endorese_premium'];
                        
                        // 상세 계산 정보 추가
                        $row['payment10_premium1'] = (int)$EndorsePremiumYpremium['payment10_premium1'];
                        $row['payment10_premium2'] = (int)$EndorsePremiumYpremium['payment10_premium2'];
                        $row['payment10_premium_total'] = (int)$EndorsePremiumYpremium['payment10_premium_total'];
                        $row['endorse_day2'] = $EndorsePremiumYpremium['endorse_day'];
                        $row['nabang'] = (int)$EndorsePremiumYpremium['nabang'];
                        $row['nabang_1'] = (int)$EndorsePremiumYpremium['nabang_1'];
                        $row['yearPremium'] = (int)$EndorsePremiumYpremium['yearPremium'];
                        $row['oneDayPremium'] = (int)$EndorsePremiumYpremium['oneDayPremium'];
                        $row['unexpired_period_premium'] = (int)$EndorsePremiumYpremium['unexpired_period_premium'];
                        $row['unexpired_period'] = (int)$EndorsePremiumYpremium['unexpired_period'];
                        $row['daum_premium'] = (int)$EndorsePremiumYpremium['daum_premium'];
                        $row['InsuraneCompany'] = (int)$EndorsePremiumYpremium['InsuraneCompany'];
                        $row['personRate2'] = $EndorsePremiumYpremium['personRate2'];
                        $row['etag2'] = (int)$EndorsePremiumYpremium['etag'];
                    }
                } else {
                    error_log("DEBUG: calculateEndorsePremium function does not exist");
                }
            }
            
            $data[] = $row;
        }
    }
    $stmt_data = null; // PDO 문장 해제
}

// 응답 데이터 구성
$response = array(
    "success" => true
);

// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// 배열 데이터를 추가
$response["data"] = $data;

// 디버깅을 위한 로그 추가
error_log("DEBUG: Final response: " . json_encode($response));

// JSON 변환 후 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>