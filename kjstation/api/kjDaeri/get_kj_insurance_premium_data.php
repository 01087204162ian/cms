<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);
// GET 파라미터 가져오기
 
$certi = isset($_GET['certi']) ? $_GET['certi'] : '';  
  
$data = array();

if (!empty($certi)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    
    $sql_data = "SELECT * FROM kj_insurance_premium_data WHERE policyNum = '$certi'";
    $result_data = mysql_query($sql_data, $conn);
    if ($result_data && mysql_num_rows($result_data) > 0) {
        while ($row = mysql_fetch_assoc($result_data)) {
            foreach ($row as $key => $value) {
                // 여기서 필요한 데이터 변환 작업을 수행할 수 있습니다.
                // 현재는 아무 변환 없이 그대로 사용합니다.
                $row[$key] = $value;
            }
            $data[] = $row;
        }
    }
}
// 응답 데이터 구성
$response = array(
    "success" => true
);

// 배열 데이터를 추가
$response["data"] = $data;
// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);
// 데이터베이스 연결 닫기
mysql_close($conn);
?>