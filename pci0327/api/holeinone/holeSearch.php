<?php
/**
 * 홀인원 정보 검색 처리 스크립트
 * 
 * GET 요청으로 전송된 검색 조건에 따라 홀인원 정보를 조회합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/holeSearch.php
 */
// 세션 시작 (로그인 확인 등을 위해)
session_start();
// 필요한 파일 포함
require_once '../config/db_config.php';
// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// GET 요청 검증
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    $response = array();
    $response['success'] = false;
    $response['message'] = '잘못된 요청 방식입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 검색 파라미터 가져오기
$sj = isset($_GET['sj']) ? $_GET['sj'] : '';
$fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : '';
$toDate = isset($_GET['toDate']) ? $_GET['toDate'] : '';

try {
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    
    // 기본 쿼리 작성
    $sql = "SELECT * FROM holeinone WHERE 1=1";
    $params = array();
    
    // 검색 조건 추가
    if (!empty($sj)) {
        $sql .= " AND title LIKE :sj";
        $params[':sj'] = "%{$sj}%";
    }
    
    // 날짜 조건 추가
    if (!empty($fromDate)) {
        $sql .= " AND date >= :fromDate";
        $params[':fromDate'] = $fromDate;
    }
    
    if (!empty($toDate)) {
        $sql .= " AND date <= :toDate";
        $params[':toDate'] = $toDate;
    }
    
    // 날짜 순으로 정렬
    $sql .= " ORDER BY date DESC";
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    
    // 결과 가져오기
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 성공 응답 반환
    $response = array();
    $response['success'] = true;
    $response['message'] = '검색 결과가 성공적으로
    조회되었습니다.';
    $response['data'] = $results;
    $response['total'] = count($results);
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    error_log('Database Error: ' . $e->getMessage());
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '데이터베이스 오류가 발생했습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // 기타 오류
    error_log('Error: ' . $e->getMessage());
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '오류가 발생했습니다. 다시 시도해 주세요.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>