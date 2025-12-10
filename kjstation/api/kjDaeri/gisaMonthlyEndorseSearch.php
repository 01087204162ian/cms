<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
include "./php/calculatePersonalRate.php"; // 함수를 먼저 include

// 파라미터 가져오기
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';
$lastMonthDueDate = isset($_GET['lastMonthDueDate']) ? $_GET['lastMonthDueDate'] : '';
$thisMonthDueDate = isset($_GET['thisMonthDueDate']) ? $_GET['thisMonthDueDate'] : '';


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
    sd.endorse_day >= '$lastMonthDueDate' 
    AND sd.endorse_day <= '$thisMonthDueDate' 
    AND sd.`2012DaeriCompanyNum` = '$dNum' 
    AND sd.dagun = '1'";
	//AND sd. get='2'";  // 미정산 건만

// 쿼리 실행 추가
$broader_result = mysql_query($broader_sql, $conn);
$smsData = array(); // SMS 데이터를 저장할 배열

if ($broader_result) {
    while ($row3 = mysql_fetch_assoc($broader_result)) {
        // UTF-8 변환
        foreach ($row3 as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                if ($converted !== false) {
                    $row3[$key] = $converted;
                }
            }
        }
        $smsData[] = $row3; // 변환된 데이터를 배열에 추가
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "smsData" => $smsData, // SMS 데이터 추가
);

echo json_encode_php4($response);
mysql_close($conn);
?>