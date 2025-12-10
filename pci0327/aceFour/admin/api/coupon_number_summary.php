<?php
/**
 * 쿠폰 데이터 정리/복구 API (coupon_number_summary.php)
 * 
 * [주요 기능]
 * 1. cleanup: 의심스러운 홀인원 보험 신청 취소 처리
 * 2. restore: 취소된 신청을 다시 활성화 처리 (새로 추가)
 */

session_start();
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 응답 함수
function sendResponse($success, $message, $data = null, $errorCode = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'errorCode' => $errorCode,
        'refresh' => $success // 성공시 화면 새로고침 요청
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

// JSON 입력 데이터 파싱
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    sendResponse(false, '잘못된 JSON 데이터입니다.', null, 'INVALID_JSON');
}

// 파라미터 검증
$id = isset($input['id']) ? trim($input['id']) : '';
$coupon_number = isset($input['coupon_number']) ? trim($input['coupon_number']) : '';
$action = isset($input['action']) ? trim($input['action']) : '';

if (empty($id) || !is_numeric($id)) {
    sendResponse(false, '유효하지 않은 신청 ID입니다.', null, 'INVALID_ID');
}

if (empty($coupon_number)) {
    sendResponse(false, '쿠폰번호가 필요합니다.', null, 'MISSING_COUPON_NUMBER');
}

// 액션 검증 수정 (cleanup과 restore 둘 다 허용)
if (!in_array($action, ['cleanup', 'restore'])) {
    sendResponse(false, '유효하지 않은 액션입니다. (cleanup 또는 restore)', null, 'INVALID_ACTION');
}

try {
    // 데이터베이스 연결
    $pdo = getDbConnection();
    $client_id = $_SESSION['client_id'];
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    // 1. 신청 정보 확인
    $stmt_check = $pdo->prepare("
        SELECT id, coupon_number, applicant_name, summary 
        FROM holeinone_applications 
        WHERE id = ? AND client_id = ?
    ");
    $stmt_check->execute([$id, $client_id]);
    $application = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$application) {
        $pdo->rollBack();
        sendResponse(false, '해당 신청을 찾을 수 없습니다.', null, 'APPLICATION_NOT_FOUND');
    }
    
    // 쿠폰번호 일치 확인
    if ($application['coupon_number'] !== $coupon_number) {
        $pdo->rollBack();
        sendResponse(false, '신청 ID와 쿠폰번호가 일치하지 않습니다.', null, 'COUPON_MISMATCH');
    }
    
    // 액션별 상태 검증
    if ($action === 'cleanup') {
        // 이미 취소된 신청인지 확인
        if ($application['summary'] === '2') {
            $pdo->rollBack();
            sendResponse(false, '이미 취소된 신청입니다.', null, 'ALREADY_CANCELLED');
        }
    } elseif ($action === 'restore') {
        // 취소된 신청인지 확인
        if ($application['summary'] !== '2') {
            $pdo->rollBack();
            sendResponse(false, '취소된 신청만 복구할 수 있습니다.', null, 'NOT_CANCELLED');
        }
    }
    
    // 로그인 사용자 정보 가져오기
    $admin_name = $_SESSION['admin_name'] ?? $_SESSION['username'] ?? 'admin';
    
    // 2. 액션별 신청 상태 업데이트
    if ($action === 'cleanup') {
        // cleanup: summary = '2' (취소)
        $stmt_update_app = $pdo->prepare("
            UPDATE holeinone_applications 
            SET summary = '2', summary_time = NOW(), summary_damdanga = ?, updated_at = NOW() 
            WHERE id = ? AND client_id = ?
        ");
        $result_app = $stmt_update_app->execute([$admin_name, $id, $client_id]);
        $new_summary = '2';
        
    } elseif ($action === 'restore') {
        // restore: summary = '1' (정상) 또는 '0' (기본값)으로 복구
        $stmt_update_app = $pdo->prepare("
            UPDATE holeinone_applications 
            SET summary = '1', summary_time = NOW(), summary_damdanga = ?, updated_at = NOW() 
            WHERE id = ? AND client_id = ?
        ");
        $result_app = $stmt_update_app->execute([$admin_name, $id, $client_id]);
        $new_summary = '1';
    }
    
    if (!$result_app) {
        $pdo->rollBack();
        $action_text = $action === 'cleanup' ? '취소' : '복구';
        sendResponse(false, "신청 {$action_text} 처리에 실패했습니다.", null, 'UPDATE_APPLICATION_FAILED');
    }
    
    // 3. 쿠폰 정보 확인
    $stmt_coupon = $pdo->prepare("
        SELECT id, used_count, is_used 
        FROM holeinone_coupons 
        WHERE coupon_number = ? AND client_id = ?
    ");
    $stmt_coupon->execute([$coupon_number, $client_id]);
    $coupon = $stmt_coupon->fetch(PDO::FETCH_ASSOC);
    
    if (!$coupon) {
        $pdo->rollBack();
        sendResponse(false, '해당 쿠폰을 찾을 수 없습니다.', null, 'COUPON_NOT_FOUND');
    }
    
    $current_used_count = (int)$coupon['used_count'];
    $new_used_count = $current_used_count;
    $new_is_used = $coupon['is_used'];
    
    // 4. 액션별 쿠폰 사용횟수 처리
    if ($action === 'cleanup') {
        // cleanup: used_count 감소
        if ($current_used_count > 0) {
            $new_used_count = $current_used_count - 1;
            
            // used_count가 0이 되면 is_used도 0으로 변경
            if ($new_used_count === 0) {
                $new_is_used = 0;
            }
        }
        
    } elseif ($action === 'restore') {
        // restore: used_count 증가
        $new_used_count = $current_used_count + 1;
        
        // used_count가 1 이상이면 is_used = 1로 변경
        if ($new_used_count > 0) {
            $new_is_used = 1;
        }
    }
    
    // 5. 쿠폰 테이블 업데이트 (변경사항이 있을 때만)
    if ($new_used_count !== $current_used_count || $new_is_used != $coupon['is_used']) {
        $stmt_update_coupon = $pdo->prepare("
            UPDATE holeinone_coupons 
            SET used_count = ?, is_used = ?
            WHERE coupon_number = ? AND client_id = ?
        ");
        $result_coupon = $stmt_update_coupon->execute([$new_used_count, $new_is_used, $coupon_number, $client_id]);
        
        if (!$result_coupon) {
            $pdo->rollBack();
            sendResponse(false, '쿠폰 사용횟수 업데이트에 실패했습니다.', null, 'UPDATE_COUPON_FAILED');
        }
    }
    
    // 6. 작업 로그 기록
    try {
        $stmt_log = $pdo->prepare("
            INSERT INTO admin_action_logs (client_id, admin_id, action_type, target_type, target_id, description, created_at)
            VALUES (?, ?, ?, 'application', ?, ?, NOW())
        ");
        
        $admin_id = $_SESSION['admin_id'] ?? 'system';
        $action_text = $action === 'cleanup' ? '취소' : '복구';
        $description = "신청 ID {$id} {$action_text} 처리, 쿠폰 {$coupon_number} 사용횟수 {$current_used_count}→{$new_used_count}";
        
        $stmt_log->execute([$client_id, $admin_id, $action, $id, $description]);
    } catch (Exception $e) {
        // 로그 기록 실패는 전체 트랜잭션을 롤백하지 않음
        error_log("Action log insert failed: " . $e->getMessage());
    }
    
    // 트랜잭션 커밋
    $pdo->commit();
    
    // 성공 응답
    $response_data = [
        'application_id' => $id,
        'coupon_number' => $coupon_number,
        'action' => $action,
        'previous_used_count' => $current_used_count,
        'new_used_count' => $new_used_count,
        'is_used' => $new_is_used,
        'summary_status' => $new_summary,
        'processed_at' => date('Y-m-d H:i:s')
    ];
    
    if ($action === 'cleanup') {
        $message = "데이터 정리가 완료되었습니다. 쿠폰 사용횟수: {$current_used_count} → {$new_used_count}";
    } else {
        $message = "데이터 복구가 완료되었습니다. 쿠폰 사용횟수: {$current_used_count} → {$new_used_count}";
    }
    
    sendResponse(true, $message, $response_data);
    
} catch (Exception $e) {
    // 롤백
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // 에러 로그 기록
    error_log("Coupon {$action} error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    $action_text = $action === 'cleanup' ? '정리' : '복구';
    sendResponse(false, "데이터 {$action_text} 중 오류가 발생했습니다: " . $e->getMessage(), [
        'error_file' => $e->getFile(),
        'error_line' => $e->getLine()
    ], 'SERVER_ERROR');
}
?>