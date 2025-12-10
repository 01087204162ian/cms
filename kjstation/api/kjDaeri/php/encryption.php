<?php
/**
 * 양방향 암호화 함수 (PHP 4.4.9 및 MySQL 4.04 호환)
 * Mcrypt 라이브러리 사용
 */

// 암호화 키 (실제 서비스에서는 외부 설정 파일에 저장하는 것이 좋습니다)
define('ENCRYPT_KEY', 'your_secret_key_here');

/**
 * 문자열을 암호화하는 함수
 * 
 * @param string $plainText 암호화할 평문
 * @return string 암호화된 문자열 (base64 인코딩)
 */
function encryptData($plainText) {
    // 빈 문자열이면 그대로 반환
    if (empty($plainText)) {
        return $plainText;
    }
    
    // 암호화 알고리즘 및 모드 설정 (PHP 4.x 호환)
    $algorithm = MCRYPT_RIJNDAEL_256;
    $mode = MCRYPT_MODE_CBC;
    
    // 초기화 벡터(IV) 생성
    $iv_size = mcrypt_get_iv_size($algorithm, $mode);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
    // 암호화 수행
    $encrypted = mcrypt_encrypt($algorithm, ENCRYPT_KEY, $plainText, $mode, $iv);
    
    // IV와 암호화된 데이터 결합 후 base64 인코딩
    $encoded = base64_encode($iv . $encrypted);
    
    return $encoded;
}

/**
 * 암호화된 문자열을 복호화하는 함수
 * 
 * @param string $encryptedText 복호화할 암호문 (base64 인코딩)
 * @return string 복호화된 평문
 */
function decryptData($encryptedText) {
    // 빈 문자열이면 그대로 반환
    if (empty($encryptedText)) {
        return $encryptedText;
    }
    
    // 알고리즘 및 모드 설정 (암호화와 동일해야 함)
    $algorithm = MCRYPT_RIJNDAEL_256;
    $mode = MCRYPT_MODE_CBC;
    
    // base64 디코딩
    $decoded = base64_decode($encryptedText);
    
    // IV 크기 계산
    $iv_size = mcrypt_get_iv_size($algorithm, $mode);
    
    // IV 추출
    $iv = substr($decoded, 0, $iv_size);
    $encrypted_data = substr($decoded, $iv_size);
    
    // 복호화 수행
    $decrypted = mcrypt_decrypt($algorithm, ENCRYPT_KEY, $encrypted_data, $mode, $iv);
    
    // 패딩 제거
    $decrypted = rtrim($decrypted, "\0");
    
    return $decrypted;
}
?>