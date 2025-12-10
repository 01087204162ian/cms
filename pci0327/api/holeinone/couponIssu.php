<?php
/**
 * 예치금 합계 조회 처리 스크립트
 * 
 * POST 요청으로 전송된 cert_id에 따라 예치금 합계를 조회합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/couponSearch.php
 */

// 오류 표시 설정 (개발용, 프로덕션에서는 제거하세요)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 세션 시작 (로그인 확인 등을 위해)
session_start();

// 필요한 파일 포함
require_once '../config/db_config.php';

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 로그 함수 정의
function logDebug($message) {
    error_log("[DEBUG] " . date('Y-m-d H:i:s') . " - " . $message);
}

// 요청 정보 로깅
logDebug("요청 메서드: " . $_SERVER['REQUEST_METHOD']);
logDebug("요청 매개변수: " . print_r($_POST, true));

// POST 요청 검증
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response = array();
    $response['success'] = false;
    $response['message'] = '잘못된 요청 방식입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 입력 파라미터 가져오기
$cert_id = isset($_POST['cert_id']) ? trim($_POST['cert_id']) : '';

try {
    logDebug("DB 연결 시도...");
    
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    logDebug("DB 연결 성공");
    
    // 파라미터 배열 준비
    $params = array();
    
    if (!empty($cert_id)) {
        // 특정 cert_id의 예치금 합계 조회
        $sql = "SELECT 
                    cert_id,
                    SUM(deposit_amount) as total_deposit_amount,
                    COUNT(*) as deposit_count
                FROM depositsAce 
                WHERE cert_id = :cert_id
                GROUP BY cert_id";
        $params[':cert_id'] = $cert_id;
        
        logDebug("특정 cert_id({$cert_id}) 합계 조회");
        
    } else {
        // 모든 cert_id별 예치금 합계 조회
        $sql = "SELECT 
                    cert_id,
                    SUM(deposit_amount) as total_deposit_amount,
                    COUNT(*) as deposit_count
                FROM depositsAce 
                GROUP BY cert_id
                ORDER BY total_deposit_amount DESC";
        
        logDebug("전체 cert_id별 합계 조회");
    }
    
    // 쿼리 로깅
    logDebug("SQL 쿼리: " . $sql);
    logDebug("파라미터: " . print_r($params, true));
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    logDebug("쿼리 실행 완료");
    
    // 결과 가져오기
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    logDebug("조회된 결과 수: " . count($results));
    
    // 응답 데이터 구성
    $response = array();
    $response['success'] = true;
    $response['timestamp'] = date('Y-m-d H:i:s');
    
    if (!empty($cert_id)) {
        if (count($results) > 0) {
            $response['message'] = "cert_id '{$cert_id}'의 예치금 합계가 성공적으로 조회되었습니다.";
            $response['data'] = $results[0]; // 단일 결과
        } else {
            $response['message'] = "cert_id '{$cert_id}'에 대한 예치금 정보가 없습니다.";
            $response['data'] = array(
                'cert_id' => $cert_id,
                'total_deposit_amount' => 0,
                'deposit_count' => 0
            );
        }
    } else {
        $response['message'] = '모든 cert_id별 예치금 합계가 성공적으로 조회되었습니다.';
        $response['data'] = $results; // 전체 결과 배열
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    logDebug('Database Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    logDebug('Stack trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '데이터베이스 오류가 발생했습니다.';
    $response['timestamp'] = date('Y-m-d H:i:s');
    
    // 개발용 오류 정보 (프로덕션에서는 제거)
    if (ini_get('display_errors')) {
        $response['error_details'] = $e->getMessage();
        $response['error_code'] = $e->getCode();
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 기타 오류
    logDebug('Error: ' . $e->getMessage());
    logDebug('Stack trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '오류가 발생했습니다. 다시 시도해 주세요.';
    $response['timestamp'] = date('Y-m-d H:i:s');
    
    // 개발용 오류 정보 (프로덕션에서는 제거)
    if (ini_get('display_errors')) {
        $response['error_details'] = $e->getMessage();
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} finally {
    logDebug("요청 처리 완료");
}
?>