<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 디버깅을 위한 오류 표시 활성화
ini_set('display_errors', 1);

// 응답 배열 초기화
$response = array(
    "success" => false,
    "message" => "",
    "data" => [],
    "errorCode" => ""
);

try {
    // POST 데이터 가져오기 (JSON으로 받음)
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        // JSON이 아닌 경우 POST로 받기
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    } else {
        $phone = isset($input['phone']) ? trim($input['phone']) : '';
        $name = isset($input['name']) ? trim($input['name']) : '';
    }
    
    // 필수 입력값 검증 (전화번호는 필수, 이름은 선택)
    if (empty($phone)) {
        $response["message"] = "휴대폰번호를 입력해주세요.";
        $response["errorCode"] = "INVALID_PHONE";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 핸드폰 번호 포맷 정리 (하이픈 제거)
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    // 핸드폰 번호 유효성 검증
    if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 11) {
        $response["message"] = "올바른 휴대폰 번호를 입력해주세요.";
        $response["errorCode"] = "INVALID_PHONE";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 데이터베이스 연결
    $conn = getDbConnection();
    
    // 검색 방식 결정: 이름이 있으면 이름+전화번호로, 없으면 전화번호만으로 검색
    if (!empty($name)) {
        // 이름 + 전화번호로 정확한 검색
        $searchHash = generateNamePhoneHash($name, $cleanPhone);
        $searchColumn = 'name_phone_hash';
        $searchType = '이름과 전화번호';
    } else {
        // 전화번호만으로 검색
        $searchHash = generatePhoneHash($cleanPhone);
        $searchColumn = 'phone_hash';
        $searchType = '전화번호';
    }
    
    // 해시를 이용한 빠른 검색
    $stmt = $conn->prepare("
        SELECT 
            id as signupId,
            client_id,
            coupon_number as couponNumber,
            applicant_name as encryptedName,
            applicant_phone as encryptedPhone,
            golf_course as golfCourseName,
            tee_time as teeOffTime,
            terms_agreed as termsAgreed,
            unique_hash as uniqueHash,
            status as dbStatus,
            created_at as signupDate,
            updated_at as updatedDate
        FROM holeinone_applications 
        WHERE {$searchColumn} = ?
        ORDER BY created_at DESC
    ");
    
    $stmt->execute([$searchHash]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        $response["message"] = "해당 {$searchType}로 가입된 내역이 없습니다.";
        $response["errorCode"] = "DATA_NOT_FOUND";
        $response["success"] = false;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 결과 데이터 처리 (복호화)
    $historyData = [];
    foreach ($results as $row) {
        // 개인정보 복호화
        $decryptedName = decryptData($row['encryptedName']);
        $decryptedPhone = decryptData($row['encryptedPhone']);
        
        // 복호화 실패한 경우 건너뛰기
        if ($decryptedName === false || $decryptedPhone === false) {
            continue;
        }
        
        // 이름만으로 검색한 경우 전화번호 재검증
        if (empty($name)) {
            $decryptedPhoneClean = preg_replace('/[^0-9]/', '', $decryptedPhone);
            if ($decryptedPhoneClean !== $cleanPhone) {
                continue; // 전화번호가 일치하지 않으면 건너뛰기
            }
        }
        
        // 상태 결정 로직 - pending을 가입완료로 처리
        $teeOffTime = strtotime($row['teeOffTime']);
        $currentTime = time();
        $dbStatus = $row['dbStatus']; // pending, approved, rejected
        
        // 상태 매핑 (pending을 정상 가입으로 처리)
        $status = 'ACTIVE'; // 기본값: 가입완료
        
        if ($dbStatus === 'pending' || $dbStatus === 'approved') {
            if ($teeOffTime < $currentTime) {
                $status = 'COMPLETED'; // 티오프 시간 지남 = 이용완료
            } else {
                $status = 'ACTIVE'; // 티오프 시간 전 = 가입완료
            }
        } elseif ($dbStatus === 'rejected') {
            $status = 'CANCELLED'; // 거절됨 = 취소됨
        }
        
        $historyData[] = [
            'signupId' => 'ACE' . str_pad($row['signupId'], 7, '0', STR_PAD_LEFT),
            'id' => $row['signupId'],
            'clientId' => $row['client_id'],
            'couponNumber' => $row['couponNumber'],
            'customerName' => $decryptedName,
            'name' => $decryptedName,
            'phoneNumber' => $decryptedPhone,
            'golfCourseName' => $row['golfCourseName'],
            'golfCourse' => $row['golfCourseName'],
            'teeOffTime' => $row['teeOffTime'],
            'teeTime' => $row['teeOffTime'],
            'createdAt' => $row['signupDate'],
            'signupDate' => $row['signupDate'],
            'updatedAt' => $row['updatedDate'],
            'status' => $status,
            'uniqueHash' => $row['uniqueHash'],
            'termsAgreed' => $row['termsAgreed']
        ];
    }
    
    // 최종 결과가 없는 경우
    if (empty($historyData)) {
        $response["message"] = "해당 {$searchType}로 가입된 내역이 없습니다.";
        $response["errorCode"] = "DATA_NOT_FOUND";
        $response["success"] = false;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 성공 응답
    $response["success"] = true;
    $response["message"] = "가입내역 조회가 완료되었습니다. (총 " . count($historyData) . "건)";
    $response["data"] = $historyData;
    $response["searchInfo"] = [
        "searchType" => $searchType,
        "resultCount" => count($historyData),
        "searchHash" => $searchHash // 디버깅용 (운영시 제거 가능)
    ];
    
} catch (PDOException $e) {
    $response["message"] = "데이터베이스 오류가 발생했습니다.";
    $response["errorCode"] = "DATABASE_ERROR";
    error_log("가입내역 조회 DB 오류: " . $e->getMessage());
} catch (Exception $e) {
    $response["message"] = "서버 오류가 발생했습니다. 고객센터로 문의해주세요.";
    $response["errorCode"] = "SERVER_ERROR";
    error_log("가입내역 조회 처리 오류: " . $e->getMessage());
} finally {
    // 연결 닫기
    $conn = null;
}

// 해시 생성 함수들
function generatePhoneHash($phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', $cleanPhone);
}

function generateNamePhoneHash($name, $phone) {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    return hash('sha256', trim($name) . $cleanPhone);
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>