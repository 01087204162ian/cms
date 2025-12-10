<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php"; 
ob_end_clean(); 


try {
    ob_start();
    header("Content-Type: application/json");

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once "db.php"; 
    ob_end_clean();

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        throw new Exception("JSON 데이터가 올바르지 않습니다.");
    }

    $username = $data['username'] ?? '';
    $userid = $data['mem_id'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $password = $data['password'] ?? '';
    $level = 3;

    // 입력값 검증
    if (empty($username) || empty($userid) || empty($email) || empty($phone) || empty($password)) {
        throw new Exception("모든 필드를 입력해야 합니다.");
    }
		// PHP 내부 문자 인코딩을 UTF-8로 설정 (멀티바이트 문자 처리 개선)
		mb_internal_encoding("UTF-8");

		// 입력값이 문자열인지 확인
		if (!is_string($username) || !preg_match("/^[가-힣]{2,5}$/u", $username)) {
			throw new Exception("이름은 2~5자의 한글만 가능합니다.");
		}

    if (!preg_match("/^[A-Za-z0-9]{6}$/", $userid)) {
        throw new Exception("아이디는 6자의 영문 또는 숫자로 입력하세요.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("올바른 이메일 형식을 입력하세요.");
    }

    if (!preg_match("/^01[0-9]-?[0-9]{3,4}-?[0-9]{4}$/", $phone)) {
        throw new Exception("올바른 전화번호 형식을 입력하세요. 예) 010-1234-5678 또는 01012345678");
    }

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password)) {
        throw new Exception("비밀번호는 영문, 숫자, 특수문자를 포함하여 8자 이상이어야 합니다.");
    }

    // 아이디 중복 검사
    $sql = "SELECT COUNT(*) FROM users WHERE mem_id = :userid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userid' => $userid]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("이미 사용 중인 아이디입니다.");
    }

    // 비밀번호 해싱
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 회원 정보 저장
    $sql = "INSERT INTO users (mem_id,username,password,phone,level, contact) VALUES (:userid,:username,:password,:phone, :level, :email )";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
		'userid' => $userid,
        'username' => $username,
         'password' => $hashed_password,
		 'phone' => $phone,
		'level' => $level,
        'email' => $email
    ]);

    echo json_encode(["success" => true, "message" => "회원가입이 완료되었습니다!"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "오류 발생: " . $e->getMessage()]);
}

?>
