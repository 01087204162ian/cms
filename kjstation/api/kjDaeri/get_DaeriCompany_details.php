<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php'; // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php'; // 데이터베이스 연결 설정
// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);
// GET 파라미터 가져오기
 
$dNum = isset($_GET['dNum']) ? $_GET['dNum'] : '';  
//echo 'dNum'; echo $dNum;
$data = array();
$main_info = array();

if (!empty($dNum)) {
    // 조인을 사용하지 않고 각 테이블에서 개별적으로 데이터 가져오기
    
    // 첫 번째 쿼리: 대리점 회사 정보 가져오기
    $sql_company = "SELECT * FROM `2012DaeriCompany` WHERE num = '$dNum'";
    $result_company = mysql_query($sql_company, $conn);
    
    if ($result_company && mysql_num_rows($result_company) > 0) {
        $company_info = mysql_fetch_assoc($result_company);
        
        // 대리점 회사 정보를 main_info에 복사
        foreach ($company_info as $key => $value) {
            if (!is_numeric($value) && !empty($value)) {
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                $main_info[$key] = ($converted !== false) ? $converted : utf8_encode($value);
            } else {
                $main_info[$key] = $value;
            }
        }
        
        // 두 번째 쿼리: 회원 정보 가져오기 (기존 코드의 INNER JOIN에 해당)
        if (isset($company_info['MemberNum']) && !empty($company_info['MemberNum'])) {
            $memberNum = $company_info['MemberNum'];
            $sql_member = "SELECT name FROM `2012Member` WHERE num = '$memberNum'";
            $result_member = mysql_query($sql_member, $conn);
            
            if ($result_member && mysql_num_rows($result_member) > 0) {
                $member_info = mysql_fetch_assoc($result_member);
                if (isset($member_info['name'])) {
                    $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $member_info['name']);
                    $main_info['name'] = ($converted !== false) ? $converted : utf8_encode($member_info['name']);
                }
            }
        }
        
        // 세 번째 쿼리: 고객 정보 가져오기 (기존 코드의 INNER JOIN에 해당)
        $sql_customer = "SELECT mem_id, permit FROM `2012Costomer` WHERE `2012DaeriCompanyNum` = '$dNum'";
        $result_customer = mysql_query($sql_customer, $conn);
        
        if ($result_customer && mysql_num_rows($result_customer) > 0) {
            $customer_info = mysql_fetch_assoc($result_customer);
            
            if (isset($customer_info['mem_id'])) {
                $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $customer_info['mem_id']);
                $main_info['mem_id'] = ($converted !== false) ? $converted : utf8_encode($customer_info['mem_id']);
            }
            
            if (isset($customer_info['permit'])) {
                $main_info['permit'] = $customer_info['permit'];
            }
        }
    }
    
    // 네 번째 쿼리: 인증서 정보 가져오기
    $sql_data = "SELECT * 
                FROM 2012CertiTable 
                WHERE 2012DaeriCompanyNum = '$dNum' 
                AND startyDay BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND CURDATE();";
    $result_data = mysql_query($sql_data, $conn);
    
    if ($result_data && mysql_num_rows($result_data) > 0) {
        while ($row = mysql_fetch_assoc($result_data)) {
            foreach ($row as $key => $value) {
                if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                    $converted = @iconv("EUC-KR", "UTF-8//IGNORE", $value);
                    $row[$key] = ($converted !== false) ? $converted : utf8_encode($value);
                }
            }
            
            // 각 증권별 인원을 구하기 위해
            // 인원 수 가져오기
            $mSql = "SELECT COUNT(*) as cnt FROM `DaeriMember` WHERE `CertiTableNum` = '" . mysql_real_escape_string($row['num']) . "' AND push='4'";
            $nRs = mysql_query($mSql, $conn);
            if ($nRs && mysql_num_rows($nRs) > 0) {
                $inwonRow = mysql_fetch_assoc($nRs);
                $row['inwon'] = $inwonRow['cnt'];
            } else {
                $row['inwon'] = 0;
            }
            $data[] = $row;
        }
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true
);

// 최상위 데이터를 추가
if (!empty($main_info)) {
    $response = array_merge($response, $main_info);
}

// 배열 데이터를 추가
$response["data"] = $data;

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>