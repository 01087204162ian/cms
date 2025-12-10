<?php
header('Content-Type: application/json; charset=utf-8');
include '../dbcon.php'; // DB 연결

// 사용자 입력
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'error' => '입력값 누락']);
    exit;
}

// 사용자 검색
$sql = "SELECT * FROM simg_admins WHERE username = ? AND is_active = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($admin = $result->fetch_assoc()) {
    // 비밀번호 확인
    if (password_verify($password, $admin['password_hash'])) {
        $token = base64_encode(random_bytes(32)); // 또는 JWT 사용
        echo json_encode([
            'success' => true,
            'token' => $token,
            'role' => $admin['role'],
            'name' => $admin['name']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => '비밀번호 불일치']);
    }
} else {
    echo json_encode(['success' => false, 'error' => '사용자 정보 없음']);
}
