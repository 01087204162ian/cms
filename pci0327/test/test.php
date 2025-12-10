
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=pci0327;charset=utf8mb4", "pci0327", "pci@716671");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$mem_id="simg0327";
    $username = "오성준";
    $password = "01087204162"; // 입력받은 비밀번호
    $phone = "01087204162";
    $level = "1";
    $contact = "sj@simg.kr";

    // 비밀번호 암호화
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (mem_id,username, password, phone, level, contact) 
            VALUES (:mem_id,:username, :password, :phone,  :level, :contact)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':mem_id'=>$mem_id,
		':username' => $username,
        ':password' => $hashedPassword,
        ':phone' => $phone,
        ':level' => $level,
        ':contact' => $contact
    ]);

    echo "사용자가 성공적으로 추가되었습니다.";

} catch (PDOException $e) {
    echo "에러 발생: " . $e->getMessage();
}
?>


