<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "./php/encryption.php";

try {
    // PDO 연결 가져오기
    $pdo = getDbConnection();
    
    // 입력 데이터 검증 및 가져오기
    $num = isset($_POST['num']) ? trim($_POST['num']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
    // 응답 데이터 초기화
    $response = array(
        "success" => false,
        "message" => ""
    );
    
    // 필수 필드 확인
    if (empty($num) || empty($phone)) {
        $response['message'] = "필수 입력값이 누락되었습니다.";
    } else {
        // 휴대폰 번호 암호화
        $encryptedPhone = encryptData($phone);
        
        // Prepared Statement 사용하여 SQL 인젝션 방지
        $sql = "UPDATE DaeriMember SET Hphone = :phone WHERE num = :num";
        $stmt = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        $stmt->bindParam(':phone', $encryptedPhone, PDO::PARAM_STR);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        
        // 쿼리 실행
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "업데이트가 성공적으로 완료되었습니다.";
            $response['affectedRows'] = $stmt->rowCount();
        } else {
            $response['message'] = "업데이트 중 오류가 발생했습니다.";
        }
    }
    
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = "데이터베이스 오류가 발생했습니다.";
    
    // 개발 환경에서만 상세 오류 표시
    if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
        $response['error'] = $e->getMessage();
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = "일반 오류가 발생했습니다.";
    
    // 개발 환경에서만 상세 오류 표시
    if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
        $response['error'] = $e->getMessage();
    }
}

// JSON 응답 출력 (PHP 8.2에서는 json_encode 기본 지원)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// PDO 연결은 자동으로 종료되지만, 명시적으로 null 할당 가능
$pdo = null;
?>