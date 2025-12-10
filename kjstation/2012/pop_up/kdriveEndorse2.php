<?
include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<!--<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<!--<script src="./js/smsAjaxState.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/kdriveEndorse.js" type="text/javascript"></script><!--ajax-->

</head>

<?
include '../../dbcon.php';
$mstart=$_GET['mstart'];
$estart=$_GET['estart'];

$date =$_GET['date'];
$daeriCompanyNum=$_GET['num'];
$normalT =$_GET['normalT']; //2022-03-05 값이 1이면 정상 분납 보험료가 표시됨


$sigi=$_GET['sigi'];
$sql="SELECT * FROM 2012DaeriCompany WHERE num='$daeriCompanyNum'";
//echo $sql;
$rs=mysql_query($sql,$connect);
$row=mysql_fetch_array($rs);


//memo값을 찾기 위해 // 
$sqlm="SELECT * FROM ssang_c_memo WHERE c_number='$row[jumin]' and memokind='2'";
$rsm=$rs=mysql_query($sqlm,$connect);
$rowM=mysql_fetch_array($rsm);

$damdanga=$row[MemberNum];

		$dsSql="SELECT * FROM  2012Member WHERE num='$damdanga'";
		$dsrs=mysql_query($dsSql,$connect);
		$dsrow=mysql_fetch_array($dsrs);


		//$damName=$dsrow[name];
$output_file_name = $row['company'].$date;
			
//증권별로  배서 기록을 찾아서 추가자 인원수 찾기

//일별 대리,탁송 별 추가,해지 건수 및 금액 확인

			$prem1_1=0; //대리 추가금액
			$prem2_1=0; //탁송 추가금액
			$prem1_2=0; //대리 해지금액
			$prem2_2=0; //탁송 해지금액
			$gunsu1_1=0;    //대리 추가건수
			$gunsu2_1=0;    //탁송 추가건수
			$gunsu1_2=0;    //대리 해지건수
			$gunsu2_2=0;    //탁송 해지건수
			$e_sql="SELECT  *  FROM SMSData a left join 2012DaeriCompany  b ";
			$e_sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.num='$num' and push>='1' ";
			$e_sql.="and (endorse_day='$date') and a.dagun='1'  order by SeqNo desc";

				//echo $j;echo $e_sql;
				
				$e_rs=mysql_query($e_sql,$connect);

				$e_num=mysql_num_rows($e_rs);
				

				
				for($k=0;$k<$e_num;$k++){
					$j_=$k+1;


				
					$e_row=mysql_fetch_array($e_rs);
					if($e_row['qboard']==2){ //킥보드는 제외하기 위해
						continue;
					}
					//echo $e_row['SeqNo'];
					$a[50]=$e_row[preminum2];
					
					$daeriMemberNum=$e_row['2012DaeriMemberNum'];
							$e2_sql="SELECT * FROM 2012DaeriMember WHERE num='$daeriMemberNum'";
							$e2_rs=mysql_query($e2_sql,$connect);
							$e2_row=mysql_fetch_array($e2_rs);
					$jumin=explode("-",$e2_row['Jumin']);
							//echo $e2_sql;
					
					switch($e2_row[etag]){
						case 1 : 
							$metat1="대리";
							switch($e_row['push']){
								case 2 :
									$pushName='해지';
									
									$gunsu1_2++;
									$a[50]=-$a[50];
									$prem1_2+=$e_row[preminum];
									break;
								case 4 :
									$pushName='추가';
									
									$a[50]=$a[50];
									$gunsu1_1++;
									$prem1_1+=$e_row[preminum];
									break;
							}

							break;
						case 2 :

							$metat2="탁송";

							switch($e_row['push']){
								case 2 :
									$pushName='해지';
									
									$gunsu2_2++;
									$a[50]=-$a[50];
									$prem2_2+=$e_row[preminum];
									break;
								case 4 :
									$pushName='추가';
									
									$a[50]=$a[50];
									$gunsu2_1++;
									$prem2_1+=$e_row[preminum];
									break;
							}
							break;
						default:
							$metat3="전탁송";
							break;
					}
					
			switch($e_row[insuranceCom]){
			case 1 :
				$inName='흥국';
			
				break;
			case 2 :
				$inName='DB';
			

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$inName='KB';
			
				break;
			case 4 :
				$inName='현대';
			
				break;
			case 5 :
				$inName='한화';
			
				break;
			case 6 :
				$inName='더케이';
			
				break;
			case 7 :
				$inName='MG';
			
				break;
			case 8 :
				$inName='삼성';
			
				break;


		}
		$pSql="SELECT * FROM 2012CertiTable WHERE num='$e2_row[CertiTableNum]'";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);


			$policy[$j]=$pRow[policyNum];
			$divi[$j]=$pRow[divi];

			switch($divi[$j]){
				case 1 :
					$divi_name[$j]='직접';
					
					break;
				case 2 :
					$divi_name[$j]='1/12';
					
					break;
				default:
					$divi_name[$j]='직접';
				    
					break;
			}
			
					$ju__=explode('-',$e_row[endorse_day]);
					if($row[FirstStartDay]==$ju__[2]){
					
						$e_row[preminum]=0;
						$a[50]=0;
					}


					$totalPrice+=$e_row[preminum];//배서보험료 소계
					$totalPrice2+=$a[50];//배서보험료 소계(지사로 부터 받은 금액)
			//배서일과 받는일이 같은 경우 배서보험료 0

			// 2022-03-05 각 운전자의 증권번호를 $e2_row['dongbuCerti'] 에서 $e_row['policyNum']로 교체함
			// 배서 당시의 증권번호로 표기 하기함
				


				}
	        /*$prem1_1=0; //대리 추가금액
			$prem2_1=0; //탁송 추가금액
			$prem1_2=0; //대리 해지금액
			$prem2_2=0; //탁송 해지금액
			$gunsu1_1=0;    //대리 추가건수
			$gunsu2_1=0;    //탁송 추가건수
			$gunsu1_2=0;    //대리 해지건수
			$gunsu2_2=0;    //탁송 해지건수*/
				
				//echo $metat1; echo "추가건수"; echo $gunsu1_1; echo "추가금액"; echo $prem1_1; echo "해지건수"; echo $gunsu1_2; echo "해지금액"; echo $prem1_2;  echo "//";

				//echo $metat2; echo "추가건수"; echo $gunsu2_1; echo "추가금액"; echo $prem2_1; echo "해지건수"; echo $gunsu2_2; echo "해지금액"; echo $prem2_2;

			
		
		$e_sql="SELECT num FROM kdriveendorse WHERE endorse_day='$date'";
		$e_rs=mysql_query($e_sql,$connect);
		$e_row=mysql_fetch_array($e_rs);


		$kdriveNum=$e_row['num'];

		if($kdriveNum){

				$updat_sql="UPDATE kdriveendorse SET ";
				$updat_sql.="gunsu1_1='$gunsu1_1',gunsu1_2='$gunsu1_2',prem1_1='$prem1_1',prem1_2='$prem1_2' ,";
				$updat_sql.="gunsu2_1='$gunsu2_1',gunsu2_2='$gunsu2_2',prem2_1='$prem2_1',prem2_2='$prem2_2' ";
				$updat_sql.=" WHERE num='$kdriveNum'";
				mysql_query($updat_sql,$connect);

		}else{

				$insert_sql = "INSERT INTO kdriveendorse (daericompanyNum,endorse_day, ";
				$insert_sql .= "gunsu1_1,gunsu1_2,prem1_1,prem1_2, ";
				$insert_sql .= "gunsu2_1,gunsu2_2,prem2_1,prem2_2) ";
				$insert_sql.= "VALUES ('$daeriCompanyNum','$date', ";
				$insert_sql.= "'$gunsu1_1','$gunsu1_2','$prem1_1','$prem1_2',";
				$insert_sql.= "'$gunsu2_1','$gunsu2_2','$prem2_1','$prem2_2')";

				mysql_query($insert_sql,$connect);
		}


		 $week = array("일", "월", "화", "수", "목", "금", "토");

		$weekday = $week[ date('w'  , strtotime($date)  ) ] ;

		$total_gunsu=$gunsu1_1+$gunsu1_2+$gunsu2_1+$gunsu2_2;


		
		$total_p=$prem1_1-$prem1_2+$prem2_1-$prem2_2;


		/*    echo $prem1_1;
			echo $prem1_2;
			echo $prem2_1;
			echo $prem2_2;
			echo $total_p;*/
?>
<body>
<form>
	<input type='text' id='daeriCompanyNum' value='<?=$daeriCompanyNum?>' />
	<input type='text' id='date' value='<?=$date?>'>
		  <div id="contentWrapper">
			<div id="kdrive" >
				<table>
					<tr>
						<th width='10%' rowspan='3'>날자</th>
						<th width='4%' rowspan='3'>요일</th>
						<th width='24%' colspan='5'>배서인원</th>
						<th width='20%' colspan='2' rowspan='2'>추징금</th>
						<th width='20%' colspan='2' rowspan='2'>환급금</th>
						<th width='18%' rowspan='3'>계좌 환급 예상금액</th>
						<th width='4%' rowspan='3'>비고</th>
				   </tr>
				   
				   <tr>
						<th width='12%' colspan='2'>대리</th>
						<th width='12%' colspan='2'>탁송</th>
						<th width='6%' rowspan='2'>합계</th>	
				   </tr>
					<tr>	
						<th width='6%'>추가</th>
						<th width='6%'>해지</th>
						<th width='6%'>추가</th>
						<th width='6%'>해지</th>	
						<th width='10%'>대리</th>
						<th width='10%'>탁송</th>
						<th width='10%'>대리</th>
						<th width='10%'>탁송</th>
				   </tr>
				  <tbody class="scrollingContent">
				 <tr>
					<th ><?=$date?></th>
					<th><?=$weekday?></th>
					<td align='right'><?=$gunsu1_1?></td>
					<td align='right'><?=$gunsu1_2?></td>
					<td align='right'><?=$gunsu2_1?></td>
					<td align='right'><?=$gunsu2_2?></td>
					<td><?=$total_gunsu?></td>
					<td><input type='text' class='textareR' id='prem1_1' value='<?=number_format($prem1_1)?>' onBlur="prem(this.id,this.value,'1')"></td>
					<td><input type='text' class='textareR' id='prem2_1' value='<?=number_format($prem2_1)?>' onBlur="prem(this.id,this.value,'2')"></td>
					<td><input type='text' class='textareR' id='prem1_2' value='<?=number_format($prem1_2)?>' onBlur="prem(this.id,this.value,'3')"></td>
					<td><input type='text' class='textareR' id='prem2_2' value='<?=number_format($prem2_2)?>' onBlur="prem(this.id,this.value,'4')"></td>
					<td><input type='text' class='textareR' id='total_p' value='<?=number_format($total_p)?>'></td>
					<td></td>
				 </tr>
				 </tbody>
				</table>
			</div>		
		  </div>
</form>
</body>
</html>





