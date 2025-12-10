<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 응답 함수
function sendResponse($success, $message, $data = null, $errorCode = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'errorCode' => $errorCode
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 로그인 체크 (applications_data.php와 동일)
if (!isset($_SESSION['client_id'])) {
    sendResponse(false, '로그인이 필요합니다.', null, 'AUTH_REQUIRED');
}

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, '잘못된 요청 방식입니다.', null, 'METHOD_NOT_ALLOWED');
}

try {
    // JSON 입력 데이터 파싱
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendResponse(false, '잘못된 요청 데이터입니다.', null, 'INVALID_JSON');
    }
    
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 세션 정보
    $client_id = $_SESSION['client_id'];
    
    // 요청 파라미터
    $page = max(1, intval($input['page'] ?? 1));
    $limit = max(1, min(100, intval($input['limit'] ?? 10)));
    $search = trim($input['search'] ?? '');
    $status = $input['status'] ?? '';
    $startDate = $input['startDate'] ?? '';
    $endDate = $input['endDate'] ?? '';
    $sortBy = $input['sortBy'] ?? 'created_at';
    $sortOrder = $input['sortOrder'] ?? 'desc';
    
    // 정렬 컬럼 검증 (SQL 인젝션 방지)
    $allowedSortColumns = [
        'created_at', 'updated_at', 'golf_course', 'play_date', 'status', 'customer_name'
    ];
    
    if (!in_array($sortBy, $allowedSortColumns)) {
        $sortBy = 'created_at';
    }
    
    // 정렬 순서 검증
    $sortOrder = strtolower($sortOrder) === 'asc' ? 'ASC' : 'DESC';
    
    // WHERE 조건 구성
    $whereConditions = ['client_id = ?'];
    $whereParams = [$client_id];
    
    // 상태 필터
    if (!empty($status) && in_array($status, ['pending', 'reviewing', 'investigating', 'approved', 'rejected', 'completed'])) {
        $whereConditions[] = 'status = ?';
        $whereParams[] = $status;
    }
    
    // 검색 조건 처리
    if (!empty($search)) {
        // 골프장명 또는 신청번호로 검색 (암호화되지 않은 필드)
        $whereConditions[] = '(golf_course LIKE ? OR claim_number LIKE ?)';
        $searchParam = '%' . $search . '%';
        $whereParams[] = $searchParam;
        $whereParams[] = $searchParam;
    }
    
    // 날짜 범위 조건
    if (!empty($startDate)) {
        $whereConditions[] = 'DATE(created_at) >= ?';
        $whereParams[] = $startDate;
    }
    
    if (!empty($endDate)) {
        $whereConditions[] = 'DATE(created_at) <= ?';
        $whereParams[] = $endDate;
    }
    
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
    
    // 총 개수 조회
    $countSql = "SELECT COUNT(*) as total FROM holeinone_claims $whereClause";
    $countStmt = $pdo->prepare($countSql);
    
    if (!$countStmt) {
        throw new Exception("COUNT 쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
    }
    
    $countResult = $countStmt->execute($whereParams);
    if (!$countResult) {
        throw new Exception("COUNT 쿼리 실행 실패: " . implode(", ", $countStmt->errorInfo()));
    }
    
    $totalRow = $countStmt->fetch();
    $total = $totalRow ? (int)$totalRow['total'] : 0;
    
    // 총 페이지 수 계산
    $totalPages = ceil($total / $limit);
    
    // 데이터 조회
    $offset = ($page - 1) * $limit;
    
    $dataSql = "
        SELECT 
            id,
            application_id,
            client_id,
            coupon_number,
            claim_number,
            customer_name,
            customer_phone,
            golf_course,
            play_date,
            hole_number,
            yardage,
            club_used,
            witness_name,
            witness_phone,
            bank_name,
            account_number,
            account_holder,
            photo_file,
            certificate_file,
            additional_files,
            additional_notes,
            status,
            customer_name_hash,
            customer_phone_hash,
            created_at,
            updated_at
        FROM holeinone_claims 
        $whereClause
        ORDER BY $sortBy $sortOrder
        LIMIT ? OFFSET ?
    ";
    
    $dataParams = array_merge($whereParams, [$limit, $offset]);
    $dataStmt = $pdo->prepare($dataSql);
    
    if (!$dataStmt) {
        throw new Exception("데이터 쿼리 준비 실패: " . implode(", ", $pdo->errorInfo()));
    }
    
    $dataResult = $dataStmt->execute($dataParams);
    if (!$dataResult) {
        throw new Exception("데이터 쿼리 실행 실패: " . implode(", ", $dataStmt->errorInfo()));
    }
    
    $claims_raw = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 암호화된 개인정보 복호화 및 데이터 처리
    $claims = [];
    $decryptionErrors = 0;
    
    foreach ($claims_raw as $claim) {
        try {
            // 개인정보 복호화
            $decrypted_customer_name = decryptData($claim['customer_name']);
            $decrypted_customer_phone = decryptData($claim['customer_phone']);
            $decrypted_witness_name = decryptData($claim['witness_name']);
            $decrypted_account_number = decryptData($claim['account_number']);
            $decrypted_account_holder = decryptData($claim['account_holder']);
            
            // 복호화 실패 시 처리
            if ($decrypted_customer_name === false || $decrypted_customer_name === null) {
                $decrypted_customer_name = '복호화 실패';
                $decryptionErrors++;
            }
            
            if ($decrypted_customer_phone === false || $decrypted_customer_phone === null) {
                $decrypted_customer_phone = '복호화 실패';
                $decryptionErrors++;
            }
            
            if ($decrypted_witness_name === false || $decrypted_witness_name === null) {
                $decrypted_witness_name = '복호화 실패';
            }
            
            if ($decrypted_account_number === false || $decrypted_account_number === null) {
                $decrypted_account_number = '복호화 실패';
            }
            
            if ($decrypted_account_holder === false || $decrypted_account_holder === null) {
                $decrypted_account_holder = '복호화 실패';
            }
            
            // 전화번호 포맷팅
            if ($decrypted_customer_phone !== '복호화 실패') {
                $decrypted_customer_phone = formatPhoneNumber($decrypted_customer_phone);
            }
            
            // 추가 파일 처리
            $additionalFiles = [];
            if (!empty($claim['additional_files'])) {
                $additionalFiles = json_decode($claim['additional_files'], true) ?: [];
            }
            
            $claims[] = [
                'id' => $claim['id'],
                'application_id' => $claim['application_id'],
                'client_id' => $claim['client_id'],
                'coupon_number' => $claim['coupon_number'],
                'claim_number' => $claim['claim_number'],
                'customer_name' => $decrypted_customer_name,
                'customer_phone' => $decrypted_customer_phone,
                'golf_course' => $claim['golf_course'],
                'play_date' => $claim['play_date'],
                'hole_number' => $claim['hole_number'],
                'yardage' => $claim['yardage'],
                'club_used' => $claim['club_used'],
                'witness_name' => $decrypted_witness_name,
                'bank_name' => $claim['bank_name'],
                'account_number' => $decrypted_account_number,
                'account_holder' => $decrypted_account_holder,
                'photo_file' => $claim['photo_file'],
                'certificate_file' => $claim['certificate_file'],
                'additional_files' => $additionalFiles,
                'additional_notes' => $claim['additional_notes'],
                'status' => $claim['status'],
                'created_at' => $claim['created_at'],
                'updated_at' => $claim['updated_at']
            ];
            
        } catch (Exception $e) {
            // 개별 레코드 처리 중 오류 발생시 로그 기록하고 계속 진행
            error_log("Claims record decryption error (ID: {$claim['id']}): " . $e->getMessage());
            $decryptionErrors++;
            
            // 기본값으로 처리
            $claims[] = [
                'id' => $claim['id'],
                'application_id' => $claim['application_id'],
                'client_id' => $claim['client_id'],
                'coupon_number' => $claim['coupon_number'],
                'claim_number' => $claim['claim_number'],
                'customer_name' => '복호화 오류',
                'customer_phone' => '복호화 오류',
                'golf_course' => $claim['golf_course'],
                'play_date' => $claim['play_date'],
                'hole_number' => $claim['hole_number'],
                'yardage' => $claim['yardage'],
                'club_used' => $claim['club_used'],
                'witness_name' => '복호화 오류',
                'bank_name' => $claim['bank_name'],
                'account_number' => '복호화 오류',
                'account_holder' => '복호화 오류',
                'photo_file' => $claim['photo_file'],
                'certificate_file' => $claim['certificate_file'],
                'additional_files' => [],
                'additional_notes' => $claim['additional_notes'],
                'status' => $claim['status'],
                'created_at' => $claim['created_at'],
                'updated_at' => $claim['updated_at']
            ];
        }
    }
    
    // 통계 정보 추가 조회
    $statsSql = "
        SELECT 
            status,
            COUNT(*) as count
        FROM holeinone_claims 
        WHERE client_id = ?
        GROUP BY status
    ";
    $statsStmt = $pdo->prepare($statsSql);
    $statsStmt->execute([$client_id]);
    $statsRaw = $statsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats = [
        'total' => 0,
        'pending' => 0,
        'reviewing' => 0,
        'investigating' => 0,
        'approved' => 0,
        'rejected' => 0,
        'completed' => 0
    ];
    
    foreach ($statsRaw as $stat) {
        if (isset($stats[$stat['status']])) {
            $stats[$stat['status']] = (int)$stat['count'];
        }
        $stats['total'] += (int)$stat['count'];
    }
    
    // 응답 데이터 구성
    $response_data = [
        'claims' => $claims,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $total,
            'itemsPerPage' => $limit,
            'hasNext' => $page < $totalPages,
            'hasPrev' => $page > 1,
            'startItem' => ($page - 1) * $limit + 1,
            'endItem' => min($page * $limit, $total)
        ],
        'statistics' => $stats,
        'filters' => [
            'search' => $search,
            'status' => $status,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sortBy' => $sortBy,
            'sortOrder' => strtolower($sortOrder)
        ],
        'meta' => [
            'decryptionErrors' => $decryptionErrors,
            'processedRecords' => count($claims_raw),
            'successfulRecords' => count($claims_raw) - $decryptionErrors
        ]
    ];
    
    $message = "홀인원 보상신청 데이터 조회 성공 (총 {$total}건)";
    if ($decryptionErrors > 0) {
        $message .= " - 복호화 실패: {$decryptionErrors}건";
    }
    
    sendResponse(true, $message, $response_data);
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Claims Data API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // 개발 환경에서는 자세한 에러 메시지, 운영 환경에서는 일반적인 메시지
    $error_message = (defined('DEBUG') && DEBUG) ? 
        '홀인원 보상신청 데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage() :
        '홀인원 보상신청 데이터를 불러오는 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    
    sendResponse(false, $error_message, null, 'SERVER_ERROR');
}

// 전화번호 포맷팅 함수 (applications_data.php와 동일)
function formatPhoneNumber($phone) {
    if (!$phone) return '';
    
    // 숫자만 추출
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    
    // 11자리 휴대폰 번호 포맷팅
    if (strlen($cleanPhone) === 11 && substr($cleanPhone, 0, 2) === '01') {
        return substr($cleanPhone, 0, 3) . '-' . substr($cleanPhone, 3, 4) . '-' . substr($cleanPhone, 7, 4);
    }
    
    // 10자리 전화번호 포맷팅 (지역번호)
    if (strlen($cleanPhone) === 10) {
        return substr($cleanPhone, 0, 3) . '-' . substr($cleanPhone, 3, 3) . '-' . substr($cleanPhone, 6, 4);
    }
    
    // 기타 경우는 원본 반환
    return $cleanPhone;
}
?>