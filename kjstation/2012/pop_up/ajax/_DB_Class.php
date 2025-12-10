<?php 

class DB_Class
{
	
	var $Host 		= "localhost";
	var $Database	= "sam0327";
	var $User		= "sam0327";
	var $Password	= "in716671";
	
	var $Connect_ID = 0;
	var $Query_id	= 0;
	var $Recode 	= array();
	var $Row;
	var $totalNum;
	
	var $ErrNo		= 0;
	var $ErrMsg		= "";
	
	private function halt($msg)
	{
		$format = "<b>데이터베이스 에러 :</b> %s<br>\n";
		$format2 = "<b>MySQL 에러</b>: %s (%s)<br>\n";
		
		printf($format, $msg);
		printf($format2, $this->ErrNo, $this->ErrMsg);
		die("연결실패!!");
	}
	
	private function connect()
	{
		if($this->Connect_ID == 0)
		{
			$this->Connect_ID = mysql_connect($this->Host, $this->User, $this->Password, $this->Database);
			
			if(!$this->Connect_ID)
			{
				$msg = "Connect-ID == 연결 실패";
				$this->halt($msg);
			}
			
			if(!mysql_query(sprintf("use %s", $this->Database), $this->Connect_ID))
			{
				$this->halt("데이터베이스(" . $this->Database . ")에 연결 할 수 없습니다.");
			}
		}
	}
	
	public function query($Query_str)
	{
		$this->connect();
		$this->Query_id = mysql_query($Query_str, $this->Connect_ID);
		$this->Row = 0;
		$this->ErrNo = mysql_errno();
		$this->ErrMsg = mysql_error();
		
		if(!$this->Query_id) {
			$this->halt("잘못된 SQL문 : " . $Query_str);
		}
		
		return $this->Query_id;
	}
	
	public function totalNum() 
	{
		$this->num = mysql_num_rows($this->Query_id);
		$this->ErrNo = mysql_errno();
		$this->ErrMsg = mysql_error();
		
		$totalNum = $this->num;
		return $totalNum;
	}
	
	public function db_insertID()
	{
		$this->insertID = mysql_insert_id();
		$this->ErrNo = mysql_errno();
		$this->ErrMsg = mysql_error();
		
		$insertID = $this->insertID;
		return $insertID;
	}
	
	public function nextRecode() 
	{
		$this->Recode = mysql_fetch_array($this->Query_id);
		$this->Row += 1;
		$this->ErrNo = mysql_errno();
		$this->ErrMsg = mysql_error();
		
		$stat = is_array($this->Recode);
		if(!$stat)
		{
			mysql_free_result($this->Query_id);
			$this->Query_id = 0;
		}
		return $stat;
	}
	
	public function seek($pos)
	{
		$status = mysql_data_seek($this->Query_id, $pos);
		if($status) 
		{
			$this->Row = $pos;
		}
		
		return ;
	}
	
	public function search_macNum($table, $userid, $macAddress)
	{
		$this->where = " where userid = '" . $userid . "'";
		$this->where = $this->where . " and macAddress = '" . $macAddress . "'";
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->num = $this->totalNum();
		
		return $this->num;
	}
	
	// 맥어드레스로 검색된 confirm 값 찾기...
	public function search_mac_confirmValue($table, $userid, $macAddress)
	{
		$this->where = " where userid = '" . $userid . "'";
		$this->where = $this->where . " and macAddress = '" . $macAddress . "'";
		$this->order = " order by id desc";
		$this->limit = " limit 0,1";
		$this->sql = "select * from " . $table . $this->where . $this->order . $this->limit;
		
		$this->query($this->sql);
		$this->nextRecode();
		
		return $this->Recode[comfirm];
	}
	
	public function search_class_num($table, $ins_id, $dbKey)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and id = " . $dbKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		return $this->Recode[num];
	}
	
	public function search_class_order($table, $ins_id, $num)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and num < " . $num;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->num = $this->totalNum();
		
		return $this->num;
	}
	
	public function search_order($table, $ins_id, $typeKey, $dbKey)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and type = " . $typeKey . " and id < " . $dbKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->num = $this->totalNum();
		
		return $this->num;
	}
	
	public function search_field($table, $ins_id, $dbKey, $fieldName)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and id = " . $dbKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		return $this->Recode[$fieldName];
	}
	
	//primary key로 검색하는 필드값
	public function search_fieldValue($table, $dbKey, $fieldName)
	{
		$this->where = " where id = " . $dbKey;
		$this->sql = "select " . $fieldName . " from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		return $this->Recode[$fieldName];
	}
	
	public function search_file($table, $ins_id, $dbKey, $fieldName)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and relativeKey = " . $dbKey;
		$this->order = " order by id desc limit 0, 1";
		$this->sql = "select * from " . $table . $this->where . $this->order;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		return $this->Recode[$fieldName];
	}
	
	//넘어온 $targetKey의 순서는 몇인가?
	public function search_teacher_order($table, $ins_id, $exceptKey1, $exceptKey2, $targetKey)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and (teacher_type != " . $exceptKey1;
		$this->where = $this->where . " and  teacher_type != " . $exceptKey2 . ")";
		$this->order = " order by name asc";
		
		$this->sql = "select * from " . $table . $this->where . $this->order;
		
		$this->query($this->sql);
		$this->i = 1;
		
		while ($this->nextRecode()) {
			if($this->Recode[id] == $targetKey){
				$this->tOrder = $this->i;
				break;
			}
			$this->i++;
		}
		
		return $this->tOrder;
	}
	
	//"전직강사" 와 "퇴직직원"키를 찾아라.. type(데이터중 강사구분키) = 7
	public function search_old_teacher($table, $ins_id, $exceptFiled)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and type = 7 and field = '" . $exceptFiled . "'";
		$this->sql = "select id as tID from " . $table . $this->where;
		
		$this->query($this->sql);	
		$this->nextRecode();
		
		return $this->Recode[tID];
	}
	
	public function schoolStateLabel($value)
	{
		$this->label;
		switch ($value) {
			case 0:
				$this->label = '';
				break;
			case 1:
				$this->label = '재학';
				break;
			case 2:
				$this->label = '졸업';
				break;
		}
		
		return $this->label;
	}
		
	public function family_typeLabel($typeKey)
	{
		$this->label;
		switch ($typeKey) {
			case 0:
				$this->label = '';
				break;
			case 1:
				$this->label = '부';
				break;
			case 2:
				$this->label = '모';
				break;
			case 3:
				$this->label = '형';
				break;
			case 4:
				$this->label = '누나';
				break;
			case 5:
				$this->label = '언니';
				break;
			case 6:
				$this->label = '동생';
				break;
			case 7:
				$this->label = '친척';
				break;
			case 8:
				$this->label = '보호자';
				break;
		}
		
		return $this->label;
	}
	
	// 수강료납입에서 사용
	public function gapLabel($day_of_gap)
	{
		$this->label;
		if($day_of_gap > 0) { //체납됨...
			$this->label = "체납";
		}else  if($day_of_gap < 0){ //선납됨..
			$this->label = "선납";
		}else { //정상
			$this->label = "정상";
		}
		
		return $this->label;
	}
	 
	public function payMethodLabel($payMethod)
	{
		$this->label;
		switch ($payMethod) {
			case 0:
				$this->label = '';
				break;
			case 1:
				$this->label = '직접납입(현금)';
				break;
			case 2:
				$this->label = '통장입금';
				break;
		}
		
		return $this->label;
	}
	
	public function scholarshipLabel($scholarship)
	{
		$this->label;
		switch ($scholarship) {
			case 0:
				$this->label = '';
				break;
			case 1:
				$this->label = '';
				break;
			case 2:
				$this->label = '장학생';
				break;
		}
		
		return $this->label;
	}
	
	public function toWeekName($targetDay)
	{
		$this->label;
		$tmpDay  = split("-", $targetDay);
		$yoilNum = date("w",strtotime($tmpDay[0].$tmpDay[1].$tmpDay[2]));
		switch($yoilNum) {
			case 0:
				$this->label = "Sun";
				break;
			case 1:
				$this->label = "Mon";
				break;
			case 2:
				$this->label = "Tue";
				break;
			case 3:
				$this->label = "Wed";
				break;
			case 4:
				$this->label = "Thu";
				break;
			case 5:
				$this->label = "Fri";
				break;
			case 6:
				$this->label = "Sat";
				break;
		}
		
		return $this->label;
	}

	public function toWeekName_tokor($week_name)
	{
		$this->label;
		switch($week_name) {
			case 'Sun':
				$this->label = "일";
				break;
			case 'Mon':
				$this->label = "월";
				break;
			case 'Tue':
				$this->label = "화";
				break;
			case 'Wed':
				$this->label = "수";
				break;
			case 'Thu':
				$this->label = "목";
				break;
			case 'Fri':
				$this->label = "금";
				break;
			case 'Sat':
				$this->label = "토";
				break;
		}
		
		return $this->label;
	}
	
	public function phone_order_define($targetNum)
	{
		$this->label;
		switch($targetNum) {
			case '02':
				$this->label = 1;
				break;
			case '031':
				$this->label = 2;
				break;
			case '032':
				$this->label = 3;
				break;
			case '033':
				$this->label = 4;
				break;
			case '041':
				$this->label = 5;
				break;
			case '042':
				$this->label = 6;
				break;
			case '043':
				$this->label = 7;
				break;
			case '051':
				$this->label = 8;
				break;
			case '052':
				$this->label = 9;
				break;
			case '053':
				$this->label = 10;
				break;
			case '054':
				$this->label = 11;
				break;
			case '055':
				$this->label = 12;
				break;
			case '061':
				$this->label = 13;
				break;
			case '062':
				$this->label = 14;
				break;
			case '063':
				$this->label = 15;
				break;
			case '064':
				$this->label = 16;
				break;
			default:
				$this->label = 0;
				break;
		}
		
		return $this->label;
	}
	
	public function cell_order_define($targetNum)
	{
		$this->label;
		switch($targetNum) {
			case '010':
				$this->label = 1;
				break;
			case '011':
				$this->label = 2;
				break;
			case '016':
				$this->label = 3;
				break;
			case '017':
				$this->label = 4;
				break;
			case '018':
				$this->label = 5;
				break;
			case '019':
				$this->label = 6;
				break;
			default:
				$this->label = 0;
				break;
		}
		
		return $this->label;
	}
	
	public function attendance_option_label($targetNum)
	{
		$this->label;
		switch($targetNum) {
			case '1':
				$this->label = '정상';
				break;
			case '2':
				$this->label = '결석';
				break;
			case '3':
				$this->label = '병결';
				break;
			case '4':
				$this->label = '지각';
				break;
			case '5':
				$this->label = '휴일';
				break;
			case '6':
				$this->label = '미처리';
				break;
			default:
				$this->label = '';
				break;
		}
		
		return $this->label;
	}

	public function attendance_option_color($targetNum)
	{
		$this->label;
		switch($targetNum) {
			case '1':
				$this->label = '#74ca8d';
				break;
			case '2':
				$this->label = '#ff0018';
				break;
			case '3':
				$this->label = '#fd8d29';
				break;
			case '4':
				$this->label = '#ffd200';
				break;
			case '5':
				$this->label = '#EEEEEE';
				break;
			case '6':
				$this->label = '#FFFFFF';
				break;
			default:
				$this->label = '#FFFFFF';
				break;
		}
		
		return $this->label;
	}
	
	public function day_of_gap_calculation($payDay, $registDay)
	{
		$this->gap;
		$this->mkRegistDay = mktime(0,0,0,substr($registDay, 5,2),substr($registDay, 8,2),substr($registDay, 0,4));
		$this->mkPaidDay = mktime(0,0,0,substr($payDay, 5,2),substr($payDay, 8,2),substr($payDay, 0,4));
		$this->timeGap = $this->mkPaidDay - $this->mkRegistDay;
		
		$this->gap = floor($this->timeGap/(24*60*60));
		
		return $this->gap;
	}
	
	// 금전출납장부사용...
	public function search_category_order($table, $ins_id, $typeKey, $code, $range)
	{
		$this->where = " where ins_id = '" . $ins_id . "'";
		$this->where = $this->where . " and type = " . $typeKey . " and code < " . $code;
		$this->where = $this->where . " and code > " . $range;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->num = $this->totalNum();
		
		return $this->num;
	}
	
	public function search_field_value($table, $id, $targetField)
	{
		$this->where = " where id = " . $id;
		$this->sql = "select " . $targetField . " from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[$targetField]; //$this->sql; //
		
		return $this->returnValue;
	}
	
	public function search_categoryCodeName($table, $userID, $categoryCode)
	{
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . "and code = " . $categoryCode;
		$this->sql = "select descript from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[descript];
		
		return $this->returnValue;
	}
	
	public function sum_money_calculation($table, $userID, $registDay, $gubunKey)
	{
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . "and registDay = '" . $registDay . "' and gubunKey = " . $gubunKey;
		$this->sql = "select sum(money) as incomeSum from " . $table . $this->where;
		
		$this->query($this->sql);
		$this->nextRecode();
		$this->returnValue = $this->Recode[incomeSum];
		
		return $this->returnValue;
	}
	
	//기준일 이전의 전기이월 갯수 찾기
	public function recent_closing_day_num($table, $userID, $registDay, $gubunKey)
	{
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and gubunKey = " . $gubunKey;
		$this->where = $this->where . " and registDay <= '" . $registDay . "'";
		$this->order = " order by id desc";
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);
		$this->returnValue = $this->totalNum();
		
		return $this->returnValue;
	}
	
	//기준일 이전의 전기이월 값 찾기
	public function recent_closing_value($table, $userID, $registDay, $gubunKey, $targetField)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->order;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and gubunKey = " . $gubunKey;
		$this->where = $this->where . " and registDay <= '" . $registDay . "'";
		$this->order = " order by registDay desc limit 0,1";
		$this->sql = "select " . $targetField . " from " . $table . $this->where . $this->order;
		
		$this->query($this->sql);
		$this->nextRecode();
		$this->returnValue = $this->Recode[$targetField];
		
		return $this->returnValue;
	}
	
	//구간계산 
	public function dayNday_sum_money_calculation($table, $userID, $gubunKey, $closing_day, $end_day)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and gubunKey = " . $gubunKey;
		$this->where = $this->where . " and (registDay >= '" . $closing_day . "'";
		$this->where = $this->where . " and  registDay <= '" . $end_day . "')";
		$this->sql = "select sum(money) as totalSum from " . $table . $this->where;
		
		$this->query($this->sql);
		$this->nextRecode();
		$this->returnValue = $this->Recode[totalSum];
		
		return $this->returnValue;
	}
	
	//전체계산 
	public function dayNday_sum_money_calculation2($table, $userID, $gubunKey, $closing_day, $end_day)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and gubunKey = " . $gubunKey;
		$this->where = $this->where . " and registDay <= '" . $closing_day . "'";
		$this->sql = "select sum(money) as totalSum from " . $table . $this->where;
		
		$this->query($this->sql);
		$this->nextRecode();
		$this->returnValue = $this->Recode[totalSum];
		
		return $this->returnValue;
	}
	
	//출결내용 인원수(확인) 체크 메쏘드
	public function attendance_check_num($userID, $targetDay, $classKey, $targetOption)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		if($classKey > 0) {
			$this->where = 				" WHERE A.id = B.studentKey";
			$this->where = $this->where . " AND B.studentKey = C.relativeKey";
			$this->where = $this->where . " AND A.ins_id = '" . $userID . "' ";
			$this->where = $this->where . " AND C.type = 'studentClassKey'";
			$this->where = $this->where . " AND C.configkey = " . $classKey;
			$this->where = $this->where . " AND B.aDay = '" . $targetDay . "'";
			$this->where = $this->where . " AND B.aOption = " . $targetOption;
			
			$this->sql = "select B.id from " . 
						 "z_attendance A, " . 
						 "z_attendance B, " . 
						 "b_configkey C " . 
						 $this->where;
		}else {
			$this->where = " where ins_id = '" . $userID . "' ";
			$this->where = $this->where . " and aDay = '" . $targetDay . "'";
			$this->where = $this->where . " and aOption = " . $targetOption;
			$this->sql = "select * from z_attendance" . $this->where;
		}
		
		$this->query($this->sql);		
		$this->returnValue = $this->totalNum();
		
		return $this->returnValue;
	}
	
	//휴일확인하는 메쏘드
	public function holiday_check($userID, $relativeKey, $type, $yoil)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select " . $yoil . " from b_attendance" . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		switch ($this->Recode[$yoil]) {
			case 0 :
				$holiday = '';
				break;
			case 1:
				$holiday = '휴일';
				break;
		}
		
		$this->returnValue = $holiday;
		
		return $this->returnValue;
	}
	
	//오늘 출결확인하기
	public function today_attendance_check($userID, $relativeKey, $targetDay, $filed)
	{
		$this->returnValue;
		$this->where;
		$this->order;
		$this->sql;
		
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and aDay = '" . $targetDay . "'";
		$this->where = $this->where . " and studentKey = " . $relativeKey;
		$this->order  = " order by id desc limit 0, 1";
		$this->sql = "select " . $filed . " from z_attendance" . $this->where . $this->order;
		
		$this->query($this->sql);		
		$this->nextRecode();		
		$this->returnValue = $this->Recode[aOption];
		
		return $this->returnValue;
	}
	
	//tableKey관련 메모 찾기
	public function search_relativeKey_memo($table, $tableName, $relativeKey, $type)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where relativeTable = '" . $tableName . "'";
		$this->where = $this->where . " and relativeKey = '" . $relativeKey . "'";
		$this->sql = "select memo from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[memo];
		
		return $this->returnValue;
	}
	
	//연계테이블값 불러오기
	public function search_relativeKey($table, $userID, $relativeKey, $type, $targetField)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select " . $targetField . " from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[$targetField];
		
		return $this->returnValue;
	}
	
	//입학상담 값 불러오기
	public function search_admisson_value($table, $userID, $relativeKey, $targetField)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and studentKey = " . $relativeKey;
		$this->sql = "select " . $targetField . " from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[$targetField];
		
		return $this->returnValue; //$this->sql; //
	}
	
	//연계학생테이블 정보 불러오기
	public function search_relative_studnet($table, $userID, $relativeKey, $targetField)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and id = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		$this->returnValue = $this->Recode[$targetField];
		
		return $this->returnValue;
	}
	
	//연계테이블 정보값 불러오기(생년월일,이메일)
	public function read_relative_value($table, $userID, $relativeKey, $type, $targetValue)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		switch ($targetValue) {
			/*
			case 'birthday':
				if($this->Recode[year]>0) {
					$this->returnValue = $this->Recode[year] . '-' . $this->Recode[month] . '-' . $this->Recode[day];
				}else{
					$this->returnValue = '';
				}
				break;
			case 'socialnumber':
				$this->tempNum = $this->Recode[num1] . '-' . $this->Recode[num2];
				if($tempNum == '-') {
					$this->returnValue = '';
				}else{
					$this->returnValue = $this->tempNum;
				}
				break;*/
			case 'cell':
				$this->tempNum = $this->Recode[num1] . '-' . $this->Recode[num2] . '-' . $this->Recode[num3] . '-' . $this->Recode[id];
				if($tempNum == '--') {
					$this->returnValue = '';
				}else{
					$this->returnValue = $this->tempNum;
				}
				break;
			case 'email':
				$this->returnValue = $this->Recode[text] . '|' . $this->Recode[id];
				break; 
			case 'registday':
				$this->returnValue = $this->Recode[text] . '|' . $this->Recode[id];
				break;
			case 'artStartDay':
				$this->returnValue = $this->Recode[text] . '|' . $this->Recode[id];
				break;
			case 'tuition':
				$this->returnValue = $this->Recode[text];
				break;
			case 'school':
				$this->returnValue = $this->Recode[text];
				break;
			case 'etc':
				$this->returnValue = $this->Recode[text] . '|' . $this->Recode[id];
				break;
			case 'file':
				$this->returnValue = $this->Recode[fileName];
				break;
			case 'inputDate':
				$this->returnValue = $this->Recode[inputDate];
				break;
			case 'memo':
				$this->returnValue = $this->Recode[memo] . '|' . $this->Recode[id];
				break;
		}
		
		return $this->returnValue;
	}
	
	//연계테이블 주소값읽기
	public function read_address_value($table, $userID, $relativeKey, $type, $targetfiled)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		switch ($targetfiled) {
			case 'post1':
				$this->returnValue = $this->Recode[post1];
				break;
			case 'post2':
				$this->returnValue = $this->Recode[post2];
				break;
			case 'address1':
				$this->returnValue = $this->Recode[address1];
				break;
			case 'address2':
				$this->returnValue = $this->Recode[address2];
				break;
			case 'id':
				$this->returnValue = $this->Recode[id];
				break;
		}
		
		return $this->returnValue;
	}
	
	//연계테이블 주민번호 읽기
	public function read_socialnumber_value($table, $userID, $relativeKey, $type, $targetfiled)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		switch ($targetfiled) {
			case 'num1':
				$this->returnValue = $this->Recode[num1];
				break;
			case 'num2':
				$this->returnValue = $this->Recode[num2];
				break;
			case 'id':
				$this->returnValue = $this->Recode[id];
				break;
		}
		
		return $this->returnValue;
	}
	
	//연계테이블 생년월일 읽기
	public function read_birthday_value($table, $userID, $relativeKey, $type, $targetfiled)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		switch ($targetfiled) {
			case 'year':
				$this->returnValue = $this->Recode[year];
				break;
			case 'month':
				$this->returnValue = $this->Recode[month];
				break;
			case 'day':
				$this->returnValue = $this->Recode[day];
				break;
			case 'id':
				$this->returnValue = $this->Recode[id];
				break;
			case 'full':
				$this->returnValue = $this->Recode[year].'-'.$this->Recode[month].'-'.$this->Recode[day];
				break;
		}
		
		return $this->returnValue;
	}
	
	//연계테이블 출결요일 읽기
	public function read_attendance_value($table, $userID, $relativeKey, $type)
	{
		$this->returnValue;
		$this->where;
		$this->sql;
		$this->where = " where ins_id = '" . $userID . "' ";
		$this->where = $this->where . " and type = '" . $type . "'";
		$this->where = $this->where . " and relativeKey = " . $relativeKey;
		$this->sql = "select * from " . $table . $this->where;
		
		$this->query($this->sql);		
		$this->nextRecode();
		
		$this->returnValue = $this->Recode[mon].'-'.$this->Recode[tue].'-'.$this->Recode[wed].'-'.
							 $this->Recode[thu].'-'.$this->Recode[fri].'-'.$this->Recode[sat].'-'.$this->Recode[sun].'-'.$this->Recode[id];
		
		return $this->returnValue;
	}
	
	//bbs에서 reply글이 존재하는 확인...
	public function bbs_reply_check($bbs_type, $text_type, $relativeKey, $field_name)
	{
		$this->returnValue;
		$this->where;
		$this->order;
		$this->sql;
		
		$this->where = " where bbs_type = '" . $bbs_type . "' ";
		$this->where = $this->where . " and text_type = '" . $text_type . "'";
		$this->where = $this->where . " and relative_key = " . $relativeKey;
		$this->order  = " order by id desc limit 0, 1";
		$this->sql = "select " . $field_name . " from z_bbs" . $this->where . $this->order;
		
		$this->query($this->sql);		
		$this->nextRecode();		
		$this->returnValue = $this->Recode[$field_name];
		
		return $this->returnValue;
	}
	
	//bbs(notice)에서 오늘기준으로 작성된지 5일이내글이 존재하는 확인
	//변수: 시작일자(타입스템프:$prev_day)~종료일자(타임스템프:$end_day)
	public function notice_auto_load_num_check($prev_day, $end_day)
	{
		$this->bbs_type = 'notice';
		$this->text_type = 'u';
		$this->where = " where bbs_type = '" . $this->bbs_type . "' ";
		$this->where = $this->where . " and text_type = '" . $this->text_type . "'";
		$this->where = $this->where . " and (inputDate >= '" . $prev_day . "'";
		$this->where = $this->where . " and  inputDate <= '" . $end_day . "')";
		$this->order  = " order by id desc";
		$this->sql = "select * from z_bbs" . $this->where . $this->order;
		
		$this->query($this->sql);	
		$this->returnValue = $this->totalNum();
		
		return $this->returnValue;
	}
	
	//========================= create(student_relative) ================================= //
	
	public function create_class($table, $type, $s_dbKey, $config_key, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " configkey, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $config_key . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_yoil($table, $s_dbKey, $yoil_opt, $type, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->yoil_opt_array = explode('-',$yoil_opt);
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " mon, tue, wed, thu, fri, sat, sun, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[0] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[1] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[2] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[3] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[4] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[5] . "'";
		$this->sql = $this->sql . ",'" . $this->yoil_opt_array[6] . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_etcTable($table, $type, $s_dbKey, $value, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " text, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $value . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_social_number($table, $type, $s_dbKey, $value, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " num1, num2, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",'" . $this->value_array[1] . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_birthday($table, $type, $s_dbKey, $value, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " year, month, day, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",'" . $this->value_array[1] . "'";
		$this->sql = $this->sql . ",'" . $this->value_array[2] . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_phone($table, $type, $s_dbKey, $value, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " num1, num2, num3, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",'" . $this->value_array[1] . "'";
		$this->sql = $this->sql . ",'" . $this->value_array[2] . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_address($table, $type, $s_dbKey, $post1, $post2, $address1, $address2, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " post1, post2, address1, address2, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $post1 . "'";
		$this->sql = $this->sql . ",'" . $post2 . "'";
		$this->sql = $this->sql . ",'" . $address1 . "'";
		$this->sql = $this->sql . ",'" . $address2 . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	public function create_memoTable($table, $type, $s_dbKey, $value, $ins_id)
	{
		$this->where;
		$this->sql;
		
		$this->sql = "insert into " . $table . 
				     " (type, relativeKey," .
				     " memo, ins_id)" .
				     " values ";
		$this->sql = $this->sql . "('" . $type . "'";
		$this->sql = $this->sql . ", " . $s_dbKey;
		$this->sql = $this->sql . ",'" . $value . "'";
		$this->sql = $this->sql . ",'" . $ins_id . "'";
		$this->sql = $this->sql . ")";
		
		$this->query($this->sql);
	}
	
	//========================= update(student) ================================= //
	
	public function update_class($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " configkey='" . $value . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}	
	
	public function update_yoil($table, $dbKey, $yoil_opt)
	{
		$this->where;
		$this->sql;
		
		$this->yoil_opt_array = explode('-',$yoil_opt);
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " mon='" . $this->yoil_opt_array[0] . "'";
		$this->sql = $this->sql . ",tue='" . $this->yoil_opt_array[1] . "'";
		$this->sql = $this->sql . ",wed='" . $this->yoil_opt_array[2] . "'";
		$this->sql = $this->sql . ",thu='" . $this->yoil_opt_array[3] . "'";
		$this->sql = $this->sql . ",fri='" . $this->yoil_opt_array[4] . "'";
		$this->sql = $this->sql . ",sat='" . $this->yoil_opt_array[5] . "'";
		$this->sql = $this->sql . ",sun='" . $this->yoil_opt_array[6] . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_etcTable($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " text='" . $value . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_memoTable($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " memo='" . $value . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_social_number($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " num1='" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",num2='" . $this->value_array[1] . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_birthday($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " year ='" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",month='" . $this->value_array[1] . "'";
		$this->sql = $this->sql . ",day  ='" . $this->value_array[2] . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_phone($table, $dbKey, $value)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " num1 ='" . $this->value_array[0] . "'";
		$this->sql = $this->sql . ",num2 ='" . $this->value_array[1] . "'";
		$this->sql = $this->sql . ",num3 ='" . $this->value_array[2] . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
	
	public function update_address($table, $dbKey, $value1, $value2, $value3, $value4)
	{
		$this->where;
		$this->sql;
		
		$this->value_array = explode('-',$value);
		
		$this->where = " where id = " . $dbKey;
		$this->sql = "update " . $table . " set ";
		$this->sql = $this->sql . " post1 	 ='" . $value1 . "'";
		$this->sql = $this->sql . ",post2 	 ='" . $value2 . "'";
		$this->sql = $this->sql . ",address1 ='" . $value3 . "'";
		$this->sql = $this->sql . ",address2 ='" . $value4 . "'";
		$this->sql = $this->sql . $this->where;
		
		$this->query($this->sql);
	}
}

?>