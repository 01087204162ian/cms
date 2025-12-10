<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
//대리운전회사 정보 update /insert
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// GET 파라미터 가져오기
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : ''; 
  
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : ''; 
$insurance = isset($_GET['insurance']) ? $_GET['insurance'] : '';  
$startDay = isset($_GET['startDay']) ? $_GET['startDay'] : '';  
$policyNum = isset($_GET['policyNum']) ? $_GET['policyNum'] : '';  
$nabang = isset($_GET['nabang']) ? $_GET['nabang'] : '';  

//echo "$cNum",echo $cNum;

if ($cNum=='new') {

	$sql_insert = "
    INSERT INTO `2012CertiTable` (
	    `2012DaeriCompanyNum`,
        InsuraneCompany,
        startyDay,
        policyNum,
        nabang
    ) VALUES (
	    '$dNum',
        '$insurance',
        '$startDay',
        '$policyNum',
        '$nabang'

    )";

	//echo "$sql_insert"; echo $sql_insert;
	mysql_query($sql_insert, $conn);
   
}else{

	 // 업데이트
    $sql_main = "
        UPDATE  2012CertiTable SET 
		InsuraneCompany='$insurance',
		startyDay='$startDay',
		policyNum='$policyNum',
		nabang='$nabang'
        WHERE num = '$cNum'";
		//echo "$sql_main"; echo $sql_main;
     mysql_query($sql_main, $conn);
}

// 응답 데이터 구성
$response = array(
    "success" => true,
);





// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
