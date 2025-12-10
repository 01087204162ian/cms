<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// 파라미터 가져오기
$dNum = $_GET['dNum'] ?? '';
$lastMonthDueDate = $_GET['lastMonthDueDate'] ?? '';
$thisMonthDueDate = $_GET['thisMonthDueDate'] ?? '';

// SMSData 테이블에서 추가 데이터 조회
$broader_sql = "SELECT 
    dm.Name,
    dm.Jumin,
    dm.nai,
    dm.dongbuCerti,
    sd.SeqNo,
    sd.get,
    sd.manager,
    sd.preminum,
    sd.c_preminum,
    sd.push, 
    sd.endorse_day,
    cd.divi
FROM 
    `2012DaeriMember` dm
JOIN 
    `SMSData` sd ON dm.`2012DaeriCompanyNum` = sd.`2012DaeriCompanyNum` 
    AND dm.num = sd.`2012DaeriMemberNum`
JOIN 
    `2012CertiTable` cd ON dm.`CertiTableNum` = cd.`num`
WHERE 
    sd.endorse_day >= :lastMonthDueDate 
    AND sd.endorse_day <= :thisMonthDueDate 
    AND sd.`2012DaeriCompanyNum` = :dNum 
    AND sd.dagun = '1'";
    //AND sd.get='2'";  // 미정산 건만

// Prepared statement 실행
$stmt = $pdo->prepare($broader_sql);
$stmt->execute([
    ':lastMonthDueDate' => $lastMonthDueDate,
    ':thisMonthDueDate' => $thisMonthDueDate,
    ':dNum' => $dNum
]);

$smsData = array(); // SMS 데이터를 저장할 배열

while ($row3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    $smsData[] = $row3; // 변환된 데이터를 배열에 추가
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "smsData" => $smsData, // SMS 데이터 추가
);

// PHP 8.2에서는 json_encode로 직접 사용
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO는 자동으로 연결을 종료하므로 명시적으로 닫을 필요 없음
$pdo = null;
?>