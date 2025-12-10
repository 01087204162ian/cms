<?php
header("Content-Type: application/json; charset=utf-8");

// 암호화/복호화 함수 포함
include "../kjDaeri/php/encryption.php";

$jumin = "660327-1069017";

// 암호화 전 데이터 출력
echo "암호화 전: " . $jumin . "<br>";

// 암호화 수행
$encryJumin = encryptData($jumin);
echo "암호화 후: " . $encryJumin . "<br>";
echo "암호화 후:". "LHgHxoi4sw/PpH6b9xk1QC44cj9f1q2IaQDUVA4FNWV4tWxWZZFJ8j3kb1XgV94Zpz2/mLwDUsyLo2FMWfCq2g==";
// 복호화 테스트 추가
$decryJumin = decryptData($encryJumin);


echo "복호화 후: " . $decryJumin . "<br>";

// 암호화/복호화 검증
if ($jumin === $decryJumin) {
    echo "검증 결과: 성공 (원본과 복호화 결과가 일치함)";
} else {
    echo "검증 결과: 실패 (원본과 복호화 결과가 일치하지 않음)";
}
?>
