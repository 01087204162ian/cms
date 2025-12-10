<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $pdo = getDbConnection();
    
    // POST 데이터 가져오기
    $jumin = $_POST['jumin'] ?? '';
    
    // 입력값 검증
    if (empty($jumin)) {
        throw new Exception('주민번호가 제공되지 않았습니다.');
    }
    
    // Prepared statement로 SQL 인젝션 방지
    $sql = "SELECT * FROM ssang_c_memo WHERE c_number = :jumin ORDER BY num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':jumin', $jumin, PDO::PARAM_STR);
    $stmt->execute();
    
    // 조회 결과 처리
    $dataArray = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       
        $dataArray[] = $row;
    }
    
    $response = [
        'status' => 'success',
        'data' => $dataArray,
        'count' => count($dataArray)
    ];
    
} catch (PDOException $e) {
    $response = [
        'status' => 'error',
        'message' => '데이터베이스 오류: ' . $e->getMessage()
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

// JSON 출력 (PHP 8.2은 기본적으로 json_encode 지원)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 자동으로 닫힘 (명시적으로 닫으려면 $pdo = null; 사용)
?>