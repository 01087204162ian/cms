<?php
/**
 * 보험료 데이터 저장 API - FormData 처리 버전
 * PHP 4.4.9 버전 호환
 */

// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함

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
    
    echo json_encode_php4($response);
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

// 트랜잭션 시작 (MySQL 4.0.22은 MyISAM을 사용하므로 트랜잭션 효과 없음)
mysql_query("START TRANSACTION", $conn);

// 각 데이터 처리
$data = $_POST['data'];
for ($i = 0; $i < count($data); $i++) {
    $item = $data[$i];
    
    // 데이터 유효성 검사
    if (empty($item['cNum'])) {
        $errors[] = '증명서 번호가 없는 항목이 있습니다.';
        continue;
    }
    
    // SQL 인젝션 방지
    $cNum = mysql_escape_string($item['cNum']);
    $rowNum = intval($item['rowNum']);
    
    // 정수 변환 및 NULL 처리
    $start_month = empty($item['start_month']) ? 'NULL' : intval($item['start_month']);
    $end_month = empty($item['end_month']) ? 'NULL' : intval($item['end_month']);
    $monthly_premium1 = empty($item['monthly_premium1']) ? 'NULL' : intval($item['monthly_premium1']);
    $monthly_premium2 = empty($item['monthly_premium2']) ? 'NULL' : intval($item['monthly_premium2']);
    $monthly_premium_total = empty($item['monthly_premium_total']) ? 'NULL' : intval($item['monthly_premium_total']);
    $payment10_premium1 = empty($item['payment10_premium1']) ? 'NULL' : intval($item['payment10_premium1']);
    $payment10_premium2 = empty($item['payment10_premium2']) ? 'NULL' : intval($item['payment10_premium2']);
    $payment10_premium_total = empty($item['payment10_premium_total']) ? 'NULL' : intval($item['payment10_premium_total']);
    
    // 기존 데이터 확인
    $checkQuery = "SELECT id FROM kj_premium_data WHERE cNum = '$cNum' AND rowNum = $rowNum LIMIT 1";
    $checkResult = mysql_query($checkQuery);
    
    if (!$checkResult) {
        $errors[] = '데이터 확인 실패: ' . mysql_error();
        continue;
    }
    
    // 기존 데이터 있으면 업데이트, 없으면 삽입
    if (mysql_num_rows($checkResult) > 0) {
        $row = mysql_fetch_array($checkResult);
        $id = $row['id'];
        
        $updateQuery = "UPDATE kj_premium_data SET 
            start_month = $start_month,
            end_month = $end_month,
            monthly_premium1 = $monthly_premium1,
            monthly_premium2 = $monthly_premium2,
            monthly_premium_total = $monthly_premium_total,
            payment10_premium1 = $payment10_premium1,
            payment10_premium2 = $payment10_premium2,
            payment10_premium_total = $payment10_premium_total,
            updated_at = '$now'
            WHERE id = $id";
            
        $updateResult = mysql_query($updateQuery);
        
        if (!$updateResult) {
            $errors[] = "데이터 업데이트 실패 (행: $rowNum): " . mysql_error();
        } else {
            $successCount++;
        }
    } else {
        $insertQuery = "INSERT INTO kj_premium_data (
            cNum, rowNum, start_month, end_month,
            monthly_premium1, monthly_premium2, monthly_premium_total,
            payment10_premium1, payment10_premium2, payment10_premium_total,
            created_at, updated_at
        ) VALUES (
            '$cNum', $rowNum, $start_month, $end_month,
            $monthly_premium1, $monthly_premium2, $monthly_premium_total,
            $payment10_premium1, $payment10_premium2, $payment10_premium_total,
            '$now', '$now'
        )";
        
        $insertResult = mysql_query($insertQuery);
        
        if (!$insertResult) {
            $errors[] = "데이터 삽입 실패 (행: $rowNum): " . mysql_error();
        } else {
            $successCount++;
        }
    }
}

// 모든 작업이 성공적이면 커밋
if (count($errors) == 0) {
    mysql_query("COMMIT", $conn);
    sendResponse(true, '', array('count' => $successCount));
} else {
    // 오류가 있으면 롤백
    mysql_query("ROLLBACK", $conn);
    sendResponse(false, implode(', ', $errors));
}

// 데이터베이스 연결 종료
mysql_close($conn);
?>