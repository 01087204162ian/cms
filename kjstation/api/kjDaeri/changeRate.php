<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

$Jumin = isset($_POST['Jumin']) ? $_POST['Jumin'] : ''; 
$rate  = isset($_POST['rate']) ? $_POST['rate'] : ''; 
$policyNum =isset($_POST['policyNum']) ? $_POST['policyNum'] : ''; 



if (!empty($Jumin) && !empty($rate)&& !empty($policyNum)) {
    // 첫 번째 쿼리: 최상위 정보 가져오기
    $sql="SELECT * FROM 2019rate WHERE policy='$policyNum' and jumin='$Jumin'";

		//echo $sql ;

		$rs=mysql_query($sql,$conn);

		$row=mysql_fetch_array($rs);

		if($row[num]){

			$update = "UPDATE 2019rate SET rate='$rate' WHERE num='$row[num]'";

			mysql_query($update,$conn);

		}else{

			$insert="INSERT into 2019rate (policy,jumin,rate ) ";
			$insert.="VALUES ('$policyNum','$Jumin','$rate')";

			//echo $insert;
			mysql_query($insert,$conn);
		}


	$message='처리완료 !!';
}
//echo $sql_main;
// 응답 데이터 구성
$message = @iconv("EUC-KR", "UTF-8", $message);
$response = array(
    "success" => true,
	"Jumin" =>$Jumin,
	"rate" =>$rate,
	"policyNum" =>$policyNum,
	"message"=>$message
);


// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
