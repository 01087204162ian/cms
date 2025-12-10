<?
	header('Content-Type: text/javascript; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 글 번호 및 비밀번호 받기 */
	$num = $_POST['num'];
	$user_pass = $_POST['user_pass'];

	/** 데이터베이스 연결 */
	db_open();

	/** 비밀번호 검사 수행 */
	$sql = "SELECT COUNT(NUM) FROM FREE_BOARD WHERE NUM=".$num;
	$sql.= " AND PASSWORD='".$user_pass."'";
	$data = mysql_fetch_row(mysql_query($sql));
	$num = $data[0];

	$result = "true";
	
	/** 비밀번호와 글 번호가 동일한 데이터가 없을 경우 비밀번호 맞지 않음 */
	if ($num < 1) {
		$result = "false";
	}

	/** 데이터베이스 연결 해제 */
	db_close();

	/** 처리 결과 JSON 출력 */
	echo("{\"result\":".$result."}");
?>