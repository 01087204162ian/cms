<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';      // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php';     // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 파라미터 가져오기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$data = array();

// dNum 값이 존재하는 경우에만 처리
if (!empty($dNum)) {
    // 회사 정보 조회
    $sql_main = "SELECT 
					 a.company,
					 dc.num,dc.mem_id,dc.hphone,dc.permit,dc.readIs,dc.user			
					 FROM `2012DaeriCompany`  a
					 INNER JOIN `2012Costomer` dc ON a.num = dc.2012DaeriCompanyNum
					 WHERE a.num = '$dNum'";

					 //echo $sql_main;
    $result_main = mysql_query($sql_main, $conn);
    
    // 회사 정보가 존재하면 처리
		if ($result_main && mysql_num_rows($result_main) > 0) {
			while ($main_info = mysql_fetch_assoc($result_main)) {
				// 문자열 인코딩 변환 (EUC-KR → UTF-8)
				foreach ($main_info as $key => $value) {
					if (!is_numeric($value) && !empty($value)) {
						$converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
						$main_info[$key] = ($converted !== false) ? $converted : utf8_encode($value);
					}
				}
				
				// 회사 정보를 data 배열에 추가
				$data[] = $main_info;
			}
		}
    
    // 회원 정보 조회
    // 여기에 추가 회원 정보 조회 코드 작성
}

// 응답 데이터 구성
$response = array(
    "success" => (!empty($data)),
    "dNum" => $dNum,
    "data" => $data
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>