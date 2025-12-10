<?php
// 에러 표시 설정 (개발 환경에서만 사용)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=UTF-8');

// 데이터베이스 연결 파일 불러오기
require_once 'DatabaseConnection.php';

/**
 * 사용자 등록을 처리하는 클래스
 */
class UserRegistration {
    private PDO $db;
    private const PASSWORD_MIN_LENGTH = 8;
    private const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    /**
     * 생성자
     * 
     * @param PDO $db 데이터베이스 연결
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * 비밀번호 유효성 검사
     * 
     * @param string $password 검사할 비밀번호
     * @return array 오류 메시지 배열
     */
    private function validatePassword(string $password): array {
        $errors = [];
        
        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            $errors[] = "비밀번호는 최소 8자 이상이어야 합니다.";
        }
        
        if (!preg_match(self::PASSWORD_REGEX, $password)) {
            $errors[] = "비밀번호는 대문자, 소문자, 숫자, 특수문자를 모두 포함해야 합니다.";
        }
        
        return $errors;
    }

    /**
     * 입력값 유효성 검사
     * 
     * @param array $data 검사할 데이터
     * @return array 오류 메시지 배열
     */
    public function validateInput(array $data): array {
        $errors = [];

        // 필수 필드 확인
        $requiredFields = ['name', 'userid', 'email', 'password', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[$field] = "이 필드는 필수입니다.";
            }
        }

        // 이미 오류가 있으면 더 자세한 검증은 건너뜀
        if (!empty($errors)) {
            return $errors;
        }

        // 이름 검증
        if (strlen($data['name']) > 50) {
            $errors['name'] = "이름은 50자 이하여야 합니다.";
        }

        // 아이디 검증 (4-12자리 영문/숫자 조합)
        if (!preg_match('/^[a-zA-Z0-9]{4,12}$/', $data['userid'])) {
            $errors['userid'] = "아이디는 4-12자리의 영문 또는 숫자로 구성해야 합니다.";
        }

        // 이메일 검증
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "올바른 이메일 주소를 입력해주세요.";
        }

        // 비밀번호 검증
        $passwordErrors = $this->validatePassword($data['password']);
        if (!empty($passwordErrors)) {
            $errors['password'] = implode(' ', $passwordErrors);
        }

        // 전화번호 검증
        if (!preg_match('/^010-\d{4}-\d{4}$/', $data['phone'])) {
            $errors['phone'] = "올바른 전화번호 형식이 아닙니다. (예: 010-1234-5678)";
        }

        return $errors;
    }

    /**
     * 중복 사용자 확인
     * 
     * @param string $userid 사용자 아이디
     * @param string $email 이메일
     * @return array 중복 오류 메시지 (없으면 빈 배열)
     */
    public function checkDuplicateUser(string $userid, string $email): array {
        $errors = [];

        // 아이디 중복 확인
        $stmt = $this->db->prepare("SELECT 1 FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute([$userid]);
        if ($stmt->fetchColumn()) {
            $errors['userid'] = "이미 사용 중인 아이디입니다.";
        }

        // 이메일 중복 확인
        $stmt = $this->db->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn()) {
            $errors['email'] = "이미 사용 중인 이메일입니다.";
        }

        return $errors;
    }

    /**
     * 사용자 등록
     * 
     * @param array $data 사용자 데이터
     * @return bool 등록 성공 여부
     * @throws Exception 등록 실패 시 예외 발생
     */
    public function registerUser(array $data): bool {
        try {
            // 비밀번호 암호화 옵션 (기본값 직접 지정)
            $options = [
                'memory_cost' => 65536,    // 기본값: 65536 KB
                'time_cost' => 4,          // 기본값: 4 패스
                'threads' => 1             // 기본값: 1 스레드
            ];
            
            // PASSWORD_ARGON2ID가 지원되는지 확인
            if (defined('PASSWORD_ARGON2ID')) {
                $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID, $options);
            } else if (defined('PASSWORD_ARGON2I')) {
                // 대체 알고리즘으로 ARGON2I 사용
                $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2I, $options);
            } else {
                // 최종 대체 알고리즘으로 BCRYPT 사용
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            if ($hashedPassword === false) {
                throw new Exception("비밀번호 암호화 실패");
            }

            // 트랜잭션 시작
            $this->db->beginTransaction();

            // 사용자 추가
            $stmt = $this->db->prepare("
                INSERT INTO users (name, userid, email, password, phone, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $result = $stmt->execute([
                $data['name'],
                $data['userid'],
                $data['email'],
                $hashedPassword,
                $data['phone']
            ]);

            if (!$result) {
                throw new Exception("회원가입 처리 중 오류가 발생했습니다.");
            }

            // 트랜잭션 완료
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // 트랜잭션 롤백
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('PDO 오류: ' . $e->getMessage());
            throw new Exception("서버 오류가 발생했습니다. 잠시 후 다시 시도해주세요.");
        } catch (Exception $e) {
            // 트랜잭션 롤백
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}

// 메인 실행 로직
try {
    // POST 요청 확인
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('잘못된 요청 방식입니다.');
    }

    // JSON 데이터 받기
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (!$data) {
        throw new Exception('잘못된 데이터 형식입니다.');
    }

    // 데이터베이스 연결
    $dbConnection = new DatabaseConnection();
    $db = $dbConnection->connect();

    // 회원가입 처리
    $registration = new UserRegistration($db);

    // 입력값 검증
    $validationErrors = $registration->validateInput($data);
    if (!empty($validationErrors)) {
        echo json_encode([
            'success' => false,
            'errors' => $validationErrors
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 중복 확인
    $duplicateErrors = $registration->checkDuplicateUser($data['userid'], $data['email']);
    if (!empty($duplicateErrors)) {
        echo json_encode([
            'success' => false,
            'errors' => $duplicateErrors
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 회원가입 실행
    if ($registration->registerUser($data)) {
        echo json_encode([
            'success' => true,
            'message' => '회원가입이 완료되었습니다.'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('회원가입 처리 중 오류가 발생했습니다.');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>