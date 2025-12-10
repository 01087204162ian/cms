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
	$chr	      =iconv("utf-8","euc-kr",$_GET['chr']);
	$damdanja=iconv("utf-8","euc-kr",$_GET['damdanja']);
  if($damdanja>=3){$damdanja=99;}//전윤선이가 

	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);


	if($insuranceComNum!=99){
			$where2="and a.InsuranceCompany='$insuranceComNum'";
	}
//	if($chr!=99){
	//		$where3="and a.ch='$chr'";
	//}
	if($damdanja!=99){

			$whrer4="and b.MemberNum='$damdanja'";
		}

	if($dongbuCerti==99){
		$dongbuCerti='undefined';
	}

	if( $dongbuCerti!='undefined'){
		$where5="and dongbuCerti='$dongbuCerti' ";
	}
	if($s_contents){
		$where="WHERE a.Name like '%$s_contents%' and a.sangtae='1' $where2 $where3 $where5 order by a.dongbuCerti  desc ";
	}else{
		//$where="WHERE start<='$end' order by start asc ";
		//$where="WHERE    a.sangtae='1' $where2  $where3  $whrer4 order by a.InPutDay  asc,a.dongbuCerti  desc";	
		$where="WHERE    a.sangtae='1' $where2  $where3  $whrer4 $where5  order by a.push desc, a.dongbuCerti  desc,a.Jumin asc,a.InPutDay   asc,a.OutPutDay   asc";
	}
	$sql="SELECT  *  FROM 2021DaeriMember a left join 2012DaeriCompany  b ";
	$sql.="ON a.2012DaeriCompanyNum = b.num $where ";
	//$sql="Select * FROM 2012DaeriMember    $where ";

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
	//echo "<sql>".$sql."</sql>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");
	   $a[2]=mysql_result($result2,$count,"Name");
	   $a[3]=mysql_result($result2,$count,"Jumin");


		$a[16]=$a[3];
	   $ju=explode('-',$a[3]);
	   $a[3]=$ju[0].$ju[1];
	   //$a[4]=mysql_result($result2,$count,"nai");
		//만나이게산 시작

	   $a[5]=mysql_result($result2,$count,"push");
	   $a[6]=mysql_result($result2,$count,"etag");

		if(!$a[6]){$a[6]=1;}//a[6] 없을 경우 일반으로 본다
	   $a[7]=mysql_result($result2,$count,"InsuranceCompany");
	   $a[9]=mysql_result($result2,$count,"InPutDay");
	   $a[10]=mysql_result($result2,$count,"dongbuSelNumber");

	    $a[14]=mysql_result($result2,$count,"ch");
		 $a[19]=mysql_result($result2,$count,"Hphone");



		$a[30]=mysql_result($result2,$count,"a6b");  //통신사
		$a[31]=mysql_result($result2,$count,"a7b");  //명의
		$a[32]=mysql_result($result2,$count,"a8b");  //주소


	   if($a[10]=='0000-00-00'){
			$a[10]='';
	   }
	   $a[11]=mysql_result($result2,$count,"2012DaeriCompanyNum");
	   
	    $a[13]=mysql_result($result2,$count,"CertiTableNum");

		$a[20]=mysql_result($result2,$count,"sago");//사고
		//모계약을 찾기 위해 

			$qSql="SELECT * FROM 2021CertiTable  WHERE num='$a[13]'";
			$qRs=mysql_query($qSql,$connect);
			$qRow=mysql_fetch_array($qRs);
			$moNum=$qRow[moNum];
if($moNum){
		//모계약의 certi번호를 찾아서 대리운전회사를 찾기위해 ....

			$rSql="SELECT * FROM 2021CertiTable  WHERE num='$moNum'";
			$rRs=mysql_query($rSql,$connect);
			$rRow=mysql_fetch_array($rRs);
			$mDaeriCompanyNum=$rRow['2012DaeriCompanyNum'];

			$jagi[$k]=$rRow[jagi];


			switch($jagi[$k]){
				case 1 :
					$jagi[$k]='10만원';
				break;
				case 2 :
					$jagi[$k]='20만원';
				break;
				case 13 :
					$jagi[$k]='30만원';
				break;
			}


			$moSql="SELECT * FROM  2012DaeriCompany WHERE num='$mDaeriCompanyNum'";
			$moRs=mysql_query($moSql,$connect);
			$moRow=mysql_fetch_array($moRs);

//모계약의 certi 번호를 기사별로 저장하기 위해 




			$UmDate="UPDATE 2021DaeriMember SET moCertiNum='$moNum' WHERE num='$a[1]'";

			mysql_query($UmDate,$connect);
//moCertiNum

}	//
	    $a[42]=mysql_result($result2,$count,"cancel");

		if($a[42]==42 && $a[5]==4){
			
			$a[5]=8;//해지중이기위해서
		}
	  //대리운전회사 찾기 
		$cSql="SELECT * FROM  2012DaeriCompany WHERE num='$a[11]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

		$a[12]=$cRow[company];
	  /////////////////////////////////
	 //	현대화재 또는 동부화재인경우 
	  ////////////////////////////////
		$a[8]= mysql_result($result2,$count,"dongbuCerti");
		$a[15]=$cRow[MemberNum];
		
		include "./php/manNai.php";//만나이계산을 위ㅐㅎ



		$mSql="SELECT * FROM 2012Member WHERE num='$a[15]'";
		$mRs=mysql_query($mSql,$connect);
		$mRow=mysql_fetch_array($mRs);
		$a[15]=$mRow[name];


if($a[5]==1){//청약일 때만 
		//중복여부를 찾기위해 
		$dSql="SELECT * from  2021DaeriMember  WHERE Jumin='$a[16]' and push='4'  ";
		//$dSql.=" and 2012DaeriCompanyNum!='$a[11]' ";

		//echo $dSql; echo "<br>";
		$drs=mysql_query($dSql,$connect);
        $drow=mysql_fetch_array($drs);


		$duplNum=$drow[num];//중복
		
}else{

	//중복여부를 찾기위해 
		$dSql="SELECT * from  2021DaeriMember  WHERE Jumin='$a[16]' and push='4'  ";
		$dSql.=" and CertiTableNum!='$a[13]' ";

		//echo $dSql; echo "<br>";
		$drs=mysql_query($dSql,$connect);
        $drow=mysql_fetch_array($drs);


		$duplNum=$drow[num];//중복


}

	if(!$a[8]){
	   //증권번호를 찾기 위해 
		$pSql="SELECT policyNum FROM 2021CertiTable WHERE num='$a[13]'";
		//echo " $pSq <br>";
		$pRs=mysql_query($pSql,$connect);
		$pRow=mysql_fetch_array($pRs);

		$a[8]=$pRow[policyNum];
	}else{

		$a[8]=$a[8];
	}
	   ///

	   // 대리운전 기사 각 개별 증권번호를 저장하기 위해

	 //  if($a[5]==4){

	   	$SUmDate="UPDATE 2021DaeriMember SET dongbuCerti='$a[8]' WHERE num='$a[1]'";
		mysql_query($SUmDate,$connect);
	//   }

	   $eNum=mysql_result($result2,$count,"EndorsePnum");// 배서번호
	

	   $eSql="SELECT * FROM 2021EndorseList WHERE pnum='$eNum' and  CertiTableNum='$a[13]' order by num desc";
	  //echo "eSql $eSql <Br>";
	   $eRs=mysql_query($eSql,$connect);
	   $eRow=mysql_fetch_array($eRs);

	//if($a[5]==1){
	   /// 최종 가입건을 찾기위행
	   $L_SQL="SELECT * From 2021DaeriMember  WHERE Jumin='$a[16]'  and push='2' order by num desc";


	   $L_rs=mysql_query($L_SQL,$connect);
	   $L_Row=mysql_fetch_array($L_rs);

	   $inserday2=explode("-",$eRow[endorse_day]);

		$inserday2[0]=substr($inserday2[0], 2, 2);  

		//$endorse_day=$inserday2[0].".".$inserday2[1].".".$inserday2[2];
		$endorse_day=$inserday2[1].".".$inserday2[2];
	   $a[33]=2;// 인증을 만들기 위해서 업체화면에서는 1이면 안나오게 하기위해

	   $a[41]=mysql_result($result2,$count,"preminum1");  //1회차 보험료


	   //개별 할인 할증을 적용하기 위해 
//2019-10-15 

		$sqlr="SELECT * FROM 2021rate WHERE policy='$a[8]' and jumin='$a[16]'";

					//	echo $sqlr ;

		$rsr=mysql_query($sqlr,$connect);

		$rowr=mysql_fetch_array($rsr);

		$rate_=$rowr[rate];


	//}	
	//}	
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2].'['.$p[0].']'."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";//InsuranceCompany
	   echo "<a8>".$a[8]."</a8>\n";//policyNum
	   echo "<a9>".$endorse_day."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";//설계번호 동부화재
	   echo "<a11>".$a[11]."</a11>\n";//2012DaeriCompanyNum
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";//CertiTableNum
	   echo "<a14>".$a[14]."</a14>\n";//CertiTableNum
	   echo "<eNum>".$eNum."</eNum>\n"; //eNum
	   echo "<endorseDay>".$endorse_day."</endorseDay>\n";
	   echo "<a15>".$a[15]."</a15>\n";//CertiTableNum
	   echo "<a16>".$duplNum."</a16>\n";//중복
	    echo "<a17>".$moRow[company].$jagi[$k]."</a17>\n";//모계약
	   echo "<a18>".$qRow[gita]."</a18>\n";//모계약
	   echo "<a19>".$moNum."</a19>\n";//모계약의 certi num
	    echo "<a20>".$a[20]."</a20>\n";//사고
	    
		 echo "<a39>".$L_Row[dongbuCerti ]."</a39>\n";//사고

		  echo "<a49>".$a[19]."</a49>\n";//핸드폰

		 echo "<a30>".$a[30]."</a30>\n";//통신사
		 echo "<a31>".$a[31]."</a31>\n";//명의자
		 echo "<a32>".$a[32]."</a32>\n";//주소
		  echo "<a33>".$a[33]."</a33>\n";//인증
		  echo "<a34>".$a[19]."</a34>\n";//핸드폰

		  echo "<a41>".number_format($a[41])."</a41>\n";//1회차 보험료
		   echo "<rate>".$rate_."</rate>\n";//개인별 할증 할증율
		$moRow[company]='';
	   $count++;
	}
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($TotalMember)."명"."</totalMember>\n";
echo"</data>";

	?>