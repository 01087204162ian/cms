<?php
session_start(); // 제일 위에 위치해야 함
header("Content-Type: application/json; charset=utf-8");

require_once '../../../api/config/db_config.php';


try {
    // POST 데이터 필터링 및 유효성 검사
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $pass_word = filter_input(INPUT_POST, 'pass_word', FILTER_DEFAULT) ?? '';
    
    if (empty($user_id) || empty($pass_word)) {
        $response = [
            "success" => false, 
            "message" => "아이디 또는 비밀번호가 누락되었습니다."
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // PDO로 데이터베이스 연결
    $pdo = getDbConnection();
    
    // 사용자 조회 - 준비된 문장(Prepared statement) 사용
    $stmt = $pdo->prepare("SELECT * FROM 2012Costomer WHERE mem_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    
    $row = $stmt->fetch();
    
    if (!$row) {
        $response = [
            "success" => false, 
            "message" => "존재하지 않는 사용자입니다."
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 권한 확인 및 비밀번호 일치 여부 확인
    // 참고: 현재 md5를 사용하고 있지만 보안을 위해 password_hash/password_verify로 전환 권장
    if ($row["permit"] == 1 && $row["passwd"] == md5($pass_word)) {
        $userId = $row["mem_id"];
        $cNum = $row["2012DaeriCompanyNum"];
        $readIs = $row["readIs"];
        $name = $row["name"];
        $userseq = md5($userId . "csj");
        $userIp = $_SERVER["REMOTE_ADDR"];
        
        // 세션 ID 재생성 (세션 고정 공격 방지)
        session_regenerate_id(true);
        
        // 세션 변수 설정 (PHP 8.2 호환)
        $_SESSION["userId"] = $userId;
        $_SESSION["userseq"] = $userseq;
        $_SESSION["userIp"] = $userIp;
        
        // 세션 타임아웃을 위한 마지막 활동 시간 저장
        $_SESSION["last_activity"] = time();
  $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
$cookieOptions = [
    'expires' => 0,
    'path' => '/',
    'domain' => '', // 도메인을 비워두거나 현재 도메인을 명시
    'secure' => $isSecure, // HTTPS 연결에 따라 자동 설정
    'httponly' => false, // JavaScript에서 접근할 수 있도록 false로 설정
    'samesite' => 'Lax'
];      
        // 쿠키 설정 - SameSite 및 보안 옵션 추가
    /*    $cookieOptions = [
            'expires' => 0,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ];*/

		// 쿠키 설정 - SameSite 및 보안 옵션 추가
        setcookie("userId", $userId, $cookieOptions);
		setcookie("userseq", $userseq, $cookieOptions);
        setcookie("host_id", $userId, $cookieOptions);
        setcookie("user_ip", $userIp, $cookieOptions);
        setcookie("small_sid", $userseq, $cookieOptions);
        
        setcookie("nAme", $name, $cookieOptions);
        setcookie("cNum", $cNum, $cookieOptions);
        setcookie("readIs", $readIs, $cookieOptions);
        
        // 대리운전회사 정보 조회
        $dNum = $cNum;
        
        // dNum_search.php 파일을 include 하는 대신 직접 쿼리 실행
        $companyStmt = $pdo->prepare("SELECT company FROM 2012DaeriCompany WHERE num = :dNum LIMIT 1");
        $companyStmt->bindParam(':dNum', $dNum, PDO::PARAM_STR);
        $companyStmt->execute();
        $Drow = $companyStmt->fetch();
        
        // login.js에서 필요로 하는 응답 데이터 추가
        $response = [
            "success" => true,
            "message" => "로그인 성공",
            "userId" => $userId,
            "userseq" => $userseq,
            "nAme" => $name,
            "cNum" => $cNum,
            "company" => $Drow['company'] ?? ''
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        $response = [
            "success" => false,
            "message" => "비밀번호가 일치하지 않거나 권한이 없습니다."
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (PDOException $e) {
    // 보안을 위해 실제 오류 메시지는 로그에만 남기고 사용자에게는 일반적인 오류 메시지 표시
    error_log("로그인 오류: " . $e->getMessage());
    
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요."
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
} catch (Exception $e) {
    error_log("일반 오류: " . $e->getMessage());
    
    $response = [
        "success" => false,
        "message" => "오류가 발생했습니다. 관리자에게 문의하세요."
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
?>