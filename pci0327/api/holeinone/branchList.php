<?php
/**
 * 지점 정보 검색 처리 스크립트
 * 
 * POST 요청으로 전송된 검색 조건에 따라 지점 정보를 조회합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/branchList.php
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
    error_log("[DEBUG] " . $message);
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
$client_id = isset($_POST['id']) ? $_POST['id'] : '';
try {
    logDebug("DB 연결 시도...");
    
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    logDebug("DB 연결 성공");
    
    // 파라미터 배열 준비
    $params = array();
    
    // 기본 쿼리 작성 - 필요한 모든 필드 선택
    $sql = "SELECT id as branch_id, branchName, branchDamdangName, branchDamdangPhone, branchDamdangEmail, allocated_quota
            FROM branches 
            WHERE 1=1";
            
    // client_id가 제공된 경우에만 조건 추가
    if (!empty($client_id)) {
        $sql .= " AND client_id = :client_id";
        $params[':client_id'] = $client_id;
    }
    
    // 정렬 방식 설정
    $sql .= " ORDER BY id DESC";
    
    // 쿼리 로깅
    logDebug("SQL 쿼리: " . $sql);
    logDebug("파라미터: " . print_r($params, true));
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    
    // 파라미터 바인딩 및 실행
    $stmt->execute($params);
    logDebug("쿼리 실행 완료");
    
    // 결과 가져오기
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    logDebug("조회된 결과 수: " . count($results));
    
    // 페이지네이션 정보 추가
    $response = array();
    $response['success'] = true;
    $response['message'] = '지점 정보가 성공적으로 조회되었습니다.';
    $response['data'] = $results;
    $response['total'] = count($results);
    $response['page'] = 1;
    $response['total_pages'] = ceil(count($results) / 10); // 페이지당 10개 항목으로 가정
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    logDebug('Database Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    logDebug('Stack trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '데이터베이스 오류가 발생했습니다.';
    $response['error_details'] = $e->getMessage(); // 개발용, 프로덕션에서는 제거하세요
    $response['error_code'] = $e->getCode();
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // 기타 오류
    logDebug('Error: ' . $e->getMessage());
    logDebug('Stack trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '오류가 발생했습니다. 다시 시도해 주세요.';
    $response['error_details'] = $e->getMessage(); // 개발용, 프로덕션에서는 제거하세요
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    logDebug("요청 처리 완료");
} 
?>