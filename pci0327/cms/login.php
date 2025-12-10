<?php
require_once 'config/database.php';

class Login {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function authenticateUser(string $userid, string $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, userid, phone, email, password 
                FROM users 
                WHERE userid = ?
            ");
            $stmt->execute([$userid]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => '아이디 또는 비밀번호가 일치하지 않습니다.'
                ];
            }

            if (password_verify($password, $user['password'])) {
                // 로그인 성공
                session_start();
                
                // 세션에 사용자 정보 저장
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'userid' => $user['userid'],
                    'phone' => $user['phone']
                ];

                return [
                    'success' => true,
                    'message' => '로그인 성공!',
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'userid' => $user['userid'],
                        'phone' => $user['phone']
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => '아이디 또는 비밀번호가 일치하지 않습니다.'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => '로그인 처리 중 오류가 발생했습니다.'
            ];
        }
    }
}

// API 엔드포인트 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('Login request received');
    $data = json_decode(file_get_contents('php://input'), true);
    
    error_log('Request data: ' . print_r($data, true));
    
    if (!isset($data['userid']) || !isset($data['password'])) {
        error_log('Missing required fields');
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => '필수 입력값이 누락되었습니다.'
        ]);
        exit;
    }

    $login = new Login();
    $result = $login->authenticateUser($data['userid'], $data['password']);
    
    error_log('Authentication result: ' . print_r($result, true));
    
    header('Content-Type: application/json');
    echo json_encode($result);
} 