<?php
/**
 * 보험료 데이터 저장 API - FormData 처리 버전 
 * PHP 8.2 버전 호환
 * 주민번호 및 휴대전화번호 암호화 적용
 */

// 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 로그 함수 정의
function logError($message) {
    $logFile = __DIR__ . '/api_error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

try {
    // 파일 포함 전에 로깅
    logError("파일 포함 시작");
    require_once '../smsApi/smsAligo.php';
    // 필요한 파일 경로 설정 및 존재 확인
    $dbConfigPath = '../../../api/config/db_config.php';
    $monthlyFeePath = "./php/monthlyFee.php";
    $encryptionPath = "../kjDaeri/php/encryption.php";
    
    // 파일 존재 확인
    if (!file_exists($dbConfigPath)) {
        throw new Exception("데이터베이스 설정 파일을 찾을 수 없습니다: $dbConfigPath");
    }
    
    if (!file_exists($encryptionPath)) {
        throw new Exception("암호화 파일을 찾을 수 없습니다: $encryptionPath");
    }
    
    // 필요한 파일 포함
    require_once $dbConfigPath;
    require_once $encryptionPath;
    
    // 선택적으로 월별 요금 파일 포함
    if (file_exists($monthlyFeePath)) {
        require_once $monthlyFeePath;
    }
    
    // PDO 연결 - db_config.php에서 정의된 getDbConnection() 함수 사용
    $pdo = getDbConnection();
    
    // POST 데이터 받기
    $formData = $_POST;
    
    // 필수 필드 확인
    $requiredFields = ['data', 'cNum', 'dNum', 'InsuraneCompany', 'endorseDay', 'policyNum', 'userName'];
    
    foreach ($requiredFields as $field) {
        if (!isset($formData[$field])) {
            throw new Exception("필수 필드가 누락되었습니다: $field");
        }
    }
    
    // 데이터 준비 (배열 형태인지 확인)
    $data = $formData['data'];
    
    // 데이터가 배열이 아닌 경우 처리
    if (!is_array($data)) {
        logError("데이터 형식이 올바르지 않습니다: 배열이 아닙니다.");
        throw new Exception("데이터 형식이 올바르지 않습니다.");
    }
    
    $cNum = $formData['cNum'];
    $dNum = $formData['dNum'];
    $gita = $formData['gita'] ?? 0;
    $insuranceCompany = $formData['InsuraneCompany'];
    $endorseDay = $formData['endorseDay'];
    $policyNum = $formData['policyNum'];
    $userName = $formData['userName'];
    
    // 증서번호로 시기(sigi) 조회
    $stmt = $pdo->prepare("SELECT sigi FROM 2012Certi WHERE certi = :policyNum");
    $stmt->bindParam(':policyNum', $policyNum, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 시기 설정
    if ($result && isset($result['sigi'])) {
        $sigi = $result['sigi'];
    } else {
        // 기본값 설정 또는 예외 처리
        $sigi = date('Y-m-d');
        logError("증서번호 $policyNum에 대한 시기를 찾을 수 없습니다. 현재 날짜를 사용합니다.");
    }
    
    // 보험사에 따른 기준일 설정
    if ($insuranceCompany == '2') {
        $baseDate = $endorseDay;
    } else {
        $baseDate = $sigi;
    }
    
    // 나이 정보 초기화
    $ageInfo = [
        'age' => 0,
        'sex' => '',
        'birthday' => '',
        'to_day' => date('Ymd')
    ];
    
    // 주민번호가 있는 경우에만 나이 계산
    if (isset($data[0]['juminNo']) && !empty($data[0]['juminNo'])) {
        $jumin = explode("-", $data[0]['juminNo']);
        if (count($jumin) == 2) {
            $jumin1 = $jumin[0];
            $jumin2 = $jumin[1];
            
            // calculateAge 함수를 통해 나이 계산
            $ageInfo = calculateAge($jumin1, $jumin2, $baseDate);
        } else {
            logError("주민번호 형식이 올바르지 않습니다: " . $data[0]['juminNo']);
        }
    } else {
        // 주민번호가 없는 경우 (특정 대리운전 회사의 경우)
        logError("주민번호가 없습니다. 기본 나이 정보를 사용합니다.");
    }
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    try {
        // 각 데이터 항목에 대해 반복 처리
        foreach ($data as $personData) {
            // 이름이 없는 경우 건너뛰기
            if (empty($personData['name'])) {
                continue;
            }
            
            // 나이 정보 초기화
            $ageInfo = [
                'age' => 0,
                'sex' => '',
                'birthday' => '',
                'to_day' => date('Ymd')
            ];
            
            // 주민번호가 있는 경우에만 나이 계산
            if (isset($personData['juminNo']) && !empty($personData['juminNo'])) {
                $jumin = explode("-", $personData['juminNo']);
                if (count($jumin) == 2) {
                    $jumin1 = $jumin[0];
                    $jumin2 = $jumin[1];
                    
                    // calculateAge 함수를 통해 나이 계산
                    $ageInfo = calculateAge($jumin1, $jumin2, $baseDate);
                } else {
                    logError("주민번호 형식이 올바르지 않습니다: " . $personData['juminNo']);
                }
            } else {
                // 주민번호가 없는 경우 (특정 대리운전 회사의 경우)
                logError("주민번호가 없습니다. 기본 나이 정보를 사용합니다.");
            }
            
            // DaeriMember 테이블에 데이터 저장
            $stmt = $pdo->prepare("
						INSERT INTO DaeriMember (
							2012DaeriCompanyNum, CertiTableNum, Name, InsuranceCompany,
							Jumin, JuminHash, nai, push, sangtae, InputDay, dongbuCerti,
							Hphone, PhoneHash, wdate, endorse_day, a8b, etag
						) VALUES (
							:dNum, :cNum, :name, :insuranceCompany,
							:jumin, :juminHash, :nai, :push, :sangtae, :inputDay, :dongbuCerti,
							:hphone, :phoneHash, NOW(), :endorseDay, '', :etag
						)
					");
            
            // 주민번호 및 휴대폰 번호 암호화 (주민번호가 없는 경우 고려)
            $encryptedJumin = '';
            $juminHash = '';
            
            if (isset($personData['juminNo']) && !empty($personData['juminNo'])) {
                $encryptedJumin = encryptData($personData['juminNo']);
                $juminHash = sha1($personData['juminNo']);
            }
            
            // 휴대폰 번호 암호화 (휴대폰 번호가 없는 경우 고려)
            $encryptedPhone = '';
            if (isset($personData['phoneNo']) && !empty($personData['phoneNo'])) {
                $encryptedPhone = encryptData($personData['phoneNo']);
				$phoneHash = sha1($personData['phoneNo']);
            }
            
            $stmt->bindParam(':dNum', $dNum, PDO::PARAM_INT);
            $stmt->bindParam(':cNum', $cNum, PDO::PARAM_INT);
            $stmt->bindParam(':name', $personData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':insuranceCompany', $insuranceCompany, PDO::PARAM_STR);
            $stmt->bindParam(':jumin', $encryptedJumin, PDO::PARAM_STR);
            $stmt->bindParam(':juminHash', $juminHash, PDO::PARAM_STR);
            $stmt->bindParam(':nai', $ageInfo['age'], PDO::PARAM_INT);
            $stmt->bindValue(':push', 1, PDO::PARAM_INT);
            $stmt->bindValue(':sangtae', 1, PDO::PARAM_STR);
            $stmt->bindParam(':inputDay', $endorseDay, PDO::PARAM_STR);
            $stmt->bindParam(':dongbuCerti', $policyNum, PDO::PARAM_STR);
            $stmt->bindParam(':hphone', $encryptedPhone, PDO::PARAM_STR);
			$stmt->bindParam(':phoneHash', $phoneHash, PDO::PARAM_STR);
            $stmt->bindParam(':endorseDay', $endorseDay, PDO::PARAM_STR);
            $stmt->bindParam(':etag', $gita, PDO::PARAM_STR);
            
            $stmt->execute();


			//케이드라인 경우 
			if($dNum == '653') {
						// 가장 최근에 삽입된 레코드 ID 가져오기
						$num = $pdo->lastInsertId();
						
						// etagName 설정
						if($gita == 1 || $gita == 2) {
							$etagName = "대리보험";
						} else {
							$etagName = "탁송보험";
						}
						
						// 전화번호 형식 확인 및 처리
						if(isset($personData['phoneNo']) && !empty($personData['phoneNo'])) {
							$phoneNo = $personData['phoneNo'];
							// 하이픈이 있는 경우 처리
							if(strpos($phoneNo, '-') !== false) {
								list($hphone1, $hphone2, $hphone3) = explode("-", $phoneNo);
								$receiver = $hphone1.$hphone2.$hphone3;
							} else {
								// 하이픈이 없는 경우 그대로 사용
								$receiver = $phoneNo;
							}
							
							// 메시지 생성
							$msg = $personData['name'] . "님 " . $etagName . " 가입에 동의 해주세요 https://www.pcikorea.com/k.html?num=" . $num;
							
							$sendData = [
								"receiver" => $receiver,
								"msg" => $msg,
								"testmode_yn" => "N"
							];

							// 함수 호출 전 존재 여부 확인
							if(function_exists('sendAligoSms')) {
								$result = sendAligoSms($sendData);
								// 결과 로깅
								logError("SMS 발송 결과: " . json_encode($result));
							} else {
								logError("sendAligoSms 함수가 정의되지 않았습니다.");
							}
							$company_tel = "070-7841-5962";
							list($sphone1, $sphone2, $sphone3) = explode("-", $company_tel); // 회사번호
							$LastTime = date('YmdHis');
							
							// PDO 방식으로 변경한 INSERT 쿼리
							try {
								$stmt = $pdo->prepare("INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3, SendName, RecvName, Msg, Url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime, `2012DaeriMemberNum`) 
													   VALUES (:sendId, :rphone1, :rphone2, :rphone3, :sphone1, :sphone2, :sphone3, :sendName, :recvName, :msg, :url, :rdate, :rtime, :result, :kind, :errCode, :retry1, :retry2, :lastTime, :memberNum )");
								
								// 파라미터 바인딩
								$stmt->bindValue(':sendId', 'csdrive');
								$stmt->bindValue(':rphone1', $hphone1);
								$stmt->bindValue(':rphone2', $hphone2);
								$stmt->bindValue(':rphone3', $hphone3);
								$stmt->bindValue(':sphone1', $sphone1);
								$stmt->bindValue(':sphone2', $sphone2);
								$stmt->bindValue(':sphone3', $sphone3);
								$stmt->bindValue(':sendName', 'drive');
								$stmt->bindValue(':recvName', 'CS');
								$stmt->bindValue(':msg', $msg);
								$stmt->bindValue(':url', $num);
								$stmt->bindValue(':rdate', '');
								$stmt->bindValue(':rtime', '');
								$stmt->bindValue(':result', '0');
								$stmt->bindValue(':kind', 's');
								$stmt->bindValue(':errCode', 0);
								$stmt->bindValue(':retry1', 0);
								$stmt->bindValue(':retry2', 0);
								$stmt->bindValue(':lastTime', $LastTime);
								$stmt->bindValue(':memberNum', $num);
								// 쿼리 실행
								$stmt->execute();
								
							} catch (PDOException $e) {
								// 오류 처리
								logError("SMS 데이터 삽입 실패: " . $e->getMessage());
							}


						} else {
							logError("케이드라인 SMS 발송 실패: 전화번호가 없습니다.");
						}
					}


				}
				
				// 트랜잭션 커밋
				$pdo->commit();
				// 케이드라이브 인 경우 


				// 성공 응답
				echo json_encode([
					'success' => true,
					'message' => '데이터가 성공적으로 저장되었습니다.',
					'data' => [
						'id' => $pdo->lastInsertId(),
						'name' => $data[0]['name'],
						'age' => $ageInfo['age'],
						'gender' => $ageInfo['sex'],
						'policyNum' => $policyNum,
						'count' => count($data) // 저장된 총 데이터 개수 추가
					]
				]);
				
			} catch (Exception $e) {
				// 트랜잭션 롤백
				$pdo->rollBack();
				throw $e;
			}
			
		} catch (Exception $e) {
			// 오류 로깅
			logError($e->getMessage());
			
			// 오류 응답
			echo json_encode([
				'success' => false,
				'error' => $e->getMessage()
			]);
		}

// 참고: calculateAge 함수는 외부 파일에서 가져옵니다.

// 참고: encryptData 함수와 getDbConnection 함수는 각각
// encryption.php, db_config.php 파일에서 가져옵니다.
?>
