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
$sql = "SELECT * FROM 2012Member order by num asc";

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







// 응답 데이터 구성
$response = array(
   "success" => true,
   "data" => $data,
   
   
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>