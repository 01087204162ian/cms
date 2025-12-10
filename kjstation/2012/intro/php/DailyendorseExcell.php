<?header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment: filename='<?=$c_name?>.xls'");
header("Content-Descripition: PHP4 Generated Datea");

//include '/sj/login/lib_session.php';
include '../../../dbcon.php';


//현재 1년 안에 있는 증권리스트 
$sql_="SELECT * FROM 2012Certi WHERE sigi>='$yearbefore'";
$rs_=mysql_query($sql_,$connect);
$num_=mysql_num_rows($rs_);

	
	//$sigi="2016-01-07";
for($_p=0;$_p<$num_;$_p++){

	$row_=mysql_fetch_array($rs_);


		$where="WHERE  a.dongbuCerti='$row_[certi]' and b.endorse_day='$sigi'  order by b.damdanga desc ";

		//$where="WHERE  policyNum ='$row_[certi]' and a.endorse_day='$sigi'  order by a.damdanga desc ";
		$sql="SELECT  *  FROM 2012DaeriMember a left join  SMSData b ";
		$sql.="ON a.num = b.2012DaeriMemberNum $where ";
		
		
		

//echo "sql $sql <br></br>";

	$result2 = mysql_query($sql,$connect);
	$rNum=mysql_num_rows($result2);
		if($rNum==0){
		
			continue;

		}
echo "\n";
echo "번호	운전자	주민앞	유형	대리운전회사	증권번호	배서일	배서보험료	담당자	정산\n ";
$k=1;
//while($row=mysql_fetch_array($result2)){
for($_q=0;$_q<$rNum;$_q++){

		$row=mysql_fetch_array($result2);
		$a[6]=$row['push'];

		$a[23]=$row[damdanga];
	 
	    $a[21]=$row[c_preminum];

		/* 보험료 Total */

		 switch($a[6]){
		   case 2 :
			   $a[44]="해지";

				$haeji_total++;
			   $a[21]=-$a[21];
			   break;
			case 4 :
				$push_total++;
				$a[44]="청약";
			  $a[21]=$a[21];
				break;
	   }
	   

	   switch($a[23]){
		   case 1 :

				$total_1+=$a[21];
			   break;
			case 2 :

				$total_2+=$a[21];
				break;
	   }

	   $a[21]=number_format($a[21]);

	 //  echo  $a[21] ; echo "<br></br>";
	//회차
		$a[7]=$row['2012DaeriMemberNum'];
		$a[20]=$row[endorse_day];
		
		$a[24]=$row[jeongsan];
		

		switch($a[24]){
			case 1 :
				$a[24]='정산';
				break;
			case 2 :
				$a[24]='미정산';
				break;
		}

			$qSql="SELECT * FROM 2012Member  WHERE num='$a[23]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);

			$a[23]=$qRow[name];

			$msql="SELECT * FROM 2012DaeriMember WHERE num='$a[7]' ";
			$mrs=mysql_query($msql,$connect);
			$mrow=mysql_fetch_array($mrs);


			switch($mrow[etag]){
				case 1 : 
					$metat="";

					break;
				case 2 :

					$metat="[탁]";
					break;
			}

		$a[7]=$mrow[Name];
		$a[8]=$mrow[Jumin];	

		$p[1]=$mrow[dongbuCerti];// 증권번호
		$p_buho =$mrow[p_buho];

		/*switch($p_buho){
			case 1 :
					$p_buhoName='조홍기';
				break;
			case 2 :
					$p_buhoName='오성준';
				break;
			case 3 :
					$p_buhoName='이근재';
				break;
			case 4 :
					$p_buhoName='오성엽';
				break;
			case 5 :
					$p_buhoName='박종민';
				break;
			case 6 :
					$p_buhoName='오경선';
				break;
		}*/
	   $daeComNum=$row['2012DaeriCompanyNum'];
		

	//대리운전회사 찾기


		$sqC="SELECT * FROM 2012DaeriCompany  WHERE num='$daeComNum'";
		$sRs=mysql_query($sqC,$connect);
		$sRow=mysql_fetch_array($sRs);

		$a[22]=$sRow[name];


		$c[1]=$sRow[company];//
	//모계약을 찾기 위해 

			$qSql="SELECT * FROM 2012CertiTable  WHERE num='$mrow[CertiTableNum]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);
						$moNum=$qRow[moNum];
			if($moNum){
					//모계약의 certi번호를 찾아서 대리운전회사를 찾기위해 ....

						$rSql="SELECT * FROM 2012CertiTable  WHERE num='$moNum'";
						$rRs=mysql_query($rSql,$connect);
						$rRow=mysql_fetch_array($rRs);
						$mDaeriCompanyNum=$rRow['2012DaeriCompanyNum'];



						switch($rRow[gita]){
							case 1 :

								$metat="";
								break;
							case 2 :
								$metat="[탁]";
								break;
						}//2014-07-16

						$moSql="SELECT * FROM  2012DaeriCompany WHERE num='$mDaeriCompanyNum'";
						$moRs=mysql_query($moSql,$connect);
						$moRow=mysql_fetch_array($moRs);

			//모계약의 certi 번호를 기사별로 저장하기 위해 

						//$UmDate="UPDATE 2012DaeriMember SET moCertiNum='$moNum' WHERE num='$a[1]'";

						//mysql_query($UmDate,$connect);
			//moCertiNum

			}	
			
			$mm=$moRow[company].$metat;//
	echo "$k	$a[7]	$a[8]	$a[6].$a[44]	$c[1]	$p[1]	$a[20]	$a[21]	$a[23]	$a[24]	$p_buhoName\n ";

	$metat='';

	$k++;
}
echo "\n";


//당일 인원을 파악하기 위해 
if($row_[certi]){
	$q_sql="SELECT * FROM 2012DaeriMember WHERE push='4' and dongbuCerti='$row_[certi]' ";

	//echo $q_sql;
	$q_rs=mysql_query($q_sql,$connect);
	$q_num=mysql_num_rows($q_rs);


	$qp_sql="SELECT * FROM 2014InWon WHERE sigi='$sigi' and dongbucerti='$row_[certi]'";

	//echo $qp_sql;
	$qp_rs=mysql_query($qp_sql,$connect);
	$qp_num=mysql_num_rows($qp_rs);

	//echo $qp_num;

	if($qp_num){

		$q_row=mysql_fetch_array($qp_rs);

		$q_nnum=$q_row[num];

		$update="UPDATE 2014InWon SET inwon='$q_num' WHERE num='$q_nnum'";

		mysql_query($update,$connect);
	}else{

		$insert="INSERT into 2014InWon (dongbucerti,sigi,inwon) ";
		$insert.=" values('$row_[certi]','$sigi','$q_num')";
		

		//echo $insert;
		mysql_query($insert,$connect);
	}
}	
///////////////////////////
	$d_total=$push_total-$haeji_total;
	$total_1=number_format($total_1);
	$total_2=number_format($total_2);
	echo "$q_num	추가	$push_total	해지	$haeji_total	계	$d_total	오 성준	$total_1	이 근재	$total_2\n";
	

	//현재일자부터 1주일전까지 인원을 구하기 위해 

	$a_sql="SELECT * FROM 2014InWon WHERE  dongbucerti='$row_[certi]' AND (sigi>='$weeksigi' AND sigi<='$now_time')  order by num desc";
//ECHO $a_sql;
	$a_rs=mysql_query($a_sql,$connect);
	$a_num=mysql_num_rows($a_rs);

	for($i=0;$i<$a_num;$i++){

		$a_row=mysql_fetch_array($a_rs);

		echo "$a_row[sigi]	$a_row[inwon]\n";

	}

	$total_1='';
	$total_2='';
	$push_total='';$haeji_total='';$d_total='';
}
?>