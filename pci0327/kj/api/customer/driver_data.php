<?php
session_start(); // 세션은 항상 가장 먼저 시작해야 함
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
include "../kjDaeri/php/calculatePersonalRate.php"; // 함수를 먼저 include
include "../kjDaeri/php/encryption.php";

// PDO 연결 사용
try {
    $pdo = getDbConnection();
    
    // 데이터 배열 초기화
    $data = array();
    
    // POST 데이터 검증 및 필터링
    $dNum = '';
    if (isset($_POST['cNum'])) {
        $dNum = trim($_POST['cNum']);
        // mysql_escape_string 대신 PDO의 prepared statements 사용
    }
    
    // 이름 검색 조건 처리
    $where2 = "";
    $params = [':dNum' => $dNum];
    
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $searchName = trim($_POST['name']);
    
        $where2 = " AND Name LIKE :searchName";
        $params[':searchName'] = '%' . $searchName . '%';
    }
    
    // SQL 쿼리 작성 - PDO prepared statements 사용
    $sql_data = "SELECT num, `2012DaeriCompanyNum`, InsuranceCompany, CertiTableNum, Name, Jumin, ";
    $sql_data .= "nai, push, etag, Hphone, dongbuCerti, cancel";
    $sql_data .= " FROM DaeriMember WHERE 2012DaeriCompanyNum = :dNum AND (push = '4' OR (push = '1' AND sangtae = '1')) $where2 ORDER BY nai ASC";
    
    $stmt = $pdo->prepare($sql_data);
    $stmt->execute($params);
    
    // 결과 처리
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // 인코딩 변환 - UTF-8로 작업하므로 필요시에만 처리
            // 복호화 처리
            $row['JuminDecrypted'] = "";
            $row['HphoneDecrypted'] = "";
            
            if (!empty($row['Jumin'])) {
                try {
                    $decrypted = decryptData($row['Jumin']);
                    if (!empty($decrypted)) {
                        $row['JuminDecrypted'] = $decrypted;
                        $row['Jumin'] = $decrypted;
                    }
                } catch (Exception $e) {
                    // 복호화 실패 시 오류 처리
                    error_log("주민번호 복호화 실패: " . $e->getMessage());
                }
            }
            
            if (!empty($row['Hphone'])) {
                try {
                    $decrypted = decryptData($row['Hphone']);
                    if (!empty($decrypted)) {
                        $row['HphoneDecrypted'] = $decrypted;
                        $row['Hphone'] = $decrypted;
                    }
                } catch (Exception $e) {
                    // 복호화 실패 시 오류 처리
                    error_log("휴대폰 번호 복호화 실패: " . $e->getMessage());
                }
            }
            
            // 개인 요율 계산
            $stmt_rate = $pdo->prepare("SELECT * FROM 2019rate WHERE policy = :policy AND jumin = :jumin");
            $stmt_rate->execute([
                ':policy' => $row['dongbuCerti'],
                ':jumin' => $row['Jumin']
            ]);
            
            $rate_row = $stmt_rate->fetch(PDO::FETCH_ASSOC);
            $discountRate = calculatePersonalRate($rate_row['rate'] ?? '');
            
            $row['personRateName'] = $discountRate['name'] ?? '';
            
            // 데이터 배열에 추가
            $data[] = $row;
        }
    }
    
    // 응답 데이터 구성
    $response = [
        "success" => true,
        "data" => $data
    ];
    
    // JSON 응답 출력 (PHP 8.2에서는 json_encode 사용)
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // 오류 처리
    $response = [
        "success" => false,
        "message" => "데이터베이스 오류: " . $e->getMessage()
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} finally {
    // PDO 연결은 자동으로 닫힘
    $pdo = null;
}
?>