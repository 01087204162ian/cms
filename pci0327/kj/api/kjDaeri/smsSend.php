<?php
header("Content-Type: application/json; charset=utf-8");
require_once '../../../api/config/db_config.php';
require_once '../smsApi/smsAligo.php';
// PDO 연결 가져오기
$pdo = getDbConnection();

// POST 데이터 가져오기
$d_hphone = isset($_POST['receiver']) ? $_POST['receiver'] : ''; 
$smsContents = isset($_POST['message']) ? $_POST['message'] : ''; 


// 회사 전화번호 설정
$company_tel = "070-7841-5962";
list($sphone1, $sphone2, $sphone3) = explode("-", $company_tel);

// 핸드폰 번호 분리
list($hphone1, $hphone2, $hphone3) = explode("-", $d_hphone);
$LastTime = date('YmdHis');

// 기본 설정값
$dong_db = "preminum";
$send_name = 'preminum';
$inSuranceCom = 10;

// smsContents를 msg 변수로 사용
$msg = $smsContents;

try {

	// 과거 무자 발송 방식이었고 
    $stmt = $pdo->prepare("INSERT INTO SMSData (SendId, Rphone1, Rphone2, Rphone3, Sphone1, Sphone2, Sphone3, 
                          SendName, RecvName, Msg, url, Rdate, Rtime, Result, kind, ErrCode, Retry1, Retry2, LastTime) 
                          VALUES (:sendId, :rphone1, :rphone2, :rphone3, :sphone1, :sphone2, :sphone3, 
                          :sendName, :recvName, :msg, :url, :rdate, :rtime, :result, :kind, :errCode, :retry1, :retry2, :lastTime)");
    
    $stmt->bindValue(':sendId', 'csdrive');
    $stmt->bindValue(':rphone1', $hphone1);
    $stmt->bindValue(':rphone2', $hphone2);
    $stmt->bindValue(':rphone3', $hphone3);
    $stmt->bindValue(':sphone1', $sphone1);
    $stmt->bindValue(':sphone2', $sphone2);
    $stmt->bindValue(':sphone3', $sphone3);
    $stmt->bindValue(':sendName', $send_name);
    $stmt->bindValue(':recvName', 'CS');
    $stmt->bindValue(':msg', $msg);
    $stmt->bindValue(':url', '');
    $stmt->bindValue(':rdate', '');
    $stmt->bindValue(':rtime', '');
    $stmt->bindValue(':result', '0');
    $stmt->bindValue(':kind', 's');
    $stmt->bindValue(':errCode', 0);
    $stmt->bindValue(':retry1', 0);
    $stmt->bindValue(':retry2', 0);
    $stmt->bindValue(':lastTime', $LastTime);
    
    $insert_result = $stmt->execute();


	// 알리고 문자 방식 2025-05-13  업그레이드 



		$receiver=$hphone1.$hphone2.$hphone3;
	// 데이터 설정
		$sendData = [
			"receiver" => $receiver,
			"msg" => $msg,
			"testmode_yn" => "N"
		];

// 함수 호출
		$result = sendAligoSms($sendData);
	//
} catch (PDOException $e) {
    // 오류 처리
    $response = array(
        "success" => false,
        "error" => $e->getMessage()
    );
    echo json_encode($response);
    exit;
}

// 응답 데이터 구성
$response = array(
    "success" => true,
    "d_hphone" => $d_hphone,
	'sendData'=>$sendData,
    "company_tel" => $company_tel,
	"result"=>$result
);

// JSON 변환 후 출력 (PHP 8.2에서는 json_encode 사용)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 연결 닫기는 PDO에서 필요 없음 (스크립트 종료 시 자동으로 닫힘)
?>