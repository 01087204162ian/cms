<?php
/*
주요 변경사항:

새로운 파라미터 추가:

dateRange: 날짜 구간 선택(sort=2)에 사용되는 시작일과 종료일(YYYY-MM-DD,YYYY-MM-DD 형식)
company: 회사명 검색(sort=3)에 사용되는 회사명
page: 페이지네이션 지원을 위한 페이지 번호


날짜 구간 선택(sort=2) 처리 기능 추가:

dateRange 파라미터가 있으면 시작일과 종료일을 분리하여 해당 기간 내 데이터 검색
없으면 기존처럼 한 달 전부터 오늘까지 검색


회사명 검색(sort=3) 기능 강화:

company 파라미터가 있으면 회사명으로 LIKE 검색 수행
company가 없고 dnum이 있으면 기존처럼 회사 번호로 검색


응답에 추가 파라미터 정보를 포함하여 클라이언트에서 확인 가능하도록 변경

이 수정사항을 통해 날짜 구간 선택과 회사명 검색 기능이 서버 측에서도 제대로 지원될 수 있습니다.

*/
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';      // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php';     // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 파라미터 가져오기
$sort = isset($_POST['sort']) ? $_POST['sort'] : '1'; // 기본값은 1 (핸드폰 번호로 검색)
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$dateRange = isset($_POST['dateRange']) ? $_POST['dateRange'] : '';
$company = isset($_POST['company']) ? $_POST['company'] : '';
$company=@iconv("UTF-8","EUC-KR",  $company);
$dnum = isset($_POST['dnum']) ? $_POST['dnum'] : '';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

$data = array();

// 쿼리 실행
$sql = "";

// sort=1: 핸드폰 번호로 검색
if ($sort == '1' && !empty($phone)) {
    $Rphone = explode('-', $phone);
    
    if (count($Rphone) == 3) {
        // SQL 인젝션 방지를 위해 입력값 이스케이프 처리
        $phone1 = mysql_real_escape_string($Rphone[0]);
        $phone2 = mysql_real_escape_string($Rphone[1]);
        $phone3 = mysql_real_escape_string($Rphone[2]);
        
        // 쿼리 작성
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE Rphone1='$phone1' 
                AND Rphone2='$phone2' 
                AND Rphone3='$phone3' 
                AND dagun='1' 
                ORDER BY SeqNo DESC";
    }
}
// sort=2: 날짜 구간 선택
else if ($sort == '2') {
    if (!empty($dateRange)) {
        // 날짜 범위 분리 (시작일,종료일 형식)
        $dates = explode(',', $dateRange);
        
        if (count($dates) == 2) {
            $startDate = mysql_real_escape_string(str_replace('-', '', $dates[0])); // YYYY-MM-DD -> YYYYMMDD
            $endDate = mysql_real_escape_string(str_replace('-', '', $dates[1]));
            
            // 시작일과 종료일 범위로 검색 (YYYYMMDDHHMMSS 형식)
            $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                    FROM SMSData 
                    WHERE LastTime >= '{$startDate}000000' 
                    AND LastTime <= '{$endDate}235959' 
                    AND dagun='1' 
                    ORDER BY SeqNo DESC";
        }
    } else {
        // 기존 방식: 한 달 전 날짜부터 오늘까지 (dateRange가 없는 경우)
        $oneMonthAgo = date('Ymd', strtotime('-1 month'));
        $today = date('Ymd');
        
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE LastTime >= '{$oneMonthAgo}000000' 
                AND LastTime <= '{$today}235959' 
                AND dagun='1' 
                ORDER BY SeqNo DESC";
    }
}
// sort=3: 회사명 또는 대리운전회사 번호로 검색
else if ($sort == '3') {
    // 회사명이 전달된 경우
    if (!empty($company)) {
        $companySearchTerm = mysql_real_escape_string($company);
        
        // 회사명으로 먼저 검색
        $sql = "SELECT a.Rphone1, a.Rphone2, a.Rphone3, a.Msg, a.LastTime 
                FROM SMSData a
				INNER JOIN `2012DaeriCompany` dc ON a.`2012DaeriCompanyNum` = dc.num
                WHERE dc.company LIKE '%$companySearchTerm%' 
                AND a.dagun='1' 
                ORDER BY a.SeqNo DESC";

				
    }

	//echo $sql;
    // 회사 번호가 전달된 경우 (company 없을 때만 dnum 사용)
    else if (!empty($dnum)) {
        $dnum = mysql_real_escape_string($dnum);
        
        $sql = "SELECT Rphone1, Rphone2, Rphone3, Msg, LastTime 
                FROM SMSData 
                WHERE 2012DaeriCompanyNum = '$dnum' 
                AND dagun='1' 
                ORDER BY SeqNo DESC";
    }
}

// 결과 처리
if (!empty($sql)) {
    $result = mysql_query($sql, $conn);
    
    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            // 문자열 인코딩 변환 (EUC-KR → UTF-8)
            foreach ($row as $key => $value) {
                if (!is_numeric($value) && !empty($value)) {
                    $converted = @iconv("EUC-KR", "UTF-8", $value);
                    $row[$key] = ($converted !== false) ? $converted : $value;
                }
            }
            $data[] = $row;
        }
    }
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "sort" => $sort,
    "phone" => $phone,
    "dateRange" => $dateRange,
    "company" => $company,
    "dnum" => $dnum,
    "page" => $page,
    "data" => $data
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>