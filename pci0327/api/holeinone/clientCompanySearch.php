<?php
/**
 * 고객사 정보 검색 처리 스크립트
 * 
 * POST 요청으로 전송된, ID에 따라 고객사 정보를 조회합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/clientCompanySearch.php
 */
// 세션 시작 (로그인 확인 등을 위해)
session_start();
// 필요한 파일 포함
require_once '../config/db_config.php';
// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');
// POST 요청 검증
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response = array();
    $response['success'] = false;
    $response['message'] = '잘못된 요청 방식입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
// ID 파라미터 가져오기
$id = isset($_POST['id']) ? $_POST['id'] : '';
// ID가 비어있는지 확인
if (empty($id)) {
    http_response_code(400);
    $response = array();
    $response['success'] = false;
    $response['message'] = 'ID가 제공되지 않았습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
try {
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    
    // 쿼리 작성 - 고객사 정보 테이블에서 특정 ID의 데이터 조회
    $sql = "SELECT * FROM clients WHERE id = :id";
    $params = array(':id' => $id);
    
    // 쿼리 실행
    $stmt = $pdo->prepare($sql);
    
    // 파라미터 바인딩
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    
    // 결과 가져오기
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 결과가 없는 경우
    if (!$result) {
        http_response_code(404);
        $response = array();
        $response['success'] = false;
        $response['message'] = '해당 ID의 고객사 정보를 찾을 수 없습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 성공 응답 반환
    $response = array();
    $response['success'] = true;
    $response['message'] = '고객사 정보가 성공적으로 조회되었습니다.';
    $response['data'] = $result;
    
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