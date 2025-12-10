<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기 (db_config.php에서 정의된 함수 사용)
$pdo = getDbConnection();




// POST 파라미터 가져오기
$todayStr = $_POST['todayStr'] ?? ''; 
$dNum = $_POST['dNum'] ?? '';
$policyNum = $_POST['policyNum'] ?? '';
$sort = $_POST['sort'] ?? '';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

$data = [];

/**1: 날짜 조회하는 경우 ,
   2 날짜와 dNum,
   3 날짜와 dNum (대리운전회사), policyNum 증권번호**/

// 쿼리 실행
$sql = "";
$params = [];

if ($sort == 1) {
    $sql = "SELECT a.SeqNo, a.LastTime, a.preminum, a.push, a.policyNum, a.c_preminum, 
                   a.Rphone1, a.Rphone2, a.Rphone3, a.manager, a.insuranceCom,
                   b.company,
                   c.name, c.Jumin, c.hphone, c.manager, c.etag, c.nai,
                   r.rate
            FROM SMSData a
            INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
            INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
            INNER JOIN `2019rate` r ON r.policy = a.policyNum AND r.jumin = c.Jumin
            WHERE a.endorse_day = :todayStr
            AND a.dagun = '1' 
            ORDER BY a.policyNum ASC, a.push ASC, c.Jumin ASC";
    $params[':todayStr'] = $todayStr;
    
} else if ($sort == 2) {
    $sql = "SELECT a.SeqNo, a.LastTime, a.preminum, a.push, a.policyNum, a.c_preminum,
                   a.Rphone1, a.Rphone2, a.Rphone3, a.manager, a.insuranceCom,
                   b.company,
                   c.name, c.Jumin, c.hphone, c.manager, c.etag, c.nai,
                   r.rate
            FROM SMSData a
            INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
            INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
            INNER JOIN `2019rate` r ON r.policy = a.policyNum AND r.jumin = c.Jumin
            WHERE a.endorse_day = :todayStr 
            AND a.`2012DaeriCompanyNum` = :dNum
            AND a.dagun = '1' 
            ORDER BY a.policyNum ASC, a.push ASC, c.Jumin ASC";
    $params[':todayStr'] = $todayStr;
    $params[':dNum'] = $dNum;
    
} else if ($sort == 3) {
    $sql = "SELECT a.SeqNo, a.LastTime, a.preminum, a.push, a.policyNum, a.c_preminum,
                   a.Rphone1, a.Rphone2, a.Rphone3, a.manager, a.insuranceCom,
                   b.company,
                   c.name, c.Jumin, c.hphone, c.manager, c.etag, c.nai,
                   r.rate
            FROM SMSData a
            INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
            INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
            INNER JOIN `2019rate` r ON r.policy = a.policyNum AND r.jumin = c.Jumin
            WHERE a.endorse_day = :todayStr 
            AND a.policyNum = :policyNum
            AND a.dagun = '1' 
            ORDER BY a.policyNum ASC, a.push ASC, c.Jumin ASC";
    $params[':todayStr'] = $todayStr;
    $params[':policyNum'] = $policyNum;
}

// 결과 처리
if (!empty($sql)) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           
            $data[] = $row;
        }
    } catch (PDOException $e) {
        // 에러 처리
        $response = [
            "success" => false,
            "error" => $e->getMessage(),
            "todayStr" => $todayStr,
            "page" => $page,
            "data" => []
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 응답 데이터 구성
$response = [
    "success" => true,
    "todayStr" => $todayStr,
    "page" => $page,
    "data" => $data
];

// JSON 출력 (PHP 8.2는 기본적으로 json_encode 지원)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO는 자동으로 연결이 종료되므로 별도 close 불필요
?>