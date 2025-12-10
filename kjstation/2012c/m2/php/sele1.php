
<? $row=mysql_fetch_array($rs); ?>

<div data-role="page" id="write">
	<div data-role="header" class="titlebar3" data-position="fixed">
		<a data-icon="home" data-theme="a" href="driverList.php?id=<?=$id?>" data-transition="flip" >홈</a>
		<h1><?=$drow[company]?>운전자 추가</h1>
		<a data-icon="plus" data-theme="b" href="endorseList.php?id=<?=$id?>" data-transition="flip">진배</a>
	</div>
	<div data-role="content">
		<li> 
			<h3>배서기준일<input type='hidden' id='endorse_day' value='<?=$now_time?>' /><?=$now_time?>(<?=$inSom?>)</h3>						
		 </li>
		<div data-role="fieldcontain">
			<label for="user_name">이름: </label>
			<input type="text" name="user_name" id="user_name" placeholder="이름을 입력하세요" />
		</div>
		<div data-role="fieldcontain">
			<label for="user_jumin">주민번호 </label>
			<input type="text" name="user_jumin" id="user_jumin" placeholder="주민번호를 입력하세요" />
		</div>
	<!--	<div data-role="fieldcontain">
			<label for="subject">제목: </label>
			<input type="text" name="subject" id="subject" placeholder="글 제목을 입력하세요" />
		</div>
		<div data-role="fieldcontain">
			<label for="content">내용: </label>
			<textarea name="content" id="content" placeholder="글 내용을 입력하세요"></textarea>
		</div>-->
		<input type="text" name="DaeriCompanyNum" id="DaeriCompanyNum" value="<?=$id?>" /><!--DaeriCompanyNum-->
		<input type="text" name="insuranceNum" id="insuranceNum" value="<?=$com?>" /><!--보험회사-->
		<input type="text" name="CertiTableNum" id="CertiTableNum" value="<?=$row[num]?>" /><!--CertiTableNum-->
	</div>

	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li>
				   <a data-icon="check" data-theme="b" id="btn_write_save">저장</a>
				<!--	<a data-icon="edit" data-role="actionsheet" data-sheet='haeji'>해지</a>
					<form id='haeji' style="display:none">
						<div class="ui-bar ui-bar-a"><?=$row[Name]?>해지 합니다</div>
						<input type='hidden' id='endorse_day' value='<?=$endorse_day?>' />
						<input type="hidden" id="num" value="<?=$num?>" />
						<input type='hidden' id='push' value='<?=$row[push]?>' />
						<a data-role='button' id="btn_edit" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
					</form>-->
				</li>
			<!--	<li>
					<a data-icon="delete" data-role="actionsheet" data-sheet='delete_password'>삭제</a>
					<form id='delete_password' style="display:none">
						<div class="ui-bar ui-bar-a">글 삭제</div>
						<input id='password' type='password' placeholder='비밀번호를 입력하세요.'/>
						<a data-role='button' id="btn_delete" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
					</form>
				</li>-->
			</ul>
		</div>
	</div>
</div>