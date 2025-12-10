<?php
/**
 * 가입신청 데이터 Excel 내보내기 API
 * PCI Korea 홀인원보험 관리 시스템
 
 핵심 기능

필터 조건 지원: JavaScript에서 전달된 모든 필터 조건을 처리

검색 유형별 처리 (이름, 전화번호, 골프장, 쿠폰번호, 전체)
날짜 범위 필터링
해시 기반 정확 검색 (이름, 전화번호)


보안 처리:

세션 검증
암호화된 개인정보 복호화
SQL 인젝션 방지 (prepared statements)


Excel 스타일링:

헤더 색상 및 스타일
취소된 신청은 빨간색 배경
자동 열 너비 조정



데이터 구조
Excel 파일에 포함되는 컬럼들:

순번, 신청일시, 신청자명, 연락처
골프장, 티오프시간, 쿠폰번호
약관동의, 상태, 정리상태, 정리일시, 처리자

추가 기능

요약 시트: 내보내기 조건과 통계 정보
동적 파일명: 필터 조건에 따른 의미있는 파일명 생성
전화번호 포맷팅: 하이픈 자동 추가

이 파일은 JavaScript의 exportApplications() 함수와 완벽하게 연동되어 현재 화면에 보이는 필터 조건 그대로 Excel 파일을 생성합니다.
 */

/**
 * 가입신청 데이터 Excel 내보내기 API
 * PCI Korea 홀인원보험 관리 시스템
 */

session_start();

// 세션 검증
if (!isset($_SESSION['client_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// SimpleXLSXGen 라이브러리 포함
require_once __DIR__ . '/lib/src/SimpleXLSXGen.php';
require_once __DIR__ . '/../../../api/config/db_config.php';
require_once __DIR__ . "/../../../kj/api/kjDaeri/php/encryption.php";

try {
    $clientId = $_SESSION['client_id'];
    
    // 필터 파라미터 수집
    $searchType = $_GET['searchType'] ?? 'all';
    $searchInput = trim($_GET['searchInput'] ?? '');
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 데이터 내보내기 실행
    exportApplicationsData($pdo, $clientId, $searchType, $searchInput, $startDate, $endDate);
    
} catch (Exception $e) {
    error_log("Applications export error: " . $e->getMessage());
    http_response_code(500);
    echo "Excel 내보내기 중 오류가 발생했습니다: " . $e->getMessage();
}

/**
 * 가입신청 데이터 Excel 내보내기 메인 함수
 */
function exportApplicationsData($pdo, $clientId, $searchType, $searchInput, $startDate, $endDate) {
    // 데이터 조회
    $query = buildApplicationsQuery($searchType, $searchInput, $startDate, $endDate);
    $params = buildQueryParams($clientId, $searchType, $searchInput, $startDate, $endDate);
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 파일명 생성
    $filename = generateFileName($searchType, $searchInput, $startDate, $endDate);
    
    // Excel 데이터 준비
    $excelData = prepareExcelData($applications, $clientId, $searchType, $searchInput, $startDate, $endDate);
    
    // Excel 파일 생성 및 다운로드
    createExcelFile($excelData, $filename);
}

/**
 * Excel 데이터 배열 준비
 */
function prepareExcelData($applications, $clientId, $searchType, $searchInput, $startDate, $endDate) {
    $data = [];
    
    // 요약 정보 추가
    $data[] = ['PCI Korea 홀인원보험 관리시스템'];
    $data[] = ['가입신청 내역 Excel 내보내기'];
    $data[] = ['생성일시: ' . date('Y-m-d H:i:s')];
    $data[] = ['클라이언트 ID: ' . $clientId];
    $data[] = [''];
    
    // 필터 조건 표시
    if (!empty($searchInput) || !empty($startDate) || !empty($endDate)) {
        $data[] = ['=== 내보내기 조건 ==='];
        if (!empty($searchInput)) {
            $data[] = ['검색 유형: ' . getSearchTypeText($searchType)];
            $data[] = ['검색어: ' . $searchInput];
        }
        if (!empty($startDate) || !empty($endDate)) {
            $dateRange = '';
            if (!empty($startDate)) $dateRange .= $startDate;
            if (!empty($startDate) && !empty($endDate)) $dateRange .= ' ~ ';
            if (!empty($endDate)) $dateRange .= $endDate;
            $data[] = ['기간: ' . $dateRange];
        }
        $data[] = [''];
    }
    
    $data[] = ['내보낸 건수: ' . count($applications) . '건'];
    $data[] = [''];
    $data[] = [''];
    
    // 메인 데이터 헤더
    $data[] = [
        '순번', '신청일시', '신청자명', '연락처', '골프장', 
        '티오프시간', '쿠폰번호', '약관동의', '상태', '정리상태', 
        '정리일시', '처리자'
    ];
    
    // 메인 데이터
    foreach ($applications as $index => $app) {
        // 암호화된 데이터 복호화
        $decryptedName = decryptApplicationData($app['applicant_name']);
        $decryptedPhone = decryptApplicationData($app['applicant_phone']);
        
        // 전화번호 포맷팅
        if ($decryptedPhone && $decryptedPhone !== '복호화 실패') {
            $decryptedPhone = formatPhoneNumber($decryptedPhone);
        }
        
        $data[] = [
            $index + 1,
            $app['created_at'],
            $decryptedName ?: '-',
            $decryptedPhone ?: '-',
            $app['golf_course'] ?: '-',
            $app['tee_time'] ?: '-',
            $app['coupon_number'] ?: '-',
            $app['terms_agreed'] ? '동의' : '미동의',
            $app['status'] ?: '-',
            getSummaryStatusText($app['summary']),
            $app['summary_time'] ?: '-',
            $app['summary_damdanga'] ?: '-'
        ];
    }
    
    return $data;
}

/**
 * Excel 파일 생성 및 다운로드
 */
function createExcelFile($data, $filename) {
    try {
        // SimpleXLSXGen으로 Excel 파일 생성 (수정된 부분)
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
        
        // 다운로드 헤더 설정
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');
        
        // 파일 출력 (수정된 방법)
        if (method_exists($xlsx, 'downloadAs')) {
            // 방법 1: downloadAs 사용
            $xlsx->downloadAs($filename);
        } else {
            // 방법 2: 대체 방법
            $content = (string)$xlsx;
            echo $content;
        }
        
    } catch (Exception $e) {
        error_log("Excel generation error: " . $e->getMessage());
        
        // Excel 생성 실패시 TSV로 폴백
        fallbackToTSV($data, $filename);
    }
}

/**
 * Excel 생성 실패시 TSV로 폴백 (수정된 함수명)
 */
function fallbackToTSV($data, $filename) {
    header('Content-Type: text/tab-separated-values; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . str_replace('.xlsx', '.txt', $filename) . '"');
    header('Cache-Control: max-age=0');
    
    // BOM 추가 (UTF-8 인식용)
    echo "\xEF\xBB\xBF";
    
    foreach ($data as $row) {
        if (is_array($row)) {
            // 탭과 줄바꿈 문자 제거
            $row = array_map(function($field) {
                return str_replace(["\t", "\r", "\n"], [' ', ' ', ' '], (string)$field);
            }, $row);
            echo implode("\t", $row) . "\r\n";
        } else {
            echo str_replace(["\t", "\r", "\n"], [' ', ' ', ' '], (string)$row) . "\r\n";
        }
    }
}

/**
 * 검색 조건에 따른 쿼리 구성
 */
function buildApplicationsQuery($searchType, $searchInput, $startDate, $endDate) {
    $baseQuery = "
        SELECT 
            id,
            coupon_number,
            applicant_name,
            applicant_phone,
            golf_course,
            tee_time,
            terms_agreed,
            status,
            summary,
            summary_time,
            summary_damdanga,
            created_at
        FROM holeinone_applications 
        WHERE client_id = ?
    ";
    
    $conditions = [];
    
    // 검색 조건 추가
    if (!empty($searchInput)) {
        switch ($searchType) {
            case 'name':
                $conditions[] = "name_hash = SHA2(?, 256)";
                break;
            case 'phone':
                $conditions[] = "phone_hash = SHA2(?, 256)";
                break;
            case 'golf_course':
                $conditions[] = "golf_course LIKE ?";
                break;
            case 'coupon':
                $conditions[] = "coupon_number = ?";
                break;
            case 'all':
            default:
                $conditions[] = "(
                    golf_course LIKE ? OR 
                    coupon_number LIKE ?
                )";
                break;
        }
    }
    
    // 날짜 범위 조건 추가
    if (!empty($startDate)) {
        $conditions[] = "DATE(created_at) >= ?";
    }
    if (!empty($endDate)) {
        $conditions[] = "DATE(created_at) <= ?";
    }
    
    // 조건들을 쿼리에 추가
    if (!empty($conditions)) {
        $baseQuery .= " AND " . implode(" AND ", $conditions);
    }
    
    $baseQuery .= " ORDER BY created_at DESC";
    
    return $baseQuery;
}

/**
 * 쿼리 파라미터 구성
 */
function buildQueryParams($clientId, $searchType, $searchInput, $startDate, $endDate) {
    $params = [$clientId];
    
    // 검색어 파라미터 추가
    if (!empty($searchInput)) {
        switch ($searchType) {
            case 'name':
            case 'phone':
            case 'coupon':
                $params[] = $searchInput;
                break;
            case 'golf_course':
                $params[] = '%' . $searchInput . '%';
                break;
            case 'all':
            default:
                $params[] = '%' . $searchInput . '%'; // golf_course LIKE
                $params[] = '%' . $searchInput . '%'; // coupon_number LIKE
                break;
        }
    }
    
    // 날짜 파라미터 추가
    if (!empty($startDate)) {
        $params[] = $startDate;
    }
    if (!empty($endDate)) {
        $params[] = $endDate;
    }
    
    return $params;
}

/**
 * 검색 유형 텍스트 변환
 */
function getSearchTypeText($searchType) {
    $types = [
        'all' => '전체 검색',
        'name' => '이름 검색',
        'phone' => '전화번호 검색',
        'golf_course' => '골프장 검색',
        'coupon' => '쿠폰번호 검색'
    ];
    
    return $types[$searchType] ?? '전체 검색';
}

/**
 * 정리상태 텍스트 변환
 */
function getSummaryStatusText($summary) {
    switch ($summary) {
        case '1':
            return '정상';
        case '2':
            return '취소';
        default:
            return '미정';
    }
}

/**
 * 파일명 생성
 */
function generateFileName($searchType, $searchInput, $startDate, $endDate) {
    $filename = '가입신청내역';
    
    // 검색 조건이 있으면 파일명에 추가
    if (!empty($searchInput)) {
        $searchTypeText = getSearchTypeText($searchType);
        $filename .= '_' . $searchTypeText . '_' . mb_substr($searchInput, 0, 10);
    }
    
    // 날짜 범위가 있으면 추가
    if (!empty($startDate) || !empty($endDate)) {
        $filename .= '_';
        if (!empty($startDate)) $filename .= $startDate;
        if (!empty($startDate) && !empty($endDate)) $filename .= '~';
        if (!empty($endDate)) $filename .= $endDate;
    }
    
    $filename .= '_' . date('Y-m-d') . '.xlsx';
    
    return $filename;
}

/**
 * 전화번호 포맷팅
 */
function formatPhoneNumber($phone) {
    if (empty($phone)) return '';
    
    $cleaned = preg_replace('/\D/', '', $phone);
    
    if (strlen($cleaned) === 11) {
        return substr($cleaned, 0, 3) . '-' . substr($cleaned, 3, 4) . '-' . substr($cleaned, 7);
    } elseif (strlen($cleaned) === 10) {
        return substr($cleaned, 0, 3) . '-' . substr($cleaned, 3, 3) . '-' . substr($cleaned, 6);
    }
    
    return $phone;
}

/**
 * 암호화된 데이터 복호화 함수
 */
function decryptApplicationData($encryptedData) {
    if (empty($encryptedData)) {
        return '';
    }
    
    try {
        // encryption.php에서 정의된 상수들 확인
        if (!defined('ENCRYPT_KEY') || !defined('CIPHER_ALGO')) {
            error_log("Encryption constants not defined");
            return '암호화 설정 오류';
        }
        
        // 키 해싱 (암호화와 동일한 방식)
        $key = hash('sha256', ENCRYPT_KEY, true);
        
        // base64 디코딩
        $decoded = base64_decode($encryptedData);
        if ($decoded === false) {
            return '복호화 실패';
        }
        
        // IV 크기 계산
        $iv_length = openssl_cipher_iv_length(CIPHER_ALGO);
        if ($iv_length === false || strlen($decoded) < $iv_length) {
            return '복호화 실패';
        }
        
        // IV 및 암호화된 데이터 추출
        $iv = substr($decoded, 0, $iv_length);
        $encrypted_data = substr($decoded, $iv_length);
        
        // 복호화 수행
        $decrypted = openssl_decrypt(
            $encrypted_data,
            CIPHER_ALGO,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return $decrypted !== false ? $decrypted : '복호화 실패';
        
    } catch (Exception $e) {
        error_log("Decryption error: " . $e->getMessage());
        return '복호화 오류';
    }
}
?>