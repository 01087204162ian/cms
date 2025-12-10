<?php
require_once 'config.php';

function connect_db() {
    global $environment, $db_config;
    
    // 현재 환경의 설정 가져오기
    $config = $db_config[$environment];
    
    try {
        $pdo = new PDO(
            "mysql:host=" . $config['host'] . ";dbname=" . $config['db'],
            $config['user'],
            $config['pass'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4")
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
        
    } catch (PDOException $e) {
        error_log('Database Connection Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
}
?> 