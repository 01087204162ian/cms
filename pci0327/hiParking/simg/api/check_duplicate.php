<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => '허용되지 않는 요청 방식입니다.']);
    exit();
}

// 데이터베이스 연결
require_once "db.php"; // DB 연결 파일

// JSON 요청 데이터 파싱
$json_data = file_get_contents('php://input');
$data = json_decode($json_data);

if (!$data || !isset($data->type) || !isset($data->value)) {
    http_response_code(400);
    echo json_encode(['error' => '잘못된 요청 데이터입니다.']);
    exit();
}

$type = $data->type;
$value = trim($data->value);

if (empty($value)) {
    http_response_code(400);
    echo json_encode(['error' => '값이 비어있습니다.']);
    exit();
}

try {
    $available = false;
    
    switch ($type) {
        case 'id':
            if (!preg_match('/^[0-9]{6}$/', $value)) {
                http_response_code(400);
                echo json_encode(['error' => '잘못된 아이디 형식입니다.']);
                exit();
            }
            
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE mem_id = ?');
            $stmt->execute([$value]);
            $count = $stmt->fetchColumn();
            $available = ($count === 0);
            break;
            
        case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['error' => '잘못된 이메일 형식입니다.']);
                exit();
            }
            
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE contact = ?');
            $stmt->execute([$value]);
            $count = $stmt->fetchColumn();
            $available = ($count === 0);
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => '지원하지 않는 검사 유형입니다.']);
            exit();
    }
    
    echo json_encode([
        'success' => true,
        'available' => $available,
        'message' => $available ? '사용 가능합니다.' : '이미 사용 중입니다.'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => '데이터베이스 오류가 발생했습니다.',
        'message' => $e->getMessage()
    ]);
}