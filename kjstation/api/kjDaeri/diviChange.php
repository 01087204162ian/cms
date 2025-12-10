<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// GET 파라미터 가져오기
$divi = isset($_GET['divi']) ? $_GET['divi'] : ''; 
  
$cNum = isset($_GET['cNum']) ? $_GET['cNum'] : '';  

if($divi==1){
	$divi=2;
	$diviName='월납';
	
}else{
	$divi=1;
	$diviName='정상납';
}

if (!empty($cNum) && !empty($divi)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    $sql_main = "
        UPDATE  2012CertiTable SET divi='$divi' 
        WHERE num = '$cNum'";

     mysql_query($sql_main, $conn);
}
//echo $sql_main;
// 응답 데이터 구성
$diviName = @iconv("EUC-KR", "UTF-8", $diviName);
$response = array(
    "success" => true,
	'divi'=>$divi,
	"diviName"=>$diviName
);





// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
