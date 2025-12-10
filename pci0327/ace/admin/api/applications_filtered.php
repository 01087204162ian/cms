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

// GET 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, '잘못된 요청 방식입니다.', null, 'METHOD_NOT_ALLOWED');
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 세션 정보
    $client_id = $_SESSION['client_id'];
    
    // GET 파라미터 받기
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 20)));
    $searchType = $_GET['searchType'] ?? 'all';
    $searchInput = trim($_GET['searchInput'] ?? '');
    $testIsFilter = $_GET['testIsFilter'] ?? ''; // 쿠폰 차수 필터
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $sortBy = $_GET['sortBy'] ?? 'created_at';
    $sortOrder = $_GET['sortOrder'] ?? 'desc';
    
    // 정렬 컬럼 검증 (SQL 인젝션 방지)
    $allowedSortColumns = [
        'created_at', 'updated_at', 'golf_course', 'tee_time', 'status', 'applicant_name'
    ];
    
    if (!in_array($sortBy, $allowedSortColumns)) {
        $sortBy = 'created_at';
    }
    
    // 정렬 순서 검증
    $sortOrder = strtolower($sortOrder) === 'asc' ? 'ASC' : 'DESC';
    
    // WHERE 조건 구성
    $whereConditions = ['client_id = ?'];
    $whereParams = [$client_id];
    
    // 쿠폰 차수 필터 추가
    if (!empty($testIsFilter)) {
        if ($testIsFilter === '1') {
            // 1차 쿠폰: coupon_number의 12번째 자리가 '1' 또는 '2'
            $whereConditions[] = '(SUBSTRING(coupon_number, 12, 1) = "1" OR SUBSTRING(coupon_number, 12, 1) = "2")';
        } elseif ($testIsFilter === '2') {
            // 2차 쿠폰: coupon_number의 12번째 자리가 '3' 또는 '4'
            $whereConditions[] = '(SUBSTRING(coupon_number, 12, 1) = "3" OR SUBSTRING(coupon_number, 12, 1) = "4")';
        }
    }
    
    // 검색 조건 처리
    if (!empty($searchInput)) {
        switch($searchType) {
            case 'name':
                // 암호화된 이름 검색 - 해시 기반
                $nameHash = hash('sha256', $searchInput);
                $whereConditions[] = 'name_hash = ?';
                $whereParams[] = $nameHash;
                break;
                
            case 'phone':
                // 암호화된 전화번호 검색 - 해시 기반
                $cleanPhone = preg_replace('/[^0-9]/', '', $searchInput);
                $phoneHash = hash('sha256', $cleanPhone);
                $whereConditions[] = 'phone_hash = ?';
                $whereParams[] = $phoneHash;
                break;
                
            case 'golf_course':
                // 골프장명 부분 검색 (암호화되지 않음)
                $whereConditions[] = 'golf_course LIKE ?';
                $whereParams[] = '%' . $searchInput . '%';
                break;
                
            case 'coupon':
                // 쿠폰번호 정확 검색 (암호화되지 않음)
                $whereConditions[] = 'coupon_number = ?';
                $whereParams[] = $searchInput;
                break;
                
            default: // 'all'
                // 전체 검색 - 골프장명과 쿠폰번호에서만 검색 (암호화되지 않은 필드)
                $whereConditions[] = '(golf_course LIKE ? OR coupon_number LIKE ?)';
                $searchParam = '%' . $searchInput . '%';
                $whereParams[] = $searchParam;
                $whereParams[] = $searchParam;
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
    $totalPages = $total > 0 ? ceil($total / $limit) : 1;
    
    // 데이터 조회
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
            status,
            created_at,
            updated_at,
            name_hash,
            phone_hash,
            name_phone_hash,
            summary,
            summary_time,
            summary_damdanga
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
    $decryptionErrors = 0;
    
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
            
            // 전화번호 포맷팅
            if ($decrypted_phone !== '복호화 실패') {
                $decrypted_phone = formatPhoneNumber($decrypted_phone);
            }
            
            // 동반자 정보 조회
            $companionCountSql = "
                SELECT COUNT(*) as count 
                FROM holeinone_companions 
                WHERE application_id = ?
            ";
            $companionStmt = $pdo->prepare($companionCountSql);
            $companionStmt->execute([$app['id']]);
            $companionRow = $companionStmt->fetch();
            $companionCount = $companionRow ? (int)$companionRow['count'] : 0;
            
            // testIs 필드 추출 (쿠폰 차수 판별용)
            // coupon_number의 12번째 자리로 판별
            $testIs = '1'; // 기본값
            if (!empty($app['coupon_number']) && strlen($app['coupon_number']) >= 12) {
                $testIs = substr($app['coupon_number'], 11, 1); // 12번째 자리 (0-based index)
            }
            
            $applications[] = [
                'id' => $app['id'],
                'client_id' => $app['client_id'],
                'coupon_number' => $app['coupon_number'],
                'couponNumber' => $app['coupon_number'], // JavaScript 호환성
                'applicant_name' => $decrypted_name,
                'applicantName' => $decrypted_name, // JavaScript 호환성
                'applicant_phone' => $decrypted_phone,
                'applicantPhone' => $decrypted_phone, // JavaScript 호환성
                'golf_course' => $app['golf_course'],
                'golfCourse' => $app['golf_course'], // JavaScript 호환성
                'tee_time' => $app['tee_time'],
                'teeTime' => $app['tee_time'], // JavaScript 호환성
                'terms_agreed' => $app['terms_agreed'],
                'termsAgreed' => $app['terms_agreed'], // JavaScript 호환성
                'unique_hash' => $app['unique_hash'],
                'uniqueHash' => $app['unique_hash'], // JavaScript 호환성
                'status' => $app['status'],
                'created_at' => $app['created_at'],
                'createdAt' => $app['created_at'], // JavaScript 호환성
                'updated_at' => $app['updated_at'],
                'updatedAt' => $app['updated_at'], // JavaScript 호환성
                'summary' => $app['summary'],
                'summary_time' => $app['summary_time'],
                'summaryTime' => $app['summary_time'], // JavaScript 호환성
                'summary_damdanga' => $app['summary_damdanga'],
                'summaryDamdanga' => $app['summary_damdanga'], // JavaScript 호환성
                'canEdit' => ($app['summary'] === '1'), // 정상인 경우만 편집 가능
                'hasCompanions' => $companionCount > 0,
                'companionCount' => $companionCount,
                'companion_count' => $companionCount, // JavaScript 호환성
                'testIs' => $testIs,
                'test_is' => $testIs // JavaScript 호환성
            ];
            
        } catch (Exception $e) {
            // 개별 레코드 처리 중 오류 발생시 로그 기록하고 계속 진행
            error_log("Applications record decryption error (ID: {$app['id']}): " . $e->getMessage());
            $decryptionErrors++;
            
            // 기본값으로 처리
            $testIs = '1';
            if (!empty($app['coupon_number']) && strlen($app['coupon_number']) >= 12) {
                $testIs = substr($app['coupon_number'], 11, 1);
            }
            
            $applications[] = [
                'id' => $app['id'],
                'client_id' => $app['client_id'],
                'coupon_number' => $app['coupon_number'],
                'couponNumber' => $app['coupon_number'],
                'applicant_name' => '복호화 오류',
                'applicantName' => '복호화 오류',
                'applicant_phone' => '복호화 오류',
                'applicantPhone' => '복호화 오류',
                'golf_course' => $app['golf_course'],
                'golfCourse' => $app['golf_course'],
                'tee_time' => $app['tee_time'],
                'teeTime' => $app['tee_time'],
                'terms_agreed' => $app['terms_agreed'],
                'termsAgreed' => $app['terms_agreed'],
                'unique_hash' => $app['unique_hash'],
                'uniqueHash' => $app['unique_hash'],
                'status' => $app['status'],
                'created_at' => $app['created_at'],
                'createdAt' => $app['created_at'],
                'updated_at' => $app['updated_at'],
                'updatedAt' => $app['updated_at'],
                'summary' => $app['summary'],
                'summary_time' => $app['summary_time'],
                'summaryTime' => $app['summary_time'],
                'summary_damdanga' => $app['summary_damdanga'],
                'summaryDamdanga' => $app['summary_damdanga'],
                'canEdit' => ($app['summary'] === '1'),
                'hasCompanions' => false,
                'companionCount' => 0,
                'companion_count' => 0,
                'testIs' => $testIs,
                'test_is' => $testIs
            ];
        }
    }
    
    // 통계 정보 추가 조회
    $statsSql = "
        SELECT 
            summary,
            COUNT(*) as count
        FROM holeinone_applications 
        WHERE client_id = ?
        GROUP BY summary
    ";
    $statsStmt = $pdo->prepare($statsSql);
    $statsStmt->execute([$client_id]);
    $statsRaw = $statsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats = [
        'total' => 0,
        'active' => 0,    // summary = '1' (정상)
        'cancelled' => 0  // summary = '2' (취소)
    ];
    
    foreach ($statsRaw as $stat) {
        if ($stat['summary'] === '1') {
            $stats['active'] = (int)$stat['count'];
        } elseif ($stat['summary'] === '2') {
            $stats['cancelled'] = (int)$stat['count'];
        }
        $stats['total'] += (int)$stat['count'];
    }
    
    // 오늘 신청건수 조회 (모든 상태 포함)
    $todayStatsSql = "
        SELECT COUNT(*) as today_count
        FROM holeinone_applications 
        WHERE client_id = ? 
        AND DATE(created_at) = CURDATE()
    ";
    $todayStatsStmt = $pdo->prepare($todayStatsSql);
    $todayStatsStmt->execute([$client_id]);
    $todayStats = $todayStatsStmt->fetch();
    $stats['today'] = $todayStats ? (int)$todayStats['today_count'] : 0;
    
    // 응답 데이터 구성
    $response_data = [
        'applications' => $applications,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $total,
            'limit' => $limit,
            'hasNext' => $page < $totalPages,
            'hasPrev' => $page > 1,
            'startItem' => $total > 0 ? ($page - 1) * $limit + 1 : 0,
            'endItem' => min($page * $limit, $total)
        ],
        'statistics' => $stats,
        'filters' => [
            'searchType' => $searchType,
            'searchInput' => $searchInput,
            'testIsFilter' => $testIsFilter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sortBy' => $sortBy,
            'sortOrder' => strtolower($sortOrder)
        ],
        'meta' => [
            'decryptionErrors' => $decryptionErrors,
            'processedRecords' => count($applications_raw),
            'successfulRecords' => count($applications_raw) - $decryptionErrors
        ]
    ];
    
    $message = "가입신청 데이터 조회 성공 (총 {$total}건)";
    if ($decryptionErrors > 0) {
        $message .= " - 복호화 실패: {$decryptionErrors}건";
    }
    
    sendResponse(true, $message, $response_data);
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Applications Filtered API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // 개발 환경에서는 자세한 에러 메시지, 운영 환경에서는 일반적인 메시지
    $error_message = (defined('DEBUG') && DEBUG) ? 
        '가입신청 데이터를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage() :
        '가입신청 데이터를 불러오는 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    
    sendResponse(false, $error_message, null, 'SERVER_ERROR');
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
    
    // 9자리 (서울 지역번호)
    if (strlen($cleanPhone) === 9 && substr($cleanPhone, 0, 2) === '02') {
        return '02-' . substr($cleanPhone, 2, 3) . '-' . substr($cleanPhone, 5, 4);
    }
    
    // 기타 경우는 원본 반환
    return $cleanPhone;
}
?>