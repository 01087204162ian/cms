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
    // 증권 목록 조회 쿼리
    $sql = "SELECT DISTINCT 
                m.dongbuCerti,
                c.gita,
                c.InsuraneCompany
            FROM 
                DaeriMember m
            LEFT JOIN 
                2012CertiTable c ON m.CertiTableNum = c.num 
            WHERE 
                m.sangtae = '1' ORDER BY m.dongbuCerti ASC";
    
    // 쿼리 실행
    $stmt = $conn->query($sql);
    $data = [];
    
    // 결과 처리 (PDO는 기본적으로 UTF-8을 사용하므로 별도 변환 필요 없음)
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    // push 값에 따른 개수 조회
    $pushCounts = [];
    
    // 청약(push=1) 개수 조회
    $sqlPush1 = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1' AND push = '1'";
    $countPush1 = $conn->query($sqlPush1)->fetch(PDO::FETCH_ASSOC);
    $pushCounts['subscription'] = (int)$countPush1['count'];
    
    // 해지(push=4) 개수 조회
    $sqlPush4 = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1' AND push = '4'";
    $countPush4 = $conn->query($sqlPush4)->fetch(PDO::FETCH_ASSOC);
    $pushCounts['termination'] = (int)$countPush4['count'];
    
    // 전체 개수 조회
    $sqlTotal = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1'";
    $countTotal = $conn->query($sqlTotal)->fetch(PDO::FETCH_ASSOC);
    $pushCounts['total'] = (int)$countTotal['count'];
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "data" => $data,
        "pushCounts" => $pushCounts,
    ];
    
    // 결과 출력 (UTF-8 지원)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 오류 발생 시 예외 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // 연결 종료 (null로 설정하여 연결 종료)
    $conn = null;
}
?>