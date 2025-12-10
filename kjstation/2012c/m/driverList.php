<?
header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일
$id=iconv("utf-8","euc-kr",$_GET['id']);//id는 DaecompanNum 해당

$dsql="SELECT * FROM 2012DaeriCompany WHERE num='$id'";
$drs=mysql_query($dsql,$connect);
$drow=mysql_fetch_array($drs);

	//대리기사 조회 
		$sql="SELECT * FROM 2012DaeriMember WHERE (push ='4' or (push='1' and sangtae='1')) and 2012DaeriCompanyNum='$id' order by Jumin asc";
		$rs=mysql_query($sql,$connect);
		$Num=mysql_num_rows($rs);
	//대리기사 조회 끝
?>	

<div data-role="page" id="list">
	<div data-role="header" class="titlebar1" data-position="fixed">
		<h1><?=$drow[company]?></h1>
		<a data-icon="home" data-theme="a" href="driverList.php?id=<?=$id?>"  >홈</a>
		<a data-icon="plus" data-theme="b" href="endorseList.php?id=<?=$id?>">진배</a>
	</div>
	<div data-role="content">
			<p><span>배서기준일<input type='hidden' id='endorse_day' value='<?=$now_time?>' /><?=$now_time?><?=$Num?>명</span></p>
			<ul data-role='listview' data-filter='true'>
			<?for($j=0;$j<$Num;$j++){
				$a='.';
					$row=mysql_fetch_array($rs);
						$jumin=explode("-",$row[Jumin]);
						switch($row[InsuranceCompany]){
							case 1 : 
								$inSname="흥국";
								break;
							case 2 :
								$inSname="동부";
								break;
							case 3 :
								$inSname="LiG";
								break;
							case 4 :
								$inSname="현대";
								break;
							case 5 :
								$inSname="한화";
								break;
						}	// switch 조회
						

						if($row[cancel]==42){
							$row[push]=8;
						}
						switch($row[push]){
							
							case 1 :
								$pushName='청약중';
								break;
							case 4 :
								$pushName='정상';
								break;
							case 8 :
								$pushName='해지중';
								break;
						}
						
						
						
						?>
						

						
				<li> 
					<h3><?=$j+1?><?=$a?><?=$row[Name]?><?=$row[Jumin]?></h3>
					<p><?=$inSname?>&nbsp;<?=$row[dongbuCerti]?></p>
				<?if($row[push]==4){?>
					<p class="ui-li-aside">
					<a data-icon="edit" data-role="actionsheet" data-sheet='edit_password' data-inline="true"><?=$pushName?></a>
					</p>
					<form id='edit_password' style="display:none">
						<div class="ui-bar ui-bar-a"><?=$row[Name]?>해지 합니다</div>	
						<input type='hidden' id='push' value='<?=$row[push]?>' />
						<input type='hidden' id='password' value='<?=$row[num]?>' />
						<a data-role='button' id="btn_edit" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
				     </form>	
				<?}else{?>
					<p class="ui-li-aside"><?=$pushName?></p>
				<?}?>
							
				</li>
			<?}//for문 조회 ?>
			</ul>
	</div>


<? $sql="SELECT distinct(InsuraneCompany),num FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$id' and startyDay>='$yearbefore' ";
//echo "Sql $sql <br>";
   $rs=mysql_query($sql,$connect);
   $Ccount=mysql_num_rows($rs);
?>
	<div data-role="footer" data-theme="b" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<?	for($_p=0;$_p<$Ccount;$_p++){
						$row=mysql_fetch_array($rs);
							switch($row[InsuraneCompany]){
								case 1 :
									$inSom[$_p]='흥국추가';
									break;
								case 2 :
									$inSom[$_p]='동부추가';
									break;
								case 3 :
									$inSom[$_p]='LiG추가';
									break;
								case 4 :
									$inSom[$_p]='현대추가';
									break;
								case 5 :
									$inSom[$_p]='한화추가';
									break;
								case 6 :
									$inSom[$_p]='더케이추가';
									break;
						}?>
			  <li><a data-icon="plus" data-theme="b" href="write.php?id=<?=$row[InsuraneCompany]?>&dNumber=<?=$id?>&CertiTableNum=<?=$row[num]?>" ><?=$inSom[$_p]?></a></li>
		     <!-- <li><a href="#"><?=$row[InsuraneCompany]?><?=$inSom[$_p]?></a></li>-->
			<?}?>
		  </ul>
		 </div>
	</div>

</div>