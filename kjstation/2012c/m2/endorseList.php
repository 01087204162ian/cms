<?
header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';
include '../php/customer_endorse_day_lig2.php';//배서기준일
$id=iconv("utf-8","euc-kr",$_GET['id']);//id는 DaecompanNum 해당

$dsql="SELECT * FROM 2012DaeriCompany WHERE num='$id'";
$drs=mysql_query($dsql,$connect);
$drow=mysql_fetch_array($drs);

	//대리기사 
		$sql="SELECT * FROM 2012DaeriMember WHERE sangtae='1' and 2012DaeriCompanyNum='$id' order by Jumin asc";
		$rs=mysql_query($sql,$connect);
		$Num=mysql_num_rows($rs);
	//대리기사 조회 끝
?>	

<div data-role="page" id="endorselist">
	<div data-role="header" class="titlebar2" data-position="fixed">
		<a data-icon="home" data-theme="a" href="driverList.php?id=<?=$id?>"  data-transition="flip" >홈</a>
		<h1><?=$drow[company]?></h1>
		
		
	</div>
	<div data-role="content">
			<!--<p><span>배서기준일<input type='hidden' id='endorse_day' value='<?=$now_time?>' /><?=$now_time?><?=$Num?>명</span></p>-->
			<ul data-role='listview' >
			<?
			if($Num>0){
					for($j=0;$j<$Num;$j++){
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
									case 7 :
									$inSom[$_p]='MG';
									break;
								case 8 :
									$inSom[$_p]='삼성';
									break;
								}	// switch 조회
								
								switch($row[push]){
									case 1 :
										$pushName='청약중';
										break;
									case 4:
										$pushName='해지중';
										break;
								}
								
								?>	

								
						<li> 
							<h3><?=$j+1?><?=$a?><?=$row[Name]?><?=$row[Jumin]?></h3>
							<p><?=$inSname?>&nbsp;<?=$row[dongbuCerti]?></p>
							<p class="ui-li-aside"><?=$pushName?></p>
										
						</li>
					<?}?>
				<?}else{?>
					<li> 
						<p>진행중인 배서가 없습니다</p>
										
					</li>
				<?}?>
					</ul>
	</div>


<? $sql="SELECT distinct(InsuraneCompany) FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$id' and startyDay>='$yearbefore' ";
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
								case 7 :
									$inSom[$_p]='MG추가';
									break;
								case 8 :
									$inSom[$_p]='삼성추가';
									break;
						}?>
			  <a  href="write.php?com=<?=$row[InsuraneCompany]?>&id=<?=$id?>" data-icon="plus" data-theme="b" data-transition="flip"><?=$inSom[$_p]?></a>
		     <!-- <li><a href="#"><?=$row[InsuraneCompany]?><?=$inSom[$_p]?></a></li>-->
			<?}?>
		  </ul>
		 </div>
	</div>

</div>