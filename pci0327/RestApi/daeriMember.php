<?php
header("Content-Type: application/json; charset=UTF-8");

// CORS 허용 설정
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// DB 설정 파일 포함
require_once '../api/config/db_config.php';

// 요청 메서드 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '허용되지 않은 메서드입니다. POST 요청만 허용됩니다.']);
    exit;
}

// POST 데이터 수신
$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
if (strpos($content_type, 'application/json') !== false) {
    // JSON 형식으로 받은 경우
    $json_data = file_get_contents("php://input");
    $received_data = json_decode($json_data, true);
} else {
    // 다른 형식으로 받은 경우
    $received_data = $_POST;
}

// 데이터 유효성 검사
if (!isset($received_data['data']) || !is_array($received_data['data'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '유효한 데이터가 제공되지 않았습니다.']);
    exit;
}

try {
    // DB 연결
    $pdo = getDbConnection();
    
    // 트랜잭션 시작
    $pdo->beginTransaction();
    
    $inserted_count = 0;
    $errors = [];
    
    // 각 데이터 항목을 처리
    foreach ($received_data['data'] as $member) {
        try {
            // 필드명 변환 (2012DaeriCompanyNum -> DaeriCompanyNum_2012)
            if (isset($member['2012DaeriCompanyNum'])) {
                $member['DaeriCompanyNum_2012'] = $member['2012DaeriCompanyNum'];
                unset($member['2012DaeriCompanyNum']);
            }
            
            // 컬럼과 값 추출
            $columns = array_keys($member);
            $placeholders = array_fill(0, count($columns), '?');
            
            // 쿼리 준비
            $sql = "INSERT INTO DaeriMember (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            
            // 쿼리 실행
            $stmt->execute(array_values($member));
            $inserted_count++;
        } catch (PDOException $e) {
            $errors[] = [
                'data' => $member,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // 트랜잭션 완료
    if (count($errors) === 0) {
        $pdo->commit();
        echo json_encode([
            'success' => true,
            'message' => "성공적으로 $inserted_count 건의 데이터가 처리되었습니다.",
            'count' => $inserted_count
        ]);
    } else {
        // 에러 발생 시 롤백
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => '데이터 처리 중 오류가 발생했습니다.',
            'errors' => $errors,
            'inserted_count' => $inserted_count
        ]);
    }
} catch (PDOException $e) {
    // DB 연결 오류 처리
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'DB 연결 오류: ' . $e->getMessage()
    ]);
}
?>