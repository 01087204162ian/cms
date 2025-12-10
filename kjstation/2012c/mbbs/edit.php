<?
	header('Content-Type: text/html; charset=utf-8');
	
	/** 공통 파일 참조 */
	include("common.php");
	
	/** 수정할 글 번호 받기 */
	$num = $_POST['num'];
	
	/** 수정할 원본글 불러오기 */
	db_open();
	$sql = "SELECT WRITER, SUBJECT, MEMO FROM FREE_BOARD WHERE BBS_ID=".$id." AND NUM=".$num;
	$result = mysql_query($sql);
	$data = mysql_fetch_row($result);
	$user_name = $data[0];
	$subject = $data[1];
	$memo = $data[2];
	db_close();
?>
<div data-role="page" id="edit">
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<h1>수정하기(<?=$bbs_title?>)</h1>
		<a data-icon="back" data-theme="a" data-rel="back">뒤로</a>
		<a data-icon="check" data-theme="b" id="btn_edit_save">저장</a>
	</div>
	<div data-role="content">
		<div data-role="fieldcontain">
			<label for="user_name">이름: </label>
			<input type="text" name="user_name" id="user_name" placeholder="이름을 입력하세요" value="<?=$user_name?>"/>
		</div>
		<div data-role="fieldcontain">
			<label for="subject">제목: </label>
			<input type="text" name="subject" id="subject" placeholder="글 제목을 입력하세요" value="<?=$subject?>"/>
		</div>
		<div data-role="fieldcontain">
			<label for="content">내용: </label>
			<textarea name="content" id="content" placeholder="글 내용을 입력하세요"><?=$memo?></textarea>
		</div>
		<input type="hidden" name="id" id="id" value="<?=$id?>" />
		<input type="hidden" name="num" id="num" value="<?=$num?>" />
	</div>
</div>
