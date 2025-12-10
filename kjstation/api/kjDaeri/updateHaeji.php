<?php
/**
 * 보험료 데이터 저장 API - FormData 처리 버전 
 * PHP 4.4.9 버전 호환
 */

// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함
include "./php/monthlyFee.php"; // 월보험료 산출 함수 
// 오류 보고 설정
error_reporting(E_ALL ^ E_NOTICE);

// POST 데이터 검증
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $response = array(
        "success" => false,
        "message" => "잘못된 요청 방식입니다."
    );
    echo json_encode_php4($response);
    exit();
}

// 받은 모든 POST 데이터 수집
$receivedData = array(
    "DariMemberNum" => isset($_POST['DariMemberNum']) ? $_POST['DariMemberNum'] : null,
    "cNum" => isset($_POST['cNum']) ? $_POST['cNum'] : null,
    "dNum" => isset($_POST['dNum']) ? $_POST['dNum'] : null,
    "InsuranceCompany" => isset($_POST['InsuranceCompany']) ? $_POST['InsuranceCompany'] : null,
    "endorseDay" => isset($_POST['endorseDay']) ? $_POST['endorseDay'] : null,
    "policyNum" => isset($_POST['policyNum']) ? $_POST['policyNum'] : null,
    "userName" => isset($_POST['userName']) ? $_POST['userName'] : null
);

// 파라미터 변수로 설정 (이후 코드에서 사용)
$DariMemberNum=$receivedData["DariMemberNum"];
$cNum = $receivedData["cNum"];
$dNum = $receivedData["dNum"];
$gita = $receivedData["gita"];
$InsuraneCompany = $receivedData["InsuraneCompany"];
$endorseDay = $receivedData["endorseDay"];
$policyNum = $receivedData["policyNum"];
$userName = $receivedData["userName"];


include "./php/endorse_num.php";


		$insertSql="UPDATE 2012DaeriMember SET sangtae='1',OutPutDay='$endorse_day',EndorsePnum='$endorse_num',cancel='42',wdate=now(),endorse_day='$endorse_day'";
		$insertSql.="WHERE num='$DariMemberNum'";
		mysql_query($insertSql);

		$insertSql2="UPDATE DaeriMember SET sangtae='1',OutPutDay='$endorse_day',EndorsePnum='$endorse_num',cancel='42',wdate=now(),endorse_day='$endorse_day'";
		$insertSql2.="WHERE num='$DariMemberNum'";
		mysql_query($insertSql2);

		$message='해지 신청 되었습니다!!';
include "./php/endorse_num_store.php";
// POST 데이터 전체 저장 (디버깅 목적)
$allPostData = $_POST;

// 응답 생성
$response = array(
    "success" => true,
    "message" => @iconv("EUC-KR","UTF-8",$message),
    "data" => array(
        "mainData" => $receivedData,
        "endorseData" => $endorseData,
        "baseDate" => $m,
        "endorseNum" => $endorse_num,
        "endorseSangtae" => $e_sangtae,
        "oldEndorseDay" => $old_endorse_day,
        "newEndorseDay" => $endorse_day,
        "endorseCase" => $ak,
        "allPostData" => $allPostData
    )
);

// 응답 반환
echo json_encode_php4($response);
exit();
?>