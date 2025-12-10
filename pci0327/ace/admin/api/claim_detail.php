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

// 파라미터 검증
$claim_id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($claim_id) || !is_numeric($claim_id)) {
    sendResponse(false, '유효하지 않은 보상신청 ID입니다.', null, 'INVALID_CLAIM_ID');
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    $client_id = $_SESSION['client_id'];
    
    // 보상신청 기본 정보 조회
    $stmt = $pdo->prepare("
        SELECT 
            hc.*,
            ha.applicant_name as application_name,
            ha.applicant_phone as application_phone,
            ha.golf_course as application_golf_course,
            ha.tee_time
        FROM holeinone_claims hc
        LEFT JOIN holeinone_applications ha ON hc.application_id = ha.id
        WHERE hc.id = ? AND hc.client_id = ?
    ");
    
    $stmt->execute([$claim_id, $client_id]);
    $claim = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$claim) {
        sendResponse(false, '보상신청 정보를 찾을 수 없습니다.', null, 'CLAIM_NOT_FOUND');
    }
    
    // 암호화된 데이터 복호화
    try {
        $decrypted_data = [
            'customer_name' => decryptData($claim['customer_name']),
            'customer_phone' => decryptData($claim['customer_phone']),
            'witness_name' => decryptData($claim['witness_name']),
            'witness_phone' => decryptData($claim['witness_phone']),
            'account_number' => decryptData($claim['account_number']),
            'account_holder' => decryptData($claim['account_holder'])
        ];
        
        // 복호화 실패 시 기본값 처리
        foreach ($decrypted_data as $key => $value) {
            if ($value === false || $value === null) {
                $decrypted_data[$key] = '복호화 실패';
            }
        }
        
        // 전화번호 포맷팅
        if ($decrypted_data['customer_phone'] !== '복호화 실패') {
            $decrypted_data['customer_phone'] = formatPhoneNumber($decrypted_data['customer_phone']);
        }
        if ($decrypted_data['witness_phone'] !== '복호화 실패') {
            $decrypted_data['witness_phone'] = formatPhoneNumber($decrypted_data['witness_phone']);
        }
        
    } catch (Exception $e) {
        error_log("Claim detail decryption error (ID: {$claim_id}): " . $e->getMessage());
        
        // 복호화 실패시 기본값
        $decrypted_data = [
            'customer_name' => '복호화 오류',
            'customer_phone' => '복호화 오류',
            'witness_name' => '복호화 오류',
            'witness_phone' => '복호화 오류',
            'account_number' => '복호화 오류',
            'account_holder' => '복호화 오류'
        ];
    }
    
    // 첨부파일 정보 조회
    $photos = [];
    $scorecard = null;
    $additional_files = [];
    
    // holeinone_claim_attachments 테이블에서 첨부파일 조회
    try {
        $stmt_files = $pdo->prepare("
            SELECT 
                file_type,
                original_filename,
                stored_filename,
                file_size,
                mime_type,
                file_path,
                created_at
            FROM holeinone_claim_attachments 
            WHERE claim_id = ? AND client_id = ? AND is_deleted = 0
            ORDER BY file_type, created_at
        ");
        
        $stmt_files->execute([$claim_id, $client_id]);
        $attachments = $stmt_files->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($attachments as $file) {
            // 파일 경로에서 실제 URL 생성
            $file_url = generateFileUrl($file['stored_filename'], $file['created_at']);
            
            // 파일 존재 여부 확인
            $file_exists = checkFileExists($file_url);
            
            $file_info = [
                'filename' => $file['original_filename'],
                'stored_filename' => $file['stored_filename'],
                'size' => $file['file_size'],
                'mime_type' => $file['mime_type'],
                'url' => $file_url,
                'uploaded_at' => $file['created_at'],
                'exists' => $file_exists,
                'size_formatted' => formatFileSize($file['file_size'])
            ];
            
            switch ($file['file_type']) {
                case 'photo':
                    $photos[] = array_merge($file_info, ['title' => '홀인원 현장 사진']);
                    break;
                case 'certificate':
                    if (!$scorecard) {
                        $scorecard = array_merge($file_info, ['title' => '스코어카드']);
                    } else {
                        $additional_files[] = array_merge($file_info, ['title' => '증명서']);
                    }
                    break;
                case 'additional':
                    $additional_files[] = array_merge($file_info, ['title' => '추가 서류']);
                    break;
            }
        }
        
    } catch (Exception $e) {
        error_log("Attachment query error: " . $e->getMessage());
    }
    
    // 레거시 파일 처리 (photo_file, certificate_file)
    if (!empty($claim['photo_file']) && empty($photos)) {
        $legacy_photo_url = generateFileUrl($claim['photo_file'], $claim['created_at']);
        $photos[] = [
            'filename' => $claim['photo_file'],
            'stored_filename' => $claim['photo_file'],
            'url' => $legacy_photo_url,
            'title' => '홀인원 현장 사진',
            'uploaded_at' => $claim['created_at'],
            'exists' => checkFileExists($legacy_photo_url),
            'size_formatted' => '크기 정보 없음'
        ];
    }
    
    if (!empty($claim['certificate_file']) && !$scorecard) {
        $legacy_cert_url = generateFileUrl($claim['certificate_file'], $claim['created_at']);
        $scorecard = [
            'filename' => $claim['certificate_file'],
            'stored_filename' => $claim['certificate_file'],
            'url' => $legacy_cert_url,
            'title' => '스코어카드',
            'uploaded_at' => $claim['created_at'],
            'exists' => checkFileExists($legacy_cert_url),
            'size_formatted' => '크기 정보 없음'
        ];
    }
    
    // additional_files JSON 처리
    if (!empty($claim['additional_files'])) {
        $legacy_additional = json_decode($claim['additional_files'], true) ?: [];
        foreach ($legacy_additional as $file) {
            if (is_array($file)) {
                $filename = $file['name'] ?? $file['filename'] ?? 'unknown';
            } else {
                $filename = $file;
            }
            
            $legacy_add_url = generateFileUrl($filename, $claim['created_at']);
            $additional_files[] = [
                'filename' => $filename,
                'stored_filename' => $filename,
                'url' => $legacy_add_url,
                'title' => '추가 서류',
                'uploaded_at' => $claim['created_at'],
                'exists' => checkFileExists($legacy_add_url),
                'size_formatted' => '크기 정보 없음'
            ];
        }
    }
    
    // 상태 변경 이력 조회
    $history = [];
    try {
        $stmt_history = $pdo->prepare("
            SELECT 
                status,
                comment,
                created_by,
                created_at
            FROM holeinone_claim_status_history 
            WHERE claim_id = ? AND client_id = ?
            ORDER BY created_at DESC
        ");
        
        $stmt_history->execute([$claim_id, $client_id]);
        $history_records = $stmt_history->fetchAll(PDO::FETCH_ASSOC);
        
        // 이력 데이터 포맷팅
        $action_titles = [
            'pending' => '보상신청 접수',
            'reviewing' => '검토 시작',
            'investigating' => '조사 시작', 
            'approved' => '보상 승인',
            'rejected' => '보상 거절',
            'completed' => '보상금 지급 완료'
        ];
        
        foreach ($history_records as $record) {
            $admin_name = '관리자';
            if ($record['created_by'] === 'system') {
                $admin_name = '시스템';
            }
            
            $history[] = [
                'status' => $record['status'],
                'action_title' => $action_titles[$record['status']] ?? '상태 변경',
                'comment' => $record['comment'] ?: '비고 없음',
                'admin_name' => $admin_name,
                'created_at' => $record['created_at']
            ];
        }
        
    } catch (Exception $e) {
        error_log("History query error: " . $e->getMessage());
    }
    
    // 이력이 없으면 기본 이력 추가
    if (empty($history)) {
        $action_titles = [
            'pending' => '보상신청 접수',
            'reviewing' => '검토 시작',
            'investigating' => '조사 시작', 
            'approved' => '보상 승인',
            'rejected' => '보상 거절',
            'completed' => '보상금 지급 완료'
        ];
        
        $history[] = [
            'status' => $claim['status'],
            'action_title' => $action_titles[$claim['status']] ?? '신청 접수',
            'comment' => '보상신청이 접수되었습니다.',
            'admin_name' => '시스템',
            'created_at' => $claim['created_at']
        ];
    }
    
    // 응답 데이터 구성
    $response_data = [
        // 기본 정보
        'id' => $claim['id'],
        'claim_number' => $claim['claim_number'],
        'coupon_number' => $claim['coupon_number'],
        'application_id' => $claim['application_id'],
        'status' => $claim['status'],
        'created_at' => $claim['created_at'],
        'updated_at' => $claim['updated_at'],
        
        // 고객 정보 (복호화된 데이터)
        'customer_name' => $decrypted_data['customer_name'],
        'customer_phone' => $decrypted_data['customer_phone'],
        'customer_email' => '', // 이메일 필드가 없으므로 빈 값
        'customer_address' => '', // 주소 필드가 없으므로 빈 값
        
        // 경기 정보
        'golf_course' => $claim['golf_course'],
        'play_date' => $claim['play_date'],
        'hole_number' => $claim['hole_number'],
        'yardage' => $claim['yardage'],
        'used_club' => $claim['club_used'],
        'caddy_name' => '', // 캐디명 필드가 없으므로 빈 값
        
        // 목격자 정보
        'witness_name' => $decrypted_data['witness_name'],
        'witness_phone' => $decrypted_data['witness_phone'],
        
        // 계좌 정보
        'bank_name' => $claim['bank_name'],
        'account_number' => $decrypted_data['account_number'],
        'account_holder' => $decrypted_data['account_holder'],
        
        // 보상 관련 정보
        'approved_amount' => $claim['approved_amount'],
        'rejection_reason' => $claim['rejection_reason'],
        'payment_date' => $claim['payment_date'],
        
        // 추가 정보
        'description' => $claim['additional_notes'] ?: '추가 설명이 없습니다.',
        'terms_agreed' => $claim['terms_agreed'],
        
        // 첨부파일 (개선된 정보 포함)
        'photos' => $photos,
        'scorecard' => $scorecard,
        'additional_files' => $additional_files,
        
        // 처리 이력
        'history' => $history,
        
        // 메타 정보
        'meta' => [
            'total_photos' => count($photos),
            'has_scorecard' => !empty($scorecard),
            'total_additional_files' => count($additional_files),
            'total_history_records' => count($history),
            'photos_exist' => count(array_filter($photos, function($p) { return $p['exists']; })),
            'scorecard_exists' => $scorecard && $scorecard['exists']
        ]
    ];
    
    sendResponse(true, '보상신청 상세 정보 조회 성공', $response_data);
    
} catch (Exception $e) {
    // 에러 로그 기록
    error_log("Claim Detail API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    sendResponse(false, '보상신청 상세 정보를 불러오는 중 오류가 발생했습니다: ' . $e->getMessage(), [
        'error_file' => $e->getFile(),
        'error_line' => $e->getLine()
    ], 'SERVER_ERROR');
}

// ===== 새로 추가된 유틸리티 함수들 =====

/**
 * 파일명과 생성일시로부터 실제 파일 URL 생성
 */
function generateFileUrl($stored_filename, $created_at) {
    // 생성일시로부터 연/월/일 경로 추출
    $date = new DateTime($created_at);
    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');
    
    return "../uploads/claims/{$year}/{$month}/{$day}/{$stored_filename}";
}

/**
 * 파일 존재 여부 확인
 */
function checkFileExists($file_url) {
    // 상대 경로를 절대 경로로 변환
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $full_path = $document_root . str_replace('..', '/pci0327/www/ace', $file_url);
    
    return file_exists($full_path);
}

/**
 * 파일 크기를 읽기 쉬운 형태로 변환
 */
function formatFileSize($bytes) {
    if ($bytes === null || $bytes === '') return '크기 정보 없음';
    
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= (1 << (10 * $pow));
    
    return round($bytes, 2) . ' ' . $units[$pow];
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