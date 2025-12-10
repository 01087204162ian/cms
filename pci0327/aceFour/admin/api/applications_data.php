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

// 로그인 체크
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
    $limit = max(1, min(100, intval($input['limit'] ?? 10))); // 최대 100개로 제한
    $search = trim($input['search'] ?? '');
    $searchType = $input['searchType'] ?? 'all'; // all, name, phone, golf_course
    $startDate = $input['startDate'] ?? '';
    $endDate = $input['endDate'] ?? '';
    $status = $input['status'] ?? ''; // pending, approved, rejected
    $sortBy = $input['sortBy'] ?? 'created_at';
    $sortOrder = $input['sortOrder'] ?? 'desc';
    
    // 정렬 컬럼 검증 (SQL 인젝션 방지)
    $allowedSortColumns = [
        'created_at', 'updated_at', 'golf_course', 'tee_time', 'status', 'coupon_number'
    ];
    
    // applicantName을 실제 DB 컬럼명으로 매핑
    if ($sortBy === 'applicantName') {
        $sortBy = 'created_at'; // 이름 정렬은 암호화되어 있어서 불가능, 생성일로 대체
    }
    
    if (!in_array($sortBy, $allowedSortColumns)) {
        $sortBy = 'created_at';
    }
    
    // 정렬 순서 검증
    $sortOrder = strtolower($sortOrder) === 'asc' ? 'ASC' : 'DESC';
    
    // WHERE 조건 구성
    $whereConditions = ['client_id = ?'];
    $whereParams = [$client_id];
    
    // 상태 필터 (상태 관리가 필요한 경우)
    if (!empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
        $whereConditions[] = 'status = ?';
        $whereParams[] = $status;
    }
    
    // 개선된 검색 조건 처리
    if (!empty($search)) {
        switch ($searchType) {
            case 'name':
                // 이름 검색 - 해시 사용
                $nameHash = generateNameHash($search);
                $whereConditions[] = 'name_hash = ?';
                $whereParams[] = $nameHash;
                break;
                
            case 'phone':
                // 전화번호 검색 - 해시 사용
                $cleanPhone = preg_replace('/[^0-9]/', '', $search);
                $phoneHash = generatePhoneHash($cleanPhone);
                $whereConditions[] = 'phone_hash = ?';
                $whereParams[] = $phoneHash;
                break;
                
            case 'golf_course':
                // 골프장 검색 - 직접 검색 (암호화되지 않음)
                $whereConditions[] = 'golf_course LIKE ?';
                $whereParams[] = '%' . $search . '%';
                break;
                
            case 'coupon':
                // 쿠폰 번호 검색
                $whereConditions[] = 'coupon_number LIKE ?';
                $whereParams[] = '%' . $search . '%';
                break;
                
            default: // 'all'
                // 전체 검색 - 골프장과 쿠폰번호만 직접 검색 가능
                $whereConditions[] = '(golf_course LIKE ? OR coupon_number LIKE ?)';
                $searchParam = '%' . $search . '%';
                $whereParams[] = $searchParam;
                $whereParams[] = $searchParam;
                break;
        }
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
    $countSql = "SELECT COUNT(*) as total FROM holeinone_applications $whereClause";
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
    
    // 데이터 조회 (summary 필드 추가)
    $offset = ($page - 1) * $limit;
    
    $dataSql = "
        SELECT 
            id,
            client_id,
            coupon_number,
            applicant_name,
            applicant_phone,
            golf_course,
            tee_time,
            terms_agreed,
            unique_hash,
            name_hash,
            phone_hash,
            name_phone_hash,
            status,
            summary,
            summary_time,
            summary_damdanga,
            created_at,
            updated_at
        FROM holeinone_applications 
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
    
    $applications_raw = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 암호화된 개인정보 복호화 및 데이터 처리
    $applications = [];
    $decryptionErrors = 0; // 복호화 실패 카운트
    
    foreach ($applications_raw as $app) {
        try {
            // 개인정보 복호화
            $decrypted_name = decryptData($app['applicant_name']);
            $decrypted_phone = decryptData($app['applicant_phone']);
            
            // 복호화 실패 시 처리
            if ($decrypted_name === false || $decrypted_name === null) {
                $decrypted_name = '복호화 실패';
                $decryptionErrors++;
            }
            
            if ($decrypted_phone === false || $decrypted_phone === null) {
                $decrypted_phone = '복호화 실패';
                $decryptionErrors++;
            }
            
            // 전화번호 포맷팅 (성공적으로 복호화된 경우)
            if ($decrypted_phone !== '복호화 실패') {
                $decrypted_phone = formatPhoneNumber($decrypted_phone);
            }
            
            // 상태 결정 로직 (간소화)
            $teeOffTime = strtotime($app['tee_time']);
            $currentTime = time();
            $dbStatus = $app['status'] ?? 'pending';
            
            // 수정 가능 여부 판단: summary가 1(정상)인 경우만 수정 가능
            $canEdit = ($app['summary'] == '1');
            
            // summary 상태 텍스트
            $summaryStatus = '';
            switch ($app['summary']) {
                case '1':
                    $summaryStatus = '정상';
                    break;
                case '2':
                    $summaryStatus = '취소';
                    break;
                default:
                    $summaryStatus = '알 수 없음';
                    break;
            }
            
            $applications[] = [
                'id' => $app['id'],
                'signupId' => 'ACE' . str_pad($app['id'], 7, '0', STR_PAD_LEFT),
                'clientId' => $app['client_id'],
                'couponNumber' => $app['coupon_number'],
                'applicantName' => $decrypted_name, // 복호화된 이름
                'applicantPhone' => $decrypted_phone, // 복호화된 전화번호
                'golfCourse' => $app['golf_course'],
                'teeTime' => $app['tee_time'],
                'termsAgreed' => (bool)$app['terms_agreed'],
                'uniqueHash' => $app['unique_hash'],
                'status' => $dbStatus,
                'summary' => $app['summary'],
                'summaryStatus' => $summaryStatus,
                'summaryTime' => $app['summary_time'],
                'summaryDamdanga' => $app['summary_damdanga'],
                'canEdit' => $canEdit, // 수정 가능 여부
                'isExpired' => $teeOffTime < $currentTime,
                'createdAt' => $app['created_at'],
                'updatedAt' => $app['updated_at']
            ];
            
        } catch (Exception $e) {
            // 개별 레코드 처리 중 오류 발생시 로그 기록하고 계속 진행
            error_log("Record decryption error (ID: {$app['id']}): " . $e->getMessage());
            $decryptionErrors++;
            
            // 기본값으로 처리
            $applications[] = [
                'id' => $app['id'],
                'signupId' => 'ACE' . str_pad($app['id'], 7, '0', STR_PAD_LEFT),
                'clientId' => $app['client_id'],
                'couponNumber' => $app['coupon_number'],
                'applicantName' => '복호화 오류',
                'applicantPhone' => '복호화 오류',
                'golfCourse' => $app['golf_course'],
                'teeTime' => $app['tee_time'],
                'termsAgreed' => (bool)$app['terms_agreed'],
                'uniqueHash' => $app['unique_hash'],
                'status' => $app['status'] ?? 'pending',
                'summary' => $app['summary'],
                'summaryStatus' => ($app['summary'] == '1') ? '정상' : (($app['summary'] == '2') ? '취소' : '알 수 없음'),
                'summaryTime' => $app['summary_time'],
                'summaryDamdanga' => $app['summary_damdanga'],
                'canEdit' => ($app['summary'] == '1'), // 수정 가능 여부
                'isExpired' => false,
                'createdAt' => $app['created_at'],
                'updatedAt' => $app['updated_at']
            ];
        }
    }
    
    // 통계 정보 추가 조회 (summary 상태별 통계 추가)
    $statsSql = "
        SELECT 
            status,
            summary,
            COUNT(*) as count
        FROM holeinone_applications 
        WHERE client_id = ?
        GROUP BY status, summary
    ";
    $statsStmt = $pdo->prepare($statsSql);
    $statsStmt->execute([$client_id]);
    $statsRaw = $statsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats = [
        'total' => 0,
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0,
        'summary_normal' => 0, // summary=1 (정상)
        'summary_cancelled' => 0, // summary=2 (취소)
        'editable_count' => 0 // 수정 가능한 건수
    ];
    
    foreach ($statsRaw as $stat) {
        $stats[$stat['status']] = ($stats[$stat['status']] ?? 0) + (int)$stat['count'];
        $stats['total'] += (int)$stat['count'];
        
        // summary 상태별 통계
        if ($stat['summary'] == '1') {
            $stats['summary_normal'] += (int)$stat['count'];
            $stats['editable_count'] += (int)$stat['count']; // 정상 상태는 수정 가능
        } elseif ($stat['summary'] == '2') {
            $stats['summary_cancelled'] += (int)$stat['count'];
        }
    }
    
    // 응답 데이터 구성
    $response_data = [
        'applications' => $applications,
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
            'searchType' => $searchType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'sortBy' => $sortBy,
            'sortOrder' => strtolower($sortOrder)
        ],
        'searchTypes' => [
            'all' => '전체',
            'name' => '이름',
            'phone' => '전화번호',
            'golf_course' => '골프장',
            'coupon' => '쿠폰번호'
        ],
        'meta' => [
            'decryptionErrors' => $decryptionErrors,
            'processedRecords' => count($applications_raw),
            'successfulRecords' => count($applications_raw) - $decryptionErrors,
            'editableRecords' => $stats['editable_count'] // 수정 가능한 레코드 수
        ]
    ];
    
    $message = "데이터 조회 성공 (총 {$total}건, 수정 가능 {$stats['editable_count']}건)";
    if ($decryptionErrors > 0) {
        $message .= " - 복호화 실패: {$decryptionErrors}건";
    }
    
    sendResponse(true, $message, $response_data);
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Applications Data API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // 개발 환경에서는 자세한 에러 메시지, 운영 환경에서는 일반적인 메시지
    $error_message = (defined('DEBUG') && DEBUG) ? 
        '데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage() :
        '데이터를 불러오는 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    
    sendResponse(false, $error_message, null, 'SERVER_ERROR');
}

// 해시 생성 함수들
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

// 전화번호 포맷팅 함수
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