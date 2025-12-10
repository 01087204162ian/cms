<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';

// 파라미터 가져오기
$seqNo = isset($_POST['seqNo']) ? $_POST['seqNo'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

// 입력 유효성 검사
if(empty($seqNo) || empty($status)) {
    $response = array(
        "success" => false,
        "message" => "필수 파라미터가 누락되었습니다."
    );
    echo json_encode_php4($response);
    mysql_close($conn);
    exit;
}

// 한글 처리 (필요한 경우)
if(!empty($userName)) {
    $userName = iconv( "UTF-8","EUC-KR", $userName);
}

// 상태 값 검증 (1 또는 2만 허용)
if($status != '1' && $status != '2') {
    $response = array(
        "success" => false,
        "message" => "유효하지 않은 상태값입니다."
    );
    echo json_encode_php4($response);
    mysql_close($conn);
    exit;
}


$update = "UPDATE SMSData SET get='$status', manager='$userName' WHERE seqNo='$seqNo'";
$result = mysql_query($update);

// 쿼리 실행 결과 확인
if($result) {
    // 영향을 받은 row 수 확인
    $affected_rows = mysql_affected_rows();
    
    if($affected_rows > 0) {
        // 업데이트된 레코드 가져오기
        $query = "SELECT * FROM SMSData WHERE seqNo='$seqNo'";
        $result = mysql_query($query);
        
        if($result && mysql_num_rows($result) > 0) {
            $smsData = mysql_fetch_assoc($result);
        } else {
            $smsData = null;
        }
        
        $response = array(
            "success" => true,
            "message" => "정산 상태가 성공적으로 업데이트되었습니다.",
            "smsData" => $smsData,
            "affected_rows" => $affected_rows
        );
    } else {
        $response = array(
            "success" => false,
            "message" => "해당 SeqNo에 맞는 레코드를 찾을 수 없습니다."
        );
    }
} else {
    $response = array(
        "success" => false,
        "message" => "데이터베이스 업데이트 중 오류가 발생했습니다: " . mysql_error()
    );
}

// 응답 반환
echo json_encode_php4($response);

// 데이터베이스 연결 종료
mysql_close($conn);
?>