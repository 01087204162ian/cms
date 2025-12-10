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
    if (!isset($_POST['num']) || !isset($_POST['level'])) {
        throw new Exception('필수 파라미터가 누락되었습니다.');
    }

    $num = (int)$_POST['num'];
    $level = trim($_POST['level']);
    $manager = isset($_POST['manager']) ? trim($_POST['manager']) : '';

    // 유효성 검사
    if ($num <= 0) {
        throw new Exception('유효하지 않은 번호입니다.');
    }

    // level 값 검증 (예: 허용된 값들만 받기)
    $allowed_levels = ['admin', 'user', 'guest'];
    if (!in_array($level, $allowed_levels)) {
        throw new Exception('유효하지 않은 레벨입니다.');
    }

    $pdo->beginTransaction();

    try {
        // users 테이블 업데이트
		$sql = "UPDATE users SET level = :level, manager = :manager WHERE num = :num";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':level', $level, PDO::PARAM_STR);
		$stmt->bindValue(':num', $num, PDO::PARAM_INT);
		$stmt->bindValue(':manager', $manager, PDO::PARAM_STR);
		$result = $stmt->execute();
        

        if ($result && $stmt->rowCount() > 0) {
            // 로그 기록 (옵션)
            if (!empty($manager)) {
                $log_sql = "INSERT INTO level_change_log (user_num, old_level, new_level, changed_by, changed_at) 
                           SELECT :num, 
                                  (SELECT level FROM users WHERE num = :num), 
                                  :new_level, 
                                  :manager, 
                                  NOW()";
                $log_stmt = $pdo->prepare($log_sql);
                $log_stmt->bindValue(':num', $num, PDO::PARAM_INT);
                $log_stmt->bindValue(':new_level', $level, PDO::PARAM_STR);
                $log_stmt->bindValue(':manager', $manager, PDO::PARAM_STR);
                $log_stmt->execute();
            }

            $pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => '레벨이 성공적으로 업데이트되었습니다.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('업데이트할 데이터가 없습니다.');
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
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