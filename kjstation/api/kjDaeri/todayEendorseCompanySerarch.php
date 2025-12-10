<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// GET 파라미터 가져오기
$endorseDay = isset($_GET['endorseDay']) ? $_GET['endorseDay'] : '';

// 접근 권한 확인
if (!$endorseDay) {
    // 잘못된 접근일 경우 오류 응답 반환 후 종료
    $response = array(
        "success" => false,
        "message" => "잘못된 접근"
    );
    echo json_encode_php4($response);
    exit; // 중요: 이후 코드 실행 방지
}

// 증권 목록 조회 쿼리
$sql = "SELECT DISTINCT  sms.2012DaeriCompanyNum as dNum,
		dc.company
        FROM 
			SMSData sms
		INNER JOIN `2012DaeriCompany` dc ON sms.`2012DaeriCompanyNum` = dc.num
        WHERE sms.endorse_day='$endorseDay'
        AND sms.dagun='1' 
        ORDER BY dc.company ASC";

$result = mysql_query($sql, $conn);
$data = array();

// 결과 처리
if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
        // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        $data[] = $row;
    }
}


// push 값에 따른 개수 조회
$pushCounts = array();

// 청약(push=1) 개수 조회
$sqlPush1 = "SELECT COUNT(*) as count FROM SMSData WHERE endorse_day='$endorseDay'
        AND dagun='1' AND push = '4' ";
$resultPush1 = mysql_query($sqlPush1, $conn);
if ($resultPush1 && $rowPush1 = mysql_fetch_assoc($resultPush1)) {
    $pushCounts['subscription'] = intval($rowPush1['count']);
}

// 해지(push=4) 개수 조회
$sqlPush4 = "SELECT COUNT(*) as count FROM SMSData WHERE endorse_day='$endorseDay'
        AND dagun='1' AND push = '2'";
$resultPush4 = mysql_query($sqlPush4, $conn);
if ($resultPush4 && $rowPush4 = mysql_fetch_assoc($resultPush4)) {
    $pushCounts['termination'] = intval($rowPush4['count']);
}

// 전체 개수 조회
$sqlTotal = "SELECT COUNT(*) as count FROM SMSData WHERE endorse_day='$endorseDay'
        AND dagun='1'";
$resultTotal = mysql_query($sqlTotal, $conn);
if ($resultTotal && $rowTotal = mysql_fetch_assoc($resultTotal)) {
    $pushCounts['total'] = intval($rowTotal['count']);
}


// 응답 데이터 구성
$response = array(
    "success" => true,
    "sql" => $sql,
    "data" => $data,
    "pushCounts" => $pushCounts,
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>