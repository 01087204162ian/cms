<?
	header('Content-Type: text/javascript; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 입력값 받기 */
	$user_name = $_POST['user_name'];
	$user_pass = $_POST['user_pass'];
	$subject = $_POST['subject'];
	$content = $_POST['content'];

	/** 데이터베이스 연결 */
	db_open();

	/** 글 저장 쿼리문 생성 */
	$sql  = "INSERT INTO FREE_BOARD (";
	$sql .= "BBS_ID, SUBJECT, WRITER, PASSWORD, MEMO, REG_DATE";
	$sql .= ") VALUES (";
	$sql .= $id.", '";
	$sql .= $subject."', '";
	$sql .= $user_name."', '";
	$sql .= $user_pass."', '";
	$sql .= $content."', now());";

	/** 글 저장 처리 */
	mysql_query($sql);

	/** 방금 저장된 글에 대한 번호 조회 */
	$sql = "SELECT MAX(NUM) FROM FREE_BOARD";
	$data = mysql_fetch_row(mysql_query($sql));
	$num = $data[0];

	/** 데이터베이스 연결 해제 */
	db_close();
	
	/** 처리 결과 JSON 출력 */
	echo("{\"num\":\"".$num."\"}");
?>