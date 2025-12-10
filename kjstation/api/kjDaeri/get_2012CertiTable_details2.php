<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);
// GET 파라미터 가져오기
 
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  
  
$data = array();
$main_info = array();
if (!empty($cNum)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    $sql_main = "
       SELECT 
	    dc.num as dNum,
		dc.company,
		ct.policyNum,
		ct.gita,
		ct.InsuraneCompany
		FROM 2012CertiTable ct
		INNER JOIN 2012DaeriCompany dc ON ct.2012DaeriCompanyNum = dc.num
		WHERE ct.num = '$cNum';
    ";
    $result_main = mysql_query($sql_main, $conn);
    if ($result_main && mysql_num_rows($result_main) > 0) {
        $main_info = mysql_fetch_assoc($result_main); // 한 개의 행만 가져옴
        foreach ($main_info as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                $main_info[$key] = ($converted !== false) ? $converted : utf8_encode($value);
            }
        }
    }
    
}
// 응답 데이터 구성
$response = array(
    "success" => true
);
// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}
// 배열 데이터를 추가
$response["data"] = $data;
// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);
// 데이터베이스 연결 닫기
mysql_close($conn);
?>