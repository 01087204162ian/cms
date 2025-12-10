<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "./php/monthlyFee.php";
include "./php/calculatePersonalRate.php";
include "./php/encryption.php";

// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 변수 가져오기
$num = $_POST['num'] ?? '';
$status = $_POST['status'] ?? '';
$push = $_POST['push'] ?? '';
$manager = $_POST['userName'] ?? '';
$reasion = $_POST['reasion'] ?? '';
$smsContents = $_POST['smsContents'] ?? '';

// 응답 생성
if (empty($num) || empty($status)) {
    $response = [
        "success" => false,
        "error" => "필수 필드(num, status)가 누락되었습니다."
    ];
} else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM DaeriMember WHERE num = :num");
        $stmt->execute(['num' => $num]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['sangtae'] == 2) {
            $message = '이미 처리 된 건 입니다';
            echo "<data>\n";
            echo "<message>" . htmlspecialchars($message) . "</message>\n";
            echo "</data>";
        } else {
            // sangtae 1인 경우 처리
            $dNum = $row['2012DaeriCompanyNum'];
            $cNum = $row['CertiTableNum'];
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
            $ju = explode('-', $jumin);
            $InsuraneCompany = $row['InsuranceCompany'];
            $etag = $row['etag'];
            $endorse_day = $row['endorse_day'];
            $personRate = $row['rate'];

            // push 값에 따른 처리
            switch ($push) {
                case 1: // 청약을 정상으로
                    $push_2 = 4;
                    $push = 4;
                    $sms = 1;
                    $message = "가입완료";
                    break;
                case 6: // 청약을 취소
                    $push_2 = 6;
                    $push = 1;
                    $cancel = 12;
                    $sms = 2;
                    $message = "청약취소";
                    break;
                case 3: // 청약을 거절
                    $push_2 = 3;
                    $push = 1;
                    $cancel = 13;
                    $sms = 2;
                    $message = "청약거절";
                    break;
                case 4: // 정상을 해지로
                    $push_2 = 2;
                    $push = 2;
                    $cancel = 42;
                    $sms = 1;
                    $message = "해지완료";
                    break;
                case 5: // 해지 신청한 것을 취소
                    $push_2 = 5;
                    $push = 4;
                    $cancel = 45;
                    $sms = 2;
                    $message = "해지취소";
                    break;
            }

            // DB 업데이트
            if ($insuranceCompany == 2) { // DB 손보인 경우
                $updateNew = "UPDATE DaeriMember SET push = :push, sangtae = '2', cancel = :cancel, 
                             dongbuSelNumber = :selNum, dongbusigi = :sigi, dongbujeongi = :jeonggi, 
                             nabang_1 = :cheriii, ch = '1', reasion = :reasion, manager = :manager 
                             WHERE num = :num";
                $stmt = $pdo->prepare($updateNew);
                $stmt->execute([
                    'push' => $push,
                    'cancel' => $cancel ?? null,
                    'selNum' => $selNum ?? null,
                    'sigi' => $sigi ?? null,
                    'jeonggi' => $jeonggi ?? null,
                    'cheriii' => $cheriii ?? null,
                    'reasion' => $reasion,
                    'manager' => $manager,
                    'num' => $num
                ]);
            } else {
                $updateNew = "UPDATE DaeriMember SET push = :push, sangtae = '2', cancel = :cancel, 
                             ch = '1', reasion = :reasion, manager = :manager 
                             WHERE num = :num";
                $stmt = $pdo->prepare($updateNew);
                $stmt->execute([
                    'push' => $push,
                    'cancel' => $cancel ?? null,
                    'reasion' => $reasion,
                    'manager' => $manager,
                    'num' => $num
                ]);
            }

            /* 배서건수 정리
            $qStmt = $pdo->prepare("SELECT e_count, e_count_2, endorse_day FROM 2012EndorseList 
                                   WHERE pnum = :pnum AND CertiTableNum = :cnum");
            $qStmt->execute(['pnum' => $pNum, 'cnum' => $cNum]);
            $qRow = $qStmt->fetch(PDO::FETCH_ASSOC);

            $e_count_2 = $qRow['e_count_2'] + 1; // 신청건수
            $updateE = $pdo->prepare("UPDATE 2012EndorseList SET e_count_2 = :e_count_2 
                                     WHERE pnum = :pnum AND CertiTableNum = :cnum");
            $updateE->execute(['e_count_2' => $e_count_2, 'pnum' => $pNum, 'cnum' => $cNum]);

            // 신청건수와 배서건수가 같으면 sangtae를 2로 변경, ch 값을 10로 변경
            $eStmt = $pdo->prepare("SELECT e_count_2 FROM 2012EndorseList 
                                   WHERE pnum = :pnum AND CertiTableNum = :cnum");
            $eStmt->execute(['pnum' => $pNum, 'cnum' => $cNum]);
            $erow = $eStmt->fetch(PDO::FETCH_ASSOC);

            if ($qRow['e_count'] == $erow['e_count_2']) {
                $updateQ = $pdo->prepare("UPDATE 2012EndorseList SET ch = 10, sangtae = '2' 
                                         WHERE pnum = :pnum AND CertiTableNum = :cnum");
                $updateQ->execute(['pnum' => $pNum, 'cnum' => $cNum]);
            }*/

            // 보험료 산출
            //include "./php/rateSearch.php";
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

            // CertiTableNum 조회
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


            // 기준일 처리
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

            $personInfo = calculateAge($ju[0], $ju[1], $dateString);
            $age = $personInfo['age'];

            // 대리업체 정기결제일
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
            // 배서발생일
           // list($eYear, $eMonth, $eDay) = explode("-", $endorse_day, 3);

            // 보험료 찾기
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
            $row2['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];
			$PreminumMonth = (int)$insurancePremiumData['monthlyFee3']; // 월 보험료
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
            $row2['Endorsement_insurance_company_premium'] = isset($EndorsePremiumYpremium['i_endorese_premium']) ? 
                (int)$EndorsePremiumYpremium['i_endorese_premium'] : 0;
			$PreminumYear = (int)$EndorsePremiumYpremium['payment10_premium_total'] * 10; // 년 보험료
            // 월보험료 산출
      /*      $insurancePremiumData = calculateProRatedFee(
                $monthly_premium1,
                $monthly_premium2,
                $monthly_premium_total,
                $InsuraneCompany,
                $dueDay,
                $eDay,
                $eMonth,
                $eYear,
                $discountRate['rate'],
                $etag
            );
            $row2['Endorsement_insurance_premium'] = (int)$insurancePremiumData['proRatedFee'];
            $PreminumMonth = $insurancePremiumData['monthlyFee3']; // 월 보험료

            // 보험회사 보험료 산출
            $EndorsePremiumYpremium = calculateEndorsePremium(
                $startDay,
                $endorse_day,
                $nabang,
                $nabang_1,
                $payment10_premium1,
                $payment10_premium2,
                $payment10_premium_total,
                $InsuraneCompany,
                $discountRate['rate'],
                $etag
            );
            $row2['Endorsement_insurance_company_premium'] = (int)$EndorsePremiumYpremium['i_endorese_premium'];
            $PreminumYear = (int)$EndorsePremiumYpremium['payment10_premium_total'] * 10; // 년 보험료
*/
            // 문자 보내기
            $company_tel = "070-7841-5962";
            $con_phone1 = $Drow['hphone'];
            $endorseCh = 1;

            if ($sms == 1) {
                // 보험회사별 처리
                switch ($InsuraneCompany) {
                    case 1:
                        $insCom = "흥국화재";
                        $po = $policyNum;
                        break;
                    case 2:
                        $insCom = "DB화재";
                        $po = "2-20" . $policyNum . "-000";
                        break;
                    case 3:
                        $insCom = "KB화재";
                        $po = $policyNum;
                        break;
                    case 4:
                        $insCom = "현대화재";
                        $po = $policyNum;
                        break;
                    case 5:
                        $insCom = "한화화재";
                        $po = $policyNum;
                        break;
                    case 6:
                        $insCom = "더케이";
                        $po = $policyNum;
                        break;
                    case 7:
                        $insCom = "MG";
                        $po = $policyNum;
                        break;
                    case 8:
                        $insCom = "삼성";
                        $po = $policyNum;
                        break;
                }

                // etag별 처리
                switch ($etag) {
                    case 1:
                        $etagName = '대리보험';
                        break;
                    case 2:
                        $etagName = '탁송보험';
                        break;
                    case 3:
                        $etagName = '대리/렌트';
                        break;
                    case 4:
                        $etagName = '탁송/렌트';
                        break;
                }

                // push별 메시지 생성
                switch ($push) {
                    case 2: // 해지
                        if ($divi == 1) { // 정상분납인 경우
                            $msg = $insCom . $etagName . $row['Name'] . "님 해지기준일[" . $endorse_day . "][" . $po . "]" . "배" . number_format($row2['Endorsement_insurance_company_premium']);
                        } else {
                            $msg = $insCom . $etagName . $row['Name'] . "님 해지기준일[" . $endorse_day . "][" . $po . "]" . number_format($row2['Endorsement_insurance_premium']);
                        }
                        break;
                    case 4: // 청약
                        if ($divi == 1) { // 정상분납인 경우
                            $msg = $insCom . $etagName . $row['Name'] . "님 기준일[" . $endorse_day . "][" . $po . "] 년" . number_format($PreminumYear) . "배" . number_format($row2['Endorsement_insurance_company_premium']);
                        } else {
                            $msg = $insCom . $etagName . $row['Name'] . "님 기준일[" . $endorse_day . "][" . $po . "]월" . number_format($PreminumMonth) . "배" . number_format($row2['Endorsement_insurance_premium']);
                        }

                        // 현대해상 체결 동의
                        if ($InsuraneCompany == 4) {
                            $jumin_ = explode('-', $row['Jumin']);
                            $hnumber_ = explode('-', $row['Hphone']);
                            $driverCompony = $Drow['company'];
                            $driverName = $row['Name'];
                            $data = $hnumber_[0] . $hnumber_[1] . $hnumber_[2];
                            $data2 = $jumin_[0] . $jumin_[1];
                            $po_ = explode("-", $po);
                            $policyNumber = $po_[0] . $po_[1];
                            $startDay__ = explode('-', $Crow['startyDay']);
                            $validStartDay = $startDay__[0] . $startDay__[1] . $startDay__[2];
                            $sigi__ = $cRow['startyDay'];
                            $endDay__ = date("Y-m-d", strtotime("$sigi__ +1 year"));
                            $endDay2__ = explode('-', $endDay__);
                            $validEndDay = $endDay2__[0] . $endDay2__[1] . $endDay2__[2];
                            include './php/hcurl.php';
                        }
                        break;
                }

                $qboard = '1';
                include "./php/coSms.php";
            } else {
                // 청약을 취소하거나, 거절, 해지를 취소하는 경우
                $msg = $smsContents;
                include "./php/coSms.php";
            }

            $response = [
                "success" => true,
                "row" => $row2
            ];
        }
    } catch (PDOException $e) {
        $response = [
            "success" => false,
            "error" => "데이터베이스 오류: " . $e->getMessage()
        ];
    }
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 자동으로 종료됨 (스크립트 종료시)
?>