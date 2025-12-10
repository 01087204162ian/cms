<?php
include '../../../dbcon.php';
header("Content-Type: text/xml; charset=euc-kr");
echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
include '../../../api/kjDaeri/php/encryption.php';

// http_build_query 대체 함수
function build_query_string($data) {
    $query = '';
    foreach($data as $key => $value) {
        if($query != '') {
            $query .= '&';
        }
        $query .= urlencode($key) . '=' . urlencode($value);
    }
    return $query;
}

$DaeriCompanyNum = iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);	
$insuranceNum = iconv("utf-8","euc-kr",$_GET['insuranceNum']);
$CertiTableNum = iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
$endorseDay = iconv("utf-8","euc-kr",$_GET['endorseDay']);
$policyNum = iconv("utf-8","euc-kr",$_GET['policyNum']);
$userId = iconv("utf-8","euc-kr",$_GET['userId']);

for($i=0;$i<15;$i++){
    $a2b[$i] = iconv("utf-8","euc-kr",$_GET['a2b'.$i]);  // 성명
    $a3b[$i] = iconv("utf-8","euc-kr",$_GET['a3b'.$i]);  // 주민번호
    $a3b_encryp_jumin[$i] = encryptData($a3b[$i]);
    $jumin1 = $a3b[$i];
    include "../php/nai.php";
    $a6b[$i] = $age;  // 나이
    $a4b[$i] = iconv("utf-8","euc-kr",$_GET['a4b'.$i]);  // 핸드폰번호
    $a4b_encryp_jumin[$i] = encryptData($a4b[$i]);
    $a5b[$i] = iconv("utf-8","euc-kr",$_GET['a5b'.$i]);  // 탁송여부
    
    // ★ 추가 필드들 (문서2 참고)
    $a7b[$i] = $_GET['a6b'.$i];  // 통신사
    $a8b[$i] = $_GET['a7b'.$i];  // 명의자
    $a9b[$i] = iconv("utf-8","euc-kr",$_GET['a8b'.$i]);  // 주소
}	

$a2bLength = sizeof($a2b);
include "../php/endorseNumSerch.php";

$e_count = 0;
$remote_server_url = "https://pcikorea.com/kjDaeriApi/api/daeriMemberInsert.php";
$debug_log_file = '../../../logs/server_send_log.txt';

for($i=0;$i<$a2bLength;$i++){
    if(!$a3b[$i]) continue;
    
    // 기존 로컬 DB INSERT
    $insertSql = "INSERT INTO 2012DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
    $insertSql .= "Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum,wdate,endorse_day )";
    $insertSql .= "values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
    $insertSql .= "'$a2b[$i]','$a3b[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b[$i]','$endorseDay','$endorse_num',now(),'$endorse_day')";
    mysql_query($insertSql);
    
    $insertSql = "INSERT INTO DaeriMember (2012DaeriCompanyNum,InsuranceCompany,CertiTableNum, ";
    $insertSql .= "Name,Jumin,nai,push,etag,sangtae,Hphone,InPutDay,EndorsePnum,wdate,endorse_day )";
    $insertSql .= "values ('$DaeriCompanyNum','$insuranceNum','$CertiTableNum', ";
    $insertSql .= "'$a2b[$i]','$a3b_encryp_jumin[$i]','$a6b[$i]','1','$a5b[$i]','1','$a4b_encryp_jumin[$i]','$endorseDay','$endorse_num',now(),'$endorse_day')";
    mysql_query($insertSql);
    
    // ★★★ 다른 서버로 데이터 전송 ★★★
    $name_utf8 = iconv("euc-kr", "utf-8//IGNORE", $a2b[$i]);
    if (!$name_utf8) $name_utf8 = $a2b[$i];
    
    $address_utf8 = iconv("euc-kr", "utf-8//IGNORE", $a9b[$i]);
    if (!$address_utf8) $address_utf8 = $a9b[$i];
    
    $post_data = array(
        'DaeriCompanyNum' => iconv("euc-kr", "utf-8//IGNORE", $DaeriCompanyNum),
        'InsuranceCompany' => iconv("euc-kr", "utf-8//IGNORE", $insuranceNum),
        'CertiTableNum' => iconv("euc-kr", "utf-8//IGNORE", $CertiTableNum),
        'Name' => $name_utf8,
        'Jumin' => $a3b[$i],
        'nai' => $a6b[$i],
        'push' => '1',
        'etag' => $a5b[$i],
        'sangtae' => '1',
        'Hphone' => $a4b[$i],
        'InPutDay' => iconv("euc-kr", "utf-8//IGNORE", $endorseDay),
        'EndorsePnum' => iconv("euc-kr", "utf-8//IGNORE", $endorse_num),
        'endorse_day' => iconv("euc-kr", "utf-8//IGNORE", $endorse_day),
        // ★ 누락된 필드 추가
        'a6b' => $a7b[$i],  // 통신사
        'a7b' => $a8b[$i],  // 명의자
        'a8b' => $address_utf8  // 주소
    );
    
    $query_string = build_query_string($post_data);
    
    if (!function_exists('curl_init')) {
        @file_put_contents($debug_log_file, date('Y-m-d H:i:s') . " - cURL 없음\n", FILE_APPEND);
        continue;
    }
    
    $ch = curl_init();
    if (!$ch) {
        @file_put_contents($debug_log_file, date('Y-m-d H:i:s') . " - cURL 초기화 실패\n", FILE_APPEND);
        continue;
    }
    
    curl_setopt($ch, CURLOPT_URL, $remote_server_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    /*$log_msg = date('Y-m-d H:i:s') . " - 이름: {$name_utf8}\n";
    $log_msg .= "  HTTP Code: {$http_code}\n";
    $log_msg .= "  Response: {$response}\n";
    if ($curl_error) $log_msg .= "  Error: {$curl_error}\n";
    $log_msg .= "----------------------------------------\n";
    @file_put_contents($debug_log_file, $log_msg, FILE_APPEND);*/
    
    $e_count++;
    $message = '배서 신청 되었습니다!!';
}

include "../php/endorseNumStore.php";

echo "<data>\n";
echo "<enday>".$endorseDay.$esql."</enday>\n";
echo "<num>".$insertSql."</num>\n";
for($_u_=1;$_u_<15;$_u_++){
    echo "<name".$_u_.">".$a2b[$_u_]."</name".$_u_.">\n";
}
echo "<message>".$message."</message>\n";
echo "</data>";
?>