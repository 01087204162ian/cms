<?php
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';      // CORS 헤더 설정 파일
include '../json4version.php'; // PHP 4 JSON 변환 함수
include '../dbcon.php';     // 데이터베이스 연결 설정

// MySQL 연결 시 UTF-8 강제 적용
mysql_query("SET NAMES utf8", $conn);

// POST 파라미터 가져오기
$todayStr = isset($_POST['todayStr']) ? $_POST['todayStr'] : ''; // 기본값은 1 
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$policyNum = isset($_POST['policyNum']) ? $_POST['policyNum'] : '';
$sort = isset($_POST['sort']) ? $_POST['sort'] : '';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

$data = array();
/**1: 날자 조회하는 경우 ,
   2 날자와 dNum,
   3 날자와 dNum (대리운전회사), policyNum 증권번호**/
// 쿼리 실행
$sql = "";


        
  if($sort==1){      
       
	$sql = "SELECT a.SeqNo,a.LastTime,a.preminum,a.push,a.policyNum,a.c_preminum,a.Rphone1,a.Rphone2,a.Rphone3,a.manager,a.insuranceCom,
				b.company,
				c.name,c.Jumin,c.hphone,c.manager,c.etag,c.nai,
				r.rate
			FROM SMSData a
			INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
			INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
			INNER JOIN `2019rate` r ON r.policy=a.policyNum  AND r.jumin=c.Jumin
			WHERE a.endorse_day='$todayStr'
			AND a.dagun='1' 
			ORDER BY a.policyNum ASC,a.push ASC, c.Jumin ASC";
  }else if($sort==2){
	 $sql = "SELECT a.SeqNo,a.LastTime,a.preminum,a.push,a.policyNum,a.c_preminum,a.Rphone1,a.Rphone2,a.Rphone3,a.manager,a.insuranceCom,
					b.company,
					c.name,c.Jumin,c.hphone,c.manager,c.etag,c.nai,
					r.rate
                FROM SMSData a
				INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
				INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
				INNER JOIN `2019rate` r ON r.policy=a.policyNum  AND r.jumin=c.Jumin
                WHERE a.endorse_day='$todayStr' 
				AND a.`2012DaeriCompanyNum`='$dNum'
                AND a.dagun='1' 
                ORDER BY a.policyNum ASC,a.push ASC, c.Jumin ASC";
  }else if($sort==3){
	$sql = "SELECT a.SeqNo,a.LastTime,a.preminum,a.push,a.policyNum,a.c_preminum,a.Rphone1,a.Rphone2,a.Rphone3,a.manager,a.insuranceCom,
					b.company,
					c.name,c.Jumin,c.hphone,c.manager,c.etag,c.nai,
					r.rate
                FROM SMSData a
				INNER JOIN `2012DaeriCompany` b ON a.`2012DaeriCompanyNum` = b.num
				INNER JOIN `2012DaeriMember` c ON a.`2012DaeriMemberNum` = c.num
				INNER JOIN `2019rate` r ON r.policy=a.policyNum  AND r.jumin=c.Jumin
                WHERE a.endorse_day='$todayStr' 
				AND a.policyNum='$policyNum'
                AND a.dagun='1' 
                ORDER BY a.policyNum ASC,a.push ASC, c.Jumin ASC";


  }
				
 
	//INNER JOIN `2019rate` r ON r.policy=a.policyNum  AND r.jumin=c.Jumin  배서하면 rate 저장하는 것으로 변경하자 2025-03-25 로드하는데 시간이 넘 많이 걸린다


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
    "todayStr" => $todayStr,
    "page" => $page,
    "data" => $data
);

// JSON 변환 후 출력 (PHP 4.4 대응)
echo json_encode_php4($response);

// 데이터베이스 연결 닫기
mysql_close($conn);
?>