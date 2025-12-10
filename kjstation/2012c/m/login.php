<?
	header('Content-Type: text/html; charset=euc-kr');

include '../../dbcon.php';
include './customer_endorse_day_lig2.php';//배서기준일
$mem_id=iconv("utf-8","euc-kr",$_GET['memid']);
$passwd=iconv("utf-8","euc-kr",$_GET['upassword']);
$query = "SELECT * FROM 2012Costomer WHERE mem_id='$mem_id' LIMIT 1";//새로운 회원가입 명단
$rs = mysql_query($query,$connect);
$row = mysql_fetch_array($rs);
$dNumber=$row['2012DaeriCompanyNum'];
$dsql="SELECT * FROM 2012DaeriCompany WHERE num='$dNumber'";
$drs=mysql_query($dsql,$connect);
$drow=mysql_fetch_array($drs);
?>
<div data-role="page" id="list">
	<div data-role="header" class="titlebar<?=$id?>" data-position="fixed">
		<h1><?=$drow[company]?></h1>
		<a data-icon="home" data-theme="a" href="index.html" data-direction="reverse" >홈</a>
		<a data-icon="plus" data-theme="b" href="write.php?id=<?=$id?>">쓰기</a>
	</div>
	<div data-role="content">
<?
if($row[permit]==1){
	if ($row[passwd] == md5($passwd)){
		//대리기사 조회 
		$sql="SELECT * FROM 2012DaeriMember WHERE push='4' and 2012DaeriCompanyNum='$dNumber' order by Jumin asc";
		$rs=mysql_query($sql,$connect);
		$Num=mysql_num_rows($rs);
		//대리기사 조회 끝
		?>	
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
						}						
			?>	
				<li> 
					
					<h3><?=$j+1?><?=$a?><?=$row[Name]?><?=$row[Jumin]?></h3>
					<p><?=$inSname?>&nbsp;<?=$row[dongbuCerti]?></p>
					<p class="ui-li-aside"><a data-icon="edit" data-role="actionsheet" data-sheet='edit_password' data-inline="true">정상</a></p>
					<form id='edit_password' style="display:none">
						<div class="ui-bar ui-bar-a"><?=$row[Name]?>해지 합니다</div>			
						<input type='hidden' id='password' value='<?=$row[num]?>' />
						<a data-role='button' id="btn_edit" data-mini="true" data-theme="b">OK</a>
						<a data-role='button' data-rel='close' data-mini="true">Cancel</a>
				     </form>			
				</li>
			<?}?>
			</ul>
	</div>
<? $sql="SELECT distinct(InsuraneCompany),num FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$dNumber' and startyDay>='$yearbefore' ";
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
			  <a data-icon="plus" data-theme="b" href="write.php?id=<?=$row[InsuraneCompany]?>&dNumber=<?=$dNumber?>&CertiTableNum=<?=$row[num]?>"><?=$inSom[$_p]?></a>
		     <!-- <li><a href="#"><?=$row[InsuraneCompany]?><?=$inSom[$_p]?></a></li>-->
			<?}?>
		  </ul>
		 </div>
	</div>
	<?	//header("Location: $redirect");
	}else{?>
		 <div  data-role="content" >	
			

		<div>
	  <!-- echo "<script>alert('비밀번호가 틀렸습니다 ./n기다려 주세요.');history.back();</script>";-->
	<?}
}else{
	//header("/kibs_admin.php");
	//echo "<script>alert('관리자가 승인을 하지 않았습니다./n기다려 주세요.');history.back();</script>";
}

?>
</div>