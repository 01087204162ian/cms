<?php
/**
 * 증권 정보 등록/수정 처리 스크립트
 * 
 * POST 요청으로 전송된 증권 정보를 데이터베이스에 저장합니다.
 * cert_id 값이 있으면 업데이트, 없으면 새로 등록합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/hollinone/certi_insert.php
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
$icomSelect = isset($_POST['icomSelect']) ? $_POST['icomSelect'] : '';
$productSelect = isset($_POST['productSelect']) ? $_POST['productSelect'] : ''; // 상품 ID 추가
$hstartDay = isset($_POST['hstartDay']) ? $_POST['hstartDay'] : '';
$insurance_number = isset($_POST['insurance_number']) ? $_POST['insurance_number'] : '';
$client_id = isset($_POST['client_id']) ? $_POST['client_id'] : '';
$cert_id = isset($_POST['cert_id']) ? (int)$_POST['cert_id'] : '';

// 필수 입력값 검증
if (empty($insurance_number) || empty($client_id) || empty($hstartDay) || empty($icomSelect) || empty($productSelect)) {
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
    
    // 현재 시간 가져오기
    $currentDateTime = date('Y-m-d H:i:s');
    
    // cert_id 값이 있으면 업데이트, 없으면 새로 등록
    if (!empty($cert_id)) {
        // 레코드 존재 여부 확인
        $checkSql = "SELECT COUNT(*) FROM certificate WHERE cert_id = :cert_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindValue(':cert_id', $cert_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() == 0) {
            $response = array();
            $response['success'] = false;
            $response['message'] = '수정할 증권 정보를 찾을 수 없습니다.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 업데이트 쿼리 작성 (product_id 필드 추가)
        $sql = "UPDATE certificate SET 
                    cert_number = :cert_number, 
                    issue_date = :issue_date, 
                    company_id = :company_id,
                    product_id = :product_id
                WHERE cert_id = :cert_id";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':cert_number', $insurance_number, PDO::PARAM_STR);
        $stmt->bindValue(':issue_date', $hstartDay, PDO::PARAM_STR);
        $stmt->bindValue(':company_id', $icomSelect, PDO::PARAM_STR);
        $stmt->bindValue(':product_id', $productSelect, PDO::PARAM_INT); // 상품 ID 바인딩 추가
        $stmt->bindValue(':cert_id', $cert_id, PDO::PARAM_INT);
        
        // SQL 및 파라미터 로깅
        error_log("SQL Update Query: " . $sql);
        error_log("Update Parameters: " . json_encode([
            'cert_number' => $insurance_number,
            'issue_date' => $hstartDay,
            'company_id' => $icomSelect,
            'product_id' => $productSelect, // 로깅에 상품 ID 추가
            'cert_id' => $cert_id
        ]));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        // 실행 결과 확인
        if ($result) {
            $response = array();
            $response['success'] = true;
            $response['message'] = '증권 정보가 성공적으로 수정되었습니다.';
            $response['cert_id'] = $cert_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '증권 정보 수정 중 오류가 발생했습니다.';
            $response['error_info'] = $stmt->errorInfo();
        }
    } else {
        // 새로운 레코드 삽입 쿼리 (product_id 필드 추가)
        $sql = "INSERT INTO certificate (
                    client_id, 
                    cert_number, 
                    issue_date, 
                    company_id, 
                    product_id, 
                    created_at
                ) VALUES (
                    :client_id, 
                    :cert_number, 
                    :issue_date, 
                    :company_id, 
                    :product_id, 
                    :created_at
                )";
        
        // 쿼리 준비
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->bindValue(':cert_number', $insurance_number, PDO::PARAM_STR);
        $stmt->bindValue(':issue_date', $hstartDay, PDO::PARAM_STR);
        $stmt->bindValue(':company_id', $icomSelect, PDO::PARAM_STR);
        $stmt->bindValue(':product_id', $productSelect, PDO::PARAM_INT); // 상품 ID 바인딩 추가
        $stmt->bindValue(':created_at', $currentDateTime, PDO::PARAM_STR);
        
        // SQL 및 파라미터 로깅
        error_log("SQL Insert Query: " . $sql);
        error_log("Insert Parameters: " . json_encode([
            'client_id' => $client_id,
            'cert_number' => $insurance_number,
            'issue_date' => $hstartDay,
            'company_id' => $icomSelect,
            'product_id' => $productSelect, // 로깅에 상품 ID 추가
            'created_at' => $currentDateTime
        ]));
        
        // 쿼리 실행
        $result = $stmt->execute();
        
        if ($result) {
            // 삽입된 ID 가져오기
            $new_cert_id = $pdo->lastInsertId();
            
            // 성공 응답 반환
            $response = array();
            $response['success'] = true;
            $response['message'] = '증권 정보가 성공적으로 등록되었습니다.';
            $response['cert_id'] = $new_cert_id;
        } else {
            $response = array();
            $response['success'] = false;
            $response['message'] = '증권 정보 등록 중 오류가 발생했습니다.';
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