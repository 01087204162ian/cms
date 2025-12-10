<?php
include "cors.php";
header("Content-Type: application/json");
require_once "db.php"; // DB 연결 파일

// 요청 데이터 가져오기
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// 입력값 검증
if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "아이디와 비밀번호를 입력하세요."]);
    exit;
}

// 데이터베이스에서 사용자 조회
$sql = "SELECT id, username, password FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([":username" => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode(["success" => true, "message" => "로그인 성공!", "user" => $user['username']]);
} else {
    echo json_encode(["success" => false, "message" => "아이디 또는 비밀번호가 잘못되었습니다."]);
}
?>
