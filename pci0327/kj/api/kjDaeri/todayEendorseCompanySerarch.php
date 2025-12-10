<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기
$endorseDay = $_GET['endorseDay'] ?? '';

// 접근 권한 확인
if (!$endorseDay) {
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
    $sql = "SELECT DISTINCT  sms.`2012DaeriCompanyNum` as dNum,
                   dc.company
            FROM SMSData sms
            INNER JOIN `2012DaeriCompany` dc ON sms.`2012DaeriCompanyNum` = dc.num
            WHERE sms.endorse_day = :endorseDay
            AND sms.dagun = '1' 
            ORDER BY dc.company ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':endorseDay' => $endorseDay]);
    
    $data = [];
    
    // 결과 처리
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
        
        $data[] = $row;
    }
    
    // push 값에 따른 개수 조회
    $pushCounts = [];
    
    // 청약(push=4) 개수 조회
    $sqlPush1 = "SELECT COUNT(*) as count FROM SMSData 
                 WHERE endorse_day = :endorseDay
                 AND dagun = '1' 
                 AND push = '4'";
    
    $stmtPush1 = $pdo->prepare($sqlPush1);
    $stmtPush1->execute([':endorseDay' => $endorseDay]);
    $rowPush1 = $stmtPush1->fetch(PDO::FETCH_ASSOC);
    $pushCounts['subscription'] = intval($rowPush1['count']);
    
    // 해지(push=2) 개수 조회
    $sqlPush4 = "SELECT COUNT(*) as count FROM SMSData 
                 WHERE endorse_day = :endorseDay
                 AND dagun = '1' 
                 AND push = '2'";
    
    $stmtPush4 = $pdo->prepare($sqlPush4);
    $stmtPush4->execute([':endorseDay' => $endorseDay]);
    $rowPush4 = $stmtPush4->fetch(PDO::FETCH_ASSOC);
    $pushCounts['termination'] = intval($rowPush4['count']);
    
    // 전체 개수 조회
    $sqlTotal = "SELECT COUNT(*) as count FROM SMSData 
                 WHERE endorse_day = :endorseDay
                 AND dagun = '1'";
    
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute([':endorseDay' => $endorseDay]);
    $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    $pushCounts['total'] = intval($rowTotal['count']);
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "sql" => $sql,
        "data" => $data,
        "pushCounts" => $pushCounts,
    ];
    
    // JSON 출력
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 에러 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류가 발생했습니다.",
        "error" => $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

// PDO는 자동으로 연결이 종료되므로 별도 close 불필요
?>