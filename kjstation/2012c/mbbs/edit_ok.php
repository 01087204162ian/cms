<?
	header('Content-Type: text/javascript; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 입력값 받기 */
	$user_name = $_POST['user_name'];
	$subject = $_POST['subject'];
	$content = $_POST['content'];
	$num = $_POST['num'];

	/** 데이터베이스 연결 */
	db_open();

	/** 글 수정 쿼리문 수행 */
	$sql = "UPDATE FREE_BOARD SET ";
	$sql.= "WRITER='".$user_name."', ";
	$sql.= "SUBJECT='".$subject."', ";
	$sql.= "MEMO='".$content."' ";
	$sql.= "WHERE NUM=".$num;
	mysql_query($sql);
	
	db_close();

	echo("{\"result\":\"ok\"}");
?>