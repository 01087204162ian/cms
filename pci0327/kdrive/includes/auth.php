<?php
session_start();
require_once 'db.php';

$db = new DatabaseConnection();
$pdo = $db->pdo;

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['admin'] = $user['username'];
    header("Location: ../dashboard.php");
    exit;
} else {
    echo "<script>alert('로그인 실패. 다시 시도하세요.'); history.back();</script>";
}
