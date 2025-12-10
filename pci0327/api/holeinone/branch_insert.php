<?php
/**
 * 고객사 정보 등록/수정 처리 스크립트
 * 
 * POST 요청으로 전송된 고객사 정보를 데이터베이스에 저장합니다.
 * branch_id 파라미터가 있으면 수정, 없으면 새로 등록합니다.
 * 결과는 JSON 형태로 반환됩니다.
 * 
 * 경로: /api/holeinone/branch_insert.php
 */
// 세션 시작 (로그인 확인 등을 위해)
session_start();
// 필요한 파일 포함
require_once '../config/db_config.php';
require_once '../utils/client_validation.php';
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
// 필수 필드 검증
if (empty($_POST['branchName'])) {
    $response = array();
    $response['success'] = false;
    $response['message'] = '지점명은 필수 입력 항목입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_POST['client_id'])) {
    $response = array();
    $response['success'] = false;
    $response['message'] = '고객사 ID는 필수 입력 항목입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 데이터베이스 연결 가져오기
    $pdo = getDbConnection();
    
    // 입력 데이터 검증 및 준비
    $validationResult = sanitizeBranchData($_POST);
    
    // 유효성 검사 실패 시
    if (!$validationResult['valid']) {
        $response = array();
        $response['success'] = false;
        $response['message'] = implode(', ', $validationResult['errors']);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 유효한 데이터 추출
    $clientData = $validationResult['data'];
    
    // branch_id가 있는지 확인 (update 또는 insert 결정)
    $isUpdate = isset($_POST['branch_id']) && !empty($_POST['branch_id']);
    
    if ($isUpdate) {
        // branch_id 값 가져오기
        $branch_id = $_POST['branch_id'];
        
        // SQL UPDATE 쿼리 준비
        $sql = "UPDATE branches SET
            client_id = :client_id,
            branchName = :branchName, 
            branchDamdangName = :branchDamdangName, 
            branchDamdangPhone = :branchDamdangPhone, 
            branchDamdangEmail = :branchDamdangEmail
            WHERE id = :branch_id";

        // 쿼리 실행
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':client_id', $clientData['client_id']);
        $stmt->bindParam(':branchName', $clientData['branchName']);
        $stmt->bindParam(':branchDamdangName', $clientData['branchDamdangName']);
        $stmt->bindParam(':branchDamdangPhone', $clientData['branchDamdangPhone']);
        $stmt->bindParam(':branchDamdangEmail', $clientData['branchDamdangEmail']);
        $stmt->bindParam(':branch_id', $branch_id);
        
        $stmt->execute();
        
        // 영향 받은 행 수 확인
        $rowCount = $stmt->rowCount();
        
        if ($rowCount === 0) {
            // ID가 존재하는지 확인
            $checkStmt = $pdo->prepare("SELECT id FROM branches WHERE id = :branch_id");
            $checkStmt->bindParam(':branch_id', $branch_id);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                $response = array();
                $response['success'] = false;
                $response['message'] = '수정할 지점을 찾을 수 없습니다.';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                // 변경사항이 없는 경우
                $response = array();
                $response['success'] = true;
                $response['message'] = '변경된 내용이 없습니다.';
                $response['data'] = array(
                    'client_id' => $clientData['client_id'],
                    'branch_id' => $branch_id
                );
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        
        // 성공 응답
        $response = array();
        $response['success'] = true;
        $response['message'] = '지점 정보가 성공적으로 수정되었습니다.';
        $response['data'] = array(
            'client_id' => $clientData['client_id'],
            'branch_id' => $branch_id
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        
    } else {
        // SQL INSERT 쿼리 준비
        $sql = "INSERT INTO branches (
                    client_id,
                    branchName, 
                    branchDamdangName, 
                    branchDamdangPhone, 
                    branchDamdangEmail, 
                    created_at
                ) VALUES (
                    :client_id,
                    :branchName, 
                    :branchDamdangName, 
                    :branchDamdangPhone, 
                    :branchDamdangEmail, 
                    :created_at
                )";
        
        // 현재 시간 설정
        $now = date('Y-m-d H:i:s');
        
        // 쿼리 실행
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':client_id', $clientData['client_id']);
        $stmt->bindParam(':branchName', $clientData['branchName']);
        $stmt->bindParam(':branchDamdangName', $clientData['branchDamdangName']);
        $stmt->bindParam(':branchDamdangPhone', $clientData['branchDamdangPhone']);
        $stmt->bindParam(':branchDamdangEmail', $clientData['branchDamdangEmail']);
        $stmt->bindParam(':created_at', $now);
        
        $stmt->execute();
        
        // 행의 영향을 받았다면 성공
        $branch_id = $pdo->lastInsertId();
        error_log('Branch ID: ' . $branch_id);
        
        if ($branch_id) {
            $response = array();
            $response['success'] = true;
            $response['message'] = '지점 정보가 성공적으로 등록되었습니다.';
            $response['data'] = array(
                'client_id' => $clientData['client_id'],
                'branch_id' => $branch_id
            );
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // 쿼리 실행은 되었으나 ID가 생성되지 않음
            http_response_code(500);
            $response = array();
            $response['success'] = false;
            $response['message'] = '지점 정보가 저장되었으나 ID를 가져오지 못했습니다.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }
    
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