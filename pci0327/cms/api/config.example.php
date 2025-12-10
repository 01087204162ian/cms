<?php
// 환경 설정
$environment = 'development'; // 'development' 또는 'production'

// 데이터베이스 설정
$db_config = [
    'development' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db'   => 'lincinsu_2025'
    ],
    'production' => [
        'host' => 'your_production_host',
        'user' => 'your_production_user',
        'pass' => 'your_production_password',
        'db'   => 'your_production_database'
    ]
];
?> 