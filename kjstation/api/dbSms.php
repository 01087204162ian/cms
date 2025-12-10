<?php
// PHP 4 호환 설정
if (function_exists('ini_set')) {
    @ini_set('memory_limit', '512M');
}
@set_time_limit(7200);

include 'dbcon.php';

$total_count = 0;
$success_count = 0;
$fail_count = 0;
$start_time = microtime(true); // PHP 4에서도 지원됨

$log_file = "./sms_data_export_log_" . date("Ymd_His") . ".txt";
$log_handle = fopen($log_file, "w");

// SQL 파일 생성
$sql_file = "./SMSData_export_" . date("Ymd_His") . ".sql";
$sql_handle = fopen($sql_file, "w");

// SQL 파일 헤더 작성
fwrite($sql_handle, "-- SMSData 데이터 내보내기\n");
fwrite($sql_handle, "-- 생성일: " . date("Y-m-d H:i:s") . "\n\n");

// 테이블 생성 SQL
fwrite($sql_handle, "-- 테이블 구조\n");
fwrite($sql_handle, "CREATE TABLE IF NOT EXISTS `SMSData` (\n");
fwrite($sql_handle, "  `SeqNo` int(11) NOT NULL auto_increment,\n");
fwrite($sql_handle, "  `InDate` varchar(8) NOT NULL default '',\n");
fwrite($sql_handle, "  `InTime` varchar(6) default NULL,\n");
fwrite($sql_handle, "  `Member` int(11) default NULL,\n");
fwrite($sql_handle, "  `SendId` varchar(16) NOT NULL default '',\n");
fwrite($sql_handle, "  `SendName` varchar(32) NOT NULL default '',\n");
fwrite($sql_handle, "  `Rphone1` varchar(16) NOT NULL default '',\n");
fwrite($sql_handle, "  `Rphone2` varchar(4) default NULL,\n");
fwrite($sql_handle, "  `Rphone3` varchar(4) default NULL,\n");
fwrite($sql_handle, "  `RecvName` char(3) default NULL,\n");
fwrite($sql_handle, "  `Sphone1` varchar(16) default NULL,\n");
fwrite($sql_handle, "  `Sphone2` varchar(4) default NULL,\n");
fwrite($sql_handle, "  `Sphone3` varchar(4) default NULL,\n");
fwrite($sql_handle, "  `Msg` varchar(120) default NULL,\n");
fwrite($sql_handle, "  `Url` varchar(80) default NULL,\n");
fwrite($sql_handle, "  `Rdate` varchar(8) default NULL,\n");
fwrite($sql_handle, "  `Rtime` varchar(8) default NULL,\n");
fwrite($sql_handle, "  `Result` char(1) default '0',\n");
fwrite($sql_handle, "  `Kind` char(1) default 'S',\n");
fwrite($sql_handle, "  `ErrCode` int(11) default NULL,\n");
fwrite($sql_handle, "  `Retry1` int(11) default NULL,\n");
fwrite($sql_handle, "  `Retry2` int(11) default NULL,\n");
fwrite($sql_handle, "  `LastTime` varchar(14) default NULL,\n");
fwrite($sql_handle, "  `company` int(11) default NULL,\n");
fwrite($sql_handle, "  `ssang_c_num` bigint(20) default NULL,\n");
fwrite($sql_handle, "  `endorse_num` int(11) default NULL,\n");
fwrite($sql_handle, "  `userid` varchar(30) default NULL,\n");
fwrite($sql_handle, "  `get` int(11) default '2',\n");
fwrite($sql_handle, "  `preminum` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `preminum2` varchar(10) NOT NULL default '',\n");
fwrite($sql_handle, "  `c_preminum` varchar(30) NOT NULL default '',\n");
fwrite($sql_handle, "  `2012DaeriMemberNum` int(11) default NULL,\n");
fwrite($sql_handle, "  `2012DaeriCompanyNum` int(11) default NULL,\n");
fwrite($sql_handle, "  `policyNum` varchar(30) default NULL,\n");
fwrite($sql_handle, "  `endorse_day` date default NULL,\n");
fwrite($sql_handle, "  `damdanga` int(11) default NULL,\n");
fwrite($sql_handle, "  `push` int(2) default NULL,\n");
fwrite($sql_handle, "  `insuranceCom` char(2) default NULL,\n");
fwrite($sql_handle, "  `joong` char(3) default '2',\n");
fwrite($sql_handle, "  `jeongsan` int(11) NOT NULL default '2',\n");
fwrite($sql_handle, "  `juso` text,\n");
fwrite($sql_handle, "  `qboard` char(2) default NULL,\n");
fwrite($sql_handle, "  `dagun` char(1) default '1',\n");
fwrite($sql_handle, "  `manager` varchar(10) default NULL,\n");
fwrite($sql_handle, "  PRIMARY KEY  (`SeqNo`),\n");
fwrite($sql_handle, "  UNIQUE KEY `SeqNo` (`SeqNo`),\n");
fwrite($sql_handle, "  KEY `Result_Kind` (`Result`,`Kind`),\n");
fwrite($sql_handle, "  KEY `RTime` (`Rtime`),\n");
fwrite($sql_handle, "  KEY `Rphone` (`Rphone1`,`Rphone2`,`Rphone3`),\n");
fwrite($sql_handle, "  KEY `idx_endorse_day` (`endorse_day`),\n");
fwrite($sql_handle, "  KEY `idx_daeri_member` (`2012DaeriMemberNum`),\n");
fwrite($sql_handle, "  KEY `idx_daeri_company` (`2012DaeriCompanyNum`),\n");
fwrite($sql_handle, "  KEY `idx_policy` (`policyNum`)\n");
fwrite($sql_handle, ") ENGINE=MyISAM DEFAULT CHARSET=utf8;\n\n");

// 테이블 컬럼 정의
$table_columns = array(
    'SeqNo',
    'InDate',
    'InTime',
    'Member',
    'SendId',
    'SendName',
    'Rphone1',
    'Rphone2',
    'Rphone3',
    'RecvName',
    'Sphone1',
    'Sphone2',
    'Sphone3',
    'Msg',
    'Url',
    'Rdate',
    'Rtime',
    'Result',
    'Kind',
    'ErrCode',
    'Retry1',
    'Retry2',
    'LastTime',
    'company',
    'ssang_c_num',
    'endorse_num',
    'userid',
    'get',
    'preminum',
    'preminum2',
    'c_preminum',
    '2012DaeriMemberNum',
    '2012DaeriCompanyNum',
    'policyNum',
    'endorse_day',
    'damdanga',
    'push',
    'insuranceCom',
    'joong',
    'jeongsan',
    'juso',
    'qboard',
    'dagun',
    'manager'
);

// 테이블 컬럼 수 확인
$column_count = count($table_columns);
write_log("테이블 컬럼 수: $column_count");

function write_log($message) {
    global $log_handle;
    $time = date("Y-m-d H:i:s");
    fwrite($log_handle, "[$time] $message\n");
}

write_log("데이터 내보내기 시작");

// 총 레코드 수 확인
$count_query = "SELECT COUNT(*) as total FROM `SMSData` WHERE endorse_day >= '2025/01/01'";
$count_result = mysql_query($count_query);
$count_row = mysql_fetch_assoc($count_result);
$total_records = $count_row['total'];
write_log("총 내보내기 대상 레코드: $total_records");

$batch_size = 200;
$batch_count = 0;
$is_first_record = true;

function memory_status() {
    // PHP 4에서는 memory_get_usage 함수가 없을 수 있음
    if (function_exists('memory_get_usage')) {
        $mem_usage = memory_get_usage(true);
        if ($mem_usage < 1024) return $mem_usage . " bytes";
        elseif ($mem_usage < 1048576) return round($mem_usage/1024, 2) . " KB";
        else return round($mem_usage/1048576, 2) . " MB";
    }
    return "알 수 없음";
}

// 테이블의 최소/최대 ID 확인
$range_query = "SELECT MIN(SeqNo) as min_id, MAX(SeqNo) as max_id FROM `SMSData`";
$range_result = mysql_query($range_query);
$range_row = mysql_fetch_assoc($range_result);
$min_id = $range_row['min_id'];
$max_id = $range_row['max_id'];
write_log("레코드 ID 범위: $min_id ~ $max_id");

// 배치 처리를 위한 준비
$current_min_id = $min_id;
$batch_step = 1000; // 한 번에 가져올 ID 범위

// INSERT 구문 시작
fwrite($sql_handle, "-- 데이터 삽입\n");

// 컬럼 이름을 SQL에 포함
$columns_sql = "INSERT INTO `SMSData` (";
$columns_sql .= "`" . implode("`, `", $table_columns) . "`";
$columns_sql .= ") VALUES\n";
fwrite($sql_handle, $columns_sql);

$progress_step = max(1, intval($total_records / 100));
$last_progress = 0;
$processed_records = 0;

// 배치 처리 반복
while ($current_min_id <= $max_id) {
    $current_max_id = min($current_min_id + $batch_step - 1, $max_id);
    
    $query = "SELECT * FROM `SMSData` WHERE SeqNo BETWEEN $current_min_id AND $current_max_id  AND endorse_day >= '2025/01/01'ORDER BY SeqNo";
    $result = mysql_query($query);
    
    if (!$result) {
        write_log("오류: 데이터 가져오기 실패 - " . mysql_error());
        fclose($log_handle);
        fclose($sql_handle);
        die("데이터를 가져오는데 실패했습니다.");
    }
    
    while ($row = mysql_fetch_assoc($result)) {
        $total_count++;
        $processed_records++;
        
        if ($processed_records % $progress_step === 0) {
            $current_progress = intval(($processed_records / $total_records) * 100);
            if ($current_progress > $last_progress) {
                $last_progress = $current_progress;
                $elapsed = microtime(true) - $start_time;
                $estimate_total = ($elapsed / $processed_records) * $total_records;
                $remain = $estimate_total - $elapsed;
                // PHP 4 호환 시간 표시 포맷팅
                $remain_hr = floor($remain / 3600);
                $remain_min = floor(($remain % 3600) / 60);
                $remain_sec = floor($remain % 60);
                $remain_str = sprintf("%02d:%02d:%02d", $remain_hr, $remain_min, $remain_sec);
                write_log("진행률: $current_progress% 완료, 메모리: " . memory_status() . ", 남은 시간: " . $remain_str);
            }
        }
        
        // 인코딩 변환 (한글 처리)
        $send_name = isset($row['SendName']) ? @iconv("EUC-KR", "UTF-8", $row['SendName']) : "";
        if ($send_name === false) $send_name = $row['SendName']; // 변환 실패시 원본 유지
        
        $recv_name = isset($row['RecvName']) ? @iconv("EUC-KR", "UTF-8", $row['RecvName']) : "";
        if ($recv_name === false) $recv_name = $row['RecvName']; // 변환 실패시 원본 유지
        
        $msg = isset($row['Msg']) ? @iconv("EUC-KR", "UTF-8", $row['Msg']) : "";
        if ($msg === false) $msg = $row['Msg']; // 변환 실패시 원본 유지
        
        $juso = isset($row['juso']) ? @iconv("EUC-KR", "UTF-8", $row['juso']) : "";
        if ($juso === false) $juso = $row['juso']; // 변환 실패시 원본 유지
        
        // SQL 구문 생성
        $sql_values = "";
        
        if (!$is_first_record) {
            $sql_values .= ",\n";
        } else {
            $is_first_record = false;
        }
        
        $sql_values .= "(";
        
        // 모든 컬럼에 대해 값 설정
        $values = array();
        
        // SeqNo
        $values[] = intval($row['SeqNo']);
        
        // InDate
        $values[] = (!empty($row['InDate'])) ? "'" . mysql_real_escape_string($row['InDate']) . "'" : "''";
        
        // InTime
        $values[] = (!empty($row['InTime'])) ? "'" . mysql_real_escape_string($row['InTime']) . "'" : "NULL";
        
        // Member
        $values[] = (!empty($row['Member'])) ? intval($row['Member']) : "NULL";
        
        // SendId
        $values[] = (!empty($row['SendId'])) ? "'" . mysql_real_escape_string($row['SendId']) . "'" : "''";
        
        // SendName
        $values[] = (!empty($send_name)) ? "'" . mysql_real_escape_string($send_name) . "'" : "''";
        
        // Rphone1
        $values[] = (!empty($row['Rphone1'])) ? "'" . mysql_real_escape_string($row['Rphone1']) . "'" : "''";
        
        // Rphone2
        $values[] = (!empty($row['Rphone2'])) ? "'" . mysql_real_escape_string($row['Rphone2']) . "'" : "NULL";
        
        // Rphone3
        $values[] = (!empty($row['Rphone3'])) ? "'" . mysql_real_escape_string($row['Rphone3']) . "'" : "NULL";
        
        // RecvName
        $values[] = (!empty($recv_name)) ? "'" . mysql_real_escape_string($recv_name) . "'" : "NULL";
        
        // Sphone1
        $values[] = (!empty($row['Sphone1'])) ? "'" . mysql_real_escape_string($row['Sphone1']) . "'" : "NULL";
        
        // Sphone2
        $values[] = (!empty($row['Sphone2'])) ? "'" . mysql_real_escape_string($row['Sphone2']) . "'" : "NULL";
        
        // Sphone3
        $values[] = (!empty($row['Sphone3'])) ? "'" . mysql_real_escape_string($row['Sphone3']) . "'" : "NULL";
        
        // Msg
        $values[] = (!empty($msg)) ? "'" . mysql_real_escape_string($msg) . "'" : "NULL";
        
        // Url
        $values[] = (!empty($row['Url'])) ? "'" . mysql_real_escape_string($row['Url']) . "'" : "NULL";
        
        // Rdate
        $values[] = (!empty($row['Rdate'])) ? "'" . mysql_real_escape_string($row['Rdate']) . "'" : "NULL";
        
        // Rtime
        $values[] = (!empty($row['Rtime'])) ? "'" . mysql_real_escape_string($row['Rtime']) . "'" : "NULL";
        
        // Result
        $values[] = (!empty($row['Result'])) ? "'" . mysql_real_escape_string($row['Result']) . "'" : "'0'";
        
        // Kind
        $values[] = (!empty($row['Kind'])) ? "'" . mysql_real_escape_string($row['Kind']) . "'" : "'S'";
        
        // ErrCode
        $values[] = (!empty($row['ErrCode'])) ? intval($row['ErrCode']) : "NULL";
        
        // Retry1
        $values[] = (!empty($row['Retry1'])) ? intval($row['Retry1']) : "NULL";
        
        // Retry2
        $values[] = (!empty($row['Retry2'])) ? intval($row['Retry2']) : "NULL";
        
        // LastTime
        $values[] = (!empty($row['LastTime'])) ? "'" . mysql_real_escape_string($row['LastTime']) . "'" : "NULL";
        
        // company
        $values[] = (!empty($row['company'])) ? intval($row['company']) : "NULL";
        
        // ssang_c_num
        $values[] = (!empty($row['ssang_c_num'])) ? $row['ssang_c_num'] : "NULL"; // bigint 주의
        
        // endorse_num
        $values[] = (!empty($row['endorse_num'])) ? intval($row['endorse_num']) : "NULL";
        
        // userid
        $values[] = (!empty($row['userid'])) ? "'" . mysql_real_escape_string($row['userid']) . "'" : "NULL";
        
        // get
        $values[] = (!empty($row['get'])) ? intval($row['get']) : "2";
        
        // preminum
        $values[] = (!empty($row['preminum'])) ? "'" . mysql_real_escape_string($row['preminum']) . "'" : "NULL";
        
        // preminum2
        $values[] = (!empty($row['preminum2'])) ? "'" . mysql_real_escape_string($row['preminum2']) . "'" : "''";
        
        // c_preminum
        $values[] = (!empty($row['c_preminum'])) ? "'" . mysql_real_escape_string($row['c_preminum']) . "'" : "''";
        
        // 2012DaeriMemberNum
        $values[] = (!empty($row['2012DaeriMemberNum'])) ? intval($row['2012DaeriMemberNum']) : "NULL";
        
        // 2012DaeriCompanyNum
        $values[] = (!empty($row['2012DaeriCompanyNum'])) ? intval($row['2012DaeriCompanyNum']) : "NULL";
        
        // policyNum
        $values[] = (!empty($row['policyNum'])) ? "'" . mysql_real_escape_string($row['policyNum']) . "'" : "NULL";
        
        // endorse_day
        $values[] = (!empty($row['endorse_day']) && $row['endorse_day'] != '0000-00-00') ? "'" . $row['endorse_day'] . "'" : "NULL";
        
        // damdanga
        $values[] = (!empty($row['damdanga'])) ? intval($row['damdanga']) : "NULL";
        
        // push
        $values[] = (!empty($row['push'])) ? intval($row['push']) : "NULL";
        
        // insuranceCom
        $values[] = (!empty($row['insuranceCom'])) ? "'" . mysql_real_escape_string($row['insuranceCom']) . "'" : "NULL";
        
        // joong
        $values[] = (!empty($row['joong'])) ? "'" . mysql_real_escape_string($row['joong']) . "'" : "'2'";
        
        // jeongsan
        $values[] = (!empty($row['jeongsan'])) ? intval($row['jeongsan']) : "2";
        
        // juso
        $values[] = (!empty($juso)) ? "'" . mysql_real_escape_string($juso) . "'" : "NULL";
        
        // qboard
        $values[] = (!empty($row['qboard'])) ? "'" . mysql_real_escape_string($row['qboard']) . "'" : "NULL";
        
        // dagun
        $values[] = (!empty($row['dagun'])) ? "'" . mysql_real_escape_string($row['dagun']) . "'" : "'1'";
        
        // manager
        $values[] = (!empty($row['manager'])) ? "'" . mysql_real_escape_string($row['manager']) . "'" : "NULL";
        
        // 모든 값을 하나의 문자열로 결합
        $sql_values .= implode(", ", $values);
        $sql_values .= ")";
        
        // SQL 파일에 기록
        fwrite($sql_handle, $sql_values);
        
        $success_count++;
        $batch_count++;
        
        if ($batch_count >= $batch_size) {
            $batch_count = 0;
            // 파일에 플러시하고 잠시 대기
            fflush($sql_handle);
            usleep(100000);
        }
    }
    
    // 다음 배치로 이동
    $current_min_id = $current_max_id + 1;
    
    // 메모리 관리를 위해 결과셋 해제
    mysql_free_result($result);
}

// SQL 파일에 세미콜론 추가하여 완료
fwrite($sql_handle, ";\n");

// 파일에 모든 데이터 플러시
fflush($sql_handle);

$total_time = microtime(true) - $start_time;
// PHP 4 호환 시간 포맷팅
$total_hr = floor($total_time/3600);
$total_min = floor(($total_time%3600)/60);
$total_sec = floor($total_time%60);
$time_str = sprintf("%02d시간 %02d분 %02d초", $total_hr, $total_min, $total_sec);

write_log("내보내기 완료\n총 시간: $time_str\n총 레코드: $total_count\n성공: $success_count\n실패: $fail_count");
fclose($log_handle);
fclose($sql_handle);

echo "내보내기 완료<br/>";
echo "총 시간: $time_str<br/>";
echo "총 레코드: $total_count<br/>";
echo "성공: $success_count<br/>";
echo "실패: $fail_count<br/>";
echo "SQL 파일: $sql_file<br/>";
echo "로그 파일: $log_file<br/>";

// phpMyAdmin에서 SQL 파일 가져오는 방법 안내
echo "<br/><strong>PhpMyAdmin에서 SQL 파일 가져오는 방법:</strong><br/>";
echo "1. PhpMyAdmin에 로그인하세요.<br/>";
echo "2. 왼쪽 사이드바에서 대상 데이터베이스를 선택하세요.<br/>";
echo "3. 상단 메뉴에서 '가져오기' 탭을 클릭하세요.<br/>";
echo "4. '파일 선택' 버튼을 클릭하고 생성된 SQL 파일을 선택하세요.<br/>";
echo "5. '실행' 버튼을 클릭하세요.<br/>";
?>