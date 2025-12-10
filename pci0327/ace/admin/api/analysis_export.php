<?php
/**
 * 데이터 분석 Excel 내보내기 API
 * PCI Korea 홀인원보험 관리 시스템
 */

session_start();

// 세션 검증
if (!isset($_SESSION['client_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// PhpSpreadsheet 라이브러리 (Composer로 설치 필요)
require_once 'vendor/autoload.php';
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    $type = $_GET['type'] ?? '';
    $clientId = $_SESSION['client_id'];
    
    // 데이터베이스 연결
    $pdo = getDbConnection();
    
    switch ($type) {
        case 'past_tee_time':
            exportPastTeeTimeData($pdo, $clientId);
            break;
            
        case 'golf_course_stats':
            exportGolfCourseStats($pdo, $clientId);
            break;
            
        case 'full_used_coupons':
            exportFullUsedCoupons($pdo, $clientId);
            break;
            
        default:
            throw new Exception('잘못된 내보내기 타입입니다.');
    }
    
} catch (Exception $e) {
    error_log("Analysis export error: " . $e->getMessage());
    http_response_code(500);
    echo "Excel 내보내기 중 오류가 발생했습니다: " . $e->getMessage();
}

/**
 * 지난 티업시간 데이터 Excel 내보내기
 */
function exportPastTeeTimeData($pdo, $clientId) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('지난 티업시간 분석');
    
    // 헤더 설정
    $headers = ['순번', '신청자명', '골프장', '티업시간', '신청일', '경과일'];
    $sheet->fromArray($headers, null, 'A1');
    
    // 헤더 스타일 적용
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
    
    // 데이터 조회
    $now = date('Y-m-d H:i:s');
    $query = "
        SELECT 
            applicant_name,
            golf_course,
            tee_time,
            created_at,
            DATEDIFF(?, tee_time) as days_after
        FROM holeinone_applications 
        WHERE client_id = ? AND tee_time < ?
        ORDER BY tee_time DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$now, $clientId, $now]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 데이터 입력
    $row = 2;
    foreach ($applications as $index => $app) {
        // 암호화된 데이터 복호화
        $decryptedName = decryptApplicationData($app['applicant_name']);
        
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $decryptedName);
        $sheet->setCellValue('C' . $row, $app['golf_course']);
        $sheet->setCellValue('D' . $row, $app['tee_time']);
        $sheet->setCellValue('E' . $row, $app['created_at']);
        $sheet->setCellValue('F' . $row, $app['days_after'] . '일');
        $row++;
    }
    
    // 열 너비 자동 조정
    foreach (range('A', 'F') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // 통계 정보 추가 (요약 시트)
    addSummarySheet($spreadsheet, $pdo, $clientId, 'past_tee_time');
    
    // 파일 출력
    $filename = '지난티업시간_' . date('Y-m-d') . '.xlsx';
    outputExcelFile($spreadsheet, $filename);
}

/**
 * 골프장별 통계 Excel 내보내기
 */
function exportGolfCourseStats($pdo, $clientId) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('골프장별 통계');
    
    // 헤더 설정
    $headers = ['순위', '골프장명', '신청건수', '비율(%)', '누적비율(%)'];
    $sheet->fromArray($headers, null, 'A1');
    
    // 헤더 스타일 적용
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '70AD47']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
    
    // 데이터 조회
    $totalQuery = "SELECT COUNT(*) FROM holeinone_applications WHERE client_id = ?";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->execute([$clientId]);
    $totalApplications = $totalStmt->fetchColumn();
    
    $coursesQuery = "
        SELECT 
            golf_course,
            COUNT(*) as count,
            ROUND((COUNT(*) * 100.0 / ?), 1) as percentage
        FROM holeinone_applications 
        WHERE client_id = ?
        GROUP BY golf_course
        ORDER BY count DESC
    ";
    $coursesStmt = $pdo->prepare($coursesQuery);
    $coursesStmt->execute([$totalApplications, $clientId]);
    $courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 데이터 입력
    $row = 2;
    $cumulativePercentage = 0;
    foreach ($courses as $index => $course) {
        $cumulativePercentage += $course['percentage'];
        
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $course['golf_course']);
        $sheet->setCellValue('C' . $row, $course['count']);
        $sheet->setCellValue('D' . $row, $course['percentage']);
        $sheet->setCellValue('E' . $row, round($cumulativePercentage, 1));
        $row++;
    }
    
    // 열 너비 자동 조정
    foreach (range('A', 'E') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // 통계 정보 추가
    addSummarySheet($spreadsheet, $pdo, $clientId, 'golf_course_stats');
    
    // 파일 출력
    $filename = '골프장별통계_' . date('Y-m-d') . '.xlsx';
    outputExcelFile($spreadsheet, $filename);
}

/**
 * 2회 사용 쿠폰의 개별 신청 목록 Excel 내보내기
 */
function exportFullUsedCoupons($pdo, $clientId) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('2회사용쿠폰 신청목록');
    
    // 헤더 설정
    $headers = ['순번', '신청일시', '신청자명', '연락처', '골프장', '티오프시간', '쿠폰번호', '사용순서', '검증상태', '비고'];
    $sheet->fromArray($headers, null, 'A1');
    
    // 헤더 스타일 적용
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E74C3C']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
    
    // 2회 사용된 쿠폰의 모든 신청 데이터 조회
    $fullUsedCouponsQuery = "
        SELECT coupon_number
        FROM holeinone_applications 
        WHERE client_id = ?
        GROUP BY coupon_number
        HAVING COUNT(*) = 2
    ";
    $fullUsedCouponsStmt = $pdo->prepare($fullUsedCouponsQuery);
    $fullUsedCouponsStmt->execute([$clientId]);
    $fullUsedCouponNumbers = $fullUsedCouponsStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($fullUsedCouponNumbers)) {
        // 데이터가 없는 경우
        $sheet->setCellValue('A2', '2회 사용된 쿠폰이 없습니다.');
        $sheet->mergeCells('A2:J2');
        outputExcelFile($spreadsheet, '2회사용쿠폰목록_' . date('Y-m-d') . '.xlsx');
        return;
    }
    
    // 모든 신청 내역 조회
    $placeholders = str_repeat('?,', count($fullUsedCouponNumbers) - 1) . '?';
    $applicationsQuery = "
        SELECT 
            applicant_name,
            applicant_phone,
            golf_course,
            tee_time,
            created_at,
            coupon_number
        FROM holeinone_applications 
        WHERE client_id = ? AND coupon_number IN ($placeholders)
        ORDER BY coupon_number ASC, created_at ASC
    ";
    
    $params = array_merge([$clientId], $fullUsedCouponNumbers);
    $applicationsStmt = $pdo->prepare($applicationsQuery);
    $applicationsStmt->execute($params);
    $applications = $applicationsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 쿠폰별 사용 순서 추적
    $couponUsageMap = [];
    
    // 데이터 입력
    $row = 2;
    foreach ($applications as $index => $app) {
        // 쿠폰 사용 순서 계산
        if (!isset($couponUsageMap[$app['coupon_number']])) {
            $couponUsageMap[$app['coupon_number']] = 0;
        }
        $couponUsageMap[$app['coupon_number']]++;
        $usageOrder = $couponUsageMap[$app['coupon_number']];
        $usageLabel = $usageOrder === 1 ? '1차 사용' : '2차 사용';
        
        // 암호화된 데이터 복호화
        $decryptedName = decryptApplicationData($app['applicant_name']);
        $decryptedPhone = decryptApplicationData($app['applicant_phone']);
        
        // 전화번호 포맷팅
        if ($decryptedPhone && $decryptedPhone !== '복호화 실패') {
            $decryptedPhone = formatPhoneNumber($decryptedPhone);
        }
        
        // 검증 상태 확인 (같은 쿠폰의 다른 신청과 비교)
        $validationStatus = '정상';
        $note = '';
        
        if ($usageOrder === 2) {
            // 2차 사용인 경우 1차 사용과 비교
            $firstUsage = null;
            foreach ($applications as $otherApp) {
                if ($otherApp['coupon_number'] === $app['coupon_number'] && 
                    $otherApp['created_at'] < $app['created_at']) {
                    $firstUsage = $otherApp;
                    break;
                }
            }
            
            if ($firstUsage) {
                $daysDiff = (strtotime($app['created_at']) - strtotime($firstUsage['created_at'])) / (60 * 60 * 24);
                
                if ($daysDiff < 1) {
                    $validationStatus = '당일 2회 사용';
                    $note = '확인 필요';
                } elseif ($firstUsage['golf_course'] === $app['golf_course']) {
                    $validationStatus = '동일 골프장';
                    $note = '확인 권장';
                }
            }
        }
        
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $app['created_at']);
        $sheet->setCellValue('C' . $row, $decryptedName);
        $sheet->setCellValue('D' . $row, $decryptedPhone);
        $sheet->setCellValue('E' . $row, $app['golf_course']);
        $sheet->setCellValue('F' . $row, $app['tee_time']);
        $sheet->setCellValue('G' . $row, $app['coupon_number']);
        $sheet->setCellValue('H' . $row, $usageLabel);
        $sheet->setCellValue('I' . $row, $validationStatus);
        $sheet->setCellValue('J' . $row, $note);
        
        // 검증 상태에 따른 행 색상 적용
        if ($validationStatus !== '정상') {
            $fillColor = $validationStatus === '당일 2회 사용' ? 'FFCDD2' : 'FFF3E0';
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $fillColor]]
            ]);
        }
        
        // 1차/2차 사용별 행 색상 구분
        if ($usageOrder === 1) {
            $sheet->getStyle('H' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']]
            ]);
        } else {
            $sheet->getStyle('H' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E8']]
            ]);
        }
        
        $row++;
    }
    
    // 열 너비 자동 조정
    foreach (range('A', 'J') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // 통계 요약 시트 추가
    addApplicationSummarySheet($spreadsheet, $pdo, $clientId, $fullUsedCouponNumbers);
    
    // 파일 출력
    $filename = '2회사용쿠폰목록_' . date('Y-m-d') . '.xlsx';
    outputExcelFile($spreadsheet, $filename);
}

function addApplicationSummarySheet($spreadsheet, $pdo, $clientId, $fullUsedCouponNumbers) {
    $summarySheet = $spreadsheet->createSheet();
    $summarySheet->setTitle('검증 요약');
    
    // 기본 정보
    $summarySheet->setCellValue('A1', 'PCI Korea 홀인원보험 관리시스템');
    $summarySheet->setCellValue('A2', '2회 사용 쿠폰 신청 목록 요약');
    $summarySheet->setCellValue('A3', '생성일시: ' . date('Y-m-d H:i:s'));
    $summarySheet->setCellValue('A4', '클라이언트 ID: ' . $clientId);
    
    // 통계 계산
    $totalCoupons = count($fullUsedCouponNumbers);
    $totalApplications = $totalCoupons * 2; // 2회 사용이므로
    
    // 검증 통계 조회
    $placeholders = str_repeat('?,', count($fullUsedCouponNumbers) - 1) . '?';
    $params = array_merge([$clientId], $fullUsedCouponNumbers);
    
    $sameDayQuery = "
        SELECT COUNT(DISTINCT coupon_number) as count
        FROM (
            SELECT coupon_number, DATE(created_at) as date, COUNT(*) as daily_count
            FROM holeinone_applications 
            WHERE client_id = ? AND coupon_number IN ($placeholders)
            GROUP BY coupon_number, DATE(created_at)
            HAVING daily_count = 2
        ) as daily_usage
    ";
    $sameDayStmt = $pdo->prepare($sameDayQuery);
    $sameDayStmt->execute($params);
    $sameDayCount = $sameDayStmt->fetchColumn();
    
    $summarySheet->setCellValue('A6', '=== 통계 요약 ===');
    $summarySheet->setCellValue('A7', '2회 사용 쿠폰:');
    $summarySheet->setCellValue('B7', $totalCoupons . '개');
    $summarySheet->setCellValue('A8', '총 신청 건수:');
    $summarySheet->setCellValue('B8', $totalApplications . '건');
    $summarySheet->setCellValue('A9', '당일 2회 사용:');
    $summarySheet->setCellValue('B9', $sameDayCount . '개');
    $summarySheet->setCellValue('A10', '정상 사용:');
    $summarySheet->setCellValue('B10', ($totalCoupons - $sameDayCount) . '개');
    $summarySheet->setCellValue('A11', '정상 사용률:');
    $summarySheet->setCellValue('B11', round((($totalCoupons - $sameDayCount) / $totalCoupons) * 100, 1) . '%');
    
    $summarySheet->getStyle('A1:A11')->getFont()->setBold(true);
    $summarySheet->getColumnDimension('A')->setAutoSize(true);
    $summarySheet->getColumnDimension('B')->setAutoSize(true);
}ays);
        $sheet->setCellValue('F' . $row, '완료');
        $sheet->setCellValue('G' . $row, $note);
        $row++;
    }
    
    // 열 너비 자동 조정
    foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // 통계 정보 추가
    addSummarySheet($spreadsheet, $pdo, $clientId, 'full_used_coupons');
    
    // 파일 출력
    $filename = '2회사용쿠폰_' . date('Y-m-d') . '.xlsx';
    outputExcelFile($spreadsheet, $filename);
}

/**
 * 요약 통계 시트 추가
 */
function addSummarySheet($spreadsheet, $pdo, $clientId, $type) {
    $summarySheet = $spreadsheet->createSheet();
    $summarySheet->setTitle('요약 통계');
    
    // 기본 정보
    $summarySheet->setCellValue('A1', 'PCI Korea 홀인원보험 관리시스템');
    $summarySheet->setCellValue('A2', '데이터 분석 요약');
    $summarySheet->setCellValue('A3', '생성일시: ' . date('Y-m-d H:i:s'));
    $summarySheet->setCellValue('A4', '클라이언트 ID: ' . $clientId);
    
    // 타입별 요약 정보
    switch ($type) {
        case 'past_tee_time':
            addPastTeeTimeSummary($summarySheet, $pdo, $clientId);
            break;
        case 'golf_course_stats':
            addGolfCourseSummary($summarySheet, $pdo, $clientId);
            break;
        case 'full_used_coupons':
            addCouponSummary($summarySheet, $pdo, $clientId);
            break;
    }
    
    // 헤더 스타일 적용
    $summarySheet->getStyle('A1:A4')->getFont()->setBold(true);
    $summarySheet->getColumnDimension('A')->setAutoSize(true);
    $summarySheet->getColumnDimension('B')->setAutoSize(true);
}

function addPastTeeTimeSummary($sheet, $pdo, $clientId) {
    $now = date('Y-m-d H:i:s');
    
    // 통계 계산
    $pastQuery = "SELECT COUNT(*) FROM holeinone_applications WHERE client_id = ? AND tee_time < ?";
    $pastStmt = $pdo->prepare($pastQuery);
    $pastStmt->execute([$clientId, $now]);
    $pastCount = $pastStmt->fetchColumn();
    
    $futureQuery = "SELECT COUNT(*) FROM holeinone_applications WHERE client_id = ? AND tee_time >= ?";
    $futureStmt = $pdo->prepare($futureQuery);
    $futureStmt->execute([$clientId, $now]);
    $futureCount = $futureStmt->fetchColumn();
    
    $totalCount = $pastCount + $futureCount;
    $completionRate = $totalCount > 0 ? round(($pastCount / $totalCount) * 100, 1) : 0;
    
    $sheet->setCellValue('A6', '=== 지난 티업시간 분석 ===');
    $sheet->setCellValue('A7', '완료된 경기:');
    $sheet->setCellValue('B7', $pastCount . '건');
    $sheet->setCellValue('A8', '예정된 경기:');
    $sheet->setCellValue('B8', $futureCount . '건');
    $sheet->setCellValue('A9', '완료율:');
    $sheet->setCellValue('B9', $completionRate . '%');
}

function addGolfCourseSummary($sheet, $pdo, $clientId) {
    $totalQuery = "SELECT COUNT(*) FROM holeinone_applications WHERE client_id = ?";
    $totalStmt = $pdo->prepare($totalQuery);
    $totalStmt->execute([$clientId]);
    $totalApplications = $totalStmt->fetchColumn();
    
    $coursesQuery = "SELECT COUNT(DISTINCT golf_course) FROM holeinone_applications WHERE client_id = ?";
    $coursesStmt = $pdo->prepare($coursesQuery);
    $coursesStmt->execute([$clientId]);
    $totalCourses = $coursesStmt->fetchColumn();
    
    $avgPerCourse = $totalCourses > 0 ? round($totalApplications / $totalCourses, 1) : 0;
    
    $sheet->setCellValue('A6', '=== 골프장별 통계 ===');
    $sheet->setCellValue('A7', '총 골프장 수:');
    $sheet->setCellValue('B7', $totalCourses . '곳');
    $sheet->setCellValue('A8', '총 신청 건수:');
    $sheet->setCellValue('B8', $totalApplications . '건');
    $sheet->setCellValue('A9', '골프장당 평균:');
    $sheet->setCellValue('B9', $avgPerCourse . '건');
}

function addCouponSummary($sheet, $pdo, $clientId) {
    // 총 쿠폰 수
    $totalCouponsQuery = "SELECT COUNT(DISTINCT coupon_number) FROM holeinone_applications WHERE client_id = ?";
    $totalCouponsStmt = $pdo->prepare($totalCouponsQuery);
    $totalCouponsStmt->execute([$clientId]);
    $totalCoupons = $totalCouponsStmt->fetchColumn();
    
    // 2회 사용 쿠폰 수
    $fullUsedQuery = "
        SELECT COUNT(*) 
        FROM (
            SELECT coupon_number 
            FROM holeinone_applications 
            WHERE client_id = ? 
            GROUP BY coupon_number 
            HAVING COUNT(*) = 2
        ) as full_used_coupons
    ";
    $fullUsedStmt = $pdo->prepare($fullUsedQuery);
    $fullUsedStmt->execute([$clientId]);
    $fullUsedCount = $fullUsedStmt->fetchColumn();
    
    $fullUsedRate = $totalCoupons > 0 ? round(($fullUsedCount / $totalCoupons) * 100, 1) : 0;
    
    $sheet->setCellValue('A6', '=== 2회 사용 쿠폰 분석 ===');
    $sheet->setCellValue('A7', '총 쿠폰 수:');
    $sheet->setCellValue('B7', $totalCoupons . '개');
    $sheet->setCellValue('A8', '2회 사용 완료:');
    $sheet->setCellValue('B8', $fullUsedCount . '개');
    $sheet->setCellValue('A9', '완전 사용률:');
    $sheet->setCellValue('B9', $fullUsedRate . '%');
}

/**
 * 암호화된 데이터 복호화 함수
 * analysis_data.php와 동일한 방식 사용
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

/**
 * Excel 파일 출력
 */
function outputExcelFile($spreadsheet, $filename) {
    // 출력 헤더 설정
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>