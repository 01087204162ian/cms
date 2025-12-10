<?
	header('Content-Type: text/html; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 한 페이지에 보여질 글 목록의 수 */
	$list_size = 5;

	/** 데이터베이스 연결 */
	db_open();

	/** 전체 글 갯수 */
	$sql = "SELECT COUNT(NUM) FROM FREE_BOARD WHERE BBS_ID=".$id;
	$data = mysql_fetch_row(mysql_query($sql));
	$total_article_count = $data[0];

	/** 전체 페이지 수 */
	$total_page_count = ((int)(($total_article_count - 1) / $list_size)) + 1;

	/** 이전 페이지 */
	$prev_page = 0;
	if ($page > 1) $prev_page = $page-1;
	
	/** 다음 페이지 */
	$next_page = 0;
	if ($page < $total_page_count) $next_page = $page+1;

	/** 페이징 변수 */
	$start = ($page-1) * $list_size;
?>
<div data-role="page" id="list">
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<h1><?=$bbs_title?>(<?=$page?>/<?=$total_page_count?>)</h1>
		<a data-icon="home" data-theme="a" href="index.html" data-direction="reverse" >홈</a>
		<a data-icon="plus" data-theme="b" href="write.php?id=<?=$id?>">쓰기</a>
	</div>
	<div data-role="content">
<?	
	/** 전체 글 수에 따른 분기 */
	if ($total_article_count < 1) {
		echo("<p align=\"center\">저장된 내용이 없습니다.</p>");
	} else {
		/** 리스트 뷰 출력을 위한 시작 */
		echo("<ul data-role='listview' data-filter='true'>");

		/** 글 조회 쿼리문 수행 */
		$sql = "SELECT NUM, SUBJECT, MEMO, REG_DATE FROM FREE_BOARD WHERE BBS_ID=".$id." ORDER BY NUM DESC LIMIT ".$start.", ".$list_size;
		$result = mysql_query($sql);
		
		/** 수행 결과만큼 반복 처리 */
		while ($data = mysql_fetch_row($result)) {
			$num = $data[0];
			$subject = $data[1];
			$memo = $data[2];
			$regdate = $data[3];
			$regdate = str_replace("-", "/", substr($regdate, 5, 5));
			
			//----- 글 목록을 리스트뷰에 출력 -----
?>            	
                	<li>
                		<a href="view.php?id=<?=$id?>&num=<?=$num?>&page=<?=$page?>">
                			<h3><?=$subject?></h3>
                			<p><?=$memo?></p>
                			<p class="ui-li-aside"><?=$regdate?></p>
                		</a>
                	</li>
<?
		} // end while
		
		/** 데이터 조회 결과 메모리 반납 */
		mysql_free_result($result);

		/** 리스트 뷰 끝 */
		echo("</ul>");
	} // end if
	
	/** 데이터베이스 연결 해제 */
	db_close();
?>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
<?
	/** 이전 페이지가 존재할 경우 처리 */
	if ($prev_page > 0) {
?>
				<li>
					<a href="list.php?id=<?=$id?>&page=<?=$prev_page?>" data-icon="back" data-transition="flip" data-direction="reverse">이전목록</a>
				</li>
<?
	}

	/** 다음 페이지가 존재할 경우 처리 */
	if ($next_page > 0) {
?>
				<li>
					<a href="list.php?id=<?=$id?>&page=<?=$next_page?>" data-icon="forward" data-transition="flip">다음목록</a>
				</li>
<?
	}
?>
			
			</ul>
		</div>
	</div>
</div>
