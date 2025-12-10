<?php
/* 대리운전회사 리스트 정기납입일 기준과 담당자 별 조회 */
//header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // PHP 4에서 JSON 변환을 위한 함수 포함
include '../dbcon.php'; // DB 연결 정보 포함
include "./php/monthlyFee.php"; // 월보험료 산출 함수 

// GET 파라미터 가져오기
$dNumCompany = isset($_GET['dNumCompany']) ? $_GET['dNumCompany'] : '';
$dNumCompany=iconv("utf-8","euc-kr",$dNumCompany);

//echo "$dNumCompany"; echo $dNumCompany;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$duDay_ = isset($_GET['duDay_']) ? $_GET['duDay_'] : '';
$damdangja = isset($_GET['damdangja']) ? $_GET['damdangja'] : '';
$currentInwonElement = isset($_GET['currentInwonElement']) ? $_GET['currentInwonElement'] : ''; // 1이면 인원이 1명 이상 2이면 모든 대리운전회사 
$offset = ($page - 1) * $limit;

// $dNumCompany 값이 있으면 해당 회사만 조회
if (!empty($dNumCompany)) {
     $where_clause = "WHERE company LIKE '%" . addslashes($dNumCompany). "%'";
} else {
    // 담당자가 있을 경우와 없을 경우를 나누어 처리
    $where_clause = "WHERE FirstStartDay='$duDay_'";
    // 만약 담당자 값이 'all'이 아니고 숫자인 경우 조건 추가
    if ($damdangja != '' && $damdangja != 'all' && is_numeric($damdangja)) {
        $where_clause .= " AND MemberNum='$damdangja'";
    }
}

// 총 레코드 수 쿼리
$count_sql = "SELECT COUNT(*) as total FROM `2012DaeriCompany` $where_clause";
$count_result = mysql_query($count_sql, $conn);
$count_row = mysql_fetch_assoc($count_result);
$totalRecords = $count_row['total'];
$totalPages = ceil($totalRecords / $limit);

// 회사 정보 쿼리
$sql = "SELECT * FROM `2012DaeriCompany` 
        $where_clause
        ORDER BY num ASC
        LIMIT $offset, $limit";

//echo $sql; 
$result = mysql_query($sql, $conn);
$data = array();

if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
        // 문자열 인코딩 변환 (PHP 4.4 호환 방식)
        foreach ($row as $key => $value) {
            if (!is_numeric($value) && !empty($value)) { // NULL, 숫자는 변환하지 않음
                $converted = @iconv("EUC-KR", "UTF-8", $value);
                $row[$key] = ($converted !== false) ? $converted : $value;
            }
        }
        
        // 담당자 정보 가져오기
        if (!empty($row['MemberNum'])) {
            $damSql = "SELECT name FROM 2012Member WHERE num = '" . mysql_real_escape_string($row['MemberNum']) . "' LIMIT 1";
            $damResult = mysql_query($damSql, $conn);
            if ($damResult && mysql_num_rows($damResult) > 0) {
                $damRow = mysql_fetch_assoc($damResult);
                $damName = @iconv("EUC-KR", "UTF-8", $damRow['name']);
                $row['damdanga'] = ($damName !== false) ? $damName : $damRow['name'];
            } else {
                $row['damdanga'] = '';
            }
        } else {
            $row['damdanga'] = '';
        }
        
        // 인원 수 가져오기
        $mSql = "SELECT COUNT(*) as cnt FROM `2012DaeriMember` WHERE `2012DaeriCompanyNum` = '" . mysql_real_escape_string($row['num']) . "' AND push='4'";
        $nRs = mysql_query($mSql, $conn);
        if ($nRs && mysql_num_rows($nRs) > 0) {
            $inwonRow = mysql_fetch_assoc($nRs);
            $row['inwon'] = $inwonRow['cnt'];
        } else {
            $row['inwon'] = 0;
        }
        
        // 인원이 1명 이상이어야 하는 경우 필터링
        if ($currentInwonElement == 1 && $row['inwon'] <= 0) {
            continue;
        }
        
        $data[] = $row;
    }
}

// 응답 구성
$response = array(
    "success" => true,
    "currentPage" => $page,
    "totalPages" => $totalPages,
    "totalRecords" => $totalRecords,
    "data" => $data
);

echo json_encode_php4($response);
mysql_close($conn);
?>