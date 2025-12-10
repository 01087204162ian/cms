<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// POST 파라미터 가져오기
$todayStr = isset($_POST['todayStr']) ? $_POST['todayStr'] : '';
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';


// 1. 메인 데이터 조회
$data = array();
$sql = "SELECT a.*,
               b.company,
               c.name, c.Jumin, c.hphone, c.manager, c.etag, c.nai,
               r.rate
        FROM SMSData a
        INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
        INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
        INNER JOIN `2019rate` r ON r.policy=a.policyNum AND r.jumin=c.Jumin
        WHERE a.endorse_day = :todayStr 
        AND a.`2012DaeriCompanyNum` = :dNum
        AND a.dagun = '1' 
        ORDER BY a.SeqNo DESC";

// 메인 데이터 처리
try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':todayStr', $todayStr, PDO::PARAM_STR);
    $stmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    $stmt->execute();
    
    // PDO::FETCH_ASSOC 모드로 데이터 가져오기
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // 오류 처리
    $response = array(
        "success" => false,
        "message" => "데이터베이스 조회 중 오류가 발생했습니다: " . $e->getMessage()
    );
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. 증권별 통계 데이터 처리
// 고유한 증권번호(policyNum) 조회
$sql2 = "SELECT DISTINCT policyNum
         FROM SMSData 
         WHERE endorse_day = :todayStr 
         AND `2012DaeriCompanyNum` = :dNum
         AND dagun = '1' 
         ORDER BY policyNum DESC";

// 증권별 개수를 저장할 배열 초기화
$policyStats = array();
$totalStats = array(
    'subscription' => 0,  // 청약(push=4)
    'termination' => 0,   // 해지(push=2)
    'total' => 0          // 전체
);

try {
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':todayStr', $todayStr, PDO::PARAM_STR);
    $stmt2->bindParam(':dNum', $dNum, PDO::PARAM_STR);
    $stmt2->execute();
    
    // 각 증권번호에 대해 청약, 해지 등의 개수 조회
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $currentPolicy = $row['policyNum'];
        
        // 각 증권번호에 대한 통계 저장 배열
        $policyStats[$currentPolicy] = array(
            'subscription' => 0,  // 청약(push=4)
            'termination' => 0,   // 해지(push=2)
            'total' => 0          // 전체
        );
        
        // 청약(push=4) 개수 조회
        $sqlPush1 = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = :policyNum
                    AND endorse_day = :todayStr 
                    AND `2012DaeriCompanyNum` = :dNum
                    AND dagun = '1'
                    AND push = '4'";
        $stmtPush1 = $pdo->prepare($sqlPush1);
        $stmtPush1->bindParam(':policyNum', $currentPolicy, PDO::PARAM_STR);
        $stmtPush1->bindParam(':todayStr', $todayStr, PDO::PARAM_STR);
        $stmtPush1->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmtPush1->execute();
        $rowPush1 = $stmtPush1->fetch(PDO::FETCH_ASSOC);
        
        $policyStats[$currentPolicy]['subscription'] = intval($rowPush1['count']);
        $totalStats['subscription'] += $policyStats[$currentPolicy]['subscription'];
        
        // 해지(push=2) 개수 조회
        $sqlPush2 = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = :policyNum
                    AND endorse_day = :todayStr 
                    AND `2012DaeriCompanyNum` = :dNum
                    AND dagun = '1'
                    AND push = '2'";
        $stmtPush2 = $pdo->prepare($sqlPush2);
        $stmtPush2->bindParam(':policyNum', $currentPolicy, PDO::PARAM_STR);
        $stmtPush2->bindParam(':todayStr', $todayStr, PDO::PARAM_STR);
        $stmtPush2->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmtPush2->execute();
        $rowPush2 = $stmtPush2->fetch(PDO::FETCH_ASSOC);
        
        $policyStats[$currentPolicy]['termination'] = intval($rowPush2['count']);
        $totalStats['termination'] += $policyStats[$currentPolicy]['termination'];
        
        // 전체 개수 조회
        $sqlTotal = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = :policyNum
                    AND endorse_day = :todayStr 
                    AND `2012DaeriCompanyNum` = :dNum
                    AND dagun = '1'";
        $stmtTotal = $pdo->prepare($sqlTotal);
        $stmtTotal->bindParam(':policyNum', $currentPolicy, PDO::PARAM_STR);
        $stmtTotal->bindParam(':todayStr', $todayStr, PDO::PARAM_STR);
        $stmtTotal->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $stmtTotal->execute();
        $rowTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        
        $policyStats[$currentPolicy]['total'] = intval($rowTotal['count']);
        $totalStats['total'] += $policyStats[$currentPolicy]['total'];
    }
} catch (PDOException $e) {
    // 오류 처리
    $response = array(
        "success" => false,
        "message" => "통계 데이터 조회 중 오류가 발생했습니다: " . $e->getMessage()
    );
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. API 응답 구성
$response = array(
    "success" => true,
	"sql"=>$sql,
    "todayStr" => $todayStr,
    "page" => $page,
    "data" => $data,
    "policyStats" => $policyStats,
    "totalStats" => array(
        "policyCount" => count($policyStats),
        "subscription" => $totalStats['subscription'],
        "termination" => $totalStats['termination'],
        "total" => $totalStats['total']
    )
);

// JSON으로 출력 (PHP 8.2에서는 json_encode가 UTF-8을 기본 지원)
echo json_encode($response,JSON_UNESCAPED_UNICODE);

// PDO 연결은 스크립트 종료 시 자동으로 닫힘
?>