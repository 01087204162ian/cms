<?php
/**
 * 양방향 암호화 함수 (PHP 8.2 호환)
 * OpenSSL 라이브러리 사용
 */
// 암호화 키 (실제 서비스에서는 외부 설정 파일에 저장하는 것이 좋습니다)
define('ENCRYPT_KEY', 'your_secret_key_here');
define('CIPHER_ALGO', 'AES-256-CBC');

/**
 * 문자열을 암호화하는 함수
 * 
 * @param string $plainText 암호화할 평문
 * @return string 암호화된 문자열 (base64 인코딩)
 */
function encryptData(string $plainText): string {
    // 빈 문자열이면 그대로 반환
    if (empty($plainText)) {
        return $plainText;
    }
    
    // 키 해싱 (고정된 길이의 키 생성)
    $key = hash('sha256', ENCRYPT_KEY, true);
    
    // 초기화 벡터(IV) 생성 - OpenSSL은 16바이트 IV 사용
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(CIPHER_ALGO));
    
    // 암호화 수행
    $encrypted = openssl_encrypt(
        $plainText,
        CIPHER_ALGO,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    // IV와 암호화된 데이터 결합 후 base64 인코딩
    $encoded = base64_encode($iv . $encrypted);
    
    return $encoded;
}

/**
 * 암호화된 문자열을 복호화하는 함수
 * 
 * @param string $encryptedText 복호화할 암호문 (base64 인코딩)
 * @return string|false 복호화된 평문 또는 실패 시 false
 */
function decryptData(string $encryptedText): string|false {
    // 빈 문자열이면 그대로 반환
    if (empty($encryptedText)) {
        return $encryptedText;
    }
    
    // 키 해싱 (암호화와 동일한 방식)
    $key = hash('sha256', ENCRYPT_KEY, true);
    
    // base64 디코딩
    $decoded = base64_decode($encryptedText);
    if ($decoded === false) {
        return false;
    }
    
    // IV 크기 계산
    $iv_length = openssl_cipher_iv_length(CIPHER_ALGO);
    
    // IV 및 암호화된 데이터 추출
    $iv = substr($decoded, 0, $iv_length);
    $encrypted_data = substr($decoded, $iv_length);
    
    // 복호화 수행
    $decrypted = openssl_decrypt(
        $encrypted_data,
        CIPHER_ALGO,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    return $decrypted;
}




/**
 * 해시 검증 함수
 * 
 * @param string $data 검증할 원본 데이터
 * @param string $hashedData 저장된 해시 데이터
 * @return bool 해시 일치 여부
 */
function verifyHash(string $data, string $hashedData): bool {
    // 솔트 추출 (앞 32자)
    $salt = substr($hashedData, 0, 32);
    
    // 해시 추출 (뒤 64자)
    $storedHash = substr($hashedData, 32);
    
    // 입력된 데이터로 동일한 방식의 해시 생성
    $calculatedHash = hash('sha256', $data . $salt);
    
    // 해시 비교
    return $calculatedHash === $storedHash;
}
?>
