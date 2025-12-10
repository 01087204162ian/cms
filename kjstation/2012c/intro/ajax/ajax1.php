<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	//계약자 정보
	/********************************************************************/
	//state 1 실효 제외 state 2 : 포함
	//////////////////////////////////////////////////////////////////
	$cNum	    =$_GET['cNum'];
	$kind_state2 = $_GET['kind_state2']; //99전체 1대리,2탁송만  //개별적으로 조회할 때
	$kind_state = $_GET['kind_state']; //99전체 1대리,2탁송만
	$sigi	    =$_GET['sigi'];
	$end	    =$_GET['end'];
	$page	    =$_GET['page'];
	$s_contents	    =iconv("utf-8","euc-kr",$_GET['s_contents']);
	$state	      =$_GET['state'];
	$insuranceComNum=$_GET['insuranceComNum'];

	
	if($s_contents){

		if($kind_state2!=99){
			
			
				$where3="and etag='$kind_state2' ";
			
		}
		$where="WHERE Name like '%$s_contents%' and 2012DaeriCompanyNum='$cNum'  and (push ='4' or (push='1' and sangtae='1')) $where $where3 order by InPutDay asc ";
	}else{
		if($insuranceComNum!=99){
			$where2="and InsuranceCompany='$insuranceComNum'";
		}

		if($kind_state!=99){
			
			
				$where3="and etag='$kind_state' ";
			
		}
		$where="WHERE 2012DaeriCompanyNum='$cNum'  and (push ='4' or (push='1' and sangtae='1')) $where2 $where3 order by Jumin asc ";
	}
	
	$sql="Select * FROM 2012DaeriMember    $where ";

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
	
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");
	   $a[2]=mysql_result($result2,$count,"Name");
	   $a[3]=mysql_result($result2,$count,"Jumin");
//대리운전기사의 나이를 update 하기 위해 

	

///////////////////////////////////////////////////


	   $a[4]=mysql_result($result2,$count,"nai");
	   $a[5]=mysql_result($result2,$count,"push");
	   //
$a[13]=mysql_result($result2,$count,"CertiTableNum");

	   $a[6]=mysql_result($result2,$count,"etag");
		if(!$a[6]){
			$a[6]=1;
		}
//증권의 성격이 1이면 일반 2이면 탁송 

		$kSql="SELECT gita FROM 2012CertiTable WHERE num='$a[13]'";
		$kRs=mysql_query($kSql,$connect);
		$kRow=mysql_fetch_array($kRs);

		/*if($kRow[gita]==1){

			$a[6]=1;
		}else{

			$a[6]=2;
		}*/
		////증권의 성격이 1이면 일반 2이면 탁송 
	   $a[7]=mysql_result($result2,$count,"InsuranceCompany");
	   $a[9]=mysql_result($result2,$count,"InPutDay");
	   $a[10]=mysql_result($result2,$count,"OutPutDay");
	   if($a[10]=='0000-00-00'){
			$a[10]='';
	   }
	   $a[11]=mysql_result($result2,$count,"2012DaeriCompanyNum");
	   
	    
	    $a[14]=mysql_result($result2,$count,"cancel");

		if($a[14]==42 && $a[5]==4){
			
			$a[5]=8;//해지중이기위해서
		}
	  //대리운전회사 찾기 
		$cSql="SELECT num,company FROM  2012DaeriCompany WHERE num='$a[11]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

	//	$a[12]=$cRow[company];
	  /////////////////////////////////
	   //증권번호를 찾기 위해 
	/*	$pSql="SELECT policyNum FROM 2012CertiTable WHERE num='$a[13]'";


		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);

		$a[8]=$pRow[policyNum];*/

		$a[8]=mysql_result($result2,$count,"dongbuCerti");

		if(!$a[8]){//증권번호가 없으면 ...

			$pSql="SELECT policyNum FROM 2012CertiTable WHERE num='$a[13]'";
			$pRs=mysql_query($pSql,$connect);
			$pRow=mysql_fetch_array($pRs);

			$a[8]=$pRow[policyNum];
			//$policyNum=$aRow[policyNum];

			$update="UPDATE 2012DaeriMember SET dongbuCerti='$a[8]' WHERE num='$a[1]'";

			mysql_query($update,$connect);

			//$a[8]=$aRow[policyNum];

		}
		switch($a[7]){
			case 1 :

				$a[8]="7-1-631-".$a[8]."-000";
				break;

			case 2 :
				if($a[5]==4){
				//$a[8]="017-".$a[8]."-000";
				$a[8]="2-20".$a[8]."-000";
				}
				break;

			default :

				
				break;

		}
		
		$a[14]=mysql_result($result2,$count,"Hphone");

		$a[30]=mysql_result($result2,$count,"a6b");
		$a[31]=mysql_result($result2,$count,"a7b");
		$a[32]=mysql_result($result2,$count,"a8b");
		

		$inserday=explode("-",$a[9]);

		$inserday[0]=substr($inserday[0], 2, 2);  

		$insertDay=$inserday[0].".".$inserday[1].".".$inserday[2];

$a[33]=1;// 인증을 만들기 위해서 업체화면에서는 1이면 안나오게 하기위해
		
	//개인별 손해율 조회하자 증권번호와 주민번호 조회 하자
	//2019-10-17

	$sqlr="SELECT * FROM 2019rate WHERE policy='$a[8]' and jumin='$a[3]'";

					//	echo $sqlr ;

	$rsr=mysql_query($sqlr,$connect);

	$rowr=mysql_fetch_array($rsr);
	$personRate=$rowr[rate];

	switch($personRate){


			case 1 :
				$personRate2=1;
				$personRate3=1;// 
				$personRateName="기본";
				break;
			case 2 :
				$personRate2=0.9;
				$personRate3=0.9;
				$personRateName="할인";
				break;
			case 3 :
				$personRate2=0.925;
				$personRate3=0.925;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상";
			break;
			case 4 :
				$personRate2=0.898;
				$personRate3=0.898;
				$personRateName=" 3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상";
				break;
			case 5 :
				$personRate2=0.889;
				$personRate3=0.889;
				$personRateName="  3년간 사고건수 0건 1년간 사고건수 0건 무사고  3년 이상";
				break;
			case 6 :
				$personRate2=1.074;
				$personRate3=1.074;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 0건 ";
				break;
			case 7 :
				$personRate2=1.085;
				$personRate3=1.085;
				$personRateName=" 3년간 사고건수 1건 1년간 사고건수 1";
				break;
			case 8 :
				$personRate2=1.242;
				$personRate3=1.242;
				$personRateName=" 3년간 사고건수 2건 1년간 사고건수 0";
				break;
			case 9 :
				$personRate2=1.253;
				$personRate3=1.253;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 1";
				break;
			case 10 :
				$personRate2=1.314;
				$personRate3=1.314;
				$personRateName="3년간 사고건수 2건 1년간 사고건수 2";
				break;
			case 11 :
				$personRate2=1.428;
				$personRate3=1.428;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 0";
				break;
			case 12 :
				$personRate2=1.435;
				$personRate3=1.435;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 1";
				break;
			case 13 :
				$personRate2=1.447;
				$personRate3=1.447;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 2";
				break;
			case 14 :
				$personRate2=1.459;
				$personRate3=1.459;
				$personRateName="3년간 사고건수 3건이상 1년간 사고건수 3건이상";
				break;
			default:
				
				$personRate2=1;
				$personRate3=1;
				$personRateName="기본";
				break;

		}





	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2].'['.$a[4].']'."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";//InsuranceCompany
	   echo "<a8>".$a[8]."</a8>\n";//policyNum
	   echo "<a9>".$insertDay."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";
	   echo "<a11>".$a[11]."</a11>\n";//2012DaeriCompanyNum
	 //  echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";//CertiTableNum
	   echo "<a14>".$a[14]."</a14>\n";//핸ㄷ폰
	   echo "<a51>".$personRateName."(".$personRate3.")"."</a51>\n";//할인할증

	   echo "<a30>".$a[30]."</a30>\n";//통신사
	   echo "<a31>".$a[31]."</a31>\n";//명의자
	   echo "<a32>".$a[32]."</a32>\n";//주소
	   echo "<a33>".$a[33]."</a33>\n";//인증
	   echo "<a34>".$a[14]."</a34>\n";//핸ㄷ폰
	   
		
	   $count++;
	}
	//////////////////////////////////////////////////////////////
	// page 구성을 위하여                                       //
	//////////////////////////////////////////////////////////////
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($TotalMember)."명"."</totalMember>\n";


	//대리운전기사 전체 인원을 보험회사별로 
	for($_k=1;$_k<9;$_k++){
		$sql="Select * FROM 2012DaeriMember WHERE 2012DaeriCompanyNum='$cNum'  and push ='4' and InsuranceCompany='$_k'";
		//echo "sql $sql <br>";
		$rs=mysql_query($sql,$connect);

		$memberTotal[$_k]=mysql_num_rows($rs);
		if($memberTotal[$_k]!=0){
			switch($_k){
				case 1 :
					$inSom[$_k]='흥국';
					$inCall[$_k]='1688-1688';
					$iColor[$_k]='#AF8AC1';
					break;
				case 2 :
					$inSom[$_k]='DB';
					$inCall[$_k]='1588-0100';
					$iColor[$_k]='#E4690C';
					break;
				case 3 :
					$inSom[$_k]='KB';
					$inCall[$_k]='1544-0114';
					$iColor[$_k]='#0A8FC1';
					break;
				case 4 :
					$inSom[$_k]='현대';
					$inCall[$_k]='1588-5656';
					$iColor[$_k]='#FA8FC1';
					break;
				case 5 :
					$inSom[$_k]='롯데';
				    $inCall[$_k]='1566-8000';
					$iColor[$_k]='#FA8FC1';
					break;
				case 6 :
					$inSom[$_k]='더케이';
					$inCall[$_k]='1566-3000';
					$iColor[$_k]='#FA8FC1';
					break;
				case 7 :
					$inSom[$_k]='MG화재';
					$inCall[$_k]='1588-5959';
					$iColor[$_k]='#FA8FC1';
					break;
				case 8 :
					$inSom[$_k]='삼성화재';
					$inCall[$_k]='1588-5114';
					$iColor[$_k]='#FA8FC1';
					break;

			}
		}
		
		echo "<m".$_k.">".$inSom[$_k]."</m".$_k.">\n";
		echo "<n".$_k.">".$memberTotal[$_k]."</n".$_k.">\n";
		echo "<o".$_k.">".$inCall[$_k]."</o".$_k.">\n";
		echo "<icolor".$_k.">".$iColor[$_k]."</icolor".$_k.">\n";//회사명
	}
/*
for($p__=1;$p__<3;$p__++){
	
	for($_j_=0;$_j_<7;$_j_++){


			if($_j_==0){
				$snai[$_j_]=26;
				$k2[$_j_]=28;
			}else if($_j_==1){
				$snai[$_j_]=29;
				$k2[$_j_]=40;				
			}else if($_j_==2){
				$snai[$_j_]=41;
				$k2[$_j_]=49;
			}else if($_j_==3){
				$snai[$_j_]=50;
				$k2[$_j_]=55;
			}else if($_j_==4){
				$snai[$_j_]=51;
				$k2[$_j_]=65;
			}else if($_j_==5){
				$snai[$_j_]=66;
				$k2[$_j_]=99;
			}

			
			if($_j_<6){

				$sQL="SELECT * FROM  2012DaeriMember WHERE 2012DaeriCompanyNum='$cNum'  and push ='4'  and  nai>='$snai[$_j_]' and nai<='$k2[$_j_]' and etag='$p__' ";
			}else{
				$sQL="SELECT * FROM  2012DaeriMember WHERE 2012DaeriCompanyNum='$cNum'  and push ='4'  and etag='$p__' ";
			}
			///echo $sQL;
			
			$sRs=mysql_query($sQL,$connect);
			$sNum[$_j_]=mysql_num_rows($sRs);

			
			//$rNum[$_j_]=round($sNum[$_j_]/$pNum,3)*100;

			if($sNum[$_j_]==0){	
				$sNum[$_j_]='';
			}else{
				$sNum[$_j_]=$sNum[$_j_]."명";
			}
			if($rNum[$_j_]==0){	
				$rNum[$_j_]='';
			}else{
				$rNum[$_j_]=$rNum[$_j_]."%";
			}
		if($_j_<6){

		  echo "<me".$p__.$_j_.">".$snai[$_j_]."세~".$k2[$_j_]."세"."</me".$p__.$_j_.">\n";//
		}else{
		  echo "<me".$p__.$_j_.">"."소계"."</me".$p__.$_j_.">\n";//
		}
		echo "<sNum".$p__.$_j_.">".number_format($sNum[$_j_])."</sNum".$p__.$_j_.">\n";//
		echo "<rNum".$p__.$_j_.">".$rNum[$_j_]."</rNum".$p__.$_j_.">\n";//
	
	}



}	
	*/

	//
echo"</data>";

	?>