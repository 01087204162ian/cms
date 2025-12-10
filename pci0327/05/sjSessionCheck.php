<?php
/**
 * 정리된 sjSessionCheck.php (운영 환경용)
 */
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'isValid' => false, 
        'message' => '잘못된 접근입니다.'
    ]);
    exit;
}

session_start();

$response = [
    'isValid' => false, 
    'loggedIn' => false, 
    'message' => '로그인이 필요합니다.'
];

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
    
    if (!empty($user['id']) && !empty($user['userid']) && !empty($user['name'])) {
        $sessionTimeout = 30 * 60; // 30분
        
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $sessionTimeout) {
                session_unset();
                session_destroy();
                $response['message'] = '세션이 만료되었습니다.';
            } else {
                $_SESSION['last_activity'] = time();
                $response = [
                    'isValid' => true,
                    'loggedIn' => true,
                    'message' => '로그인 상태입니다.',
                    'user' => [
                        'name' => $user['name'], 
                        'userid' => $user['userid']
                    ]
                ];
            }
        } else {
            $_SESSION['last_activity'] = time();
            $response = [
                'isValid' => true,
                'loggedIn' => true,
                'message' => '로그인 상태입니다.',
                'user' => [
                    'name' => $user['name'], 
                    'userid' => $user['userid']
                ]
            ];
        }
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>