<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : ''; 
$mem_id = isset($_POST['mem_id']) ? $_POST['mem_id'] : ''; 
$phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
$password = isset($_POST['password']) ? $_POST['password'] : ''; 
$company= isset($_POST['company']) ? $_POST['company'] : ''; 
$company=@iconv("UTF-8","EUC-KR",  $company);
$user= isset($_POST['user']) ? $_POST['user'] : ''; 
$user=@iconv("UTF-8","EUC-KR",  $user);
if (!empty($dNum)) {

	$pass=md5($password);
    $sql="INSERT INTO `2012Costomer` (`2012DaeriCompanyNum`,mem_id ,passwd ,name ,jumin1 ,jumin2 ,hphone ,email ,level ,";
		$sql.="mail_check ,wdate ,inclu ,kind ,pAssKey ,pAssKeyoPen,permit,readIs,user )";
		$sql.="VALUES ('$dNum','$mem_id','$pass','$company','','','$phone','','5','', now(), '', '2', '', '','1','2','$user')"; //처음에 모든 권한자2 읽기 전용은 별도 1

		mysql_query($sql,$conn);
    
    
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
