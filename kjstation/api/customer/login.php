<?php
session_start(); // ← 제일 위에 와야 안전
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
$pass_word = isset($_POST['pass_word']) ? trim($_POST['pass_word']) : '';
$response = array("success" => false, "message" => "로그인 실패");
// 유효성 검사
if ($user_id == '' || $pass_word == '') {
    $response["message"] = iconv("EUC-KR","UTF-8","아이디 또는 비밀번호가 누락되었습니다.") ;
    echo json_encode_php4($response);
    exit;
}
// 사용자 조회
$query = "SELECT * FROM 2012Costomer WHERE mem_id = '$user_id' LIMIT 1";
$rs = mysql_query($query, $conn);
if (!$rs || mysql_num_rows($rs) == 0) {
    $response["message"] = $query.iconv("EUC-KR","UTF-8","존재하지 않는 사용자입니다.") ;
    echo json_encode_php4($response);
    exit;
}
$row = mysql_fetch_array($rs);
// 권한 확인 및 비밀번호 일치 여부 확인
if ($row["permit"] == 1 && $row["passwd"] == md5($pass_word)) {
    $userId = $row["mem_id"];
    $cNum = $row["2012DaeriCompanyNum"];
    $readIs = $row["readIs"];
    $name = $row["name"];
    $userseq = md5($userId . "csj");
    $userIp = $_SERVER["REMOTE_ADDR"];
    


    // 세션 ID 재생성 (세션 고정 공격 방지)
    session_regenerate_id(true);
    
    // 세션 등록 (PHP 4.4 용)
    session_register("userId");
    session_register("userseq");
    session_register("userIp");
    
    // 세션 타임아웃을 위한 마지막 활동 시간 저장
    $_SESSION["last_activity"] = time();
    
    // 쿠키 설정
    setcookie("host_id", $userId, 0, "/");
    setcookie("user_ip", $userIp, 0, "/");
    setcookie("small_sid", $userseq, 0, "/");
    setcookie("userId", $userId, 0, "/");
    setcookie("userseq", $userseq, 0, "/");
    setcookie("nAme", $name, 0, "/");
    setcookie("cNum", $cNum, 0, "/");
    setcookie("readIs", $readIs, 0, "/");

	//대리운전회사 명 찾기 
	$dNum=$cNum;
	include "../kjDaeri/php/dNum_search.php";


    
    // login.js에서 필요로 하는 응답 데이터 추가
    $response["success"] = true;
    $response["message"] = iconv("EUC-KR","UTF-8","로그인 성공");
    $response["userId"] = $userId;
    $response["userseq"] = $userseq;
    $response["nAme"] = iconv("EUC-KR","UTF-8",$name);
    $response["cNum"] = $cNum;
	$response["company"] = iconv("EUC-KR","UTF-8",$Drow['company']);
} else {
    $response["message"] = iconv("EUC-KR","UTF-8","비밀번호가 일치하지 않거나 권한이 없습니다.");
}
echo json_encode_php4($response);
mysql_close($conn);
?>