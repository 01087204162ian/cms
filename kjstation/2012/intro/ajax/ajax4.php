<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//state 1 실효 제외 state 2 : 포함
	//////////////////////////////////////////////////////////////////
	$sigi	    =iconv("utf-8","euc-kr",$_GET['sigi']);
	$end	    =iconv("utf-8","euc-kr",$_GET['end']);
	$page	    =iconv("utf-8","euc-kr",$_GET['page']);
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$state	      =iconv("utf-8","euc-kr",$_GET['state']);
	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);

	$you=iconv("utf-8","euc-kr",$_GET['you']); //유예기간

	$manGi=iconv("utf-8","euc-kr",$_GET['manGi']); //만기 
	

	$beforeYear=explode("-",$yearbefore);//1년전
	$magGi=$beforeYear[0]."-".$manGi;
	if($manGi!=99){

			
				if($insuranceComNum!=99){
					
					$where2="and InsuraneCompany='$insuranceComNum' ";
				}
			$where="WHERE substring(startyDay,1,7)='$magGi' $where2";
			$sql="Select * FROM 2012CertiTable   $where ";
	}else{
			if($s_contents){

				if($insuranceComNum!=99){
					$where=" and InsuraneCompany='$insuranceComNum'  ";
				}else{


				}

				$sql="SELECT  *  FROM 2012CertiTable a left join 2012DaeriCompany  b ";
				$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.company like '%$s_contents%'";
				$sql.="$where order by a.FirstStart desc";
				
			}else{
				//$where="WHERE endorse_day >='$sigi' and endorse_day <='$end' order by start asc ";
				if($you!=99){
						$where3="and FirstStartDay='$you'";
				}
				if($insuranceComNum==2){
						$where2="$where3 ";
					}else{
						$where2="$where3  and state!='3'";
					}
				if($insuranceComNum!=99){
					
					$where="WHERE InsuraneCompany='$insuranceComNum' and startyDay>='$sigi' $where2 order by num  desc ";
				}else{

				   $where="WHERE startyDay>='$sigi' $where2  order by FirstStartDay asc";
				}
				$sql="Select * FROM 2012CertiTable    $where ";	
			}
	
	}

//	echo "Sql $sql <br>";
	$result2 = mysql_query($sql,$connect);
	$total  = mysql_num_rows($result2);
	
	/**********************************************************************************/
	//기간동안의 전체 건수를 나누어서
	///페이지 마다 20개의 Data 만 불러 온다                                            //
	/////////////////////////////////////////////////////////////////////////////////////////
	if(!$page){//처음에는 페이지가 없다
		$page=1;
	}
	$max_num =20;
	$count = $max_num * $page - 20;
	if($total<20){
		$last=$total;
	}else{
		$last=20;
	}
	if($total-$count<20){//총개수-$congt 32-20인경우에만
		$last=$total-$count;
	}
	$last=$count+$last;
	$totalDaeriMember=0;
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");//2012CertiTableNum
	   $DaeriCompnayNum[$k]=mysql_result($result2,$count,"2012DaeriCompanyNum");

		//대리회사명을 찾기위해
			$dSql="SELECT company,MemberNum FROM 2012DaeriCompany WHERE num='$DaeriCompnayNum[$k]'";
			$dRs=mysql_query($dSql,$connect);
			$dRow=mysql_fetch_array($dRs);

			//담당자를 찾기위해 
			$sSql="SELECT * FROM 2012Member  WHERE num='$dRow[MemberNum]'";
			$sRs=mysql_query($sSql,$connect);
			$sRow=mysql_fetch_array($sRs);

			$a[9]=$sRow[name];

			
	   $a[2]=$dRow[company];
	   $a[12]=mysql_result($result2,$count,"DaeriCompany");
	   $a[3]=mysql_result($result2,$count,"InsuraneCompany");

//gita 1 이면 일반  2이면 탁송  3은 

		$a[30]=mysql_result($result2,$count,"gita");

		switch($a[30]){

			case 1 :
				//$a[30]='일반';
				break;
			case 2 :
				$a[30]='탁송';
				break;
		}
		
		if($a[30]=='1'){ 
			$a[30]='';
		}else{

			$a[30]="[".$a[30]."]";
		}
	   $a[4]=mysql_result($result2,$count,"policyNum");
	   $a[5]=mysql_result($result2,$count,"startyDay");
	   if($insuranceComNum!=2){
	   $a[6]=mysql_result($result2,$count,"nabang");
	   $a[7]=mysql_result($result2,$count,"nabang_1");
	   }
	   //$a[8]=mysql_result($result2,$count,"divi");
		//대리기사 인원을 찾기 위해
			$msql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$a[1]' and push='4'";
			$mrs=mysql_query($msql,$connect);
			$mNum=mysql_num_rows($mrs);
		

			$totalDaeriMember+=$mNum;
			$policyNum[$_m]=$rCertiRow[policyNum];
			$nabang[$_m]=$rCertiRow[nabang];
			$nabang_1[$_m]=$rCertiRow[nabang_1];

			//각증권별 납입 상태를 파악하기 위해
			$sigiStart=$a[5];
			$naBang=$a[7];
			
			$inPnum=$a[3];
			


			//////////////////////////////////////////////////////////
			//
			//납입상태를 저장하는데 ,FirstStart  기준일 FirstStartDay 는 유예남은일자$gigan,state 1 납,2유예 3실효
			//
			// FirstStart  기준일과 now_time을 비교하여 다를경우에  다음모줄이 실행된다.
			//////////////////////////////////////////////////

		$FirStart=mysql_result($result2,$count,"FirstStart");
		if($FirStart!=$now_time){
			include "./php/nabStateChagen.php";
		}
		

		$naBang=$a[7];
		include "../../pop_up/ajax/php/nabState.php";//nabSunsoChange.php 공동으로 사용
		
		if($a[5]<=$yearbefore){//기준일이 1년전 오늘 보다 작은 경우엔

			$naState='';$gigan='';$a[7]='';$a[6]='';
		}

		 if($insuranceComNum==2){

			$naState='';$gigan='';
		 }
	   $a[20]=$naColor;
	   echo "<a1>".$DaeriCompnayNum[$k]."</a1>\n";
	   echo "<a2>".$a[2]."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4].$a[30]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[7]."/".$a[6]."</a6>\n";
	  
	   echo "<a7>".$naState.$gigan."</a7>\n";
	   echo "<a8>".number_format($mNum)."</a8>\n";
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a12>".$a[12]."</a12>\n";

	   echo "<a20>".$a[20]."</a20>\n";   
	
	  echo "<cNum>".$a[1]."</cNum>\n";//certiTableNum
	   echo "<pBankNum>".$pBankNum."</pBankNum>\n";

	   $count++;
	  
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($totalDaeriMember)."명"."</totalMember>\n";
echo"</data>";

	?>