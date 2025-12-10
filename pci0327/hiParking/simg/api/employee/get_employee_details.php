<?php
header('Content-Type: application/json; charset=UTF-8');
require_once "../db.php"; // DB 연결 파일
session_start();
session_regenerate_id(true);

try {
    // id 파라미터 확인
    if (!isset($_GET['id'])) {
        throw new Exception('직원 번호(id)가 제공되지 않았습니다.');
    }

    $id = (int)$_GET['id'];
    if ($id <= 0) {
        throw new Exception('유효하지 않은 직원 번호입니다.');
    }

    // PDO prepared statement
    $sql = "SELECT 
                *
            FROM users 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception('해당하는 직원 정보를 찾을 수 없습니다.');
    }

   
    $response = array(
        'success' =>true,
        'message' => '직원 정보를 성공적으로 조회했습니다.',
        'data' => $employee
    );

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => '데이터베이스 오류: ' . $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}