<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// 응답 함수
function sendResponse($success, $message = '', $data = array()) {
    $response = array(
        'success' => $success
    );
    
    if (!empty($message)) {
        $response['error'] = $message;
    }
    
    if (!empty($data)) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// POST 데이터 확인
if (empty($_POST['data'])) {
    sendResponse(false, '데이터가 전송되지 않았습니다.');
}

// 성공 카운트
$successCount = 0;
$errors = array();

// 현재 시간
$now = date('Y-m-d H:i:s');

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $pdo = getDbConnection();
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    // 각 데이터 처리
    $data = $_POST['data'];
    for ($i = 0; $i < count($data); $i++) {
        $item = $data[$i];
        
        // 데이터 유효성 검사
        if (empty($item['cNum'])) {
            $errors[] = '증명서 번호가 없는 항목이 있습니다.';
            continue;
        }
        
        // 파라미터 준비
        $cNum = $item['cNum'];
        $rowNum = intval($item['rowNum']);
        
        // NULL 처리
        $start_month = empty($item['start_month']) ? null : intval($item['start_month']);
        $end_month = empty($item['end_month']) ? null : intval($item['end_month']);
        $monthly_premium1 = empty($item['monthly_premium1']) ? null : intval($item['monthly_premium1']);
        $monthly_premium2 = empty($item['monthly_premium2']) ? null : intval($item['monthly_premium2']);
        $monthly_premium_total = empty($item['monthly_premium_total']) ? null : intval($item['monthly_premium_total']);
        $payment10_premium1 = empty($item['payment10_premium1']) ? null : intval($item['payment10_premium1']);
        $payment10_premium2 = empty($item['payment10_premium2']) ? null : intval($item['payment10_premium2']);
        $payment10_premium_total = empty($item['payment10_premium_total']) ? null : intval($item['payment10_premium_total']);
        
        // 기존 데이터 확인
        $checkStmt = $pdo->prepare("SELECT id FROM kj_premium_data WHERE cNum = :cNum AND rowNum = :rowNum LIMIT 1");
        $checkStmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
        $checkStmt->bindParam(':rowNum', $rowNum, PDO::PARAM_INT);
        $checkStmt->execute();
        
        // 기존 데이터 있으면 업데이트, 없으면 삽입
        if ($checkStmt->rowCount() > 0) {
            $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
            
            $updateStmt = $pdo->prepare("UPDATE kj_premium_data SET 
                start_month = :start_month,
                end_month = :end_month,
                monthly_premium1 = :monthly_premium1,
                monthly_premium2 = :monthly_premium2,
                monthly_premium_total = :monthly_premium_total,
                payment10_premium1 = :payment10_premium1,
                payment10_premium2 = :payment10_premium2,
                payment10_premium_total = :payment10_premium_total,
                updated_at = :now
                WHERE id = :id");
                
            $updateStmt->bindParam(':start_month', $start_month, PDO::PARAM_INT);
            $updateStmt->bindParam(':end_month', $end_month, PDO::PARAM_INT);
            $updateStmt->bindParam(':monthly_premium1', $monthly_premium1, PDO::PARAM_INT);
            $updateStmt->bindParam(':monthly_premium2', $monthly_premium2, PDO::PARAM_INT);
            $updateStmt->bindParam(':monthly_premium_total', $monthly_premium_total, PDO::PARAM_INT);
            $updateStmt->bindParam(':payment10_premium1', $payment10_premium1, PDO::PARAM_INT);
            $updateStmt->bindParam(':payment10_premium2', $payment10_premium2, PDO::PARAM_INT);
            $updateStmt->bindParam(':payment10_premium_total', $payment10_premium_total, PDO::PARAM_INT);
            $updateStmt->bindParam(':now', $now, PDO::PARAM_STR);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if (!$updateStmt->execute()) {
                $errors[] = "데이터 업데이트 실패 (행: $rowNum): " . implode(', ', $updateStmt->errorInfo());
            } else {
                $successCount++;
            }
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO kj_premium_data (
                cNum, rowNum, start_month, end_month,
                monthly_premium1, monthly_premium2, monthly_premium_total,
                payment10_premium1, payment10_premium2, payment10_premium_total,
                created_at, updated_at
            ) VALUES (
                :cNum, :rowNum, :start_month, :end_month,
                :monthly_premium1, :monthly_premium2, :monthly_premium_total,
                :payment10_premium1, :payment10_premium2, :payment10_premium_total,
                :now, :now
            )");
            
            $insertStmt->bindParam(':cNum', $cNum, PDO::PARAM_STR);
            $insertStmt->bindParam(':rowNum', $rowNum, PDO::PARAM_INT);
            $insertStmt->bindParam(':start_month', $start_month, PDO::PARAM_INT);
            $insertStmt->bindParam(':end_month', $end_month, PDO::PARAM_INT);
            $insertStmt->bindParam(':monthly_premium1', $monthly_premium1, PDO::PARAM_INT);
            $insertStmt->bindParam(':monthly_premium2', $monthly_premium2, PDO::PARAM_INT);
            $insertStmt->bindParam(':monthly_premium_total', $monthly_premium_total, PDO::PARAM_INT);
            $insertStmt->bindParam(':payment10_premium1', $payment10_premium1, PDO::PARAM_INT);
            $insertStmt->bindParam(':payment10_premium2', $payment10_premium2, PDO::PARAM_INT);
            $insertStmt->bindParam(':payment10_premium_total', $payment10_premium_total, PDO::PARAM_INT);
            $insertStmt->bindParam(':now', $now, PDO::PARAM_STR);
            
            if (!$insertStmt->execute()) {
                $errors[] = "데이터 삽입 실패 (행: $rowNum): " . implode(', ', $insertStmt->errorInfo());
            } else {
                $successCount++;
            }
        }
    }
    
    // 모든 작업이 성공적이면 커밋
    if (count($errors) == 0) {
        $pdo->commit();
        sendResponse(true, '', array('count' => $successCount));
    } else {
        // 오류가 있으면 롤백
        $pdo->rollBack();
        sendResponse(false, implode(', ', $errors));
    }
} catch (PDOException $e) {
    // PDO 예외 처리
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendResponse(false, '데이터베이스 오류: ' . $e->getMessage());
} catch (Exception $e) {
    // 일반 예외 처리
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendResponse(false, '오류 발생: ' . $e->getMessage());
} finally {
    // 연결 명시적 종료 (PDO는 자동으로 닫히지만 명시적으로 표시)
    $pdo = null;
}
?>