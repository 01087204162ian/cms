 <?php
 $pdo = new PDO("mysql:host=localhost;dbname=pci0327;charset=utf8mb4", "pci0327", "pci@716671");
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$inputPassword = "securepassword"; // 사용자가 입력한 비밀번호
$username="testuser";
$sql = "SELECT password FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($inputPassword, $user['password'])) {
    echo "로그인 성공!";
} else {
    echo "로그인 실패!";
}
?>
