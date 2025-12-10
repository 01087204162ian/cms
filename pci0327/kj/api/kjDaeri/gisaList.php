<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";

// 파라미터 가져오기
$dNum = $_GET['dNum'] ?? ''; 
$sort = $_GET['sort'] ?? '';  
// sort 1이면 현재와 같이 $dNum 이 2012DaeriCompanyNum 의미하고
// sort 2이면 $dNum Jumin 을 의미한다.
$dongbuCerti = $_GET['dongbuCerti'] ?? '';
$page = (int)($_GET['page'] ?? 1);
$itemsPerPage = (int)($_GET['itemsPerPage'] ?? 15);

// 시작 위치 계산
$start = ($page - 1) * $itemsPerPage;

// 쿼리 조건 설정 (sort에 따라 다른 조건 적용)
$whereCondition = "";
$params = [];

if ($sort == '2') {
    // sort=2: 주민번호로 검색

    $jumin_hash = sha1($dNum);
    $whereCondition = "JuminHash = :jumin_hash";
    $params[':jumin_hash'] = $jumin_hash;
} else {
    // sort=1 또는 기본값: 회사번호로 검색
    $whereCondition = "`2012DaeriCompanyNum` = :dNum";
    $params[':dNum'] = $dNum;
}

// 전체 레코드 수 먼저 계산
$countSql = "SELECT COUNT(*) as total FROM `DaeriMember` WHERE $whereCondition";
             
if (!empty($dongbuCerti)) {
    $countSql .= " AND dongbuCerti = :dongbuCerti";
    $params[':dongbuCerti'] = $dongbuCerti;
}
$countSql .= " AND push = '4'";

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalCount = $countStmt->fetchColumn();

// 기본 멤버 데이터 조회 (조인 없이)
$sql = "SELECT num, Name, nai, Jumin, Hphone, push, cancel, etag, 
               `2012DaeriCompanyNum`, CertiTableNum, dongbuCerti, InsuranceCompany
        FROM `DaeriMember`
        WHERE $whereCondition";

if (!empty($dongbuCerti)) {
    $sql .= " AND dongbuCerti = :dongbuCerti";
}
$sql .= " AND push = '4'";

// 정렬 적용
if ($sort == '2') {
    // 주민번호로 검색하는 경우 회사명으로 정렬
    $sql .= " ORDER BY `2012DaeriCompanyNum` ASC";
} else {
    // 회사번호로 검색하는 경우 주민번호로 정렬
    $sql .= " ORDER BY Jumin ASC";
}

$sql .= " LIMIT :start, :itemsPerPage";
$params[':start'] = $start;
$params[':itemsPerPage'] = $itemsPerPage;

$stmt = $pdo->prepare($sql);
// LIMIT 매개변수는 PDO에서 정수로 바인딩해야 함
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

// 나머지 매개변수들 바인딩
foreach ($params as $key => $value) {
    if ($key !== ':start' && $key !== ':itemsPerPage') {
        $stmt->bindValue($key, $value);
    }
}

$stmt->execute();
$data = [];

// 결과 처리
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    
    // 회사 정보 가져오기
    $companyNum = $row['2012DaeriCompanyNum'];
    $companySql = "SELECT company FROM 2012DaeriCompany WHERE num = :companyNum LIMIT 1";
    $companyStmt = $pdo->prepare($companySql);
    $companyStmt->bindValue(':companyNum', $companyNum);
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
        $rateSql = "SELECT rate FROM 2019rate WHERE policy = :policyNum AND jumin = :jumin LIMIT 1";
        $rateStmt = $pdo->prepare($rateSql);
        $rateStmt->bindValue(':policyNum', $policyNum);
        $rateStmt->bindValue(':jumin', $jumin);
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
    
    // 로그 기록
    error_log("보험료 정보를 찾을 수 없음: cNum=$cNum, age=$age");
}

// 두 번째 쿼리: kj_insurance_premium_data 테이블에서 10회 분납 보험료 정보 조회
$mSql2 = "SELECT payment10_premium1, payment10_premium2, payment10_premium_total 
          FROM kj_insurance_premium_data 
          WHERE policyNum = :policyNum AND start_month <= :age AND end_month >= :age3";

$premiumStmt2 = $pdo->prepare($mSql2);
$premiumStmt2->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
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
    
    // 로그 기록
    error_log("10회 분납 보험료 정보를 찾을 수 없음: policyNum=$policyNum, age=$age");
}
    $row['monthly_premium_total'] = $monthly_premium_total;  // 월별 보험료
    $AdjustedInsuranceMothlyPremium = floor(($monthly_premium_total * $discountRate['rate']) / 10) * 10;
    $row['AdjustedInsuranceMothlyPremium'] = $AdjustedInsuranceMothlyPremium;

    $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10
    $AdjustedInsuranceCompanyPremium = floor(($payment10_premium_total * $discountRate['rate']) / 10) * 10;
    $row['AdjustedInsuranceCompanyPremium'] = $AdjustedInsuranceCompanyPremium;
    
    $row['ConversionPremium'] = floor(($AdjustedInsuranceCompanyPremium * 10 / 12) / 10) * 10; // 보험회사 보험료를 월 환산
    $data[] = $row;
}

// 응답 데이터 구성
$response = [
    "success" => true,
    "data" => $data,
    "totalCount" => $totalCount,
    "totalPages" => ceil($totalCount / $itemsPerPage)
];

// json_encode_php4 대신 PHP 8.2의 기본 json_encode 사용
// 한글 깨짐 방지를 위한 JSON_UNESCAPED_UNICODE 옵션 추가
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>