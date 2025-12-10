<?php
/**
 * 홀인원 보상신청서 PDF 출력 (수정된 버전)
 * 파일: api/export_claim_pdf.php
 */

session_start();

// 디버깅을 위한 오류 표시
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/html; charset=utf-8");

// 필요한 파일 include
require_once '../../../api/config/db_config.php';
require_once "../../../kj/api/kjDaeri/php/encryption.php";

// 로그인 체크
if (!isset($_SESSION['client_id'])) {
    http_response_code(401);
    echo '<h1>접근 권한이 없습니다.</h1><p>로그인이 필요합니다.</p>';
    exit;
}

$claim_id = $_GET['id'] ?? null;

if (!$claim_id) {
    http_response_code(400);
    echo '<h1>잘못된 요청</h1><p>신청서 ID가 필요합니다.</p>';
    exit;
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    $client_id = $_SESSION['client_id'];
    
    // 홀인원 보상신청 정보 조회 (claims 테이블과 applications 테이블 JOIN)
    // 임시로 client_id 조건 제거 (테스트용)
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.application_id,
            c.client_id,
            c.coupon_number,
            c.claim_number,
            c.customer_name as applicant_name,
            c.customer_phone as applicant_phone,
            c.golf_course,
            c.play_date,
            c.hole_number,
            c.yardage,
            c.club_used,
            c.witness_name,
            c.witness_phone,
            c.bank_name,
            c.account_number,
            c.account_holder,
            c.additional_notes,
            c.terms_agreed,
            c.claim_hash as unique_hash,
            c.status,
            c.approved_amount,
            c.rejection_reason,
            c.payment_date,
            c.created_at,
            c.updated_at,
            a.tee_time
        FROM holeinone_claims c
        LEFT JOIN holeinone_applications a ON c.application_id = a.id
        WHERE c.id = ?
    ");
    
    $stmt->execute([$claim_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 디버깅 정보 출력 (임시)
    if (!$application) {
        echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #ddd; margin: 20px;'>";
        echo "<h3>디버깅 정보</h3>";
        echo "claim_id: " . htmlspecialchars($claim_id) . "<br>";
        echo "client_id: " . htmlspecialchars($client_id) . "<br>";
        
        // 해당 ID가 존재하는지 확인
        $debug_stmt = $pdo->prepare("SELECT id, client_id, status FROM holeinone_claims WHERE id = ?");
        $debug_stmt->execute([$claim_id]);
        $debug_result = $debug_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($debug_result) {
            echo "레코드 존재: ID=" . $debug_result['id'] . ", client_id=" . $debug_result['client_id'] . ", status=" . $debug_result['status'] . "<br>";
            if ($debug_result['client_id'] != $client_id) {
                echo "<strong style='color: red;'>client_id 불일치: DB=" . $debug_result['client_id'] . " vs 세션=" . $client_id . "</strong><br>";
            }
        } else {
            echo "<strong style='color: red;'>해당 ID의 레코드 없음</strong><br>";
        }
        echo "</div>";
    }
    
    if (!$application) {
        http_response_code(404);
        echo '<h1>신청서를 찾을 수 없습니다.</h1><p>해당 신청서가 존재하지 않습니다.</p>';
        exit;
    }
    
    // 개인정보 복호화
    try {
        $decrypted_name = decryptData($application['applicant_name']);
        $decrypted_phone = decryptData($application['applicant_phone']);
        $decrypted_witness_name = decryptData($application['witness_name']);
        $decrypted_witness_phone = decryptData($application['witness_phone']);
        $decrypted_account_number = decryptData($application['account_number']);
        $decrypted_account_holder = decryptData($application['account_holder']);
        
        // 복호화 실패 시 기본값 설정
        if ($decrypted_name === false || $decrypted_name === null) {
            $decrypted_name = '복호화 실패';
        }
        
        if ($decrypted_phone === false || $decrypted_phone === null) {
            $decrypted_phone = '복호화 실패';
        } else {
            // 전화번호 포맷팅
            $decrypted_phone = formatPhoneNumber($decrypted_phone);
        }
        
        if ($decrypted_witness_name === false || $decrypted_witness_name === null) {
            $decrypted_witness_name = '복호화 실패';
        }
        
        if ($decrypted_witness_phone === false || $decrypted_witness_phone === null) {
            $decrypted_witness_phone = '복호화 실패';
        } else {
            $decrypted_witness_phone = formatPhoneNumber($decrypted_witness_phone);
        }
        
        if ($decrypted_account_number === false || $decrypted_account_number === null) {
            $decrypted_account_number = '복호화 실패';
        }
        
        if ($decrypted_account_holder === false || $decrypted_account_holder === null) {
            $decrypted_account_holder = '복호화 실패';
        }
        
    } catch (Exception $e) {
        $decrypted_name = '복호화 오류';
        $decrypted_phone = '복호화 오류';
        $decrypted_witness_name = '복호화 오류';
        $decrypted_witness_phone = '복호화 오류';
        $decrypted_account_number = '복호화 오류';
        $decrypted_account_holder = '복호화 오류';
        error_log("Decryption error for claim ID {$claim_id}: " . $e->getMessage());
    }
    
    // 처리된 데이터로 배열 업데이트
    $application['applicant_name'] = $decrypted_name;
    $application['applicant_phone'] = $decrypted_phone;
    $application['witness_name'] = $decrypted_witness_name;
    $application['witness_phone'] = $decrypted_witness_phone;
    $application['account_number'] = $decrypted_account_number;
    $application['account_holder'] = $decrypted_account_holder;
    $application['signup_id'] = $application['claim_number']; // 보상신청번호 사용
    
} catch (Exception $e) {
    http_response_code(500);
    error_log("Export PDF error: " . $e->getMessage());
    echo '<h1>서버 오류</h1><p>데이터를 불러올 수 없습니다.</p>';
    exit;
}

// PDF용 HTML 생성 및 출력
echo generateClaimPDFHTML($application);

/**
 * 전화번호 포맷팅 함수
 */
function formatPhoneNumber($phone) {
    if (!$phone || $phone === '복호화 실패' || $phone === '복호화 오류') {
        return $phone;
    }
    
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

/**
 * 계좌번호 마스킹 함수
 */
function maskAccountNumber($accountNumber) {
    if (!$accountNumber || $accountNumber === '복호화 실패' || $accountNumber === '복호화 오류') {
        return $accountNumber;
    }
    
    $length = strlen($accountNumber);
    if ($length <= 4) {
        return $accountNumber;
    }
    
    // 앞 2자리와 뒤 2자리만 표시
    return substr($accountNumber, 0, 2) . str_repeat('*', $length - 4) . substr($accountNumber, -2);
}

/**
 * PDF용 HTML 생성 함수 (홀인원 보상신청서용)
 */
function generateClaimPDFHTML($application) {
    $statusTexts = [
        'pending' => '검토중',
        'reviewing' => '심사중',
        'approved' => '승인완료',
        'rejected' => '거부됨',
        'completed' => '지급완료'
    ];
    
    $statusText = $statusTexts[$application['status']] ?? $application['status'];
    
    // 경기 날짜와 현재 시간 비교
    $playDate = strtotime($application['play_date']);
    $currentTime = time();
    
    return '
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>홀인원 보상신청서 - ' . htmlspecialchars($application['claim_number']) . '</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; font-size: 10pt; -webkit-print-color-adjust: exact; color-adjust: exact; }
            .page-break { page-break-before: always; }
            .info-section { page-break-inside: avoid; }
            .print-button, .close-button { display: none !important; }
        }
        
        body {
            font-family: "Malgun Gothic", "맑은 고딕", Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .header .claim-number {
            background: #dc3545;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .info-section {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .section-title {
            background: #f8f9fa;
            padding: 12px 20px;
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
            border-bottom: 1px solid #ddd;
        }
        
        .section-content {
            padding: 20px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            width: 140px;
        }
        
        .info-table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-reviewing { background: #cff4fc; color: #055160; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        
        .terms-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #dc3545;
        }
        
        .terms-section h4 {
            color: #dc3545;
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .print-info {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .print-button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin: 20px 5px;
        }
        
        .print-button:hover {
            background: #c82333;
        }
        
        .close-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin: 20px 5px;
        }
        
        .close-button:hover {
            background: #5a6268;
        }
        
        .highlight {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .amount-highlight {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
    <script>
        window.onload = function() {
            // 사용자가 수동으로 인쇄할 수 있도록 자동 인쇄 제거
            console.log('PDF 페이지 로드 완료');
        }
        
        function safePrint() {
            try {
                window.print();
            } catch(e) {
                console.error('인쇄 오류:', e);
                alert('인쇄 중 오류가 발생했습니다. 브라우저의 인쇄 기능을 직접 사용해주세요.');
            }
        }
    </script>
</head>
<body>
    <div class="no-print">
        <button class="print-button" onclick="safePrint()">
            🖨️ 인쇄하기
        </button>
        <button class="close-button" onclick="window.close()">
            ❌ 창 닫기
        </button>
    </div>
    
    <div class="header">
        <h1>🏆 홀인원 보상신청서</h1>
        <div class="claim-number">신청번호: ' . htmlspecialchars($application['claim_number']) . '</div>
        <div style="margin-top: 10px;">
            <span class="status-badge status-' . $application['status'] . '">' . $statusText . '</span>
        </div>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">👤 신청자 정보</h3>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <th>성명</th>
                    <td class="highlight">' . htmlspecialchars($application['applicant_name']) . '</td>
                    <th>연락처</th>
                    <td class="highlight">' . htmlspecialchars($application['applicant_phone']) . '</td>
                </tr>
                <tr>
                    <th>신청일시</th>
                    <td>' . date('Y년 m월 d일 H시 i분', strtotime($application['created_at'])) . '</td>
                    <th>쿠폰번호</th>
                    <td><strong>' . htmlspecialchars($application['coupon_number']) . '</strong></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">⛳ 홀인원 달성 정보</h3>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <th>골프장명</th>
                    <td class="highlight">' . htmlspecialchars($application['golf_course']) . '</td>
                    <th>경기날짜</th>
                    <td><strong>' . date('Y년 m월 d일', strtotime($application['play_date'])) . '</strong></td>
                </tr>
                <tr>
                    <th>홀 번호</th>
                    <td><strong>' . htmlspecialchars($application['hole_number']) . '번 홀</strong></td>
                    <th>거리</th>
                    <td>' . htmlspecialchars($application['yardage'] ?: '-') . '</td>
                </tr>
                <tr>
                    <th>사용클럽</th>
                    <td>' . htmlspecialchars($application['club_used'] ?: '-') . '</td>
                    <th>티오프시간</th>
                    <td>' . ($application['tee_time'] ? date('Y년 m월 d일 H시 i분', strtotime($application['tee_time'])) : '-') . '</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">👥 목격자 정보</h3>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <th>목격자 성명</th>
                    <td class="highlight">' . htmlspecialchars($application['witness_name']) . '</td>
                    <th>목격자 연락처</th>
                    <td class="highlight">' . htmlspecialchars($application['witness_phone']) . '</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="info-section">
        <h3 class="section-title">💰 보상금 지급 정보</h3>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <th>은행명</th>
                    <td>' . htmlspecialchars($application['bank_name']) . '</td>
                    <th>예금주</th>
                    <td>' . htmlspecialchars($application['account_holder']) . '</td>
                </tr>
                <tr>
                    <th>계좌번호</th>
                    <td>' . maskAccountNumber($application['account_number']) . '</td>
                    <th>승인금액</th>
                    <td>' . ($application['approved_amount'] ? '<span class="amount-highlight">' . number_format($application['approved_amount']) . '원</span>' : '-') . '</td>
                </tr>
                ' . ($application['payment_date'] ? '<tr><th>지급일시</th><td colspan="3"><strong>' . date('Y년 m월 d일 H시 i분', strtotime($application['payment_date'])) . '</strong></td></tr>' : '') . '
            </table>
        </div>
    </div>
    
    ' . ($application['additional_notes'] ? '<div class="info-section">
        <h3 class="section-title">📝 추가 특이사항</h3>
        <div class="section-content">
            <p>' . nl2br(htmlspecialchars($application['additional_notes'])) . '</p>
        </div>
    </div>' : '') . '
    
    ' . ($application['rejection_reason'] ? '<div class="info-section">
        <h3 class="section-title">❌ 거절 사유</h3>
        <div class="section-content">
            <p style="color: #721c24; font-weight: bold;">' . nl2br(htmlspecialchars($application['rejection_reason'])) . '</p>
        </div>
    </div>' : '') . '
    
    <div class="info-section">
        <h3 class="section-title">🔐 보안 정보</h3>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <th>고유 해시</th>
                    <td><code style="font-size: 10px;">' . htmlspecialchars(substr($application['unique_hash'], 0, 32)) . '...</code></td>
                    <th>데이터 처리</th>
                    <td>암호화 저장됨</td>
                </tr>
                <tr>
                    <th>가입신청 ID</th>
                    <td>' . htmlspecialchars($application['application_id']) . '</td>
                    <th>약관동의</th>
                    <td>' . ($application['terms_agreed'] ? '✅ 동의함' : '❌ 미동의') . '</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="terms-section">
        <h4>📋 홀인원 보상 약관 (요약)</h4>
        <p><strong>보상 조건:</strong> 정당한 홀인원 달성 시 보상금 지급</p>
        <p><strong>증명 자료:</strong> 현장 사진, 목격자 확인, 골프장 증명서 등</p>
        <p><strong>지급 방식:</strong> 승인 후 지정 계좌로 입금</p>
        <p><strong>신청자 동의:</strong> ' . ($application['terms_agreed'] ? '✅ 약관에 동의하였음' : '❌ 약관 동의 미완료') . '</p>
    </div>
    
    <div class="print-info">
        <p><strong>출력 정보</strong></p>
        <p>출력일시: ' . date('Y년 m월 d일 H시 i분') . ' | 출력자: PCI Korea 관리시스템</p>
        <p>본 문서는 홀인원 보상신청서의 공식 출력본입니다.</p>
        <p style="font-size: 10px; color: #999; margin-top: 10px;">
            개인정보 보호를 위해 암호화된 데이터를 복호화하여 표시하였습니다.<br>
            계좌번호는 보안을 위해 일부 마스킹 처리되었습니다.<br>
            이 문서는 관리 목적으로만 사용되어야 하며, 개인정보 보호 정책에 따라 관리되어야 합니다.
        </p>
    </div>
    
    <div class="no-print">
        <hr style="margin: 30px 0;">
        <button class="print-button" onclick="safePrint()">
            🖨️ 다시 인쇄
        </button>
        <button class="close-button" onclick="window.close()">
            ❌ 창 닫기
        </button>
    </div>
</body>
</html>';
}
?>