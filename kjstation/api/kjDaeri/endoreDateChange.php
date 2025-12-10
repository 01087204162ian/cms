<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// GET 파라미터 가져오기
$endorse_day = isset($_POST['endorse_day']) ? $_POST['endorse_day'] : ''; 
$after_day = isset($_POST['after_day']) ? $_POST['after_day'] : ''; 
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';  
$cNum = isset($_POST['cNum']) ? $_POST['cNum'] : '';  
$pNum = isset($_POST['pNum']) ? $_POST['pNum'] : '';  

$data = array();



if (!empty($cNum) && !empty($pNum)) {
    // 두 번째 쿼리: data 배열에 들어갈 정보 가져오기
    $sql_data = "
        SELECT num 
        FROM DaeriMember
        WHERE CertiTableNum = '$cNum'
        AND EndorsePnum = '$pNum'
        AND sangtae = '1'
        ORDER BY Jumin DESC
    ";
    //echo $sql_data;
    
    $result_data = mysql_query($sql_data, $conn);
    if ($result_data && mysql_num_rows($result_data) > 0) {
        while ($row = mysql_fetch_assoc($result_data)) {
            $memberNum = $row['num'];
            $update = "UPDATE `2012DaeriMember` 
                      SET endorse_day = '$after_day', manager = '$userName'
                      WHERE num = '$memberNum'";

			 $update2 = "UPDATE `DaeriMember` 
                      SET endorse_day = '$after_day', manager = '$userName'
                      WHERE num = '$memberNum'";
            
            // 업데이트 쿼리 실행
            mysql_query($update, $conn);
			mysql_query($update2, $conn);
            
            // 필요한 경우 여기에 오류 처리 추가
             if (!mysql_query($update, $conn)) {
                echo "Error updating record: " . mysql_error($conn);
             }
            
            // $data[] = $row; // 필요한 경우 데이터 배열에 추가
        }
    }

	// endoser Table  endorse_day 변경하자
}

// 응답 데이터 구성
$response = array(
    "success" => true,
	"endorse_day"=>$endorse_day,
	"after_day"=>$after_day,
	"userName"=>$userName,
	"cNum"=>$cNum,
	"pNum"=>$pNum

);
// 배열 데이터를 추가
$response["data"] = $data;



// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>
