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
	$d_contents	    =iconv("utf-8","euc-kr",$_GET['d_contents']);
	$state	      =iconv("utf-8","euc-kr",$_GET['state']);


	if($s_contents){

		if($d_contents){
		$sql="SELECT  *  FROM 2012DaeriMember  a left join 2012DaeriCompany  b ";
		$sql.="ON a.2012DaeriCompanyNum = b.num WHERE  b.company like '%$d_contents%' and  a.dongbuCerti ='$s_contents'  and a.push='4' ";
		$sql.="order by a.Jumin asc";

		}else{


			$where="WHERE dongbuCerti ='$s_contents'  and push='4' $where order by jumin asc ";	
			$sql="Select * FROM 2012DaeriMember  $where ";

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
	$q=0;
echo"<data>\n";
	echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	  
		$dNum=mysql_result($result2,$count,"num"); 
	    $DaeriCompanyNum = mysql_result($result2,$count,"2012DaeriCompanyNum"); 
		$c_name = mysql_result($result2,$count,"name");
		//$wdate			= mysql_result($result2,$count,"wdate"); 	
		//$user_id		=mysql_result($result2,$count,"userid"); 
		$ga_date     = mysql_result($result2,$count,"jumin");
		
		$a[3]=mysql_result($result2,$count,"InsuranceCompany");

		$certi=mysql_result($result2,$count,"dongbuCerti");

		$InsuranceCompany=mysql_result($result2,$count,"InsuranceCompany");

		$sCsql="SELECT company FROM 2012DaeriCompany  WHERE num='$DaeriCompanyNum'";
		$srs=mysql_query($sCsql,$connect);
		$srow=mysql_fetch_array($srs);

		$company=$srow[company];

		//$gita=mysql_result($result2,$count,"gita");//2이면 탁송
	   echo "<a3>".$a[3]."</a3>\n";
	  

	   echo "<dnum>".$dNum."</dnum>\n";
	   echo "<company>".$company."</company>\n";
	   echo"<certi>".$certi."</certi>\n";
	   echo"<ga_date>".$ga_date."</ga_date>\n";
	   echo"<nabang>"."</nabang>\n";
	   echo"<nabang_1>"."</nabang_1>\n";
	   echo"<c_name>".$c_name."</c_name>\n";
	   echo"<user_id>"."</user_id>\n";
	   echo"<ssang_c_num>".$ssang_c_num."</ssang_c_num>\n";
	   echo"<wdate>".$wdate."</wdate>\n";
	   echo"<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n";

	   /*******************************************/
	   /* 동일대리운전회사의 certiTableNum을 찾기위해*/
	   /*************************************************/
			$cSql="SELECT * FROM 2012CertiTable WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' ";
			$cSql.="and InsuraneCompany!='$InsuranceCompany' and startyDay>='$yearbefore' order by InsuraneCompany asc ";
			//$cSql="SELECT * FROM 2012CertiTable  WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and InsuraneCompany!='$InsuranceCompany' ";
			//$cSql.="and startyDay>='$yearbefore' order by InsuraneCompany asc ";
			$Crs=mysql_query($cSql,$connect);

			$Cnum=mysql_num_rows($Crs);

			//echo"<Cnum>".$cSql.$Cnum."</Cnum>\n";
			

				$C2num=$Cnum+1;//개수보다 하나 많게 하는 이유은 ?
				echo "<Cnum>".$C2num."</Cnum>\n";
				
			for($_p=0;$_p<$C2num;$_p++){

				$Crow=mysql_fetch_array($Crs);

				$Ccnum[$_p]=$Crow[num];
				$Cinsum[$_p]=$Crow[InsuraneCompany];
				$gita[$_p]=$Crow[gita];

				//$Snum[$_p]=$Crow[changeCom];
				if($_p==$Cnum){// 앞부분에 선택을 만들기 위해 
					$Ccnum[$_p]='99999999';
					$Cinsum[$_p]='99';
				}
				echo "<Ctnum".$q.$_p.">".$Ccnum[$_p]."</Ctnum".$q.$_p.">\n";//2012CertiTablenum
				echo "<inum".$q.$_p.">".$Cinsum[$_p]."</inum".$q.$_p.">\n";
				echo "<gita".$q.$_p.">".$gita[$_p]."</gita".$q.$_p.">\n";


				$gita[$_p]='';
			}


		 

	  //**************************************/
	   /*업체별 인원                          */
	   /**************************************/
		/*	$sql="Select num ";
			$sql.="FROM ssang_drive WHERE ssang_c_num='$ssang_c_num' and push='4'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$total_1=mysql_num_rows($rs);
			//$row=mysql_fetch_array($rs);
			//$sum_push=$row['sum_push'];//대리운전회사별 인원수
			$sum_push[$i]=mysql_num_rows($rs);
		/*	$total_driver=$total_driver+$sum_push;//나머지 인원
			for($_j=0;$_j<$total_1;$_j++){
				$row_st=mysql_fetch_array($rs);
				$push=$row_st['push'];
				//echo "push $push <br>";
				if($push=='1'){
					$push_count=$row_st['sum_push'];
					//echo "pus $push_count <br>";
				}
				
			}*/
			$TotalMember+=$sum_push[$i];
		
		//동부 계약이 있다면 

		//2011-09-24 동부 인원과 현황을 찾기 위해	
		//$con_phone1 =mysql_result($result2,$count,"con_phone1");
		//$qs="SELECT num FROM dongbu2_c WHERE con_phone1='$con_phone1'";
		//$qrs=mysql_query($qs,$connect);
		//$qrs=mysql_fetch_array($qrs);

		//$dongNum=$qrs[num];


		//$wqs="SELECT num FROM dongbu2_drive WHERE ssang_c_num='$dongNum' and push='4'";
		//$wrs=mysql_query($wqs,$connect);
		//$dCount=mysql_num_rows($wrs);


		
		/************************************************************/
		/*업체의 보허료 납입 상태를 파악하기 위해*/
		/************************************************************/
			//	$oldState	 = mysql_result($result2,$count,"state");
			//	$oldPeriod	 = mysql_result($result2,$count,"period");
		//include "./301_sangtae.php";
		//if($this_month==4){//실효인것은 제외
		//	continue;
		//}
		echo"<dongCount>"."</dongCount>\n";
		echo"<dongNum>".$dongNum."</dongNum>\n";
		echo"<driverCount>"."</driverCount>\n";
		echo"<this_nab>".$this_nab."</this_nab>\n";
		echo"<this_month>".$this_month."</this_month>\n";
	   $count++;
	   $q++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<DaeriCompanyNewNum>".$DaeriCompanyNum."</DaeriCompanyNewNum>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($total)."명"."</totalMember>\n";
echo"</data>";



	?>