<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$conn = getDbConnection();

// GET 파라미터 가져오기
$sj = $_GET['sj'] ?? '';

// 접근 권한 확인
if ($sj != 'sj_') {
    // 잘못된 접근일 경우 오류 응답 반환 후 종료
    $response = [
        "success" => false,
        "message" => "잘못된 접근"
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit; // 중요: 이후 코드 실행 방지
}

try {
    // 데이터 조회 쿼리
    // 주의: "2012DaeriCompanyNum"은 숫자로 시작하는 컬럼명이라 백틱으로 감싸야 함
    $sql = "SELECT DISTINCT c.company, c.num
            FROM DaeriMember m
            LEFT JOIN 2012DaeriCompany c ON m.`2012DaeriCompanyNum` = c.num
            WHERE m.sangtae = '1' 
            ORDER BY c.company ASC";
    
    // 쿼리 실행
    $stmt = $conn->query($sql);
    $data = [];
    
    // 결과 처리
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "data" => $data
    ];
    
    // JSON으로 출력 (UTF-8 지원)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 오류 발생 시 예외 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // 연결 종료
    $conn = null;
}
?>