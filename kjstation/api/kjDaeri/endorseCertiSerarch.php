<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// GET 파라미터 가져오기 및 정수로 변환
$sj = isset($_GET['sj']) ? $_GET['sj'] : '';

// 접근 권한 확인
if ($sj != 'sj_') {
   // 잘못된 접근일 경우 오류 응답 반환 후 종료
   $response = array(
       "success" => false,
       "message" => "잘못된 접근"
   );
   echo json_encode_php4($response);
   exit; // 중요: 이후 코드 실행 방지
}

// 증권 목록 조회 쿼리
$sql = "SELECT DISTINCT 
			m.dongbuCerti,
			c.gita,
			c.InsuraneCompany
	FROM 
		DaeriMember m
	LEFT JOIN 
		2012CertiTable c ON m.CertiTableNum = c.num 
	WHERE 
    m.sangtae = '1' order by m.dongbuCerti asc";

// 쿼리 실행
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
$sqlPush1 = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1' AND push = '1'";
$resultPush1 = mysql_query($sqlPush1, $conn);
if ($resultPush1 && $rowPush1 = mysql_fetch_assoc($resultPush1)) {
    $pushCounts['subscription'] = intval($rowPush1['count']);
}

// 해지(push=4) 개수 조회
$sqlPush4 = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1' AND push = '4'";
$resultPush4 = mysql_query($sqlPush4, $conn);
if ($resultPush4 && $rowPush4 = mysql_fetch_assoc($resultPush4)) {
    $pushCounts['termination'] = intval($rowPush4['count']);
}

// 전체 개수 조회
$sqlTotal = "SELECT COUNT(*) as count FROM DaeriMember WHERE sangtae = '1'";
$resultTotal = mysql_query($sqlTotal, $conn);
if ($resultTotal && $rowTotal = mysql_fetch_assoc($resultTotal)) {
    $pushCounts['total'] = intval($rowTotal['count']);
}






// 응답 데이터 구성
$response = array(
   "success" => true,
   "data" => $data,
   "pushCounts" => $pushCounts,
   
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>