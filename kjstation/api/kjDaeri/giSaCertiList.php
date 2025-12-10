<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// GET 파라미터 가져오기 및 정수로 변환
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';


// 1. 증권번호별 인원 조회 쿼리
$sql1 = "SELECT dongbuCerti, COUNT(*) as count
         FROM `2012DaeriMember`
         WHERE `2012DaeriCompanyNum`='$dNum' and push='4'
         GROUP BY dongbuCerti";

// 쿼리 실행
$result1 = mysql_query($sql1, $conn);
$certData = array();

// 결과 처리
if ($result1) {
   while ($row = mysql_fetch_assoc($result1)) {
       // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
       foreach ($row as $key => $value) {
           if (!is_numeric($value) && !empty($value)) {
               $converted = @iconv("EUC-KR", "UTF-8", $value);
               $row[$key] = ($converted !== false) ? $converted : $value;
           }
       }
       $certData[] = $row;
   }
}

// 2. 보험회사별 인원 조회 쿼리
$sql2 = "SELECT InsuranceCompany, COUNT(*) as count
         FROM `2012DaeriMember`
         WHERE `2012DaeriCompanyNum`='$dNum' and push='4'
         GROUP BY InsuranceCompany";

// 쿼리 실행
$result2 = mysql_query($sql2, $conn);
$companyData = array();

// 결과 처리
if ($result2) {
   while ($row = mysql_fetch_assoc($result2)) {
       // 문자열 인코딩 변환 (EUC-KR -> UTF-8)
       foreach ($row as $key => $value) {
           if (!is_numeric($value) && !empty($value)) {
               $converted = @iconv("EUC-KR", "UTF-8", $value);
               $row[$key] = ($converted !== false) ? $converted : $value;
           }
       }
       $companyData[] = $row;
   }
}

// 응답 데이터 구성 - 두 결과를 별도 키로 포함
$response = array(
   "success" => true,
   "data" => $certData,
   "companyData" => $companyData
);

// PHP 4.4 호환 JSON 인코딩으로 출력
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>