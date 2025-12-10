<?
	header('Content-Type: text/html; charset=euc-kr');
	//include("common.php");


include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일

$id=iconv("utf-8","euc-kr",$_GET['id']);

switch($id){
	case 1 :
		$inSom='흥국추가';
		break;
	case 2 :
		$inSom='동부추가';
		break;
	case 3 :
		$inSom='LiG추가';
		break;
	case 4 :
		$inSom='현대추가';
		break;
	case 5 :
		$inSom='한화추가';
		break;
	case 6 :
		$inSom='더케이추가';
		break;

 }
?>
<div data-role="page" id="write">
	<div data-role="header" class="titlebar3" data-position="fixed">
		<h1><span>운전자 추가</h1>
		<a data-icon="home" data-theme="a" href="driverList.php?id=<?=$dNumber?>"  >홈</a>
		<a data-icon="check" data-theme="b" id="btn_write_save">저장</a>
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
		<input type="hidden" name="DaeriCompanyNum" id="DaeriCompanyNum" value="<?=$dNumber?>" /><!--DaeriCompanyNum-->
		<input type="hidden" name="insuranceNum" id="insuranceNum" value="<?=$id?>" /><!--보험회사-->
		<input type="hidden" name="CertiTableNum" id="CertiTableNum" value="<?=$CertiTableNum?>" /><!--CertiTableNum-->
	</div>
</div>