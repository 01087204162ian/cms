<?php
ini_set('memory_limit', '512M');
set_time_limit(7200);

include "kjDaeri/php/encryption.php";
require_once '../../api/config/db_config.php';
// PDO 연결 사용
$pdo = getDbConnection();

$total_count = 0;
$success_count = 0;
$fail_count = 0;
$start_time = microtime(true);

$log_file = "./migration_log_" . date("Ymd_His") . ".txt";
$log_handle = fopen($log_file, "w");

function write_log($message) {
    global $log_handle;
    $time = date("Y-m-d H:i:s");
    fwrite($log_handle, "[$time] $message\n");
}

write_log("데이터 마이그레이션 시작");

// 레거시 mysql_ 함수를 PDO로 대체
$count_stmt = $pdo->query("SELECT COUNT(*) as total FROM `2012DaeriMember` WHERE push='1' AND sangtae='1'");
//$count_stmt = $pdo->query("SELECT COUNT(*) as total FROM `2012DaeriMember` WHERE push='4'");
$count_row = $count_stmt->fetch(PDO::FETCH_ASSOC);
$total_records = $count_row['total'];
write_log("총 마이그레이션 대상 레코드 (push='4'): $total_records");

$min_id = 0;
$max_id = 187629;
$batch_size = 200;
$batch_count = 0;
$insert_values = [];

function memory_status() {
    if (function_exists('memory_get_usage')) {
        $mem_usage = memory_get_usage(true);
        if ($mem_usage < 1024) return $mem_usage . " bytes";
        elseif ($mem_usage < 1048576) return round($mem_usage/1024, 2) . " KB";
        else return round($mem_usage/1048576, 2) . " MB";
    }
    return "알 수 없음";
}
//$query = "SELECT * FROM `2012DaeriMember` WHERE push='4'";
$query = "SELECT * FROM `2012DaeriMember` WHERE push='1' AND sangtae='1'";
$stmt = $pdo->prepare($query);
$stmt->execute(); // 매개변수 없이 실행
if (!$stmt) {
    write_log("오류: 데이터 가져오기 실패 - " . implode(" ", $pdo->errorInfo()));
    fclose($log_handle);
    die("데이터를 가져오는데 실패했습니다.");
}

$progress_step = max(1, intval($total_records / 100));
$last_progress = 0;

// PDO에서는 fetch()를 사용하여 레코드를 하나씩 가져옵니다.
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $total_count++;
    if ($total_count % $progress_step === 0) {
        $current_progress = intval(($total_count / $total_records) * 100);
        if ($current_progress > $last_progress) {
            $last_progress = $current_progress;
            $elapsed = microtime(true) - $start_time;
            $estimate_total = ($elapsed / $total_count) * $total_records;
            $remain = $estimate_total - $elapsed;
            write_log("진행률: $current_progress% 완료, 메모리: " . memory_status() . ", 남은 시간: " . gmdate("H:i:s", $remain));
        }
    }

    $error_occurred = false;
    $error_message = "";
    $encrypted_jumin = "";
    $jumin_hash = "";
    $encrypted_hphone = "";
    $phone_hash = ""; // 변수 초기화 추가

    if (isset($row['Jumin']) && !empty($row['Jumin'])) {
        $encrypted_jumin = encryptData($row['Jumin']);
        if ($encrypted_jumin === false) {
            $error_occurred = true;
            $error_message = "주민번호 암호화 실패";
        } else {
            $plain_jumin = decryptData($encrypted_jumin);
            if (!empty($plain_jumin)) {
                $jumin_hash = hash('sha1', $plain_jumin); // PHP 8.2에서는 sha1() 보다 hash() 사용 권장
            } else {
                $error_occurred = true;
                $error_message = "주민번호 복호화 실패";
            }
        }
    }

    if (!$error_occurred && isset($row['Hphone']) && !empty($row['Hphone'])) {
        $encrypted_hphone = encryptData($row['Hphone']);
        if ($encrypted_hphone === false) {
            $error_occurred = true;
            $error_message = "핸드폰번호 암호화 실패";
        } else {
            $plain_hphone = decryptData($encrypted_hphone);
            if (!empty($plain_hphone)) {
                $phone_hash = hash('sha1', $plain_hphone); // PHP 8.2에서는 sha1() 보다 hash() 사용 권장
            } else {
                $error_occurred = true;
                $error_message = "핸드폰 복호화 실패";
            }
        }
    }

    if (!$error_occurred) {
        // PHP 8.2에서는 배열에 데이터를 넣을 때 명확하게 타입 지정 필요
        $insert_values[] = [
            'num' => intval($row['num']),
            'moCertiNum' => intval($row['moCertiNum']),
            '2012DaeriCompanyNum' => intval($row['2012DaeriCompanyNum']),
            'InsuranceCompany' => $row['InsuranceCompany'],
            'CertiTableNum' => intval($row['CertiTableNum']),
            'Name' => $row['Name'],
            'Jumin' => $encrypted_jumin,
            'JuminHash' => $jumin_hash,
            'nai' => intval($row['nai']),
            'push' => intval($row['push']),
            'etag' => $row['etag'],
            'FirstStart' => $row['FirstStart'] ?: null,
            'state' => intval($row['state']),
            'cancel' => intval($row['cancel']),
            'sangtae' => $row['sangtae'],
            'Hphone' => $encrypted_hphone,
            'PhoneHash' => $phone_hash, // 대소문자 일치시킴
            'InputDay' => $row['InputDay'] ?: null,
            'OutPutDay' => $row['OutPutDay'] ?: null,
            'EndorsePnum' => intval($row['EndorsePnum']),
            'dongbuCerti' => $row['dongbuCerti'],
            'dongbuSelNumber' => $row['dongbuSelNumber'],
            'dongbusigi' => $row['dongbusigi'] ?: null,
            'dongbujeongi' => $row['dongbujeongi'] ?: null,
            'nabang_1' => $row['nabang_1'],
            'ch' => $row['ch'],
            'changeCom' => intval($row['changeCom']),
            'sPrem' => $row['sPrem'],
            'sago' => $row['sago'],
            'p_buho' => $row['p_buho'],
            'a6b' => intval($row['a6b']),
            'a7b' => intval($row['a7b']),
            'a8b' => $row['a8b'],
            'preminum1' => $row['preminum1'],
            'wdate' => $row['wdate'] ?: null,
            'endorse_day' => $row['endorse_day'] ?: null,
            'rate' => $row['rate'],
            'reasion' => $row['reasion'],
            'manager' => $row['manager'],
            'progress' => $row['progress']
        ];

        $batch_count++;

        if ($batch_count >= $batch_size) {
            execute_batch_insert($insert_values, $pdo);
            $insert_values = [];
            $batch_count = 0;
            usleep(100000);
        }
    } else {
        $fail_count++;
        write_log("오류: 레코드 ID " . $row['num'] . " 실패 - " . $error_message);
    }
}

if (count($insert_values) > 0) {
    execute_batch_insert($insert_values, $pdo);
}

function execute_batch_insert($values, $pdo) {
    global $success_count, $fail_count;
    if (empty($values)) return;

    try {
        // PDO 트랜잭션 시작
        $pdo->beginTransaction();
        
        // PDO 준비된 구문 사용 - PhoneHash 필드 추가
        $insert_sql = "INSERT INTO `DaeriMember` (
            `num`, `moCertiNum`, `2012DaeriCompanyNum`, `InsuranceCompany`, `CertiTableNum`,
            `Name`, `Jumin`, `JuminHash`, `nai`, `push`, `etag`, `FirstStart`, `state`, `cancel`, `sangtae`,
            `Hphone`, `phoneHash`, `InputDay`, `OutPutDay`, `EndorsePnum`, `dongbuCerti`, `dongbuSelNumber`,
            `dongbusigi`, `dongbujeongi`, `nabang_1`, `ch`, `changeCom`, `sPrem`, `sago`, `p_buho`,
            `a6b`, `a7b`, `a8b`, `preminum1`, `wdate`, `endorse_day`, `rate`, `reasion`, `manager`, `progress`
        ) VALUES (
            :num, :moCertiNum, :2012DaeriCompanyNum, :InsuranceCompany, :CertiTableNum,
            :Name, :Jumin, :JuminHash, :nai, :push, :etag, :FirstStart, :state, :cancel, :sangtae,
            :Hphone, :PhoneHash, :InputDay, :OutPutDay, :EndorsePnum, :dongbuCerti, :dongbuSelNumber,
            :dongbusigi, :dongbujeongi, :nabang_1, :ch, :changeCom, :sPrem, :sago, :p_buho,
            :a6b, :a7b, :a8b, :preminum1, :wdate, :endorse_day, :rate, :reasion, :manager, :progress
        )";

        $stmt = $pdo->prepare($insert_sql);
        
        foreach ($values as $row) {
            // 준비된 구문에 매개변수 바인딩 - PhoneHash 바인딩 추가
            $stmt->execute([
                ':num' => $row['num'],
                ':moCertiNum' => $row['moCertiNum'],
                ':2012DaeriCompanyNum' => $row['2012DaeriCompanyNum'],
                ':InsuranceCompany' => $row['InsuranceCompany'],
                ':CertiTableNum' => $row['CertiTableNum'],
                ':Name' => $row['Name'],
                ':Jumin' => $row['Jumin'],
                ':JuminHash' => $row['JuminHash'],
                ':nai' => $row['nai'],
                ':push' => $row['push'],
                ':etag' => $row['etag'],
                ':FirstStart' => $row['FirstStart'],
                ':state' => $row['state'],
                ':cancel' => $row['cancel'],
                ':sangtae' => $row['sangtae'],
                ':Hphone' => $row['Hphone'],
                ':PhoneHash' => $row['PhoneHash'], // PhoneHash 바인딩 추가
                ':InputDay' => $row['InputDay'],
                ':OutPutDay' => $row['OutPutDay'],
                ':EndorsePnum' => $row['EndorsePnum'],
                ':dongbuCerti' => $row['dongbuCerti'],
                ':dongbuSelNumber' => $row['dongbuSelNumber'],
                ':dongbusigi' => $row['dongbusigi'],
                ':dongbujeongi' => $row['dongbujeongi'],
                ':nabang_1' => $row['nabang_1'],
                ':ch' => $row['ch'],
                ':changeCom' => $row['changeCom'],
                ':sPrem' => $row['sPrem'],
                ':sago' => $row['sago'],
                ':p_buho' => $row['p_buho'],
                ':a6b' => $row['a6b'],
                ':a7b' => $row['a7b'],
                ':a8b' => $row['a8b'],
                ':preminum1' => $row['preminum1'],
                ':wdate' => $row['wdate'],
                ':endorse_day' => $row['endorse_day'],
                ':rate' => $row['rate'],
                ':reasion' => $row['reasion'],
                ':manager' => $row['manager'],
                ':progress' => $row['progress']
            ]);
            $success_count++;
        }
        
        // 트랜잭션 커밋
        $pdo->commit();
        
        $inserted_count = count($values);
        write_log("성공: $inserted_count 레코드 이전됨");
    } catch (PDOException $e) {
        // 오류 발생 시 롤백
        $pdo->rollBack();
        $fail_count += count($values);
        write_log("오류: 배치 삽입 실패 - " . $e->getMessage());
    }
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (float) $val; // PHP 8.2에서는 명시적 타입 변환 권장
    
    if ($last === 'g') $val *= 1024;
    if ($last === 'm') $val *= 1024;
    if ($last === 'k') $val *= 1024;
    
    return (int) $val; // 정수로 반환
}

$total_time = microtime(true) - $start_time;
$time_str = sprintf("%02d시간 %02d분 %02d초", floor($total_time/3600), floor(($total_time%3600)/60), $total_time%60);
write_log("마이그레이션 완료\n총 시간: $time_str\n총 레코드: $total_count\n성공: $success_count\n실패: $fail_count");
fclose($log_handle);

echo "마이그레이션 완료<br/>총 시간: $time_str<br/>총 레코드: $total_count<br/>성공: $success_count<br/>실패: $fail_count<br/>";
?>