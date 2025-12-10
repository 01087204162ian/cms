<?php
/**
 * 사고 데이터 처리 API
 * PHP 버전: 8.2
 * 
 * 클라이언트로부터 사고 관련 데이터를 받아 데이터베이스에 저장합니다.
 */
declare(strict_types=1);
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once '../smsApi/smsAligo.php';
// 에러 핸들링 설정
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // POST 데이터 확인
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('POST 요청만 허용됩니다.');
    }
    
    // 필수 입력 필드 검증 - 클라이언트 측 필드명 사용
    $requiredFields = ['customerName', 'customerPhone', 'usageDate', 'claimDetails'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        throw new Exception('다음 필드는 필수입니다: ' . implode(', ', $missingFields));
    }
    
    // 접수번호 생성 (날짜 + 랜덤 숫자)
    $today = date('Ymd');
    $randomNum = str_pad((string)mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    $accident_id = (int)($today . $randomNum);
    
    // 현재 시간 설정
    $currentDateTime = date('Y-m-d H:i:s');
    
    // 데이터 준비 - 클라이언트 필드명을 데이터베이스 필드명에 매핑
// 데이터 준비 - 클라이언트 필드명을 데이터베이스 필드명에 매핑
$data = [
    'gisa_name' => $_POST['driverName'] ?? '',
    'gisa_contact' => $_POST['driverPhone'] ?? '',
    'customer_name' => $_POST['customerName'],
    'customer_contact' => $_POST['customerPhone'],
    'customer_email' => $_POST['customerEmail'] ?? '',
    'usageDate' => $_POST['usageDate'],
    'start_location' => $_POST['startLocation'] ?? '',
    'destination' => $_POST['destination'] ?? '',
    'claimType' => $_POST['claimType'] ?? '',
    'claimUrgency' => $_POST['claimUrgency'] ?? '일반',
    'claimDetails' => $_POST['claimDetails'],
    'claimRequest' => $_POST['claimRequest'] ?? '',
    'contractor_name' => $_POST['contractorName'] ?? '',
    'contractor_contact' => $_POST['contractorPhone'] ?? '',
    'accident_location' => $_POST['accidentLocation'] ?? '',
    'accident_date' => $_POST['accidentDate'] ?? date('Y-m-d'),
    'accident_time' => $_POST['accidentTime'] ?? date('H:i:s'),
    'receiver' => 'system', // 시스템 자동 접수
    'received_datetime' => $currentDateTime,
    'accident_details' => $_POST['accidentDetails'] ?? $_POST['claimDetails'],
    'handler' => NULL,
    'notes' => $_POST['notes'] ?? '',
    'created_at' => $currentDateTime,
    'folder' => $_POST['folder'] ?? '',
    'receipt_number' => $_POST['receiptNumber'] ?? null  // 바로 여기에 추가
];
    
    // SQL 쿼리 작성
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $sql = "INSERT INTO accident_data ($columns) VALUES ($placeholders)";
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));
    
		
    // 응답 데이터 구성
    $response = [
        'success' => true,
        'message' => '사고 데이터가 성공적으로 저장되었습니다.',
        'claimNumber' => "AC-{$accident_id}",
        'timestamp' => $currentDateTime
    ];
     list($hphone1, $hphone2, $hphone3) = explode("-",$_POST['customerPhone']);
		    $msg=$_POST['customerName']." 님 불편사항 접수되었습니다. 곧 연락드리겠습니다.";
		    $receiver=$hphone1.$hphone2.$hphone3;
			$sendData = [
					"receiver" => $receiver,
					"msg" => $msg,
					"testmode_yn" => "N"
				];

// 함수 호출
			$result = sendAligoSms($sendData);
    // JSON 응답 반환
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류 처리
    $response = [
        'success' => false,
        'message' => '데이터베이스 오류: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    http_response_code(500);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // 일반 오류 처리
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    http_response_code(400);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // 데이터베이스 연결 닫기 (PDO는 스크립트 종료 시 자동으로 닫히므로 옵션)
    $pdo = null;
}
?>