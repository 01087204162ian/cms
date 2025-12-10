<?php
session_start();
session_unset();      // 세션 변수 제거
session_destroy();    // 세션 완전 제거
header("Location: ../login.php");  // 로그인 페이지로 이동
exit;
