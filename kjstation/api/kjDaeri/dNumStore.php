<?php
/**
 * 신규 대리운전회사 등록 API
 * PHP 4.4.9 버전 호환
 */
// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함
// 응답 배열 초기화
$response = array(
    'success' => false,
    'error' => '',
    'dNum' => 0
);
// POST 데이터 받기
$dNum = isset($_POST['dNum']) ? $_POST['dNum'] : '';
$jumin = isset($_POST['jumin']) ? $_POST['jumin'] : '';
$company = isset($_POST['company']) ? $_POST['company'] : '';
$company=iconv( "UTF-8","EUC-KR", $company);
$Pname = isset($_POST['Pname']) ? $_POST['Pname'] : '';
$Pname=iconv( "UTF-8","EUC-KR", $Pname);
$hphone = isset($_POST['hphone']) ? $_POST['hphone'] : '';
$cphone = isset($_POST['cphone']) ? $_POST['cphone'] : '';
$cNumber = isset($_POST['cNumber']) ? $_POST['cNumber'] : '';
$lNumber = isset($_POST['lNumber']) ? $_POST['lNumber'] : '';
// 필수 필드 검증
if (empty($jumin)) {
    $response['error'] = iconv("EUC-KR", "UTF-8", '주민번호는 필수 입력 항목입니다.');
    echo json_encode_php4($response);
    exit;
}
if (empty($company)) {
    $response['error'] = iconv("EUC-KR", "UTF-8", '대리운전회사명은 필수 입력 항목입니다.');
    echo json_encode_php4($response);
    exit;
}
if (empty($Pname)) {
    $response['error'] = iconv("EUC-KR", "UTF-8", '대표자는 필수 입력 항목입니다.');
    echo json_encode_php4($response);
    exit;
}
// SQL 인젝션 방지
$dNum = mysql_real_escape_string($dNum);
$jumin = mysql_real_escape_string($jumin);
$company = mysql_real_escape_string($company);
$Pname = mysql_real_escape_string($Pname);
$hphone = mysql_real_escape_string($hphone);
$cphone = mysql_real_escape_string($cphone);
$cNumber = mysql_real_escape_string($cNumber);
$lNumber = mysql_real_escape_string($lNumber);
// 데이터 삽입 SQL 쿼리
if($dNum){
    // dNum이 존재하면 해당 레코드 업데이트
    $query = "UPDATE `2012DaeriCompany` 
              SET company='$company', 
                  Pname='$Pname', 
                  jumin='$jumin', 
                  hphone='$hphone', 
                  cphone='$cphone', 
                  cNumber='$cNumber', 
                  lNumber='$lNumber' 
              WHERE num=$dNum";
    
    // 쿼리 실행
    $result = mysql_query($query);
    
    if ($result) {
        // 성공 응답 설정
        $response['success'] = true;
        $response['dNum'] = $dNum;
    } else {
        $response['error'] = '데이터베이스 오류: ' . mysql_error();
    }
}else{
		$query = "INSERT INTO `2012DaeriCompany` 
				  (company, Pname, jumin, hphone, cphone, cNumber, lNumber) 
				  VALUES 
				  ('$company', '$Pname', '$jumin', '$hphone', '$cphone', '$cNumber', '$lNumber')"; 
		// 쿼리 실행
		$result = mysql_query($query);
		if ($result) {
			// 새로 생성된 ID 가져오기
			$new_id = mysql_insert_id();
			
			// 성공 응답 설정
			$response['success'] = true;
			$response['dNum'] = $new_id;
		} else {
			$response['error'] = '데이터베이스 오류: ' . mysql_error();
		}
}
// JSON 응답 반환
echo json_encode_php4($response);
// 데이터베이스 연결 종료
mysql_close($conn);
?>