<?php
ini_set('memory_limit', '512M');
set_time_limit(7200);

include 'dbcon.php';

$total_count = 0;
$success_count = 0;
$fail_count = 0;
$start_time = microtime(true);

$log_file = "./migration_log_" . date("Ymd_His") . ".txt";
$log_handle = fopen($log_file, "w");

// SQL 파일 생성
$sql_file = "./daeri_member_import_" . date("Ymd_His") . ".sql";
$sql_handle = fopen($sql_file, "w");

// SQL 파일 헤더 작성
fwrite($sql_handle, "-- DaeriMember 데이터 가져오기\n");
fwrite($sql_handle, "-- 생성일: " . date("Y-m-d H:i:s") . "\n\n");

// 테이블 생성 SQL
fwrite($sql_handle, "-- 테이블 구조\n");
fwrite($sql_handle, "CREATE TABLE IF NOT EXISTS `2012DaeriMember` (\n");
fwrite($sql_handle, "  `num` int(11) NOT NULL auto_increment,\n");
fwrite($sql_handle, "  `moCertiNum` int(11) default NULL,\n");
fwrite($sql_handle, "  `2012DaeriCompanyNum` int(11) default NULL,\n");
fwrite($sql_handle, "  `InsuranceCompany` char(2) NOT NULL default '',\n");
fwrite($sql_handle, "  `CertiTableNum` int(11) default NULL,\n");
fwrite($sql_handle, "  `Name` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `Jumin` varchar(15) default NULL,\n");
fwrite($sql_handle, "  `nai` int(2) NOT NULL default '0',\n");
fwrite($sql_handle, "  `push` int(2) default NULL,\n");
fwrite($sql_handle, "  `etag` char(2) default NULL,\n");
fwrite($sql_handle, "  `FirstStart` date default NULL,\n");
fwrite($sql_handle, "  `state` int(2) default NULL,\n");
fwrite($sql_handle, "  `cancel` int(2) default NULL,\n");
fwrite($sql_handle, "  `sangtae` char(2) default NULL,\n");
fwrite($sql_handle, "  `Hphone` varchar(15) default NULL,\n");
fwrite($sql_handle, "  `InputDay` datetime default NULL,\n");
fwrite($sql_handle, "  `OutPutDay` date default NULL,\n");
fwrite($sql_handle, "  `EndorsePnum` int(11) default NULL,\n");
fwrite($sql_handle, "  `dongbuCerti` varchar(20) default NULL,\n");
fwrite($sql_handle, "  `dongbuSelNumber` varchar(10) default NULL,\n");
fwrite($sql_handle, "  `dongbusigi` date default NULL,\n");
fwrite($sql_handle, "  `dongbujeongi` date default NULL,\n");
fwrite($sql_handle, "  `nabang_1` char(2) default NULL,\n");
fwrite($sql_handle, "  `ch` char(2) default '1',\n");
fwrite($sql_handle, "  `changeCom` int(11) default NULL,\n");
fwrite($sql_handle, "  `sPrem` varchar(10) default NULL,\n");
fwrite($sql_handle, "  `sago` char(2) default '1',\n");
fwrite($sql_handle, "  `p_buho` char(2) NOT NULL default '1',\n");
fwrite($sql_handle, "  `a6b` int(11) NOT NULL default '0',\n");
fwrite($sql_handle, "  `a7b` int(11) NOT NULL default '0',\n");
fwrite($sql_handle, "  `a8b` text NOT NULL,\n");
fwrite($sql_handle, "  `preminum1` varchar(10) default NULL,\n");
fwrite($sql_handle, "  `wdate` datetime default NULL,\n");
fwrite($sql_handle, "  `endorse_day` date default NULL,\n");
fwrite($sql_handle, "  `rate` char(2) default NULL,\n");
fwrite($sql_handle, "  `reasion` text NOT NULL,\n");
fwrite($sql_handle, "  `manager` varchar(18) NOT NULL default '',\n");
fwrite($sql_handle, "  `progress` char(1) NOT NULL default '',\n");
fwrite($sql_handle, "  PRIMARY KEY  (`num`),\n");
fwrite($sql_handle, "  KEY `idx_sangtae` (`sangtae`),\n");
fwrite($sql_handle, "  KEY `idx_push_wdate` (`push`,`wdate`),\n");
fwrite($sql_handle, "  KEY `idx_2012DaeriCompanyNum` (`2012DaeriCompanyNum`),\n");
fwrite($sql_handle, "  KEY `idx_dongbuCerti` (`dongbuCerti`),\n");
fwrite($sql_handle, "  KEY `idx_jumin` (`Jumin`),\n");
fwrite($sql_handle, "  KEY `idx_dongbu_jumin` (`dongbuCerti`,`Jumin`)\n");
fwrite($sql_handle, ") ENGINE=MyISAM DEFAULT CHARSET=utf8;\n\n");

// 테이블 구조 정의
$table_columns = array(
    'num',
    'moCertiNum',
    '2012DaeriCompanyNum',  // 정확한 필드명 사용
    'InsuranceCompany',
    'CertiTableNum',
    'Name',
    'Jumin',
    'nai',
    'push',
    'etag',
    'FirstStart',
    'state',
    'cancel',
    'sangtae',
    'Hphone',
    'InputDay',
    'OutPutDay',
    'EndorsePnum',
    'dongbuCerti',
    'dongbuSelNumber',
    'dongbusigi',
    'dongbujeongi',
    'nabang_1',
    'ch',
    'changeCom',
    'sPrem',
    'sago',
    'p_buho',
    'a6b',
    'a7b',
    'a8b',
    'preminum1',
    'wdate',
    'endorse_day',
    'rate',
    'reasion',
    'manager',
    'progress'
);

// 테이블 컬럼 수 확인
$column_count = count($table_columns);
write_log("테이블 컬럼 수: $column_count");

function write_log($message) {
    global $log_handle;
    $time = date("Y-m-d H:i:s");
    fwrite($log_handle, "[$time] $message\n");
}

write_log("데이터 마이그레이션 시작");

// 조건 설정
//$count_query = "SELECT COUNT(*) as total FROM `2012DaeriMember` WHERE push='4'";
$count_query = "SELECT COUNT(*) as total FROM `2012DaeriMember` WHERE push='1' AND sangtae='1'";
$count_result = mysql_query($count_query);
$count_row = mysql_fetch_assoc($count_result);
$total_records = $count_row['total'];
write_log("총 마이그레이션 대상 레코드 (push='4'): $total_records");

$min_id = 0;
// 데이터베이스에서 최대 ID 값을 자동으로 가져오도록 수정
$max_id_query = "SELECT MAX(num) as max_id FROM `2012DaeriMember` WHERE push='4'";
$max_id_result = mysql_query($max_id_query);
$max_id_row = mysql_fetch_assoc($max_id_result);
$max_id = $max_id_row['max_id'];
write_log("최대 ID 값: $max_id");

$batch_size = 200;
$batch_count = 0;
$is_first_record = true;

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
$query = "SELECT * FROM `2012DaeriMember` WHERE  push='1' AND sangtae='1'";
$result = mysql_query($query);

if (!$result) {
    write_log("오류: 데이터 가져오기 실패 - " . mysql_error());
    fclose($log_handle);
    fclose($sql_handle);
    die("데이터를 가져오는데 실패했습니다.");
}

// INSERT 구문 시작
fwrite($sql_handle, "-- 데이터 삽입\n");

// 컬럼 이름을 SQL에 포함
$columns_sql = "INSERT INTO `2012DaeriMember` (";
$columns_sql .= "`" . implode("`, `", $table_columns) . "`";
$columns_sql .= ") VALUES\n";
fwrite($sql_handle, $columns_sql);

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

    // 암호화 과정 없이 원본 데이터 그대로 사용
    $jumin = isset($row['Jumin']) ? $row['Jumin'] : "";
    
    // 인코딩 변환 함수 안전하게 사용
    $name = isset($row['Name']) ? @iconv("EUC-KR", "UTF-8", $row['Name']) : "";
    if ($name === false) $name = $row['Name']; // 변환 실패시 원본 유지
    
    $a8b = isset($row['a8b']) ? @iconv("EUC-KR", "UTF-8", $row['a8b']) : "";
    if ($a8b === false) $a8b = $row['a8b']; // 변환 실패시 원본 유지
    
    $reasion = isset($row['reasion']) ? @iconv("EUC-KR", "UTF-8", $row['reasion']) : "";
    if ($reasion === false) $reasion = $row['reasion']; // 변환 실패시 원본 유지

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
    
    // moCertiNum
    $values[] = intval($row['moCertiNum']);
    
    // 2012DaeriCompanyNum (원래 필드명 그대로 사용)
    $values[] = intval($row['2012DaeriCompanyNum']);
    
    // InsuranceCompany
    $values[] = "'" . mysql_real_escape_string($row['InsuranceCompany']) . "'";
    
    // CertiTableNum
    $values[] = intval($row['CertiTableNum']);
    
    // Name
    $values[] = "'" . mysql_real_escape_string($name) . "'";
    
    // Jumin
    $values[] = "'" . mysql_real_escape_string($jumin) . "'";
    
    // nai
    $values[] = intval($row['nai']);
    
    // push
    $values[] = intval($row['push']);
    
    // etag
    $values[] = (!empty($row['etag'])) ? "'" . mysql_real_escape_string($row['etag']) . "'" : "NULL";
    
    // FirstStart (날짜 필드)
    $values[] = (!empty($row['FirstStart']) && $row['FirstStart'] != '0000-00-00') ? "'" . $row['FirstStart'] . "'" : "NULL";
    
    // state
    $values[] = intval($row['state']);
    
    // cancel
    $values[] = intval($row['cancel']);
    
    // sangtae
    $values[] = (!empty($row['sangtae'])) ? "'" . mysql_real_escape_string($row['sangtae']) . "'" : "NULL";
    
    // Hphone
    $values[] = (!empty($row['Hphone'])) ? "'" . mysql_real_escape_string($row['Hphone']) . "'" : "NULL";
    
    // InputDay (날짜시간 필드)
    $values[] = (!empty($row['InputDay']) && $row['InputDay'] != '0000-00-00 00:00:00') ? "'" . $row['InputDay'] . "'" : "NULL";
    
    // OutPutDay (날짜 필드)
    $values[] = (!empty($row['OutPutDay']) && $row['OutPutDay'] != '0000-00-00') ? "'" . $row['OutPutDay'] . "'" : "NULL";
    
    // EndorsePnum
    $values[] = intval($row['EndorsePnum']);
    
    // dongbuCerti
    $values[] = (!empty($row['dongbuCerti'])) ? "'" . mysql_real_escape_string($row['dongbuCerti']) . "'" : "NULL";
    
    // dongbuSelNumber
    $values[] = (!empty($row['dongbuSelNumber'])) ? "'" . mysql_real_escape_string($row['dongbuSelNumber']) . "'" : "NULL";
    
    // dongbusigi (날짜 필드)
    $values[] = (!empty($row['dongbusigi']) && $row['dongbusigi'] != '0000-00-00') ? "'" . $row['dongbusigi'] . "'" : "NULL";
    
    // dongbujeongi (날짜 필드)
    $values[] = (!empty($row['dongbujeongi']) && $row['dongbujeongi'] != '0000-00-00') ? "'" . $row['dongbujeongi'] . "'" : "NULL";
    
    // nabang_1
    $values[] = (!empty($row['nabang_1'])) ? "'" . mysql_real_escape_string($row['nabang_1']) . "'" : "NULL";
    
    // ch
    $values[] = (!empty($row['ch'])) ? "'" . mysql_real_escape_string($row['ch']) . "'" : "'1'";
    
    // changeCom
    $values[] = intval($row['changeCom']);
    
    // sPrem
    $values[] = (!empty($row['sPrem'])) ? "'" . mysql_real_escape_string($row['sPrem']) . "'" : "NULL";
    
    // sago
    $values[] = (!empty($row['sago'])) ? "'" . mysql_real_escape_string($row['sago']) . "'" : "'1'";
    
    // p_buho
    $values[] = (!empty($row['p_buho'])) ? "'" . mysql_real_escape_string($row['p_buho']) . "'" : "'1'";
    
    // a6b
    $values[] = intval($row['a6b']);
    
    // a7b
    $values[] = intval($row['a7b']);
    
    // a8b
    $values[] = "'" . mysql_real_escape_string($a8b) . "'";
    
    // preminum1
    $values[] = (!empty($row['preminum1'])) ? "'" . mysql_real_escape_string($row['preminum1']) . "'" : "NULL";
    
    // wdate (날짜시간 필드)
    $values[] = (!empty($row['wdate']) && $row['wdate'] != '0000-00-00 00:00:00') ? "'" . $row['wdate'] . "'" : "NULL";
    
    // endorse_day (날짜 필드)
    $values[] = (!empty($row['endorse_day']) && $row['endorse_day'] != '0000-00-00') ? "'" . $row['endorse_day'] . "'" : "NULL";
    
    // rate
    $values[] = (!empty($row['rate'])) ? "'" . mysql_real_escape_string($row['rate']) . "'" : "NULL";
    
    // reasion
    $values[] = "'" . mysql_real_escape_string($reasion) . "'";
    
    // manager
    $values[] = (!empty($row['manager'])) ? "'" . mysql_real_escape_string($row['manager']) . "'" : "''";
    
    // progress
    $values[] = (!empty($row['progress'])) ? "'" . mysql_real_escape_string($row['progress']) . "'" : "''";
    
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

// SQL 파일에 세미콜론 추가하여 완료
fwrite($sql_handle, ";\n");

// 파일에 모든 데이터 플러시
fflush($sql_handle);

$total_time = microtime(true) - $start_time;
$time_str = sprintf("%02d시간 %02d분 %02d초", floor($total_time/3600), floor(($total_time%3600)/60), $total_time%60);
write_log("마이그레이션 완료\n총 시간: $time_str\n총 레코드: $total_count\n성공: $success_count\n실패: $fail_count");
fclose($log_handle);
fclose($sql_handle);

echo "마이그레이션 완료<br/>";
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