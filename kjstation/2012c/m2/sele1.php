<div data-role="page" id="write">
	<input type="hidden" id="id" value="<?=$id?>" />
	
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<a data-icon="home" data-theme="a" href="driverList.php?id=<?=$id?>"  >홈</a>
		<h1><?=$drow[company]?></h1>
		<a data-icon="plus" data-theme="b" href="endorseList.php?id=<?=$id?>">진배</a>
	</div>
	<div data-role="content">
		<div class='ui-bar ui-bar-b'>
			배서기준일 <?=$endorse_day?>
		</div>
		<div class='ui-body ui-body-e'>
			<?=$row[Name]?><br/>
			<?=$row[Jumin]?><hr />
			<?=$inSname?><?=$row[dongbuCerti]?>
		</div>
		<div class='ui-bar ui-bar-b'>
			사고접수 <?=$sos?>
		</div>
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li>
					<a data-icon="edit" data-role="actionsheet" data-sheet='haeji'>해지</a>
					<form id='haeji' style="display:none">
						<div class="ui-bar ui-bar-a"><?=$row[Name]?>해지 합니다</div>
						<input type='hidden' id='endorse_day' value='<?=$endorse_day?>' />
						<input type="hidden" id="num" value="<?=$num?>" />
						<input type='hidden' id='push' value='<?=$row[push]?>' />
						<a data-role='button' id="btn_edit" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
					</form>
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