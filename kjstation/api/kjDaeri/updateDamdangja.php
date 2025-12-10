<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);


  
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : ''; 
$memberNum= isset($_POST['memberNum']) ? $_POST['memberNum'] : ''; 



if (!empty($memberNum) && !empty($dNum)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
   $Sql="UPDATE 2012DaeriCompany SET MemberNum='$memberNum' WHERE num='$dNum'";
 
     mysql_query($Sql, $conn);
}
//echo $sql_main;
// 응답 데이터 구성

$response = array(
    "success" => true,
	'memberNum'=>$memberNum,
	'dNum'=>$dNum
	
);





// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
