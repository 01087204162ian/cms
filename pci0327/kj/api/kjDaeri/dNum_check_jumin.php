<?php
/* 대리운전회사 등록 
주민번호가 유니크한 값이므로 유효성 검사하여 
있으면 기존 대리운전 회사의 num 을 return 하고 
없으면 신규 등록 절차를 진행함
*/
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

try {
    // PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
    $pdo = getDbConnection();
    
    // GET 파라미터 가져오기
    $jumin = isset($_GET['jumin']) ? $_GET['jumin'] : '';
    
    // 주민번호 유효성 검사
    if (empty($jumin)) {
        $response = [
            "success" => false,
            "message" => "주민번호가 입력되지 않았습니다."
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 데이터베이스에서 조회 - 준비된 구문 사용으로 SQL 인젝션 방지
    $stmt = $pdo->prepare("SELECT num, jumin FROM 2012DaeriCompany WHERE jumin = :jumin");
    $stmt->bindParam(':jumin', $jumin, PDO::PARAM_STR);
    $stmt->execute();
    
    // 결과 확인
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 클라이언트 측 요청에 맞게 응답 구성
        $response = [
            "success" => true,
            "exists" => true,
            "dNum" => $row['num'] // dNum으로 num 필드 반환
        ];
    } else {
        // 데이터가 없는 경우
        $response = [
            "success" => true,
            "exists" => false
        ];
    }
    
    // 응답 전송
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // 오류 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // PDO 연결 종료 (필요 시)
    $pdo = null;
}
?>