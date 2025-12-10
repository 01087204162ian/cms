<?php
/**
 * 예치금 정보 등록/수정 처리 스크립트
 * 
 * POST 요청으로 전송된 예치금 정보를 데이터베이스에 저장합니다.
 * deposit_id 값이 있으면 업데이트, 없으면 새로 등록합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/depositSetting.php
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
$client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : '';
$cert_number = isset($_POST['cert_number']) ? $_POST['cert_number'] : '';
$deposit_amount = isset($_POST['deposit']) ? $_POST['deposit'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$manager_name = isset($_POST['userName']) ? $_POST['userName'] : '';
$deposit_id = isset($_POST['deposit_id']) ? (int)$_POST['deposit_id'] : '';

// 필수 입력값 검증
if (empty($client_id) || empty($cert_number) || empty($deposit_amount) || empty($manager_name)) {
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

    // 인증서 ID 가져오기
    $certSql = "SELECT cert_id FROM certificate WHERE cert_number = :cert_number LIMIT 1";
    $certStmt = $pdo->prepare($certSql);
    $certStmt->bindValue(':cert_number', $cert_number, PDO::PARAM_STR);
    $certStmt->execute();
    $cert_id = $certStmt->fetchColumn();

    if (!$cert_id) {
        $response = array();
        $response['success'] = false;
        $response['message'] = '해당 인증서 번호를 찾을 수 없습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // deposit_id 값이 있으면 업데이트, 없으면 새로 등록
    if (!empty($deposit_id)) {
        // 레코드 존재 여부 확인
        $checkSql = "SELECT COUNT(*) FROM depositsAce WHERE deposit_id = :deposit_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindValue(':deposit_id', $deposit_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() == 0) {
            $response = array();
            $response['success'] = false;
            $response['message'] = '수정할 예치금 정보를 찾을 수 없습니다.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 업데이트 쿼리 작성
        $sql = "UPDATE depositsAce SET 
                    deposit_amount = :deposit_amount, 
                    description = :description,
                    manager_name = :manager_name,
                    updated_at = CURRENT_TIMESTAMP
                WHERE deposit_id = :deposit_id";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':deposit_amount', $deposit_amount, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':manager_name', $manager_name, PDO::PARAM_STR);
        $stmt->bindValue(':deposit_id', $deposit_id, PDO::PARAM_INT);
        
        // SQL 및 파라미터 로깅
        error_log("SQL Update Query: " . $sql);
        error_log("Update Parameters: " . json_encode([
            'deposit_amount' => $deposit_amount,
            'description' => $description,
            'manager_name' => $manager_name,
            'deposit_id' => $deposit_id
        ], JSON_UNESCAPED_UNICODE));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        // 실행 결과 확인
        if ($result) {
            $response = array();
            $response['success'] = true;
            $response['message'] = '예치금 정보가 성공적으로 수정되었습니다.';
            $response['deposit_id'] = $deposit_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '예치금 정보 수정 중 오류가 발생했습니다.';
            $response['error_info'] = $stmt->errorInfo();
        }
    } else {
        // 새로운 레코드 삽입 쿼리
        $sql = "INSERT INTO depositsAce (
                    client_id, 
                    cert_id, 
                    cert_number, 
                    deposit_amount, 
                    description,
                    manager_name
                ) VALUES (
                    :client_id, 
                    :cert_id, 
                    :cert_number, 
                    :deposit_amount, 
                    :description,
                    :manager_name
                )";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->bindValue(':cert_id', $cert_id, PDO::PARAM_INT);
        $stmt->bindValue(':cert_number', $cert_number, PDO::PARAM_STR);
        $stmt->bindValue(':deposit_amount', $deposit_amount, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':manager_name', $manager_name, PDO::PARAM_STR);
        
        // SQL 및 파라미터 로깅
        error_log("SQL Insert Query: " . $sql);
        error_log("Insert Parameters: " . json_encode([
            'client_id' => $client_id,
            'cert_id' => $cert_id,
            'cert_number' => $cert_number,
            'deposit_amount' => $deposit_amount,
            'description' => $description,
            'manager_name' => $manager_name
        ], JSON_UNESCAPED_UNICODE));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        if ($result) {
            // 삽입된 ID 가져오기
            $new_deposit_id = $pdo->lastInsertId();
            
            // 성공 응답 반환
            $response = array();
            $response['success'] = true;
            $response['message'] = '예치금 정보가 성공적으로 등록되었습니다.';
            $response['deposit_id'] = $new_deposit_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '예치금 정보 등록 중 오류가 발생했습니다.';
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