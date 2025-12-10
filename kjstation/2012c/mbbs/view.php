<?
	header('Content-Type: text/html; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");

	/** 글 번호 받기 */
	$num = $_GET['num'];

	/** 데이터베이스 연결 */
	db_open();

	/** 글 내용 조회 */
	$sql = "SELECT WRITER, SUBJECT, MEMO, REG_DATE FROM FREE_BOARD WHERE NUM=".$num;
	$result = mysql_query($sql, $dbcon);
	$data = mysql_fetch_row($result);
	
	/** 조회 결과를 변수에 담는다. */
	$writer = $data[0];
	$subject = $data[1];
	$content = $data[2];
	$regdate = $data[3];
?>
<div data-role="page" id="view">
	<input type="hidden" id="id" value="<?=$id?>" />
	<input type="hidden" id="num" value="<?=$num?>" />
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<h1>글 읽기(<?=$bbs_title?>)</h1>
		<a data-icon="back" data-theme="b" href="list.php?id=<?=$id?>&page=<?=$page?>" data-direction="reverse" class="ui-btn-right">목록</a>
	</div>
	<div data-role="content">
		<div class='ui-bar ui-bar-b'>
			<?=$subject?>
		</div>
		<div class='ui-body ui-body-e'>
			작성자: <?=$writer?><br/>
			작성일시: <?=$regdate?><hr />
			<?=nl2br(htmlspecialchars($content))?>
		</div>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li>
					<a data-icon="edit" data-role="actionsheet" data-sheet='edit_password'>수정</a>
					<form id='edit_password' style="display:none">
						<div class="ui-bar ui-bar-a">글 수정</div>
						<input id='password' type='password' placeholder='비밀번호를 입력하세요.'/>
						<a data-role='button' id="btn_edit" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
					</form>
				</li>
				<li>
					<a data-icon="delete" data-role="actionsheet" data-sheet='delete_password'>삭제</a>
					<form id='delete_password' style="display:none">
						<div class="ui-bar ui-bar-a">글 삭제</div>
						<input id='password' type='password' placeholder='비밀번호를 입력하세요.'/>
						<a data-role='button' id="btn_delete" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
					</form>
				</li>
			</ul>
		</div>
	</div>
</div>
