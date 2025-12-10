<?
	header('Content-Type: text/html; charset=utf-8');
	include("common.php");
?>
<div data-role="page" id="write">
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<h1>새글쓰기(<?=$bbs_title?>)</h1>
		<a data-icon="back" data-theme="a" data-rel="back">뒤로</a>
		<a data-icon="check" data-theme="b" id="btn_write_save">저장</a>
	</div>
	<div data-role="content">
		<div data-role="fieldcontain">
			<label for="user_name">이름: </label>
			<input type="text" name="user_name" id="user_name" placeholder="이름을 입력하세요" />
		</div>
		<div data-role="fieldcontain">
			<label for="user_pass">비밀번호: </label>
			<input type="password" name="user_pass" id="user_pass" placeholder="비밀번호를 입력하세요" />
		</div>
		<div data-role="fieldcontain">
			<label for="subject">제목: </label>
			<input type="text" name="subject" id="subject" placeholder="글 제목을 입력하세요" />
		</div>
		<div data-role="fieldcontain">
			<label for="content">내용: </label>
			<textarea name="content" id="content" placeholder="글 내용을 입력하세요"></textarea>
		</div>
		<input type="hidden" name="id" id="id" value="<?=$id?>" />
	</div>
</div>
