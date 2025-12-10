<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// FileManager 클래스 포함 (위에서 생성한 파일)
require_once "./php/FileManager.php";

// 디버깅을 위한 오류 표시 활성화
ini_set('display_errors', 1);

// 응답 배열 초기화
$response = array(
    "success" => false,
    "message" => "",
    "data" => []
);

try {
    // POST 데이터 가져오기 (기존과 동일)
    $signupId = isset($_POST['signupId']) ? trim($_POST['signupId']) : '';
    $customerName = isset($_POST['customerName']) ? trim($_POST['customerName']) : '';
    $customerPhone = isset($_POST['customerPhone']) ? trim($_POST['customerPhone']) : '';
    $golfCourse = isset($_POST['golfCourse']) ? trim($_POST['golfCourse']) : '';
    $playDate = isset($_POST['playDate']) ? trim($_POST['playDate']) : (isset($_POST['date']) ? trim($_POST['date']) : '');
    $holeNumber = isset($_POST['holeNumber']) ? trim($_POST['holeNumber']) : (isset($_POST['hole']) ? trim($_POST['hole']) : '');
    $yardage = isset($_POST['yardage']) ? trim($_POST['yardage']) : '';
    $club = isset($_POST['club']) ? trim($_POST['club']) : '';
    $witnessName = isset($_POST['witnessName']) ? trim($_POST['witnessName']) : '';
    $witnessPhone = isset($_POST['witnessPhone']) ? trim($_POST['witnessPhone']) : '';
    $bankName = isset($_POST['bankName']) ? trim($_POST['bankName']) : '';
    $accountNumber = isset($_POST['accountNumber']) ? trim($_POST['accountNumber']) : '';
    $accountHolder = isset($_POST['accountHolder']) ? trim($_POST['accountHolder']) : '';
    $additionalNotes = isset($_POST['additionalNotes']) ? trim($_POST['additionalNotes']) : '';
    $termsAgreed = isset($_POST['termsAgreed']) ? filter_var($_POST['termsAgreed'], FILTER_VALIDATE_BOOLEAN) : false;
    
    // 홀 번호에서 숫자만 추출 (기존과 동일)
    $holeNumberInt = null;
    if (!empty($holeNumber)) {
        preg_match('/(\d+)/', $holeNumber, $matches);
        $holeNumberInt = isset($matches[1]) ? intval($matches[1]) : null;
    }
    
    // 필수 입력값 검증 (기존과 동일)
    if (empty($signupId) || empty($customerName) || empty($customerPhone) || 
        empty($golfCourse) || empty($playDate) || $holeNumberInt === null || 
        empty($bankName) || empty($accountNumber) || empty($accountHolder) ||
        !$termsAgreed) {
        
        $missingFields = [];
        if (empty($signupId)) $missingFields[] = '가입 ID';
        if (empty($customerName)) $missingFields[] = '고객명';
        if (empty($customerPhone)) $missingFields[] = '고객 전화번호';
        if (empty($golfCourse)) $missingFields[] = '골프장명';
        if (empty($playDate)) $missingFields[] = '경기일자';
        if ($holeNumberInt === null) $missingFields[] = '홀 번호';
        if (empty($bankName)) $missingFields[] = '은행명';
        if (empty($accountNumber)) $missingFields[] = '계좌번호';
        if (empty($accountHolder)) $missingFields[] = '예금주';
        if (!$termsAgreed) $missingFields[] = '약관 동의';
        
        $response["message"] = "필수 입력값이 누락되었습니다: " . implode(', ', $missingFields);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 홀 번호 유효성 검증 (기존과 동일)
    if ($holeNumberInt < 1 || $holeNumberInt > 18) {
        $response["message"] = "홀 번호는 1번부터 18번까지 입력 가능합니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 목격자 정보 기본값 설정 (기존과 동일)
    if (empty($witnessName)) {
        $witnessName = '정보없음';
    }
    if (empty($witnessPhone)) {
        $witnessPhone = '00000000000';
    }
    
    // 핸드폰 번호 포맷 정리 (기존과 동일)
    $customerPhone = preg_replace('/[^0-9]/', '', $customerPhone);
    if (!empty($witnessPhone) && $witnessPhone !== '00000000000') {
        $witnessPhone = preg_replace('/[^0-9]/', '', $witnessPhone);
    }
    $accountNumber = preg_replace('/[^0-9-]/', '', $accountNumber);
    
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    // 트랜잭션 시작
    $conn->beginTransaction();

    // 가입 정보 확인 (기존과 동일)
    $applicationId = str_replace('ACE', '', $signupId);
    $applicationId = intval($applicationId);
    
    $appStmt = $conn->prepare("
        SELECT id, client_id, coupon_number, applicant_name, applicant_phone 
        FROM holeinone_applications 
        WHERE id = ?
    ");
    $appStmt->execute([$applicationId]);
    $applicationData = $appStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$applicationData) {
        $conn->rollBack();
        $response["message"] = "존재하지 않는 가입 정보입니다. (가입 ID: {$signupId})";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 중복 신청 검증 (기존과 동일)
    $duplicateStmt = $conn->prepare("
        SELECT id FROM holeinone_claims 
        WHERE application_id = ? AND golf_course = ? AND play_date = ? AND hole_number = ?
    ");
    $duplicateStmt->execute([$applicationId, $golfCourse, $playDate, $holeNumberInt]);
    
    if ($duplicateStmt->fetch()) {
        $conn->rollBack();
        $response["message"] = "이미 동일한 홀인원에 대한 보상 신청이 접수되었습니다.";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 개인정보 암호화 (기존과 동일)
    $encryptedCustomerName = encryptData($customerName);
    $encryptedCustomerPhone = encryptData($customerPhone);
    $encryptedWitnessName = encryptData($witnessName);
    $encryptedWitnessPhone = encryptData($witnessPhone);
    $encryptedAccountNumber = encryptData($accountNumber);
    $encryptedAccountHolder = encryptData($accountHolder);
    
    // 검색용 해시 생성 (기존과 동일)
    $customerNameHash = generateNameHash($customerName);
    $customerPhoneHash = generatePhoneHash($customerPhone);
    $customerNamePhoneHash = generateNamePhoneHash($customerName, $customerPhone);
    $witnessNameHash = generateNameHash($witnessName);
    $witnessPhoneHash = generatePhoneHash($witnessPhone);
    
    // 고유 해시 생성 (기존과 동일)
    $claimHash = generateClaimHash($applicationId, $golfCourse, $playDate, $holeNumberInt);
    
    // 신청 번호 생성 (기존과 동일)
    $claimNumber = generateClaimNumber($conn);
    
    // ====== 새로운 부분: 파일 업로드 전에 보상 신청 정보 먼저 저장 ======
    // 보상 신청 정보 삽입 (파일 관련 컬럼 제거)
    $claimStmt = $conn->prepare("
        INSERT INTO holeinone_claims (
            application_id, client_id, coupon_number, claim_number,
            customer_name, customer_phone, golf_course, play_date,
            hole_number, yardage, club_used, witness_name, witness_phone,
            bank_name, account_number, account_holder,
            additional_notes, terms_agreed, claim_hash, status,
            customer_name_hash, customer_phone_hash, customer_name_phone_hash,
            witness_name_hash, witness_phone_hash, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $claimStmt->execute([
        $applicationId,
        $applicationData['client_id'],
        $applicationData['coupon_number'],
        $claimNumber,
        $encryptedCustomerName,
        $encryptedCustomerPhone,
        $golfCourse,
        $playDate,
        $holeNumberInt,
        $yardage ?: null,
        $club ?: null,
        $encryptedWitnessName,
        $encryptedWitnessPhone,
        $bankName,
        $encryptedAccountNumber,
        $encryptedAccountHolder,
        $additionalNotes ?: null,
        $termsAgreed ? 1 : 0,
        $claimHash,
        'pending',
        $customerNameHash,
        $customerPhoneHash,
        $customerNamePhoneHash,
        $witnessNameHash,
        $witnessPhoneHash
    ]);
    
    $claimId = $conn->lastInsertId();
    
    // ====== 새로운 부분: FileManager를 사용한 파일 업로드 ======
    $fileManager = new FileManager();
    $uploadedFileInfo = [];
    
    // 사진 파일 업로드
    if (isset($_FILES['photoFile']) && $_FILES['photoFile']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = $fileManager->uploadFile(
            $_FILES['photoFile'], 
            $claimId, 
            'photo', 
            $conn, 
            $applicationData['client_id'], 
            $applicationData['coupon_number']
        );
        $uploadedFileInfo[] = $fileInfo;
    }
    
    // 증명서 파일 업로드
    if (isset($_FILES['certificateFile']) && $_FILES['certificateFile']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = $fileManager->uploadFile(
            $_FILES['certificateFile'], 
            $claimId, 
            'certificate', 
            $conn, 
            $applicationData['client_id'], 
            $applicationData['coupon_number']
        );
        $uploadedFileInfo[] = $fileInfo;
    }
    
    // 추가 파일들 처리
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'additionalFile_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
            $fileInfo = $fileManager->uploadFile(
                $file, 
                $claimId, 
                'additional', 
                $conn, 
                $applicationData['client_id'], 
                $applicationData['coupon_number']
            );
            $uploadedFileInfo[] = $fileInfo;
        }
    }
    
    // 상태 이력 추가 (기존과 동일)
    $statusStmt = $conn->prepare("
        INSERT INTO holeinone_claim_status_history (
            claim_id, client_id, coupon_number, status, 
            comment, created_by, created_at
        ) VALUES (?, ?, ?, 'pending', '보상 신청 접수', 'system', NOW())
    ");
    $statusStmt->execute([$claimId, $applicationData['client_id'], $applicationData['coupon_number']]);
    
    // 트랜잭션 완료
    $conn->commit();

    // 성공 응답 (개선된 정보 포함)
    $response["success"] = true;
    $response["message"] = "홀인원 보상 신청이 성공적으로 접수되었습니다.";
    $response["data"] = [
        "claimId" => $claimId,
        "claimNumber" => $claimNumber,
        "claimHash" => $claimHash,
        "status" => "pending",
        "uploadedFiles" => [
            "count" => $fileManager->getUploadedFileCount(),
            "hasPhoto" => $fileManager->hasFileType('photo'),
            "hasCertificate" => $fileManager->hasFileType('certificate'),
            "hasAdditional" => $fileManager->hasFileType('additional')
        ]
    ];
    
} catch (PDOException $e) {
    // 트랜잭션 롤백
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // 업로드된 파일들 정리
    if (isset($fileManager)) {
        $fileManager->cleanupUploadedFiles();
    }
    
    $response["message"] = "데이터베이스 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보상 신청 PDO 오류: " . $e->getMessage());
    
} catch (Exception $e) {
    // 트랜잭션 롤백
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // 업로드된 파일들 정리
    if (isset($fileManager)) {
        $fileManager->cleanupUploadedFiles();
    }
    
    $response["message"] = "처리 중 오류가 발생했습니다: " . $e->getMessage();
    error_log("홀인원 보상 신청 처리 오류: " . $e->getMessage());
    
} finally {
    // 연결 닫기
    $conn = null;
}

// 기존 해시 생성 함수들 (동일)
function generateNameHash($name) {
    return hash('sha256', trim($name));
}

function generatePhoneHash($phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', $cleanPhone);
}

function generateNamePhoneHash($name, $phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', trim($name) . $cleanPhone);
}

// 기존 유틸리티 함수들 (동일)
function generateClaimNumber($conn) {
    $today = date('Ymd');
    $prefix = 'CLAIM' . $today;
    
    $stmt = $conn->prepare("
        SELECT claim_number FROM holeinone_claims 
        WHERE claim_number LIKE ? 
        ORDER BY claim_number DESC 
        LIMIT 1
    ");
    $stmt->execute([$prefix . '%']);
    $lastNumber = $stmt->fetchColumn();
    
    if ($lastNumber) {
        $sequence = intval(substr($lastNumber, -3)) + 1;
    } else {
        $sequence = 1;
    }
    
    return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
}

function generateClaimHash($applicationId, $golfCourse, $playDate, $holeNumber) {
    $data = $applicationId . $golfCourse . $playDate . $holeNumber . time();
    return hash('sha256', $data);
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>