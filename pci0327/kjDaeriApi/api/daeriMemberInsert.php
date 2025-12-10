<?php
header("Content-Type: text/html; charset=utf-8");
$log_file = 'receive_log.txt';

require_once '../../api/config/db_config.php';

try {
    $pdo = getDbConnection();
    
    $data = [
        'DaeriCompanyNum' => $_POST['DaeriCompanyNum'] ?? '',
        'InsuranceCompany' => $_POST['InsuranceCompany'] ?? '',
        'CertiTableNum' => $_POST['CertiTableNum'] ?? '',
        'Name' => $_POST['Name'] ?? '',
        'Jumin' => $_POST['Jumin'] ?? '',
        'nai' => $_POST['nai'] ?? '',
        'push' => $_POST['push'] ?? '',
        'etag' => $_POST['etag'] ?? '',
        'sangtae' => $_POST['sangtae'] ?? '',
        'Hphone' => $_POST['Hphone'] ?? '',
        'InPutDay' => $_POST['InPutDay'] ?? '',
        'EndorsePnum' => $_POST['EndorsePnum'] ?? '',
        'endorse_day' => $_POST['endorse_day'] ?? '',
        'a6b' => $_POST['a6b'] ?? '',  // 통신사
        'a7b' => $_POST['a7b'] ?? '',  // 명의자
        'a8b' => $_POST['a8b'] ?? ''   // 주소
    ];
    
    $log_msg = date('Y-m-d H:i:s') . " - 데이터 수신: " . $data['Name'] . "\n";
    file_put_contents($log_file, $log_msg, FILE_APPEND);
    
    $sql = "INSERT INTO 2012DaeriMember (
                2012DaeriCompanyNum, InsuranceCompany, CertiTableNum, 
                Name, Jumin, nai, push, etag, sangtae, Hphone, 
                InPutDay, EndorsePnum, wdate, endorse_day,
                a6b, a7b, a8b
            ) VALUES (
                :DaeriCompanyNum, :InsuranceCompany, :CertiTableNum, 
                :Name, :Jumin, :nai, :push, :etag, :sangtae, :Hphone, 
                :InPutDay, :EndorsePnum, NOW(), :endorse_day,
                :a6b, :a7b, :a8b
            )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    
    echo "SUCCESS";
    file_put_contents($log_file, "  -> 저장 성공 (ID: " . $pdo->lastInsertId() . ")\n\n", FILE_APPEND);
    
} catch(PDOException $e) {
    $error_msg = "ERROR: " . $e->getMessage();
    echo $error_msg;
    file_put_contents($log_file, "  -> " . $error_msg . "\n\n", FILE_APPEND);
}
?>