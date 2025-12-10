<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기
    $pdo = getDbConnection();
    
    include "./php/encryption.php";
    include "./php/calculatePersonalRate.php"; // 함수를 먼저 include
    
    // GET 파라미터 가져오기 및 정수로 변환
    $sj = $_GET['sj'] ?? '';
    $gisa_name = $_GET['gisa_name'] ?? '';
    $gisaPushSangtae = $_GET['gisaPushSangtae'] ?? '';
    
    // 접근 권한 확인
    if ($sj != 'gisa_') {
        // 잘못된 접근일 경우 오류 응답 반환 후 종료
        $response = [
            "success" => false,
            "message" => "잘못된 접근"
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit; // 중요: 이후 코드 실행 방지
    }
    
    // where절 조건 초기화
    $where2 = "";
    $params = ['gisa_name' => '%' . $gisa_name . '%'];
    
    if($gisaPushSangtae == 1){
        $where2 = "AND m.push='4'";
    }
    
    // 회원 목록 조회 쿼리 - prepared statement 사용
    $sql = "SELECT m.num, m.Name, m.nai, m.dongbuCerti, m.Jumin, m.push, 
                   m.wdate, m.InPutDay, m.OutPutDay, m.2012DaeriCompanyNum,
                   m.CertiTableNum, m.EndorsePnum, m.InsuranceCompany, 
                   m.endorse_day, m.etag, m.manager, m.Hphone, m.cancel,m.wdate,m.InputDay,m.OutPutDay,
                   c.company 
            FROM DaeriMember m
            LEFT JOIN 2012DaeriCompany c ON m.2012DaeriCompanyNum = c.num
            WHERE m.Name LIKE :gisa_name $where2 
            ORDER BY m.Jumin ASC";
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $data = [];
    
    // 결과 처리
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              
        
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
            $rateSql = "SELECT rate FROM `2019rate` WHERE policy = :policy AND jumin = :jumin LIMIT 1";
            $rateStmt = $pdo->prepare($rateSql);
            $rateStmt->execute(['policy' => $policyNum, 'jumin' => $jumin]);
            
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

        
        $row['monthly_premium_total'] = $monthly_premium_total;  //월별 보험료
        
        $AdjustedInsuranceMothlyPremium = floor(($monthly_premium_total * $discountRate['rate']) / 10) * 10;
        $row['AdjustedInsuranceMothlyPremium'] = $AdjustedInsuranceMothlyPremium;
        
        $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10;
        
        $AdjustedInsuranceCompanyPremium = floor(($payment10_premium_total * $discountRate['rate']) / 10) * 10;
        $row['AdjustedInsuranceCompanyPremium'] = $AdjustedInsuranceCompanyPremium;
        
        $row['ConversionPremium'] = floor(($AdjustedInsuranceCompanyPremium * 10 / 12) / 10) * 10; //보험회사 보험료를 월 환산
        
        $data[] = $row;
    }
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "data" => $data
    ];
    
    // JSON 출력 (UTF-8 지원)
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    // 데이터베이스 에러 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // 기타 에러 처리
    $response = [
        "success" => false,
        "message" => "일반 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}