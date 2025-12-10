<?
header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일

$dsql="SELECT * FROM 2012DaeriCompany WHERE num='$id'";
$drs=mysql_query($dsql,$connect);
$drow=mysql_fetch_array($drs);
//증권번호 조회를 위하여 ...
	$certiSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$id' and InsuraneCompany='$com' and startyDay>='$yearbefore'";

	$rs=mysql_query($certiSql,$connect);
	$Rnum=mysql_num_rows($rs);

	$nam="추가";
			switch($com){
				case 1 :
					$inSom='흥국';
					break;
				case 2 :
					$inSom='동부';
					break;
				case 3 :
					$inSom='LiG';
					break;
				case 4 :
					$inSom='현대';
					break;
				case 5 :
					$inSom='한화';
					break;
				case 6 :
					$inSom='더케이';
					break;

			 }

?>
<?//증권이 하나인경우 바로 대리기사를 입력가능하고 
//만약 두개인 경우에 증권을 선택가능하게 


if($Rnum==1){?>


 <?include "./php/sele1.php";//write.php seleCerti.php 공통사용 ?>

<?}else{?>
<div data-role="page" id="view">
	<input type="hidden" id="id" value="<?=$id?>" />
	
	<div data-role="header" class="titlebar3" data-position="fixed">
		<a  href="driverList.php?id=<?=$id?>"  data-icon="home" data-theme="a" data-transition="flip">홈</a>
		<h1><?=$drow[company]?></h1>
		<a  href="endorseList.php?id=<?=$id?>" data-icon="plus" data-theme="b" data-transition="flip">진배</a>
	</div>
	<div data-role="content">
		<div class='ui-bar ui-bar-b'>
			배서기준일<input type='hidden' id='endorse_day' value='<?=$now_time?>' /><?=$now_time?>
		</div>

		<?for($j=0;$j<$Rnum;$j++){
			
			$row=mysql_fetch_array($rs);
			
		?>
		<div class='ui-body ui-body-e'>
			<h3><?=$inSom?> <?=$row[startyDay]?></h3>
			<p><?=$row[policyNum]?></p>
			<p class="ui-li-aside">
			<a href="write.php?com=<?=$com?>&id=<?=$id?>&CertiTableNum=<?=$row[num]?>" data-icon="edit" data-inline="true" data-role='button'><?=$inSom?><?=$nam?></a>
			
		</div>

		<?}?>
		<div class='ui-bar ui-bar-b'>
			사고접수 <?=$sos?>
		</div>
	</div>
	<!--<div data-role="footer" data-position="fixed">
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
				</li>
			</ul>
		</div>
	</div>-->
</div>




<?}//Rnum 종료?>
