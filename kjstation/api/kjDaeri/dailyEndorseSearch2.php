<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';      // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php';     // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 파라미터 가져오기
$todayStr = isset($_POST['todayStr']) ? $_POST['todayStr'] : '';
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$policyNum = isset($_POST['policyNum']) ? $_POST['policyNum'] : '';
$sort = isset($_POST['sort']) ? $_POST['sort'] : '';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

// 1. 메인 데이터 조회
$data = array();
$sql = "SELECT a.*,
               b.company,
               c.name, c.Jumin, c.hphone, c.manager, c.etag, c.nai,
			   r.rate
        FROM SMSData a
        INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
        INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
		INNER JOIN `2019rate` r ON r.policy=a.policyNum  AND r.jumin=c.Jumin
        WHERE a.endorse_day='$todayStr' 
        AND a.`2012DaeriCompanyNum`='$dNum'
        AND a.dagun='1' 
        ORDER BY a.SeqNo DESC";

// 메인 데이터 처리
if (!empty($sql)) {
    $result = mysql_query($sql, $conn);
    
    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            // 문자열 인코딩 변환 (EUC-KR → UTF-8)
            foreach ($row as $key => $value) {
                if (!is_numeric($value) && !empty($value)) {
                    $converted = @iconv("EUC-KR", "UTF-8", $value);
                    $row[$key] = ($converted !== false) ? $converted : $value;
                }
            }
            
            $data[] = $row;
        }
    }
}

// 2. 증권별 통계 데이터 처리
// 고유한 증권번호(policyNum) 조회
$sql2 = "SELECT DISTINCT policyNum
         FROM SMSData 
         WHERE endorse_day='$todayStr' 
         AND `2012DaeriCompanyNum`='$dNum'
         AND dagun='1' 
         ORDER BY etag DESC";
$result2 = mysql_query($sql2, $conn);

// 증권별 개수를 저장할 배열 초기화
$policyStats = array();
$totalStats = array(
    'subscription' => 0,  // 청약(push=4)
    'termination' => 0,   // 해지(push=2)
    'total' => 0          // 전체
);

// 각 증권번호에 대해 청약, 해지 등의 개수 조회
if ($result2) {
    while ($row = mysql_fetch_assoc($result2)) {
        $policyNum = $row['policyNum'];
        
        // 각 증권번호에 대한 통계 저장 배열
        $policyStats[$policyNum] = array(
            'subscription' => 0,  // 청약(push=4)
            'termination' => 0,   // 해지(push=2)
            'total' => 0          // 전체
        );
        
        // 청약(push=4) 개수 조회
        $sqlPush1 = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = '$policyNum'
                    AND endorse_day='$todayStr' 
                    AND `2012DaeriCompanyNum`='$dNum'
                    AND dagun='1'
                    AND push = '4'";
        $resultPush1 = mysql_query($sqlPush1, $conn);
        if ($resultPush1 && $rowPush1 = mysql_fetch_assoc($resultPush1)) {
            $policyStats[$policyNum]['subscription'] = intval($rowPush1['count']);
            $totalStats['subscription'] += $policyStats[$policyNum]['subscription'];
        }
        
        // 해지(push=2) 개수 조회
        $sqlPush2 = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = '$policyNum'
                    AND endorse_day='$todayStr' 
                    AND `2012DaeriCompanyNum`='$dNum'
                    AND dagun='1'
                    AND push = '2'";
        $resultPush2 = mysql_query($sqlPush2, $conn);
        if ($resultPush2 && $rowPush2 = mysql_fetch_assoc($resultPush2)) {
            $policyStats[$policyNum]['termination'] = intval($rowPush2['count']);
            $totalStats['termination'] += $policyStats[$policyNum]['termination'];
        }
        
        // 전체 개수 조회
        $sqlTotal = "SELECT COUNT(*) as count 
                    FROM SMSData 
                    WHERE policyNum = '$policyNum'
                    AND endorse_day='$todayStr' 
                    AND `2012DaeriCompanyNum`='$dNum'
                    AND dagun='1'";
        $resultTotal = mysql_query($sqlTotal, $conn);
        if ($resultTotal && $rowTotal = mysql_fetch_assoc($resultTotal)) {
            $policyStats[$policyNum]['total'] = intval($rowTotal['count']);
            $totalStats['total'] += $policyStats[$policyNum]['total'];
        }
    }
}

// 3. API 응답 구성
$response = array(
    "success" => true,
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

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>