<?
	header('Content-Type: text/javascript; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 글 번호 받기 */
	$num = $_POST['num'];

	/** 데이터베이스 연결 */
	db_open();

	/** 글 삭제 쿼리문 수행 */
	$sql = "DELETE FROM FREE_BOARD WHERE NUM=".$num;
	mysql_query($sql);

	/** 데이터베이스 연결 해제 */
	db_close();

	/** 처리 결과 JSON 출력 */
	echo("{\"result\":\"ok\"}");
?>