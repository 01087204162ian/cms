<?php
/* 대리운전회사 등록 
주민번호가 유니크한 값이므로 유효성 검사하여 
있으면 기존 대리운전 회사의 num 을 return 하고 
없으면 신규 등록 절차를 진행함
*/
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // PHP 4에서 JSON 변환을 위한 함수 포함
include '../dbcon.php'; // DB 연결 정보 포함


// GET 파라미터 가져오기 (GET 요청으로 받는 것으로 변경)
$jumin = isset($_GET['jumin']) ? $_GET['jumin'] : '';

// 주민번호 유효성 검사 (필요한 경우)
if (empty($jumin)) {
    $response = array(
        "success" => false,
        "message" => "주민번호가 입력되지 않았습니다."
    );
    echo json_encode_php4($response);
    exit;
}

// 데이터베이스에서 조회
$sql = "SELECT num, jumin FROM 2012DaeriCompany WHERE jumin='$jumin'";
$rs = mysql_query($sql, $conn);

// 결과 확인
if ($rs && mysql_num_rows($rs) > 0) {
    $row = mysql_fetch_array($rs);
    
    // 클라이언트 측 요청에 맞게 응답 구성
    $response = array(
        "success" => true,
        "exists" => true,
        "dNum" => $row['num'] // dNum으로 num 필드 반환
    );
} else {
    // 데이터가 없는 경우
    $response = array(
        "success" => true,
        "exists" => false
    );
}

// 응답 전송
echo json_encode_php4($response);
mysql_close($conn);
?>