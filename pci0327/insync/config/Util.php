<?php

class Util
{

    // 암호화 키와 알고리즘을 클래스 상수로 정의
    private const ENCRYPT_KEY = 'your_secret_key_here';
    private const CIPHER_ALGO = 'AES-256-CBC';


    /**
     * 임시 header 체크
     */
    public static function checkHeader()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            if ($headers['Authorization'] == "Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJraW0iLCJhdXRoIjoiUk9MRV9BRE1JTiIsImV4cCI6MTc3MDk1NzI1Nn0.2M9Gp46V2-nag3r96VWX9p6ZurrK5S9apjo5jTToI3U") {
                //echo "Authorization Header: " . $headers['Authorization'];
            } else {
                echo "apikey를 확인해주세요."; //. $headers['Authorization'];
                exit;
            }
        } else {
            echo "접근권한이 필요합니다.";
            exit;
        }
    }

    /**
     * 나이 계산 함수
     * @param string $birthday YYYYMMDD 형식의 생년월일
     * @param string $today YYYYMMDD 형식의 기준일
     * @return int 계산된 나이
     */
    public static function calculateAge($birthday, $today) {
        $cal_age = $today - $birthday;
        return (strlen($cal_age) == 6) ? floor(substr($cal_age, 0, 2)) : floor(substr($cal_age, 0, 1));
    }

    /**
     * 보험 플랜 결정 함수
     * @param int $planType 플랜 타입
     * @param int $age 나이
     * @return string 결정된 플랜 코드
     */
    public static function determinePlan($planType, $age) {
        if ($planType == 1) {
            if ($age <= 14) return "J";
            if ($age >= 15 && $age <= 69) return "A";
            return "D";
        } elseif ($planType == 2) {
            if ($age <= 14) return "K";
            if ($age >= 15 && $age <= 69) return "B";
            return "D";
        } else {
            if ($age <= 14) return "P";
            if ($age >= 15 && $age <= 69) return "O";
            return "D";
        }
    }

    /**
     * 주민번호 포맷팅 함수
     * @param string $jumin 주민번호
     * @return string 포맷팅된 주민번호
     */
    public static function formatJumin($jumin) {
        if (strlen($jumin) == 13) {
            return substr($jumin, 0, 6) . "-" . substr($jumin, 6, 7);
        }
        return $jumin;
    }

    public static function getTodayDate() {
        // 'Y'는 4자리 년도, 'm'은 2자리 월, 'd'는 2자리 일을 의미
        return date('Y-m-d');
    }

    /**
     * 문자열을 암호화하는 함수
     * 
     * @param string $plainText 암호화할 평문
     * @return string 암호화된 문자열 (base64 인코딩)
     */
    public static function encryptData(string $plainText): string {
        // 빈 문자열이면 그대로 반환
        if (empty($plainText)) {
            return $plainText;
        }
        
        // 키 해싱 (고정된 길이의 키 생성)
        $key = hash('sha256', self::ENCRYPT_KEY, true);
        
        // 초기화 벡터(IV) 생성 - OpenSSL은 16바이트 IV 사용
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER_ALGO));
        
        // 암호화 수행
        $encrypted = openssl_encrypt(
            $plainText,
            self::CIPHER_ALGO,
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
    public static function decryptData(string $encryptedText): string|false {
        // 빈 문자열이면 그대로 반환
        if (empty($encryptedText)) {
            return $encryptedText;
        }
        
        // 키 해싱 (암호화와 동일한 방식)
        $key = hash('sha256', self::ENCRYPT_KEY, true);
        
        // base64 디코딩
        $decoded = base64_decode($encryptedText);
        if ($decoded === false) {
            return false;
        }
        
        // IV 크기 계산
        $iv_length = openssl_cipher_iv_length(self::CIPHER_ALGO);
        
        // IV 및 암호화된 데이터 추출
        $iv = substr($decoded, 0, $iv_length);
        $encrypted_data = substr($decoded, $iv_length);
        
        // 복호화 수행
        $decrypted = openssl_decrypt(
            $encrypted_data,
            self::CIPHER_ALGO,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        return $decrypted;
    }
}
?>