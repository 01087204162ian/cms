<?php
session_start();

// 세션이 없으면 로그인 페이지로 리디렉트
if (!isset($_SESSION['username'])) {
    header("Location: /login.html"); // 로그인 페이지로 이동
    exit();
}
?>