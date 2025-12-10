<?php
// /search_policy.php
include '../../../dbcon.php';
header("Content-Type: text/xml; charset=euc-kr");

if (!function_exists('json_decode')) {
    function json_decode($content, $assoc = false) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/_db/libs/JSON.php';
        if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }
}

if (!function_exists('json_encode')) {
    function json_encode($content) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/_db/libs/JSON.php';
        $json = new Services_JSON;
        return $json->encode($content);
    }
}

// 요청 데이터를 JSON으로 디코딩
$input = json_decode(file_get_contents("php://input"), true);

// 증권번호가 제공되지 않았을 경우 오류 메시지 반환
if (!isset($input['policyNumber']) || empty($input['policyNumber'])) {
    echo json_encode(array("error" => "증권번호를 입력하세요."));
    exit;
}

$policyNumber = $input['policyNumber'];


// MySQL 데이터베이스 연결 정보




$sql = "SELECT * FROM 2014CertiTable WHERE policyNum ='$policyNumber'";
echo $sql;
$rs = mysql_query($sql,$connect);

$rnum=mysql_num_rows($result);


echo $rnum;
?>
