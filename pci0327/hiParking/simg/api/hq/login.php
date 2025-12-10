<?php
include "../cors.php";
header("Content-Type: application/json");
require_once "../db.php"; // DB 연결 파일

session_start();
session_regenerate_id(true); // 세션 고정 공격 방지

// 요청 데이터 가져오기
$data = json_decode(file_get_contents("php://input"), true);
$mem_id = $data['mem_id'] ?? '';
$password = $data['password'] ?? '';

// 입력값 검증
if (empty($mem_id) || empty($password)) {
    echo json_encode(["success" => false, "message" => "아이디와 비밀번호를 입력하세요."]);
    exit;
}

// 데이터베이스에서 사용자 조회
$sql = "SELECT num, username, password FROM users WHERE mem_id = :mem_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":mem_id" => $mem_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "존재하지 않는 아이디입니다."]);
    exit;
}

// 비밀번호 검증
if (password_verify($password, $user["password"])) {
    $_SESSION['user_id'] = $user['num']; 
    $_SESSION['username'] = $user['username'];

    echo json_encode([
        "success" => true,
        "message" => "로그인 성공!",
        "user" => ["username" => $user['username']]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "아이디 또는 비밀번호가 잘못되었습니다."]);
}
?>
