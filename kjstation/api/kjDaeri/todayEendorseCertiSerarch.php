<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// GET 파라미터 가져오기
$endorseDay = isset($_GET['endorseDay']) ? $_GET['endorseDay'] : '';
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';
$policyNum = isset($_GET['policyNum']) ? $_GET['policyNum'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

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

if($sort==1){

	$where="WHERE endorse_day='$endorseDay'
		AND dagun='1'";
    $where1="ORDER BY SeqNo DESC";
}else if($sort==2){
	$where="WHERE endorse_day='$endorseDay'
		AND `2012DaeriCompanyNum`='$dNum'
		AND dagun='1'";
     $where1="ORDER BY SeqNo DESC";
        
	
}else if($sort==3){
	$where="WHERE endorse_day='$endorseDay'
		AND `2012DaeriCompanyNum`='$dNum'
		AND policyNum='$policyNum'
		AND dagun='1'";
	 $where1="ORDER BY SeqNo DESC";
       

}
$sql = "SELECT DISTINCT  policyNum
        FROM SMSData 
        $where $where1";

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

 //const pushType={"1":"청약","2":"해지","3":"청약거절","4":"정상","5":"해지취소","6":"청약취소"}
// push 값에 따른 개수 조회
$pushCounts = array();

// 청약(push=4) 개수 조회
$sqlPush1 = "SELECT COUNT(*) as count FROM SMSData  $where AND push = '4' $where1";
$resultPush1 = mysql_query($sqlPush1, $conn);
if ($resultPush1 && $rowPush1 = mysql_fetch_assoc($resultPush1)) {
    $pushCounts['subscription'] = intval($rowPush1['count']);
}

// 해지(push=2) 개수 조회
$sqlPush4 = "SELECT COUNT(*) as count FROM SMSData $where AND push = '2' $where1";
$resultPush4 = mysql_query($sqlPush4, $conn);
if ($resultPush4 && $rowPush4 = mysql_fetch_assoc($resultPush4)) {
    $pushCounts['termination'] = intval($rowPush4['count']);
}

// 청약거절(push=3) 개수 조회
$sqlPush3 = "SELECT COUNT(*) as count FROM SMSData $where AND push = '3' $where1";
$resultPush3 = mysql_query($sqlPush3, $conn);
if ($resultPush3 && $rowPush3 = mysql_fetch_assoc($resultPush3)) {
    $pushCounts['subscriptionReject'] = intval($rowPush3['count']);
}

// 해지취소(push=5) 개수 조회
$sqlPush5 = "SELECT COUNT(*) as count FROM SMSData $where AND push = '5' $where1";
$resultPush5 = mysql_query($sqlPush5, $conn);
if ($resultPush5 && $rowPush5 = mysql_fetch_assoc($resultPush5)) {
    $pushCounts['terminationCancel'] = intval($rowPush5['count']);
}

// 청약취소(push=6) 개수 조회
$sqlPush6 = "SELECT COUNT(*) as count FROM SMSData $where AND push = '6' $where1";
$resultPush6 = mysql_query($sqlPush6, $conn);
if ($resultPush6 && $rowPush6 = mysql_fetch_assoc($resultPush6)) {
    $pushCounts['subscriptionCancel'] = intval($rowPush6['count']);
}

// 전체 개수 조회
$sqlTotal = "SELECT COUNT(*) as count FROM SMSData $where $where1";
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