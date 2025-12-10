<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

/*
2012CertiTable에서 특정 대리점 번호($dNum)와 최근 1년 내 시작된 증권 정보를 조회합니다.
조회된 각 증권에 대해:

1. DaeriMember 테이블에서 해당 증권에 속한 회원 정보를 조회합니다.
2. 각 회원의 보험료를 계산합니다.
3. 특정 기간 동안의 배서(endorsement) 정보를 SMSData 테이블에서 조회합니다.
4. 결과를 policyNum 별로 합산하여 반환합니다.

추가 기능: 
- 특정 증권($cNum)에 한정된 배서 정보와 전체 배서 정보의 차이를 계산하여 반환합니다.
- 한 증권번호에 여러 건의 배서 데이터가 있는 경우를 고려합니다.
- 각 policyNum별 대리기사 인원 수를 계산하여 반환합니다.
*/

include "./php/calculatePersonalRate.php"; // 함수를 먼저 include

// 파라미터 가져오기
$dNum = $_GET['dNum'] ?? '';
$lastMonthDueDate = $_GET['lastMonthDueDate'] ?? '';
$thisMonthDueDate = $_GET['thisMonthDueDate'] ?? '';

// policyNum별 데이터를 저장할 배열 초기화
$policyData = [];

// 매핑 배열 초기화
$cert_to_policy = []; // CertiTable.num -> policyNum 매핑
$policy_to_certs = []; // policyNum -> [CertiTable.num1, CertiTable.num2, ...] 매핑

// 원래 쿼리 결과를 저장할 배열 초기화
$original_endorsement_data = [];

// policyNum별 대리기사 인원수를 저장할 배열 초기화
$policy_driver_count = [];

try {
    // 1. CertiTable에서 데이터 조회 및 매핑 구성
    $sql_data = "SELECT num, policyNum, divi 
                 FROM `2012CertiTable` 
                 WHERE `2012DaeriCompanyNum` = :dNum 
                 AND startyDay BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()";

    $stmt_data = $pdo->prepare($sql_data);
    $stmt_data->execute(['dNum' => $dNum]);
    
    while ($row_data = $stmt_data->fetch(PDO::FETCH_ASSOC)) {
        $cNum = $row_data['num'];
        $certPolicyNum = $row_data['policyNum']; // 증권번호
        $certDivi = $row_data['divi']; // divi 값 저장
        
        // 양방향 매핑 저장
        $cert_to_policy[$cNum] = ['policyNum' => $certPolicyNum, 'divi' => $certDivi];
        
        // 동일한 policyNum이 여러 cert_num을 가질 수 있음
        if (!isset($policy_to_certs[$certPolicyNum])) {
            $policy_to_certs[$certPolicyNum] = [];
        }
        $policy_to_certs[$certPolicyNum][] = $cNum;
        
        // policyNum별 대리기사 인원수 초기화
        if (!isset($policy_driver_count[$certPolicyNum])) {
            $policy_driver_count[$certPolicyNum] = 0;
        }
        
        // 2. 기본 멤버 데이터 조회 및 보험료 계산
        $sql = "SELECT num, Name, nai, Jumin, Hphone, push, cancel, etag, 
                      `2012DaeriCompanyNum`, CertiTableNum, dongbuCerti, InsuranceCompany
               FROM `DaeriMember`
               WHERE `2012DaeriCompanyNum` = :dNum 
               AND push = '4' 
               AND CertiTableNum = :cNum
               ORDER BY CertiTableNum ASC, Jumin ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['dNum' => $dNum, 'cNum' => $cNum]);

        // 대리기사 인원수 계산
        $driver_count = $stmt->rowCount();
        
        // 해당 policy의 대리기사 인원수 누적
        $policy_driver_count[$certPolicyNum] += $driver_count;

        // 결과 처리
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // 회사 정보 가져오기
            $companyNum = $row['2012DaeriCompanyNum'];
            $companySql = "SELECT company FROM 2012DaeriCompany WHERE num = :num LIMIT 1";
            $companyStmt = $pdo->prepare($companySql);
            $companyStmt->execute(['num' => $companyNum]);
            
            if ($companyRow = $companyStmt->fetch(PDO::FETCH_ASSOC)) {
                $row['company'] = $companyRow['company'];
            } else {
                $row['company'] = '';
            }
            
            // 비율 정보 가져오기
            $jumin = $row['Jumin'];
            $policy = $row['dongbuCerti'];
            
            if (!empty($jumin) && !empty($policy)) {
                $rateSql = "SELECT rate FROM 2019rate WHERE policy = :policy AND jumin = :jumin LIMIT 1";
                $rateStmt = $pdo->prepare($rateSql);
                $rateStmt->execute(['policy' => $policy, 'jumin' => $jumin]);
                
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
            
            $policyNum = $certPolicyNum; // CertiTable의 증권번호를 사용하여 일관성 유지
            $age = $row['nai'];
            $row['age'] = $age;
            
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
            $row['monthly_premium1'] = $monthly_premium1;
            $row['monthly_premium2'] = $monthly_premium2;
            $row['monthly_premium_total'] = $monthly_premium_total;
            $row['payment10_premium1'] = $payment10_premium1;
            $row['payment10_premium2'] = $payment10_premium2;
            $row['payment10_premium_total'] = $payment10_premium_total;

            $row['monthly_premium_total'] = $monthly_premium_total;  //월별 보험료
            $AdjustedInsuranceMothlyPremium = floor(($monthly_premium_total * $discountRate['rate'])/10)*10;
            $row['AdjustedInsuranceMothlyPremium'] = $AdjustedInsuranceMothlyPremium;

            $row['payment10_premium_total'] = $payment10_premium_total; // 보험회사 내는 보험료 1/10
            $AdjustedInsuranceCompanyPremium = floor(($payment10_premium_total * $discountRate['rate'])/10)*10;
            $row['AdjustedInsuranceCompanyPremium'] = $AdjustedInsuranceCompanyPremium;
            
            // policyNum 기준으로 데이터 구성
            if (!isset($policyData[$policyNum])) {
                $policyData[$policyNum] = [
                    'policyNum' => $policyNum,
                    'divi' => $certDivi, // divi 값 추가
                    'total_AdjustedInsuranceMothlyPremium' => 0,
                    'total_AdjustedInsuranceCompanyPremium' => 0,
                    'Conversion_AdjustedInsuranceCompanyPremium' => 0,
                    'eTotalMonthPremium' => 0,
                    'eTotalCompanyPremium' => 0,
                    'drivers_count' => 0, // 대리기사 인원수 초기화
                    'members' => [], // 회원 정보 저장
                ];
            }
            
            // policyNum별 합계 누적
            $policyData[$policyNum]['total_AdjustedInsuranceMothlyPremium'] += $AdjustedInsuranceMothlyPremium;
            $policyData[$policyNum]['total_AdjustedInsuranceCompanyPremium'] += $AdjustedInsuranceCompanyPremium;
            
            // 회원 정보 저장 (선택적)
            // $policyData[$policyNum]['members'][] = $row;
        }

        // 3. 원래 쿼리 결과 처리
        // 첫 번째 쿼리 - 특정 증권($cNum)에 한정된 배서 정보
        $e_sql = "SELECT * 
                  FROM SMSData 
                  WHERE endorse_day >= :lastMonthDueDate 
                  AND endorse_day <= :thisMonthDueDate 
                  AND `2012DaeriCompanyNum` = :dNum 
                  AND ssang_c_num = :cNum 
                  AND dagun = '1'";
                  
        $e_stmt = $pdo->prepare($e_sql);
        $e_stmt->execute([
            'lastMonthDueDate' => $lastMonthDueDate,
            'thisMonthDueDate' => $thisMonthDueDate,
            'dNum' => $dNum,
            'cNum' => $cNum
        ]);
        
        while ($row2 = $e_stmt->fetch(PDO::FETCH_ASSOC)) {
            // 고유 식별자 생성
            $uniqueId = $row2['num'] . '_' . $row2['ssang_c_num'];
            
            // 원래 쿼리 결과 저장 - 모든 증권의 배서 정보를 누적
            $original_endorsement_data[$uniqueId] = $row2;
            
            // 기존 처리 로직
            $endorsePolicyNum = $certPolicyNum; // CertiTable의 증권번호 사용
            
            // policyNum 기준으로 데이터 구성 (누락된 경우 초기화)
            if (!isset($policyData[$endorsePolicyNum])) {
                $policyData[$endorsePolicyNum] = [
                    'policyNum' => $endorsePolicyNum,
                    'divi' => $certDivi,
                    'total_AdjustedInsuranceMothlyPremium' => 0,
                    'total_AdjustedInsuranceCompanyPremium' => 0,
                    'Conversion_AdjustedInsuranceCompanyPremium' => 0,
                    'eTotalMonthPremium' => 0,
                    'eTotalCompanyPremium' => 0,
                    'drivers_count' => 0, // 대리기사 인원수 초기화
                    'members' => [],
                ];
            }
            
            // 배서 보험료 계산 및 합산
            if ($row2['push'] == 2) { // 해지
                $policyData[$endorsePolicyNum]['eTotalMonthPremium'] -= $row2['preminum'];
                $policyData[$endorsePolicyNum]['eTotalCompanyPremium'] -= $row2['c_preminum'];
            } else if($row2['push'] == 4){
                $policyData[$endorsePolicyNum]['eTotalMonthPremium'] += $row2['preminum'];
                $policyData[$endorsePolicyNum]['eTotalCompanyPremium'] += $row2['c_preminum'];
            }
        }
    }

    // 4. 모든 배서 데이터 조회 (두 번째 쿼리)
    $broader_sql = "SELECT * 
                   FROM SMSData 
                   WHERE endorse_day >= :lastMonthDueDate 
                   AND endorse_day <= :thisMonthDueDate 
                   AND `2012DaeriCompanyNum` = :dNum 
                   AND dagun = '1' 
                   AND get = '2'";  // 미정산 데이터만 
                   
    $broader_stmt = $pdo->prepare($broader_sql);
    $broader_stmt->execute([
        'lastMonthDueDate' => $lastMonthDueDate,
        'thisMonthDueDate' => $thisMonthDueDate,
        'dNum' => $dNum
    ]);
    
    $additional_data = []; // 추가 데이터 저장

    while ($row3 = $broader_stmt->fetch(PDO::FETCH_ASSOC)) {
        // 고유 식별자 생성
        $uniqueId = $row3['num'] . '_' . $row3['ssang_c_num'];
        
        // 이 항목이 원래 조회에서 처리되지 않았는지 확인 (후자에만 있는지 확인)
        if (!isset($original_endorsement_data[$uniqueId])) {
            // 새로운 데이터에 추가
            $additional_data[] = $row3;
            
            // 해당 배서의 증권번호 찾기
            $additionalPolicyNum = '';
            $additionalDivi = '';
            $ssang_c_num = $row3['ssang_c_num'];
            
            // 1. policyNum 필드 사용 (존재하는 경우)
            if (!empty($row3['policyNum'])) {
                $additionalPolicyNum = $row3['policyNum'];
                
                // 해당 policyNum의 divi 값 찾기
                if (isset($policy_to_certs[$additionalPolicyNum]) && !empty($policy_to_certs[$additionalPolicyNum])) {
                    $first_cert = $policy_to_certs[$additionalPolicyNum][0];
                    $additionalDivi = $cert_to_policy[$first_cert]['divi'];
                }
            }
            // 2. ssang_c_num을 통해 policyNum 찾기
            else if (isset($cert_to_policy[$ssang_c_num])) {
                $additionalPolicyNum = $cert_to_policy[$ssang_c_num]['policyNum'];
                $additionalDivi = $cert_to_policy[$ssang_c_num]['divi'];
            }
            // 3. 위 방법으로 찾지 못한 경우 직접 조회
            else {
                $cert_sql = "SELECT policyNum, divi FROM `2012CertiTable` WHERE num = :num LIMIT 1";
                $cert_stmt = $pdo->prepare($cert_sql);
                $cert_stmt->execute(['num' => $ssang_c_num]);
                
                if ($cert_row = $cert_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $additionalPolicyNum = $cert_row['policyNum'];
                    $additionalDivi = $cert_row['divi'];
                    
                    // 매핑 정보 업데이트
                    $cert_to_policy[$ssang_c_num] = [
                        'policyNum' => $additionalPolicyNum,
                        'divi' => $additionalDivi
                    ];
                    
                    if (!isset($policy_to_certs[$additionalPolicyNum])) {
                        $policy_to_certs[$additionalPolicyNum] = [];
                    }
                    $policy_to_certs[$additionalPolicyNum][] = $ssang_c_num;
                }
            }
            
            // 유효한 증권번호가 있는 경우에만 처리
            if (!empty($additionalPolicyNum)) {
                // divi 값이 비어있는 경우 CertiTable에서 직접 조회
                if (empty($additionalDivi)) {
                    $divi_sql = "SELECT divi FROM `2012CertiTable` 
                                WHERE policyNum = :policyNum 
                                AND `2012DaeriCompanyNum` = :dNum LIMIT 1";
                    $divi_stmt = $pdo->prepare($divi_sql);
                    $divi_stmt->execute(['policyNum' => $additionalPolicyNum, 'dNum' => $dNum]);
                    
                    if ($divi_row = $divi_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $additionalDivi = $divi_row['divi'];
                    } else {
                        // 기본값 설정
                        $additionalDivi = "2"; // 가장 일반적인 divi 값
                    }
                }
                
                // policyNum 기준으로 데이터 구성 (누락된 경우 초기화)
                if (!isset($policyData[$additionalPolicyNum])) {
                    $policyData[$additionalPolicyNum] = [
                        'policyNum' => $additionalPolicyNum,
                        'divi' => $additionalDivi,
                        'total_AdjustedInsuranceMothlyPremium' => 0,
                        'total_AdjustedInsuranceCompanyPremium' => 0,
                        'Conversion_AdjustedInsuranceCompanyPremium' => 0,
                        'eTotalMonthPremium' => 0,
                        'eTotalCompanyPremium' => 0,
                        'drivers_count' => 0, // 대리기사 인원수 초기화
                        'members' => [],
                        'is_additional' => true, // 추가 데이터에서 온 것을 표시
                    ];
                } else {
                    // is_additional 플래그 설정
                    $policyData[$additionalPolicyNum]['is_additional'] = true;
                    
                    // divi 값이 없는 경우 업데이트
                    if (empty($policyData[$additionalPolicyNum]['divi']) && !empty($additionalDivi)) {
                        $policyData[$additionalPolicyNum]['divi'] = $additionalDivi;
                    }
                }
                
                // 배서 보험료 계산 및 합산
                $premium = $row3['preminum'] ?? 0;
                $c_premium = $row3['c_preminum'] ?? 0;
                
                if ($row3['push'] == 2) { // 해지
                    $policyData[$additionalPolicyNum]['eTotalMonthPremium'] -= $premium;
                    $policyData[$additionalPolicyNum]['eTotalCompanyPremium'] -= $c_premium;
                } else if ($row3['push'] == 4) {
                    $policyData[$additionalPolicyNum]['eTotalMonthPremium'] += $premium;
                    $policyData[$additionalPolicyNum]['eTotalCompanyPremium'] += $c_premium;
                }
                
                // 배서 정보가 처리됨을 표시
                $row3['processed'] = true;
                $row3['applied_to_policy'] = $additionalPolicyNum;
            } else {
                // 증권번호를 찾을 수 없는 경우, 처리되지 않음을 표시
                $row3['processed'] = false;
                $row3['error'] = '증권번호를 찾을 수 없음';
            }
        }
    }

    // 5. policyNum별 대리기사 인원수를 policyData에 반영
    foreach ($policy_driver_count as $policyNum => $count) {
        if (isset($policyData[$policyNum])) {
            $policyData[$policyNum]['drivers_count'] = $count;
        }
    }

    // 6. policyNum별 변환된 조정 보험회사 보험료 계산
    foreach ($policyData as $key => $value) {
        $policyData[$key]['Conversion_AdjustedInsuranceCompanyPremium'] = 
            floor(($value['total_AdjustedInsuranceCompanyPremium'] * 10 / 12)/10)*10;
    }

    // 7. 추가 데이터(후자에만 있는 데이터)를 policyNum별로 그룹화
    $additional_data_by_policy = [];
    foreach ($additional_data as $item) {
        $policyNum = '';
        $ssang_c_num = $item['ssang_c_num'];
        
        // 1. policyNum 필드 사용 (존재하는 경우)
        if (!empty($item['policyNum'])) {
            $policyNum = $item['policyNum'];
        }
        // 2. ssang_c_num을 통해 policyNum 찾기
        else if (isset($cert_to_policy[$ssang_c_num])) {
            $policyNum = $cert_to_policy[$ssang_c_num]['policyNum'];
        }
        
        if (!empty($policyNum)) {
            if (!isset($additional_data_by_policy[$policyNum])) {
                $additional_data_by_policy[$policyNum] = [];
            }
            $additional_data_by_policy[$policyNum][] = $item;
        }
    }

    // 8. 배열을 인덱스 배열로 변환
    $resultData = array_values($policyData);

    // 9. 디버깅 정보 추가
    $policy_numbers = array_keys($policyData);

    $debug = [
        "total_policies" => count($policyData),
        "policy_numbers" => $policy_numbers,
        "additional_data_count" => count($additional_data),
        "additional_policies_count" => count($additional_data_by_policy),
        "original_data_count" => count($original_endorsement_data),
        "policy_to_certs_count" => count($policy_to_certs)
    ];

    // 10. 응답 데이터 구성
    $response = [
        "success" => true,
        "data" => $resultData,
        "additional_data" => $additional_data,
        "additional_data_by_policy" => $additional_data_by_policy,
        "debug" => $debug
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // 에러 처리
    $response = [
        "success" => false,
        "error" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}