<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

$num = isset($_POST['num']) ? $_POST['num'] : ''; 
$progress  = isset($_POST['progress']) ? $_POST['progress'] : ''; 
$manager = isset($_POST['userName']) ? $_POST['userName'] : '';
$manager = @iconv("UTF-8//IGNORE", "EUC-KR", $manager);

if (!empty($num) && !empty($progress)&& !empty($manager)) {
    
   
	$update = "UPDATE 2012DaeriMember SET progress='$progress', manager='$manager' WHERE num='$num'";

	 $update2 = "UPDATE DaeriMember SET progress='$progress', manager='$manager' WHERE num='$num'";

	mysql_query($update,$conn);mysql_query($update2,$conn);

	$message='처리완료 !!';
}
//echo $sql_main;
// 응답 데이터 구성
$manager = @iconv("EUC-KR", "UTF-8", $manager);
$message = @iconv("EUC-KR", "UTF-8", $message);
$response = array(
    "success" => true,
	//"num" =>$num,
	//"progress" =>$progress,
	"message" =>$message,
	//"update"=>$update,
	"manager"=>$manager
	
);


// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
