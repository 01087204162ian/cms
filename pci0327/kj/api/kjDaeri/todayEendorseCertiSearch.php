<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();

// GET 파라미터 가져오기
$endorseDay = $_GET['endorseDay'] ?? '';
$dNum = $_GET['dNum'] ?? '';
$policyNum = $_GET['policyNum'] ?? '';
$sort = $_GET['sort'] ?? '';

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
    $params = [];
    $where = "";
    $where1 = "ORDER BY SeqNo DESC";
    
    if ($sort == 1) {
        $where = "WHERE endorse_day = :endorseDay
                  AND dagun = '1'";
        $params[':endorseDay'] = $endorseDay;
    } else if ($sort == 2) {
        $where = "WHERE endorse_day = :endorseDay
                  AND `2012DaeriCompanyNum` = :dNum
                  AND dagun = '1'";
        $params[':endorseDay'] = $endorseDay;
        $params[':dNum'] = $dNum;
    } else if ($sort == 3) {
        $where = "WHERE endorse_day = :endorseDay
                  AND `2012DaeriCompanyNum` = :dNum
                  AND policyNum = :policyNum
                  AND dagun = '1'";
        $params[':endorseDay'] = $endorseDay;
        $params[':dNum'] = $dNum;
        $params[':policyNum'] = $policyNum;
    }
    
    $sql = "SELECT DISTINCT policyNum, insuranceCom
            FROM SMSData 
            $where $where1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $data = [];
    
    // 결과 처리
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @mb_convert_encoding($value, "UTF-8", "EUC-KR");
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        $data[] = $row;
    }
    
    // push 값에 따른 개수 조회
    $pushCounts = [];
    
    // 각 push 타입별 개수 조회 함수
    function getPushCount($pdo, $where, $params, $pushValue) {
        // ORDER BY는 COUNT 쿼리에서 필요없으므로 제거
        $countSql = "SELECT COUNT(*) as count FROM SMSData $where AND push = :push";
        $countParams = array_merge($params, [':push' => $pushValue]);
        
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($countParams);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['count']);
    }
    
    // 청약(push=4) 개수 조회  
    $pushCounts['subscription'] = getPushCount($pdo, $where, $params, '4');
    
    // 해지(push=2) 개수 조회
    $pushCounts['termination'] = getPushCount($pdo, $where, $params, '2');
    
    // 청약거절(push=3) 개수 조회
    $pushCounts['subscriptionReject'] = getPushCount($pdo, $where, $params, '3');
    
    // 해지취소(push=5) 개수 조회
    $pushCounts['terminationCancel'] = getPushCount($pdo, $where, $params, '5');
    
    // 청약취소(push=6) 개수 조회
    $pushCounts['subscriptionCancel'] = getPushCount($pdo, $where, $params, '6');
    
    // 전체 개수 조회
    $sqlTotal = "SELECT COUNT(*) as count FROM SMSData $where";
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute($params);
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