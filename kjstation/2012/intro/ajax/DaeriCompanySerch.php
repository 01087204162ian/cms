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
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);
	$insuranceComNum=iconv("utf-8","euc-kr",$_GET['insuranceComNum']);
	
/*	if($s_contents){
		$where="WHERE Name like '%$s_contents%' $where order by InPutDay desc ";
	}else{
		//$where="WHERE start<='$end' order by start asc ";
		$where="order by num desc ";
	}*/
	if($insuranceComNum!=99){

		$where4="and InsuranceCompany='$insuranceComNum'";
	}
	
	if($DaeriCompanyNum){
		if($CertiTableNum){
				$where4="and CertiTableNum='$CertiTableNum' ";
		}

		if($DaeriCompanyNum=='494'){ //2015-10-13 이에스엘비에스 동부화재 분납체크를 쉽게 하기위해
			//$where="WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and (push='4' or push='1')  $where4 order by dongbuCerti asc";
			$where="WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and (push='4')   order by Jumin desc";
		}else{

			$where="WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' and (push='4' )   $where4 order by Jumin asc";
	   }
	}
	if($s_contents){
		$where="WHERE Name like '%$s_contents%' $where order by InPutDay asc ";
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
	echo "<sql>".$sql.$insuranceComNum."</sql>\n";
	//echo "<last>".$last.$count."</last>\n";
	echo "<total>".$total."</total>\n";
	for($k=$count;$k<$last;$k++){
	   $a[1]=mysql_result($result2,$count,"num");
	   $a[2]=mysql_result($result2,$count,"Name");
	   $a[3]=mysql_result($result2,$count,"Jumin");
	   $a[13]=mysql_result($result2,$count,"CertiTableNum");
	   //만나이 계산을 위해 


	   ///
	   $a[5]=mysql_result($result2,$count,"push");
	   $a[6]=mysql_result($result2,$count,"etag");
	   $a[7]=mysql_result($result2,$count,"InsuranceCompany");
	   $a[11]=mysql_result($result2,$count,"2012DaeriCompanyNum");  
	   $a[14]=mysql_result($result2,$count,"cancel");

		if($a[14]==42 && $a[5]==4){
			
			$a[5]=8;//해지중이기위해서
		}

		if($a[14]==12 && $a[5]==1){//청약 취소 
			$count++;
			continue;	
		}

		if($a[14]==13 && $a[5]==1){//청약 거절 
			$count++;
			continue;
			
		}

       
		
	  //대리운전회사 찾기 
		$cSql="SELECT * FROM  2012DaeriCompany WHERE num='$a[11]'";
		$cRs=mysql_query($cSql,$connect);
		$cRow=mysql_fetch_array($cRs);

		$a[12]=$cRow[company];
			  /////////////////////////////////
	   //증권번호를 찾기 위해 
		if($a[7]==2){//
			$a[8]=mysql_result($result2,$count,"dongbuCerti");
			$a[14]=mysql_result($result2,$count,"dongbuSelNumber");
			$a[15]=mysql_result($result2,$count,"nabang_1");//회차
			
			//동부화재는 가입일 기준이지만 가입일이 2011.09.10일이고 
			//모증권의 보험시작일이 2012.09.11일인 경우  가입일보다 
			//2012.09.11일이 보험 나이  계산 기준일이 된다
			
			    $mSql="SELECT * FROM 2012CertiTable WHERE num='$a[13]'";
				
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);
			if(mysql_result($result2,$count,"InPutDay")>$mRow[startyDay]){
				$mRow[startyDay]=mysql_result($result2,$count,"InPutDay");
			}else{
				$mRow[startyDay]=$mRow[startyDay];//동부화재는 가입일 기준으로 
			}	//
		}else{
			$a[8]=mysql_result($result2,$count,"dongbuCerti");//
			if(!$a[8]){
				$pSql="SELECT * FROM 2012CertiTable WHERE num='$a[13]'";
				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);	
				//개별증권번호가 없으면 입력하게 ...
				$uCsql="UPDATE 2012DaeriMember  SET dongbuCerti='$pRow[policyNum]' WHERE  num='$a[1]'";
				mysql_query($uCsql,$connect);
				$a[8]=$pRow[policyNum];
			}
			//만나이 계산을 위해 
				$mSql="SELECT * FROM 2012CertiTable WHERE num='$a[13]'";
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);	
			//	echo "mSql $mSql <bR>";
				//동부화재를 제외한 나머지 회사는 가입일 기준으로 
			//


		}
		//만나이 계산을 위해 
			$p=explode("-",$a[3]);
			$s=explode("-",$mRow[startyDay]);
			$m1=substr($mRow[startyDay],0,4);
			$m2=substr($mRow[startyDay],5,2);
			$m3=substr($mRow[startyDay],8,2);
			$sigi=$m1.$m2.$m3;			
			$birth="19".$p[0];
			$p[0]=$sigi-$birth;
			$p[0]=floor(substr($p[0],0,2));



		//만나이 계산을 완료 후  저장한다


		$tupdate="UPDATE 2012DaeriMember SET nai='$p[0]' WHERE num='$a[1]'";
		mysql_query($tupdate,$connect);
	   if(!$a[6]){$a[6]=1;}//a[6] 없을 경우 일반으로 본다

	   



	   //유예 및 실효를 위해 

		if($a[7]==2){//동부화재 인 경우만
			//$sigiStart=mysql_result($result2,$count,"dongbusigi");
			//$sigiEnd=mysql_result($result2,$count,"dongbujeongi");
		   $a[9]=mysql_result($result2,$count,"InPutDay");
		   $a[10]=mysql_result($result2,$count,"OutPutDay");
			//$gigan=((strtotime("$sigiEnd"))-(strtotime("$sigiStart")))/(60*60*24);//완전한 개인 계약만 유예,실효 등등을 찾는다
			//if($gigan>=365){
			//	$naBang=mysql_result($result2,$count,"nabang_1");
			
			//	$inPnum=$a[7];
			//	 include "../../pop_up/ajax/php/nabState.php";//nabSunsoChange.php 공동으로 사용
			//}
			if($a[10]=='0000-00-00'){
				$a[10]='';
		   }
		}else{

		   $a[9]=mysql_result($result2,$count,"InPutDay");
		   $a[10]=mysql_result($result2,$count,"OutPutDay");
		   if($a[10]=='0000-00-00'){
				$a[10]='';
		   }



		}
	   $b=explode("-",$a[9]);
	   $a[9]=substr($b[0],2,2).".".$b[1].".".$b[2];
	   $b=explode("-",$a[10]);
	   if($a[10]){
	   $a[10]=substr($b[0],2,2).".".$b[1].".".$b[2];
	   }
	   if($a[14]==00){
			$a[14]='';
			
	   }

	   

	    $a[4]=mysql_result($result2,$count,"nai");


		// 각각의 보험료를 구하기 위해 
		
		//보험료을 구하기 위해 
		//echo "a4  $a[5] <Br>";
if($a[5]==4){	//정상인 경우만 	
		switch($a[7]){
			case 1 :
				$rRow[nab]='흥국';
				include "./php/naiPr1.php";
				break;
			case 2 :
				$rRow[nab]='동부';
				include "./php/naiPr2.php";

				$sRow[dongbuCerti]="017-".$sRow[dongbuCerti]."-000";
				break;
			case 3 :
				$rRow[nab]='LiG';
			    include "./php/naiPr3.php";
				break;
			case 4 :
				$rRow[nab]='현대';
			    include "./php/naiPr4.php";
				break;
			case 5 :
				$rRow[nab]='한화';
			   include "./php/naiPr5.php";
				break;
			case 6 :
				$rRow[nab]='더케이';
			   include "./php/naiPr5.php";
				break;
			case 5 :
				$rRow[nab]='MG';
			   include "./php/naiPr5.php";
				break;


		}
		$a[10]=number_format($a[10]);
		if($a[10]==0){ $a[10]='';}
}	
			$a[34]=mysql_result($result2,$count,"Hphone");

		$a[30]=mysql_result($result2,$count,"a6b");
		$a[31]=mysql_result($result2,$count,"a7b");
		$a[32]=mysql_result($result2,$count,"a8b");

		$a[33]=2;// 인증을 만들기 위해서 업체화면에서는 1이면 안나오게 하기위해
	   echo "<a1>".$a[1]."</a1>\n";
	   echo "<a2>".$a[2].'['.$a[4].']'."</a2>\n";
	   echo "<a3>".$a[3]."</a3>\n";
	   echo "<a4>".$a[4]."</a4>\n";
	   echo "<a5>".$a[5]."</a5>\n";
	   echo "<a6>".$a[6]."</a6>\n";
	   echo "<a7>".$a[7]."</a7>\n";//InsuranceCompany
	   echo "<a8>".$a[8]."</a8>\n";//policyNum
	   echo "<a9>".$a[9]."</a9>\n";
	   echo "<a10>".$a[10]."</a10>\n";
	   echo "<a11>".$a[11]."</a11>\n";//2012DaeriCompanyNum
	   echo "<a12>".$a[12]."</a12>\n";
	   echo "<a13>".$a[13]."</a13>\n";//CertiTableNum
	   echo "<a14>".$a[14]."</a14>\n";//dongbuSelNumber 설게번호
	   echo "<a15>".$a[15]."</a15>\n";//납입회차
	   echo "<a16>".$naState.$gigan."</a16>\n";//

		echo "<a30>".$a[30]."</a30>\n";//통신사
	   echo "<a31>".$a[31]."</a31>\n";//명의자
	   echo "<a32>".$a[32]."</a32>\n";//주소
	   echo "<a33>".$a[33]."</a33>\n";//인증
	   echo "<a34>".$a[34]."</a34>\n";//핸드폰
		
	   //다른 증권을 찾기 위해  //
	   $sql="SELECT distinct(InsuraneCompany),policyNum FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$DaeriCompanyNum' and startyDay>='$yearbefore'";
	   $rs=mysql_query($sql,$connect);
	   $certiCount=mysql_num_rows($rs);

		$cc=$certiCount+1;
	echo "<c>".$cc."</c>\n";//
	   for($_u=0;$_u<$cc;$_u++){

		   
			$CroW=mysql_fetch_array($rs);
			if($_u==$certiCount){
				$CroW[InsuraneCompany]='';
				$CroW[policyNum]='';
			}
		echo "<c".$k.$_u.">".$CroW[InsuraneCompany]."</c".$k.$_u.">\n";////1.흥국,2동부,3Lig,4현대
		echo "<d".$k.$_u.">".$CroW[policyNum]."</d".$k.$_u.">\n";////증권번호
		echo "<f".$k.$_u.">".$CroW[policyNum]."</f".$k.$_u.">\n";//2012CertiTable의 num
	   }
		
	   $count++;
	}
	echo "<CertiTableNum>".$a[13]."</CertiTableNum>\n";//
	echo "<insuranceComNum>".$insuranceComNum."</insuranceComNum>\n";//
	/***********************************************************/
	/* page 구성을 위하여                                       */
	/***********************************************************/
	include"./page.php";
	echo"<page>".$page."</page>\n";
	echo"<totalpage>".$total_page."</totalpage>\n";
	echo"<totalMember>".number_format($TotalMember)."명"."</totalMember>\n";
echo"</data>";

	?>