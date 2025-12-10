<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";
// 파라미터 가져오기
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : ''; 
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';  
///sort 1이면 현재와 같이 $dNum 이 2012DaeriCompanyNum 의미하고
///sort 2이면 $dNum Jumin 을 의미한다.
$dongbuCerti = isset($_GET['dongbuCerti']) ? $_GET['dongbuCerti'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 15;

// 시작 위치 계산
$start = ($page - 1) * $itemsPerPage;

// 쿼리 조건 설정 (sort에 따라 다른 조건 적용)
$whereCondition = "";
if ($sort == '2') {
    // sort=2: 주민번호로 검색
	$jumin_hash = sha1($dNum);
    $whereCondition = "JuminHash='$jumin_hash'";
} else {
    // sort=1 또는 기본값: 회사번호로 검색
    $whereCondition = "`2012DaeriCompanyNum`='$dNum'";
}

// 전체 레코드 수 먼저 계산
$countSql = "SELECT COUNT(*) as total FROM `DaeriMember` WHERE $whereCondition";
             
if (!empty($dongbuCerti)) {
    $countSql .= " AND dongbuCerti='$dongbuCerti'";
}
$countSql .= " AND push='4'";
$countResult = mysql_query($countSql, $conn);
$totalCount = 0;
if ($countResult) {
    $row = mysql_fetch_assoc($countResult);
    $totalCount = $row['total'];
}

// 기본 멤버 데이터 조회 (조인 없이)
$sql = "SELECT num, Name,nai, Jumin, Hphone, push, cancel, etag, 
               `2012DaeriCompanyNum`, CertiTableNum, dongbuCerti, InsuranceCompany
        FROM `DaeriMember`
        WHERE $whereCondition";
if (!empty($dongbuCerti)) {
    $sql .= " AND dongbuCerti='$dongbuCerti'";
}
$sql .= " AND push='4'";

// 정렬 적용
if ($sort == '2') {
    // 주민번호로 검색하는 경우 회사명으로 정렬
    $sql .= " ORDER BY `2012DaeriCompanyNum` ASC";
} else {
    // 회사번호로 검색하는 경우 주민번호로 정렬
    $sql .= " ORDER BY Jumin ASC";
}

$sql .= " LIMIT $start, $itemsPerPage";

$result = mysql_query($sql, $conn);
$data = array();

// 결과 처리
if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
        // UTF-8 변환
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        
        // 회사 정보 가져오기
        $companyNum = $row['2012DaeriCompanyNum'];
        $companySql = "SELECT company FROM 2012DaeriCompany WHERE num='$companyNum' LIMIT 1";
        $companyResult = mysql_query($companySql, $conn);
        if ($companyResult && $companyRow = mysql_fetch_assoc($companyResult)) {
            $companyValue = $companyRow['company'];
            if (!is_numeric($companyValue) && !empty($companyValue)) {
                $converted = @iconv("EUC-KR", "UTF-8", $companyValue);
                $row['company'] = ($converted !== false) ? $converted : $companyValue;
            } else {
                $row['company'] = $companyValue;
            }
        } else {
            $row['company'] = '';
        }
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

		//보험료 계산 // 보험료 계산
        include "./php/premiumSearch.php";
        $row['monthly_premium_total'] = $monthly_premium_total;  //월별 보험료
		$AdjustedInsuranceMothlyPremium=floor(($monthly_premium_total*$discountRate['rate'])/10)*10;
		$row['AdjustedInsuranceMothlyPremium']=$AdjustedInsuranceMothlyPremium;
		//$total_AdjustedInsuranceMothlyPremium+=$AdjustedInsuranceMothlyPremium;


        $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10;
		$AdjustedInsuranceCompanyPremium=floor(($payment10_premium_total*$discountRate['rate'])/10)*10;
		$row['AdjustedInsuranceCompanyPremium']=$AdjustedInsuranceCompanyPremium;
		//$total_AdjustedInsuranceCompanyPremium+=$AdjustedInsuranceCompanyPremium;
        
		$row['ConversionPremium']=floor(($AdjustedInsuranceCompanyPremium*10/12)/10)*10; //보험회사 보험료를 월 환산
        $data[] = $row;
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "data" => $data,
    "totalCount" => $totalCount,
    "totalPages" => ceil($totalCount / $itemsPerPage)
);

echo json_encode_php4($response);
mysql_close($conn);
?>