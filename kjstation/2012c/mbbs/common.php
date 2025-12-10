<?
	/** 게시판 구분을 위한 번호 */
	$id = $_GET['id'];
	if (!$id) $id = $_POST['id'];
	if (!$id) $id = 1;

	/** 리스트 페이징에 사용되는 페이지 번호 */
	$page = $_GET['page'];
	if (!$page) $page = $_POST['page'];
	if (!$page) $page = 1;

	/** 게시판 제목 배열 */
	$bbs_titles[] = "본관";
	$bbs_titles[] = "연구동";
	$bbs_titles[] = "강의동";
	$bbs_titles[] = "종합연구강의동";
	$bbs_titles[] = "유교관";
	$bbs_titles[] = "실습동";

	/** 게시판 번호를 통하여 제목 배열에서 현재 머물고 있는 게시판 이름 추출 */
	$bbs_title = $bbs_titles[$id-1];

	/** 데이터베이스 연결 객체 */
	$dbcon = null;

	/** 데이터베이스 연결 함수 */
	function db_open() {
		global $dbcon;
		$dbcon = mysql_connect("localhost", "sam0327", "in716671");
		mysql_select_db("sam0327", $dbcon);
	}

	/** 데이터베이스 연결 해제 함수 */
	function db_close() {
		global $dbcon;
		mysql_close($dbcon);
	}
?>