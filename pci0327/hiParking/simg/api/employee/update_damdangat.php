<?php
header('Content-Type: application/json; charset=UTF-8');
require_once "../db.php"; // DB 연결 파일
session_start();
session_regenerate_id(true);

try {
    // POST 요청 확인
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('잘못된 요청 메소드입니다.');
    }

    // 필수 파라미터 확인
    if (!isset($_POST['num']) || !isset($_POST['damdangat'])) {
        throw new Exception('필수 파라미터가 누락되었습니다.');
    }

    $num = (int)$_POST['num'];
    $phone = trim($_POST['damdangat']);

    // 유효성 검사
    if ($num <= 0) {
        throw new Exception('유효하지 않은 번호입니다.');
    }

    if (empty($phone)) {
        throw new Exception('전화번호를 입력해주세요.');
    }

    // 전화번호 형식 검사 (숫자와 하이픈만 허용)
    if (!preg_match('/^[0-9-]+$/', $phone)) {
        throw new Exception('유효하지 않은 전화번호 형식입니다.');
    }

    // PDO prepared statement로 업데이트 실행
    $sql = "UPDATE users SET phone = :phone WHERE num = :num";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindValue(':num', $num, PDO::PARAM_INT);
    
    $result = $stmt->execute();

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => '전화번호가 성공적으로 업데이트되었습니다.'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception('업데이트할 데이터가 없습니다.');
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => '데이터베이스 오류: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}