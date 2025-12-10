<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 데이터 가져오기
$d_hphone = isset($_POST['d_hphone']) ? $_POST['d_hphone'] : ''; 
$smsContents = isset($_POST['smsContents']) ? $_POST['smsContents'] : ''; 
$smsContents = iconv("UTF-8", "EUC-KR", $smsContents);

// 회사 전화번호 설정
$company_tel = "070-7841-5962";
list($sphone1, $sphone2, $sphone3) = explode("-", $company_tel);

// 핸드폰 번호 분리
list($hphone1, $hphone2, $hphone3) = explode("-", $d_hphone);

// 기본 설정값
$dong_db = "preminum";
$send_name = 'preminum';
$inSuranceCom = 10;

// 핸드폰 번호 형식 확인 및 SMS 발송
if($hphone1 == '011' || $hphone1 == '016' || $hphone1 == '017' || 
   $hphone1 == '018' || $hphone1 == '019' || $hphone1 == '010') {
    
    // smsContents를 msg 변수로 사용
    $msg = $smsContents;
    
    // SMS 데이터 삽입 쿼리
    $insert_sql = "INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3, SendName, RecvName, Msg, url, ";
    $insert_sql .= "Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2) ";
    $insert_sql .= "VALUES ('csdrive', '$hphone1', '$hphone2', '$hphone3', '$sphone1', '$sphone2', '$sphone3', '$send_name', 'CS', ";
    $insert_sql .= "'$msg', '', '', '', '0', 's', 0, 0, 0)";
    
    $insert_result = mysql_query($insert_sql, $conn);
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "d_hphone" => $d_hphone,
    "company_tel" => $company_tel
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>