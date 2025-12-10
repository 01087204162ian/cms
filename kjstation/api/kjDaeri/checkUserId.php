<?php
/**
 * 사용자 아이디 중복 확인 API
 * PHP 4.4.9 버전 호환
 */
// 헤더 설정 및 기존 파일 포함
header("Content-Type: application/json; charset=utf-8");
include '../cors.php';
include '../json4version.php'; // JSON 변환 함수 포함 (json_encode_php4 함수가 있음)
include '../dbcon.php'; // DB 연결 정보 포함

// 결과 배열 초기화
$result = array(
    'success' => false,
    'available' => 0,
    'message' => ''
);

// POST로 전송된 userId 확인
if (isset($_POST['userId']) && !empty($_POST['userId'])) {
    $mem_id = trim($_POST['userId']);
    
    // SQL 인젝션 방지를 위한 기본적인 처리
    $mem_id = stripslashes($mem_id);
    $mem_id = mysql_real_escape_string($mem_id);
    
    // 데이터베이스에서 아이디 중복 확인
    $sql = "SELECT * FROM 2012Costomer WHERE mem_id='$mem_id'";
    $rs = mysql_query($sql, $conn);
    
    // 쿼리 실행 성공 여부 확인
    if ($rs) {
        $total = mysql_num_rows($rs);
        
        // 중복 여부에 따라 결과 설정
        if ($total > 0) {
            // 이미 존재하는 아이디
            $result['success'] = true;
            $result['available'] = 1; // 사용 불가능
            $result['message'] =iconv("EUC-KR","UTF-8",'이미 사용 중인 아이디입니다.'); 
        } else {
            // 사용 가능한 아이디
            $result['success'] = true;
            $result['available'] = 2; // 사용 가능
            $result['message'] = iconv("EUC-KR","UTF-8",'사용할 수 있는 ID 입니다');
        }
    } else {
        // 쿼리 실행 실패
        $result['message'] = iconv("EUC-KR","UTF-8",'데이터베이스 조회 중 오류가 발생했습니다: ' ). mysql_error();
    }
} else {
    // 아이디가 전송되지 않은 경우
    $result['message'] = iconv("EUC-KR","UTF-8",'아이디가 전송되지 않았습니다.');
}

// 데이터베이스 연결 종료
mysql_close($conn);

// PHP 4.4.9 호환 JSON 인코딩 함수 사용
echo json_encode_php4($result);
?>