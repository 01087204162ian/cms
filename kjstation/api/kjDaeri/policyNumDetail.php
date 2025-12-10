<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// POST 
$certi = isset($_POST['num']) ? $_POST['num'] : '';




// 증권 목록 조회 쿼리
$sql = "SELECT * FROM 2012Certi WHERE certi='$certi' ";

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

	  // 인원 수 가져오기
        $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` WHERE `dongbuCerti` = '" . mysql_real_escape_string($row['certi']) . "' AND push='4'";
        $nRs = mysql_query($mSql, $conn);
        if ($nRs && mysql_num_rows($nRs) > 0) {
            $inwonRow = mysql_fetch_assoc($nRs);
            $row['inwon'] = $inwonRow['cnt'];
        } else {
            $row['inwon'] = 0;
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