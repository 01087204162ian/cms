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

$log_file = "./cpreminum_export_log_" . date("Ymd_His") . ".txt";
$log_handle = fopen($log_file, "w");

// SQL 파일 생성
$sql_file = "./2012Cpreminum_export_" . date("Ymd_His") . ".sql";
$sql_handle = fopen($sql_file, "w");

// SQL 파일 헤더 작성
fwrite($sql_handle, "-- 2012Cpreminum 데이터 내보내기\n");
fwrite($sql_handle, "-- 생성일: " . date("Y-m-d H:i:s") . "\n\n");

// 테이블 생성 SQL
fwrite($sql_handle, "-- 테이블 구조\n");
fwrite($sql_handle, "CREATE TABLE IF NOT EXISTS `2012Cpreminum` (\n");
fwrite($sql_handle, "  `num` int(11) NOT NULL auto_increment,\n");
fwrite($sql_handle, "  `InsuraneCompany` char(2) default NULL,\n");
fwrite($sql_handle, "  `CertiTableNum` int(12) default NULL,\n");
fwrite($sql_handle, "  `DaeriCompanyNum` int(12) default NULL,\n");
fwrite($sql_handle, "  `sPreminum` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `ePreminum` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `sunso` int(12) default NULL,\n");
fwrite($sql_handle, "  `yPreminum` varchar(30) default NULL,\n");
fwrite($sql_handle, "  `mPreminum` varchar(30) default NULL,\n");
fwrite($sql_handle, "  `certi` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `policyNum` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `dPreminum` varchar(12) default NULL,\n");
fwrite($sql_handle, "  PRIMARY KEY  (`num`),\n");
fwrite($sql_handle, "  KEY `userid` (`InsuraneCompany`),\n");
fwrite($sql_handle, "  KEY `uname` (`DaeriCompanyNum`),\n");
fwrite($sql_handle, "  KEY `idnumber1` (`sPreminum`),\n");
fwrite($sql_handle, "  KEY `idx_InsuraneCompany` (`InsuraneCompany`),\n");
fwrite($sql_handle, "  KEY `idx_CertiTableNum` (`CertiTableNum`),\n");
fwrite($sql_handle, "  KEY `idx_DaeriCompanyNum` (`DaeriCompanyNum`),\n");
fwrite($sql_handle, "  KEY `idx_policyNum` (`policyNum`)\n");
fwrite($sql_handle, ") ENGINE=MyISAM DEFAULT CHARSET=utf8;\n\n");

// 테이블 컬럼 정의
$table_columns = array(
    'num',
    'InsuraneCompany',
    'CertiTableNum',
    'DaeriCompanyNum',
    'sPreminum',
    'ePreminum',
    'sunso',
    'yPreminum',
    'mPreminum',
    'certi',
    'policyNum',
    'dPreminum'
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
$count_query = "SELECT COUNT(*) as total FROM `2012Cpreminum`";
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
$range_query = "SELECT MIN(num) as min_id, MAX(num) as max_id FROM `2012Cpreminum`";
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
$columns_sql = "INSERT INTO `2012Cpreminum` (";
$columns_sql .= "`" . implode("`, `", $table_columns) . "`";
$columns_sql .= ") VALUES\n";
fwrite($sql_handle, $columns_sql);

$progress_step = max(1, intval($total_records / 100));
$last_progress = 0;
$processed_records = 0;

// 배치 처리 반복
while ($current_min_id <= $max_id) {
    $current_max_id = min($current_min_id + $batch_step - 1, $max_id);
    
    $query = "SELECT * FROM `2012Cpreminum` WHERE num BETWEEN $current_min_id AND $current_max_id ORDER BY num";
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
        
        // num
        $values[] = intval($row['num']);
        
        // InsuraneCompany
        $values[] = (!empty($row['InsuraneCompany'])) ? "'" . mysql_real_escape_string($row['InsuraneCompany']) . "'" : "NULL";
        
        // CertiTableNum
        $values[] = (!empty($row['CertiTableNum'])) ? intval($row['CertiTableNum']) : "NULL";
        
        // DaeriCompanyNum
        $values[] = (!empty($row['DaeriCompanyNum'])) ? intval($row['DaeriCompanyNum']) : "NULL";
        
        // sPreminum
        $values[] = (!empty($row['sPreminum'])) ? "'" . mysql_real_escape_string($row['sPreminum']) . "'" : "NULL";
        
        // ePreminum
        $values[] = (!empty($row['ePreminum'])) ? "'" . mysql_real_escape_string($row['ePreminum']) . "'" : "NULL";
        
        // sunso
        $values[] = (!empty($row['sunso'])) ? intval($row['sunso']) : "NULL";
        
        // yPreminum
        $values[] = (!empty($row['yPreminum'])) ? "'" . mysql_real_escape_string($row['yPreminum']) . "'" : "NULL";
        
        // mPreminum
        $values[] = (!empty($row['mPreminum'])) ? "'" . mysql_real_escape_string($row['mPreminum']) . "'" : "NULL";
        
        // certi
        $values[] = (!empty($row['certi'])) ? "'" . mysql_real_escape_string($row['certi']) . "'" : "NULL";
        
        // policyNum
        $values[] = (!empty($row['policyNum'])) ? "'" . mysql_real_escape_string($row['policyNum']) . "'" : "NULL";
        
        // dPreminum
        $values[] = (!empty($row['dPreminum'])) ? "'" . mysql_real_escape_string($row['dPreminum']) . "'" : "NULL";
        
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