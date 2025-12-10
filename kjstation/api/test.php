<?php
ini_set('memory_limit', '512M');
set_time_limit(7200);

include "kjDaeri/php/encryption.php";
include 'dbcon.php';

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

$count_query = "SELECT COUNT(*) as total FROM `2012DaeriMember` ";
//$count_query = "SELECT COUNT(*) as total FROM `2012DaeriMember` WHERE push='1' AND sangtae='1'";
$count_result = mysql_query($count_query);
$count_row = mysql_fetch_assoc($count_result);
$total_records = $count_row['total'];
write_log("총 마이그레이션 대상 레코드 (push='4'): $total_records");

$min_id = 0;
$max_id = 187629;
$batch_size = 200;
$batch_count = 0;
$insert_values = array();

function memory_status() {
    if (function_exists('memory_get_usage')) {
        $mem_usage = memory_get_usage(true);
        if ($mem_usage < 1024) return $mem_usage . " bytes";
        elseif ($mem_usage < 1048576) return round($mem_usage/1024, 2) . " KB";
        else return round($mem_usage/1048576, 2) . " MB";
    }
    return "알 수 없음";
}

$query = "SELECT * FROM `2012DaeriMember`  ";
//$query = "SELECT * FROM `2012DaeriMember` WHERE num BETWEEN $min_id AND $max_id AND push='1' AND sangtae='1'";
$result = mysql_query($query);

if (!$result) {
    write_log("오류: 데이터 가져오기 실패 - " . mysql_error());
    fclose($log_handle);
    die("데이터를 가져오는데 실패했습니다.");
}

$progress_step = max(1, intval($total_records / 100));
$last_progress = 0;

while ($row = mysql_fetch_assoc($result)) {
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

    if (isset($row['Jumin'])) {
        $encrypted_jumin = encryptData($row['Jumin']);
        if ($encrypted_jumin === false) {
            $error_occurred = true;
            $error_message = "주민번호 암호화 실패";
        } else {
            $plain_jumin = decryptData($encrypted_jumin);
            if (!empty($plain_jumin)) {
                $jumin_hash = sha1($plain_jumin);
            } else {
                $error_occurred = true;
                $error_message = "주민번호 복호화 실패";
            }
        }
    }

    if (!$error_occurred && isset($row['Hphone'])) {
        $encrypted_hphone = encryptData($row['Hphone']);
        if ($encrypted_hphone === false) {
            $error_occurred = true;
            $error_message = "핸드폰번호 암호화 실패";
        }
    }

    if (!$error_occurred) {
        $insert_values[] = "(
            " . intval($row['num']) . ",
            " . intval($row['moCertiNum']) . ",
            " . intval($row['2012DaeriCompanyNum']) . ",
            '" . mysql_real_escape_string($row['InsuranceCompany']) . "',
            " . intval($row['CertiTableNum']) . ",
            '" . mysql_real_escape_string($row['Name']) . "',
            '" . mysql_real_escape_string($encrypted_jumin) . "',
            '" . mysql_real_escape_string($jumin_hash) . "',
            " . intval($row['nai']) . ",
            " . intval($row['push']) . ",
            '" . mysql_real_escape_string($row['etag']) . "',
            " . ($row['FirstStart'] ? "'" . $row['FirstStart'] . "'" : "NULL") . ",
            " . intval($row['state']) . ",
            " . intval($row['cancel']) . ",
            '" . mysql_real_escape_string($row['sangtae']) . "',
            '" . mysql_real_escape_string($encrypted_hphone) . "',
            " . ($row['InputDay'] ? "'" . $row['InputDay'] . "'" : "NULL") . ",
            " . ($row['OutPutDay'] ? "'" . $row['OutPutDay'] . "'" : "NULL") . ",
            " . intval($row['EndorsePnum']) . ",
            '" . mysql_real_escape_string($row['dongbuCerti']) . "',
            '" . mysql_real_escape_string($row['dongbuSelNumber']) . "',
            " . ($row['dongbusigi'] ? "'" . $row['dongbusigi'] . "'" : "NULL") . ",
            " . ($row['dongbujeongi'] ? "'" . $row['dongbujeongi'] . "'" : "NULL") . ",
            '" . mysql_real_escape_string($row['nabang_1']) . "',
            '" . mysql_real_escape_string($row['ch']) . "',
            " . intval($row['changeCom']) . ",
            '" . mysql_real_escape_string($row['sPrem']) . "',
            '" . mysql_real_escape_string($row['sago']) . "',
            '" . mysql_real_escape_string($row['p_buho']) . "',
            " . intval($row['a6b']) . ",
            " . intval($row['a7b']) . ",
            '" . mysql_real_escape_string($row['a8b']) . "',
            '" . mysql_real_escape_string($row['preminum1']) . "',
            " . ($row['wdate'] ? "'" . $row['wdate'] . "'" : "NULL") . ",
            " . ($row['endorse_day'] ? "'" . $row['endorse_day'] . "'" : "NULL") . ",
            '" . mysql_real_escape_string($row['rate']) . "',
            '" . mysql_real_escape_string($row['reasion']) . "',
            '" . mysql_real_escape_string($row['manager']) . "',
            '" . mysql_real_escape_string($row['progress']) . "'
        )";

        $batch_count++;

        if ($batch_count >= $batch_size) {
            execute_batch_insert($insert_values);
            $insert_values = array();
            $batch_count = 0;
            usleep(100000);
        }
    } else {
        $fail_count++;
        write_log("오류: 레코드 ID " . $row['num'] . " 실패 - " . $error_message);
    }
}

if (count($insert_values) > 0) {
    execute_batch_insert($insert_values);
}

function execute_batch_insert($values) {
    global $success_count, $fail_count;
    if (empty($values)) return;

    $insert_sql = "INSERT INTO `DaeriMember` (
        `num`, `moCertiNum`, `2012DaeriCompanyNum`, `InsuranceCompany`, `CertiTableNum`,
        `Name`, `Jumin`, `JuminHash`, `nai`, `push`, `etag`, `FirstStart`, `state`, `cancel`, `sangtae`,
        `Hphone`, `InputDay`, `OutPutDay`, `EndorsePnum`, `dongbuCerti`, `dongbuSelNumber`,
        `dongbusigi`, `dongbujeongi`, `nabang_1`, `ch`, `changeCom`, `sPrem`, `sago`, `p_buho`,
        `a6b`, `a7b`, `a8b`, `preminum1`, `wdate`, `endorse_day`, `rate`, `reasion`, `manager`, `progress`
    ) VALUES " . implode(", ", $values);

    $result = mysql_query($insert_sql);
    if ($result) {
        $inserted_count = count($values);
        $success_count += $inserted_count;
        write_log("성공: $inserted_count 레코드 이전됨");
    } else {
        $fail_count += count($values);
        write_log("오류: 배치 삽입 실패 - " . mysql_error());
    }
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

$total_time = microtime(true) - $start_time;
$time_str = sprintf("%02d시간 %02d분 %02d초", floor($total_time/3600), floor(($total_time%3600)/60), $total_time%60);
write_log("마이그레이션 완료\n총 시간: $time_str\n총 레코드: $total_count\n성공: $success_count\n실패: $fail_count");
fclose($log_handle);

echo "마이그레이션 완료<br/>총 시간: $time_str<br/>총 레코드: $total_count<br/>성공: $success_count<br/>실패: $fail_count<br/>";
?>
