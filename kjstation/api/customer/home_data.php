<?php
session_start(); // 세션은 항상 가장 먼저 시작해야 함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php';
include '../dbcon.php';
// 데이터 배열 초기화
$data = array();
// POST 데이터 검증 및 필터링
$dNum = '';
if (isset($_POST['cNum'])) {
    $dNum = trim($_POST['cNum']);
    $dNum = mysql_escape_string($dNum); // PHP 4.4에서는 mysql_real_escape_string 대신 이 함수 사용
}
// 대리운전회사 증권 찾기 - 최근 1년 내 증권 조회
$sql_data = "SELECT num,policyNum,gita,startyDay,InsuraneCompany
            FROM 2012CertiTable 
            WHERE 2012DaeriCompanyNum = '$dNum' 
            AND startyDay BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE()";
            
$result_data = mysql_query($sql_data, $conn);
if ($result_data && mysql_num_rows($result_data) > 0) {
    while ($row = mysql_fetch_assoc($result_data)) {
        // 문자 인코딩 변환 (EUC-KR → UTF-8)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                if ($converted !== false) {
                    $row[$key] = $converted;
                } else {
                    $row[$key] = utf8_encode($value);
                }
            }
        }
        
        // 각 증권별 인원 수 조회
        $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` 
                WHERE `CertiTableNum` = '" . mysql_escape_string($row['num']) . "' 
                AND push='4'";
        $nRs = mysql_query($mSql, $conn);
        
        if ($nRs && mysql_num_rows($nRs) > 0) {
            $inwonRow = mysql_fetch_assoc($nRs);
            $row['inwon'] = $inwonRow['cnt'];
        } else {
            $row['inwon'] = 0;
        }
        
        // 인원이 0보다 큰 경우에만 데이터 배열에 추가
        if ($row['inwon'] > 0) {
            $data[] = $row;
        }
    }
}
// 응답 데이터 구성
$response = array(
    "success" => true,
    "data" => $data
);
// JSON 응답 출력 (PHP 4.4 버전용 함수 사용)
echo json_encode_php4($response);
mysql_close($conn);
?>