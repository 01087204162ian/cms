<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기 및 정수로 변환
$sj = $_GET['sj'] ?? '';

// 접근 권한 확인
if ($sj != 'sj_') {
    // 잘못된 접근일 경우 오류 응답 반환 후 종료
    $response = array(
        "success" => false,
        "message" => "잘못된 접근"
    );
    echo json_encode($response);
    exit; // 중요: 이후 코드 실행 방지
}

try {
    // 증권 목록 조회 쿼리
    $sql = "SELECT * FROM 2012Member ORDER BY num ASC";
    
    // PDO로 쿼리 실행
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $data = array();
    
    // 결과 처리
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
        
        $data[] = $row;
    }
    
    // 응답 데이터 구성
    $response = array(
        "success" => true,
        "data" => $data
    );
    
} catch (PDOException $e) {
    // 에러 발생 시 처리
    $response = array(
        "success" => false,
        "message" => "데이터베이스 오류가 발생했습니다."
    );
}

// JSON 인코딩으로 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 스크립트 종료 시 자동으로 종료됨
?>