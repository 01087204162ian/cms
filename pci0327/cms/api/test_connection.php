<?php
require_once 'config.php';
require_once 'db_connection.php';

try {
    $conn = connect_db();
    echo "Database connection successful\n";
    echo "Server info: " . $conn->server_info . "\n";
    echo "Host info: " . $conn->host_info . "\n";
    
    // 테이블 존재 여부 확인
    $result = $conn->query("SHOW TABLES LIKE 'questionnaire'");
    if ($result->num_rows > 0) {
        echo "Table 'questionnaire' exists\n";
    } else {
        echo "Table 'questionnaire' not found\n";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?> 