<?php
/**
 * 담보 정보 등록/수정 처리 스크립트
 * 
 * POST 요청으로 전송된 담보 정보를 데이터베이스에 저장합니다.
 * dambo_id 값이 있으면 업데이트, 없으면 새로 등록합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/damboSetting.php
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

// POST 데이터 가져오기
$cert_number = isset($_POST['cert_number']) ? $_POST['cert_number'] : '';
$collateral = isset($_POST['collateral']) ? $_POST['collateral'] : '';
$coverage_limit = isset($_POST['coverage_limit']) ? $_POST['coverage_limit'] : '';
$deductible = isset($_POST['deductible']) ? $_POST['deductible'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$acePremium = isset($_POST['acePremium']) ? $_POST['acePremium'] : ''; // 프리미엄 필드 추가
$dambo_id = isset($_POST['dambo_id']) ? (int)$_POST['dambo_id'] : '';

// 필수 입력값 검증
if (empty($cert_number) || empty($collateral) || empty($coverage_limit)) {
    http_response_code(400);
    $response = array();
    $response['success'] = false;
    $response['message'] = '필수 입력값이 누락되었습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    
    // dambo_id 값이 있으면 업데이트, 없으면 새로 등록
    if (!empty($dambo_id)) {
        // 레코드 존재 여부 확인
        $checkSql = "SELECT COUNT(*) FROM dambo WHERE id = :dambo_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindValue(':dambo_id', $dambo_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() == 0) {
            $response = array();
            $response['success'] = false;
            $response['message'] = '수정할 담보 정보를 찾을 수 없습니다.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 업데이트 쿼리 작성 (premium 필드 추가)
        $sql = "UPDATE dambo SET 
                    collateral = :collateral, 
                    coverage_limit = :coverage_limit, 
                    deductible = :deductible,
                    description = :description,
                    premium = :premium
                WHERE id = :dambo_id";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':collateral', $collateral, PDO::PARAM_STR);
        $stmt->bindValue(':coverage_limit', $coverage_limit, PDO::PARAM_STR);
        $stmt->bindValue(':deductible', $deductible, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':premium', $acePremium, PDO::PARAM_STR); // 프리미엄 바인딩 추가
        $stmt->bindValue(':dambo_id', $dambo_id, PDO::PARAM_INT);
        
        // SQL 및 파라미터 로깅
        error_log("SQL Update Query: " . $sql);
        error_log("Update Parameters: " . json_encode([
            'collateral' => $collateral,
            'coverage_limit' => $coverage_limit,
            'deductible' => $deductible,
            'description' => $description,
            'premium' => $acePremium, // 로그에 프리미엄 추가
            'dambo_id' => $dambo_id
        ], JSON_UNESCAPED_UNICODE));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        // 실행 결과 확인
        if ($result) {
            $response = array();
            $response['success'] = true;
            $response['message'] = '담보 정보가 성공적으로 수정되었습니다.';
            $response['dambo_id'] = $dambo_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '담보 정보 수정 중 오류가 발생했습니다.';
            $response['error_info'] = $stmt->errorInfo();
        }
    } else {
        // 새로운 레코드 삽입 쿼리 (premium 필드 추가)
        $sql = "INSERT INTO dambo (
                    policy_number, 
                    collateral, 
                    coverage_limit, 
                    deductible, 
                    description,
                    premium
                ) VALUES (
                    :policy_number, 
                    :collateral, 
                    :coverage_limit, 
                    :deductible, 
                    :description,
                    :premium
                )";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':policy_number', $cert_number, PDO::PARAM_STR);
        $stmt->bindValue(':collateral', $collateral, PDO::PARAM_STR);
        $stmt->bindValue(':coverage_limit', $coverage_limit, PDO::PARAM_STR);
        $stmt->bindValue(':deductible', $deductible, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':premium', $acePremium, PDO::PARAM_STR); // 프리미엄 바인딩 추가
        
        // SQL 및 파라미터 로깅
        error_log("SQL Insert Query: " . $sql);
        error_log("Insert Parameters: " . json_encode([
            'policy_number' => $cert_number,
            'collateral' => $collateral,
            'coverage_limit' => $coverage_limit,
            'deductible' => $deductible,
            'description' => $description,
            'premium' => $acePremium // 로그에 프리미엄 추가
        ], JSON_UNESCAPED_UNICODE));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        if ($result) {
            // 삽입된 ID 가져오기
            $new_dambo_id = $pdo->lastInsertId();
            
            // 성공 응답 반환
            $response = array();
            $response['success'] = true;
            $response['message'] = '담보 정보가 성공적으로 등록되었습니다.';
            $response['dambo_id'] = $new_dambo_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '담보 정보 등록 중 오류가 발생했습니다.';
            $response['error_info'] = $stmt->errorInfo();
        }
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 데이터베이스 오류
    error_log('Database Error: ' . $e->getMessage());
    http_response_code(500);
    $response = array();
    $response['success'] = false;
    $response['message'] = '데이터베이스 오류가 발생했습니다: ' . $e->getMessage();
    $response['error_info'] = isset($stmt) ? $stmt->errorInfo() : null;
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