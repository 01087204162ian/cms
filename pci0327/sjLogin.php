<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once 'DatabaseConnection.php';

class UserLogin {
    private PDO $db;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15분 (초 단위)

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function validateInput(array $data): array {
        $errors = [];

        if (!preg_match('/^[0-9]{4,12}$/', $data['userid'])) {
            $errors['userid'] = "올바른 아이디 형식이 아닙니다.";
        }

        if (empty($data['password'])) {
            $errors['password'] = "비밀번호를 입력해주세요.";
        }

        return $errors;
    }

    public function checkLoginAttempts(string $userid): bool {
        $stmt = $this->db->prepare("
            SELECT failed_login_attempts, last_login_attempt 
            FROM users 
            WHERE userid = ?
        ");
        $stmt->execute([$userid]);
        $result = $stmt->fetch();

        if ($result) {
            if ($result['failed_login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
                $lockoutTime = strtotime($result['last_login_attempt']) + self::LOCKOUT_TIME;
                if (time() < $lockoutTime) {
                    $remainingTime = ceil(($lockoutTime - time()) / 60);
                    throw new Exception("로그인이 잠겼습니다. {$remainingTime}분 후에 다시 시도해주세요.");
                } else {
                    // 잠금 시간이 지났으면 카운트 초기화
                    $this->resetLoginAttempts($userid);
                }
            }
        }
        return true;
    }

    private function resetLoginAttempts(string $userid): void {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET failed_login_attempts = 0, 
                last_login_attempt = NULL 
            WHERE userid = ?
        ");
        $stmt->execute([$userid]);
    }

    private function incrementLoginAttempts(string $userid): void {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET failed_login_attempts = failed_login_attempts + 1,
                last_login_attempt = NOW()
            WHERE userid = ?
        ");
        $stmt->execute([$userid]);
    }

    public function authenticateUser(string $userid, string $password): array {
        $stmt = $this->db->prepare("
            SELECT id, name, userid, phone, email, password 
            FROM users 
            WHERE userid = ?
        ");
        $stmt->execute([$userid]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("아이디 또는 비밀번호가 일치하지 않습니다.");
        }

        if (password_verify($password, $user['password'])) {
            // 로그인 성공
            $this->resetLoginAttempts($userid);
            
            // 세션 시작
            session_start();
            
            // 세션에 사용자 정보 저장
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'userid' => $user['userid'],
                'phone' => $user['phone'],
                'email' => $user['email'],
				'username'=>$user['name']
            ];
			
			// 추가: 세션 보안 정보
			$_SESSION['last_activity'] = time(); // 마지막 활동 시간
			$_SESSION['login_time'] = time();    // 로그인 시간

            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'userid' => $user['userid'],
				'username'=>$user['name'],
                'phone' => $user['phone']
            ];
        } else {
            // 로그인 실패
            $this->incrementLoginAttempts($userid);
            throw new Exception("아이디 또는 비밀번호가 일치하지 않습니다.");
        }
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('잘못된 요청 방식입니다.');
    }

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (!$data) {
        throw new Exception('잘못된 데이터 형식입니다.');
    }

    $dbConnection = new DatabaseConnection();
    $db = $dbConnection->connect();

    $login = new UserLogin($db);

    // 입력값 검증
    $errors = $login->validateInput($data);
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }

    // 로그인 시도 횟수 확인
    $login->checkLoginAttempts($data['userid']);

    // 사용자 인증
    $user = $login->authenticateUser($data['userid'], $data['password']);

    echo json_encode([
        'success' => true,
        'message' => '로그인 성공!',
        'user' => $user
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 