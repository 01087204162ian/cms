<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';

// PDO 연결 가져오기
$pdo = getDbConnection();

// 결과 배열 초기화
$result = array(
    'success' => false,
    'available' => 0,
    'message' => ''
);

// POST로 전송된 userId 확인
if (isset($_POST['userId']) && !empty($_POST['userId'])) {
    $mem_id = trim($_POST['userId']);
    
    try {
        // Prepared statement를 사용하여 SQL 인젝션 방지
        $sql = "SELECT * FROM `2012Costomer` WHERE mem_id = :mem_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mem_id', $mem_id, PDO::PARAM_STR);
        $stmt->execute();
        
        // 중복 여부에 따라 결과 설정
        if ($stmt->rowCount() > 0) {
            // 이미 존재하는 아이디
            $result['success'] = true;
            $result['available'] = 1; // 사용 불가능
            $result['message'] = '이미 사용 중인 아이디입니다.';
        } else {
            // 사용 가능한 아이디
            $result['success'] = true;
            $result['available'] = 2; // 사용 가능
            $result['message'] = '사용할 수 있는 ID 입니다';
        }
    } catch (PDOException $e) {
        // 쿼리 실행 실패
        $result['message'] = '데이터베이스 조회 중 오류가 발생했습니다: ' . $e->getMessage();
    }
} else {
    // 아이디가 전송되지 않은 경우
    $result['message'] = '아이디가 전송되지 않았습니다.';
}

// JSON 인코딩하여 출력
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>