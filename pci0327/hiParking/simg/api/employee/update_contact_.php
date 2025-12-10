<?php
header('Content-Type: application/json; charset=UTF-8');
require_once "../db.php";
session_start();
session_regenerate_id(true);

try {
    // POST 요청 확인
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('잘못된 요청 메소드입니다.');
    }

    // 필수 파라미터 확인
    if (!isset($_POST['contact_']) || !isset($_POST['num'])) {
        throw new Exception('필수 파라미터가 누락되었습니다.');
    }

    $contact = trim($_POST['contact_']);
    $num = (int)$_POST['num'];

    // 유효성 검사
    if ($num <= 0) {
        throw new Exception('유효하지 않은 번호입니다.');
    }

    // 이메일 형식 검사
    if (!empty($contact) && !filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('유효하지 않은 이메일 형식입니다.');
    }

    // PDO prepared statement로 업데이트 실행
    $sql = "UPDATE users SET contact = :contact WHERE num = :num";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindValue(':contact', $contact, PDO::PARAM_STR);
    $stmt->bindValue(':num', $num, PDO::PARAM_INT);
    
    $result = $stmt->execute();

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => '이메일이 성공적으로 업데이트되었습니다.'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('업데이트할 데이터가 없습니다.');
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => '데이터베이스 오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}