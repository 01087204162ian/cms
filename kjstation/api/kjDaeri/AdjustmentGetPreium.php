<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// POST 데이터 받기
$cleanValue = isset($_POST['cleanValue']) ? $_POST['cleanValue'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';

$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

// 필수 매개변수 검증
if (empty($cleanValue) || empty($id) ) {
    $response = array(
        "success" => false,
        "message" => iconv("EUC-KR","UTF-8", "필수 매개변수가 누락되었습니다.")
    );
    echo json_encode_php4($response);
    exit;
}

// 현재 날짜 및 시간
$currentDateTime = date('Y-m-d H:i:s');

// 이름 UTF-8 변환
$userName_euckr = @iconv("UTF-8", "EUC-KR", $userName);
if ($userName_euckr === false) {
    $userName_euckr = $userName; // 변환 실패 시 원본 사용
}

    // 기존 데이터 업데이트
    $update_sql = "UPDATE AdjustmentPremium 
                   SET receivedAmount = '$cleanValue', 
                       receiveDate = '$currentDateTime', 
                       receiveUSer = '$userName_euckr'
                   WHERE id = '$id'";
    $result = mysql_query($update_sql, $conn);


	//조회하여 차액 계산 
	$sql ="SELECT adjustmentAmount,receiveUSer FROM AdjustmentPremium WHERE id='$id' ";
	$rs=mysql_query($sql,$conn);
	$row=mysql_fetch_assoc($rs);

	$chai=$row['adjustmentAmount']-$cleanValue;
	$receiveUSer=@iconv( "EUC-KR","UTF-8",$row['receiveUSer']);

// 쿼리 실행 결과 확인
if ($result) {
    $response = array(
        "success" => true,
        "message" => iconv("EUC-KR","UTF-8",  "받는 보험료 입력 완료.")
    );
} else {
    $error = mysql_error($conn);
    $response = array(
        "success" => false,
        "message" => iconv("EUC-KR","UTF-8", "데이터베이스 오류: ") . $error
    );
}


$data = array(
    "cleanValue" => $cleanValue,
    "id" => $id,
    "userName" => $userName,
	"chai"=>$chai,
	"receiveUSer"=>$receiveUSer
  //  "query" => isset($insert_sql) ? $insert_sql : $update_sql
);

// 응답에 디버깅 정보 추가
$response["data"] = $data;

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>