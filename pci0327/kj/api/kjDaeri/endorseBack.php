<?php

/*# 증권번호 또는 대리운전회사 별 배서 리스트 처리

## 파일 개요
- 목적: 증권번호 또는 대리운전회사 별 배서리스트 처리
- 기능: certi 처리 (certi=1은 모든 증권을 의미), 월보험료 계산, 정상납 보험료 계산

``

/* 증권번호 또는 대리운전회사 별 배서리트스 
    // certi 처리 (certi=1은 모든 증권을 의미)
    월보험료 계산, 정상납 보험료 계산
*/
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$conn = getDbConnection();
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
include "./php/encryption.php";
/*include "./php/calculateEndorsePremium.php"; // 배서 보험료 계산 함수
include "./php/calculateProRatedFee.php"; // 일할 계산 함수
include "./php/calculateAge.php"; // 나이 계산 함수*/

// GET 파라미터 가져오기
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 15;
$certi = $_GET['certi'] ?? '';
$dNum = $_GET['dNum'] ?? '';
$push = $_GET['push'] ?? ''; // 1 은청약 2해지
$offset = ($page - 1) * $limit;

// WHERE 조건 구성
$whereConditions = ["sangtae = '1'"];
$params = [];

// certi 처리 (certi=1은 모든 증권을 의미)
if ($certi != '' && $certi != '1') {
    $whereConditions[] = "dongbuCerti = :certi";
    $params[':certi'] = $certi;
}

// dNum 처리
if ($dNum != '') {
    $whereConditions[] = "2012DaeriCompanyNum = :dNum";
    $params[':dNum'] = $dNum;
}

if ($push != '') {
    $whereConditions[] = "push = :push";
    $params[':push'] = $push;
}

// WHERE 조건을 문자열로 결합
$whereClause = implode(' AND ', $whereConditions);

try {
    // 전체 레코드 수 조회
    $sqlCount = "SELECT COUNT(*) AS total FROM DaeriMember WHERE $whereClause";
    $stmtCount = $conn->prepare($sqlCount);
    foreach ($params as $key => $value) {
        $stmtCount->bindValue($key, $value);
    }
    $stmtCount->execute();
    $totalRecords = (int)$stmtCount->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    // *** PUSH 값에 따른 개수 조회 ***
    $pushCounts = [];

    // 청약(push=1) 개수 조회
    $sqlPush1 = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause AND push = '1'";
    $stmtPush1 = $conn->prepare($sqlPush1);
    foreach ($params as $key => $value) {
        $stmtPush1->bindValue($key, $value);
    }
    $stmtPush1->execute();
    $pushCounts['subscription'] = (int)$stmtPush1->fetchColumn();

    // 해지(push=4) 개수 조회
    $sqlPush4 = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause AND push = '4'";
    $stmtPush4 = $conn->prepare($sqlPush4);
    foreach ($params as $key => $value) {
        $stmtPush4->bindValue($key, $value);
    }
    $stmtPush4->execute();
    $pushCounts['termination'] = (int)$stmtPush4->fetchColumn();

    // 전체 개수 조회
    $sqlPushOther = "SELECT COUNT(*) AS count FROM DaeriMember WHERE $whereClause";
    $stmtPushOther = $conn->prepare($sqlPushOther);
    foreach ($params as $key => $value) {
        $stmtPushOther->bindValue($key, $value);
    }
    $stmtPushOther->execute();
    $pushCounts['total'] = (int)$stmtPushOther->fetchColumn();

    // JOIN을 사용하여 필요한 데이터 가져오기
    $sql = "
        SELECT 
            num, Name, nai, dongbuCerti, Jumin, push, wdate, InPutDay, OutPutDay, `2012DaeriCompanyNum`,
            CertiTableNum, EndorsePnum, InsuranceCompany, endorse_day, etag, manager, Hphone, progress, JuminHash
        FROM DaeriMember 
        WHERE $whereClause
        ORDER BY Jumin ASC, dongbuCerti DESC, push DESC, wdate ASC, InPutDay ASC, OutPutDay ASC
        LIMIT :offset, :limit";
    
    $mainStmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $mainStmt->bindValue($key, $value);
    }
    $mainStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $mainStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $mainStmt->execute();
    
    $data = [];

    while ($row = $mainStmt->fetch(PDO::FETCH_ASSOC)) {
        // 보험료 산출을 하기 위해 
        $dNum = $row['2012DaeriCompanyNum'];  // 
        $cNum = $row['CertiTableNum'];  // 
        $pNum = $row['EndorsePnum'];
        $policyNum = $row['dongbuCerti'];
        
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
        
        // 주민번호 분리 개선 - 하이픈 유무와 관계없이 처리
        if (strpos($jumin, '-') !== false) {
            $ju = explode('-', $jumin);
        } else {
            $ju = [substr($jumin, 0, 6), substr($jumin, 6)];
        }
        
        // 주민번호 검증 - 길이 확인
        if (empty($ju[0]) || empty($ju[1]) || strlen($ju[0]) != 6 || strlen($ju[1]) < 7) {
            // 유효하지 않은 주민번호 처리
            $ju = ["000000", "0000000"]; // 기본값 설정
        }
        
        $row['jumin2'] = $ju[0] . $ju[1]; // 하이푼 없는 주민번호
        $InsuraneCompany = $row['InsuranceCompany'];  // 보험회사 
        $etag = $row['etag']; // "1": "대리", "2": "탁송", "3": "대리렌트", "4": "탁송렌트" 
        $endorse_day = $row['endorse_day'];
        $push = $row['push'];

        // SQL 인젝션을 방지하기 위해 자리 표시자를 사용하여 쿼리 준비 - 고유한 변수명 사용
        $rSql = "SELECT rate FROM `2019rate` WHERE policy = :policy AND jumin = :jumin";
        $rateStmt = $conn->prepare($rSql);

        // 안전하게 매개변수 바인딩
        $rateStmt->bindParam(':policy', $policyNum, PDO::PARAM_STR);
        $rateStmt->bindParam(':jumin', $jumin, PDO::PARAM_STR);

        // 쿼리 실행
        $rateStmt->execute();

        // 결과 가져오기
        $rRow = $rateStmt->fetch(PDO::FETCH_ASSOC);

        // 결과가 존재하면 rate 값 설정 - null 체크 추가
        $row['rate'] = $rRow ? $rRow['rate'] : null;
        $personRate = $row['rate'];
        $discountRate = calculatePersonalRate($row['rate']);
		
        // CertiTableNum 조회하여 증권의 
        // 보험시작일 $startDay     
        // 분납횟수 $nabang  
        // 분납회차 $nabang_1 
        // calculateProRatedFee 함수에서 사용 

        // 보험회사 보험료 2012Certi 종합적으로 관리함 2025-04-01
        // 첫 번째 쿼리: 2012CertiTable에서 정보 가져오기
        $Csql2 = "SELECT divi FROM 2012CertiTable WHERE num = :cNum";
        $certiTableStmt = $conn->prepare($Csql2);
        $certiTableStmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
        $certiTableStmt->execute();
        $Crow2 = $certiTableStmt->fetch(PDO::FETCH_ASSOC);

        /*
        $startDay=$Crow2['startyDay']; // 보험시작일
        $nabang=$Crow2['nabang'];     // 분납횟수
        $nabang_1=$Crow2['nabang_1']; // 분납회차
        */

        // divi 값 가져오기 (1정상납, 2월납)
        $divi = $Crow2['divi'] ?? null;

        // 두 번째 쿼리: 2012Certi에서 정보 가져오기
        $Csql = "SELECT sigi, nab FROM 2012Certi WHERE certi = :policyNum";
        $certiStmt = $conn->prepare($Csql);
        $certiStmt->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
        $certiStmt->execute();
        $Crow = $certiStmt->fetch(PDO::FETCH_ASSOC);

        // 결과 저장
        $startDay = $Crow['sigi'] ?? null; // 보험시작일
        $nabang = 10;                      // 분납횟수
        $nabang_1 = $Crow['nab'] ?? null;  // 분납회차

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
                $dateString = $Crow['sigi'] ?? $endorse_day; // 보험시작일 사용, null이면 배서일 사용
                break;
            default:
                // 기본값으로 배서기준일 사용
                $dateString = $endorse_day;
        }

        // 날짜 형식 검증
        if (empty($dateString) || !strtotime($dateString)) {
            $dateString = date('Y-m-d'); // 유효하지 않은 날짜는 오늘 날짜로 대체
        }

        // calculateAge 함수에 문자열 형태의 날짜 전달
        $personInfo = calculateAge($ju[0], $ju[1], $dateString);
        $age = $personInfo['age'] ?? 0; // null 안전 처리

        if ($push == 1) { // 나이 계산
			if($ju[0]!='000000'){//2025-05-17 케이드라이브 경우엔 나이계산 하지마세요
				$updateStmt = $conn->prepare("UPDATE DaeriMember SET nai = :age WHERE num = :num");
				$updateStmt->bindValue(':age', $age);
				$updateStmt->bindValue(':num', $row['num']);
				$updateStmt->execute();
			}
            $row['nai'] = $age;
        }

        // 대리운전 회사 정보 조회 - PDO 사용
        $Dsql = "SELECT company, FirstStart, MemberNum, jumin, hphone, cNumber FROM 2012DaeriCompany WHERE num = :dNum";
        $companyStmt = $conn->prepare($Dsql);
        $companyStmt->bindValue(':dNum', $dNum, PDO::PARAM_STR);
        $companyStmt->execute();
        $Drow = $companyStmt->fetch(PDO::FETCH_ASSOC);
        $row['company'] = $Drow['company'] ?? '';
        $row['MemberNum'] = $Drow['MemberNum'] ?? '';
        
        // 배서발생일 calculateProRatedFee 함수에서 사용
        list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);
        
        /* 보험료 산출은 $row['rate'] 입력된 경우만 산출하자 */
        // 정기결제일 분리
        if (isset($Drow['FirstStart']) && !empty($Drow['FirstStart'])) {
            list($duYear, $duMonth, $dueDay) = explode("-", $Drow['FirstStart'], 3);
        }
        
        if ($row['rate']) {
            // 첫 번째 쿼리: kj_premium_data 테이블에서 월 보험료 정보 조회
            $mSql = "SELECT monthly_premium1, monthly_premium2, monthly_premium_total 
                    FROM kj_premium_data 
                    WHERE cNum = :cNum AND start_month <= :age AND end_month >= :age2";

            $premiumStmt = $conn->prepare($mSql);
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
            $premiumStmt2 = $conn->prepare($mSql2);
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
            $row['monthly_premium1'] = $monthly_premium1;
            $row['monthly_premium2'] = $monthly_premium2;
            $row['monthly_premium_total'] = $monthly_premium_total;
            $row['payment10_premium1'] = $payment10_premium1;
            $row['payment10_premium2'] = $payment10_premium2;
            $row['payment10_premium_total'] = $payment10_premium_total;

            //$row['discountRate'] = $discountRate['rate'];

            // 월보험료 산출
            $insurancePremiumData = calculateProRatedFee(
                (int)$monthly_premium1, 
                (int)$monthly_premium2, 
                (int)$monthly_premium_total, 
                (int)$InsuraneCompany, 
                (int)$dueDay, 
                (int)$eDay, 
                (int)$eMonth, 
                (int)$eYear, 
                (float)$discountRate['rate'], 
                (int)$etag
            );
            $row['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];

            // 보험회사 보험료 산출
            // 변수 유효성 검사 및 기본값 설정
            $startDay = !empty($startDay) && strtotime($startDay) ? $startDay : date('Y-m-d');
            $endorse_day = !empty($endorse_day) && strtotime($endorse_day) ? $endorse_day : date('Y-m-d');
            $nabang = is_numeric($nabang) ? (int)$nabang : 10; // 기본값 10
            $nabang_1 = is_numeric($nabang_1) ? (int)$nabang_1 : 1; // 기본값 1
            $payment10_premium1 = is_numeric($payment10_premium1) ? (float)$payment10_premium1 : 0;
            $payment10_premium2 = is_numeric($payment10_premium2) ? (float)$payment10_premium2 : 0;
            $payment10_premium_total = is_numeric($payment10_premium_total) ? (float)$payment10_premium_total : 0;
            $InsuraneCompany = is_numeric($InsuraneCompany) ? (int)$InsuraneCompany : 0;
            $discountRate_rate = isset($discountRate['rate']) && is_numeric($discountRate['rate']) ? (float)$discountRate['rate'] : 1.0;
            $etag = is_numeric($etag) ? (int)$etag : 1;

            // 디버깅을 위한 로그 추가
            error_log("calculateEndorsePremium 호출 매개변수: startDay=$startDay, endorse_day=$endorse_day, nabang=$nabang, nabang_1=$nabang_1, payment10_premium1=$payment10_premium1, payment10_premium2=$payment10_premium2, payment10_premium_total=$payment10_premium_total, InsuraneCompany=$InsuraneCompany, rate=$discountRate_rate, etag=$etag");

            // 함수 호출 시 명시적 타입 변환
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

            // 결과 확인을 위한 로그
            error_log("calculateEndorsePremium 결과: " . json_encode($EndorsePremiumYpremium));

            // 결과 검증 - 만약 계산된 보험료가 비정상적으로 크거나 작으면 로그 기록
            if (isset($EndorsePremiumYpremium['i_endorese_premium'])) {
                $premium = $EndorsePremiumYpremium['i_endorese_premium'];
                if ($premium < 0 || $premium > 1000000) { // 0원 미만 또는 100만원 초과시 의심
                    error_log("Warning: 비정상적인 보험료 계산 결과: $premium");
                }
            }

            // 결과 할당
            $row['Endorsement_insurance_company_premium'] = isset($EndorsePremiumYpremium['i_endorese_premium']) ? 
                (int)$EndorsePremiumYpremium['i_endorese_premium'] : 0;
        }

        // 중복여부 판단
        $hashJumin = $row['JuminHash'];
        $duplStmt = $conn->prepare("SELECT num FROM DaeriMember WHERE JuminHash = :hashJumin AND push = '4' AND CertiTableNum != :cNum");
        $duplStmt->bindValue(':hashJumin', $hashJumin);
        $duplStmt->bindValue(':cNum', $cNum);
        $duplStmt->execute();
        $drow = $duplStmt->fetch(PDO::FETCH_ASSOC);
        $row['duplNum'] = $drow['num'] ?? null; // 중복

		if($row['jumin2']=="0000000000000"){ //케이드라이브 인 경우 주민번호 없는  경우 
			$row['jumin2']='';	
			$row['nai']='';
		}
        $data[] = $row;
    }

    // 응답 구성
    $response = [
        "success" => true,
        "currentPage" => $page,
        "totalPages" => $totalPages,
        "totalRecords" => $totalRecords,
        "pushCounts" => $pushCounts,
        "mSql" => $mSql,
        "data" => $data
    ];

    // JSON으로 출력 (UTF-8 지원)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // 오류 발생 시
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // 연결 종료
    $conn = null;
}

/*

## 코드 구조

### 1. 초기 설정 및 파일 포함
- Content-Type 헤더 설정: JSON, UTF-8
- 데이터베이스 설정 및 연결 (PDO 사용)
- 필요한 함수 파일 include
  - monthlyFee.php: 월보험료 산출 함수
  - calculatePersonalRate.php: 개인 요율 계산 함수
  - encryption.php: 암호화 관련 함수

### 2. GET 파라미터 처리
- page: 페이지 번호 (기본값: 1)
- limit: 페이지당 레코드 수 (기본값: 15)
- certi: 증권번호 (certi=1은 모든 증권)
- dNum: 대리운전회사 번호
- push: 상태 코드 (1: 청약, 4: 해지)

### 3. 데이터베이스 쿼리
- WHERE 조건 구성: 안전한 매개변수 바인딩
- 전체 레코드 수 및 페이지 수 계산
- PUSH 값에 따른 개수 조회 (청약, 해지, 전체)
- 기본 회원 데이터 조회 (JOIN 사용)

### 4. 데이터 처리 과정
- 개인정보 복호화 (전화번호, 주민번호)
- 주민번호 분리 및 검증
- 개인 요율 계산
- 증권 정보 조회
- 보험회사별 기준일 결정
- 나이 계산 및 업데이트
- 대리운전 회사 정보 조회

### 5. 보험료 계산 로직
- 월 보험료 정보 조회
- 10회 분납 보험료 정보 조회
- 월보험료 산출
- 보험회사 보험료 산출
- 중복 여부 판단

### 6. 응답 처리
- 성공 시: JSON 형식으로 데이터 반환
- 오류 시: 오류 메시지 반환
- 최종적으로 DB 연결 종료

*/
?>