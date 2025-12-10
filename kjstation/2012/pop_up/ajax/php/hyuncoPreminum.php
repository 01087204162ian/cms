<?
$Sql="SELECT  * FROM 2012CertiTable   WHERE num='$num'";
//echo "S $Sql <br>";
	$rs=mysql_query($Sql,$connect);
	
	$row=mysql_fetch_array($rs);
		

		///$startDay=$row[FirstStart];

	    //$SigiDay=explode("-",$startDay);
		//일반보험료 
	//if($row[yearP1]){//보험료 입력이 되어 있을 경우에만
	//if($startDay){//보험료 입력이 되어 있을 경우에만
		$a[0]=$row[yearP1];
		$a[1]=$row[yearP2];
		$a[2]=$row[yearP3];//년간보험료
		$a[3]=$row[yearP4];//년간보험료
		$a[4]=$row[yearP5];//년간보험료
	
		$b[0]=round($row[yearP1]*0.20,-1);//1회차 보험료
		$b[1]=round($row[yearP2]*0.20,-1);	
		$b[2]=round($row[yearP3]*0.20,-1);
		$b[3]=round($row[yearP4]*0.20,-1);
		$b[4]=round($row[yearP5]*0.20,-1);

		$c[0]=round($row[yearP1]*0.1,-1); //2회차 보험료
		$c[1]=round($row[yearP2]*0.1,-1);	
		$c[2]=round($row[yearP3]*0.1,-1);
		$c[3]=round($row[yearP4]*0.1,-1);
		$c[4]=round($row[yearP5]*0.1,-1);

		$d[0]=round($row[yearP1]*0.05,-1);//3회차 보험료
		$d[1]=round($row[yearP2]*0.05,-1);	
		$d[2]=round($row[yearP3]*0.05,-1);
		$d[3]=round($row[yearP4]*0.05,-1);
		$d[4]=round($row[yearP5]*0.05,-1);

	/*	//탁송보험료 
		$aE[0]=$row[yearPE1];
		$aE[1]=$row[yearPE2];
		$aE[2]=$row[yearPE3];//년간보험료
	
		$bE[0]=round($row[yearPE1]*0.20,-1);//1회차 보험료
		$bE[1]=round($row[yearPE2]*0.20,-1);	
		$bE[2]=round($row[yearPE3]*0.20,-1);

		$cE[0]=round($row[yearPE1]*0.1,-1); //2회차 보험료
		$cE[1]=round($row[yearPE2]*0.1,-1);	
		$cE[2]=round($row[yearPE3]*0.1,-1);

		$dE[0]=round($row[yearPE1]*0.05,-1);//3회차 보험료
		$dE[1]=round($row[yearPE2]*0.05,-1);	
		$dE[2]=round($row[yearPE3]*0.05,-1);*/



	//$DaeriCompanyNum  //2012DaeriMember 에서 연령별 인원을 구한다...

	$mSql="SELECT num FROM 2012DaeriMember    WHERE nai<'26' and nai<='34' ";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4'";
	$m1rs=mysql_query($mSql,$connect);
	$inwon[0]=mysql_num_rows($m1rs);


	$mSql="SELECT num FROM 2012DaeriMember  WHERE nai>='35' and nai<='47' ";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4'";
	$m2rs=mysql_query($mSql,$connect);
	$inwon[1]=mysql_num_rows($m2rs);

	$mSql="SELECT num FROM 2012DaeriMember   WHERE nai>='48' and nai<='54' ";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4'";
	$m3rs=mysql_query($mSql,$connect);
	$inwon[2]=mysql_num_rows($m3rs);




	$mSql="SELECT num FROM 2012DaeriMember WHERE nai>='55'  ";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4'";
	$m3rs=mysql_query($mSql,$connect);
	$inwon[3]=mysql_num_rows($m3rs);

/*
	$mSql="SELECT num FROM 2012DaeriMember  WHERE nai>='35' and nai<='47' and etag='2'";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4' ";
	$m2rs=mysql_query($mSql,$connect);
	$inwonE[1]=mysql_num_rows($m2rs);

	$mSql="SELECT num FROM 2012DaeriMember  WHERE nai>='48'  and etag='2'";
	$mSql.="and 2012DaeriCompanyNum='$DaeriCompanyNum' and CertiTableNum='$num' and push='4'";
	$m3rs=mysql_query($mSql,$connect);
	$inwonE[2]=mysql_num_rows($m3rs);

*/
	//연령별 보험료 

	$monthsP[0]=$row[preminum1];//26세미만
	$monthsP[1]=$row[preminum2];//36세~30세 보험료
	$monthsP[2]=$row[preminum3];//31세~44세 보험료
	$monthsP[3]=$row[preminum4];//45세~49세 보험료
	$monthsP[4]=$row[preminum5];//50세 보험료


	//일반 인원 합계

	$generalDaeriTotal=$inwon[0]+$inwon[1]+$inwon[2]+$inwon[3]+$inwon[4];

		
//if($generalDaeriTotal>0){$monthsP[4]
if($monthsP[0]>0){
  if($generalDaeriTotal>0){
	$RateInwon[0]=round(($inwon[0]/$generalDaeriTotal)*100,-1);
	$RateInwon[1]=round(($inwon[1]/$generalDaeriTotal)*100,-1);
	$RateInwon[2]=round(($inwon[2]/$generalDaeriTotal)*100,-1);
	$RateInwon[3]=round(($inwon[3]/$generalDaeriTotal)*100,-1);
	$RateInwon[4]=round(($inwon[4]/$generalDaeriTotal)*100,-1);

	$DaeriComP1=round($monthsP[0]*($inwon[0]/$generalDaeriTotal)+$monthsP[1]*($inwon[1]/$generalDaeriTotal)+$monthsP[2]*($inwon[2]/$generalDaeriTotal)+$monthsP[3]*($inwon[3]/$generalDaeriTotal)+$monthsP[4]*($inwon[4]/$generalDaeriTotal),-1);
//보험회사에 내는 보험료 
	$InsuP1=round(($a[0]/12)*($inwon[0]/$generalDaeriTotal)+($a[1]/12)*($inwon[1]/$generalDaeriTotal)+($a[2]/12)*($inwon[2]/$generalDaeriTotal)+($a[3]/12)*($inwon[3]/$generalDaeriTotal)+($a[4]/12)*($inwon[4]/$generalDaeriTotal),-1);

  }
	
/*	$monthsEP[0]=$row[preminumE1];//26미만 보험료 탁송
	$monthsEP[1]=$row[preminumE2];//26세~30세 보험료 탁송
	$monthsEP[2]=$row[preminumE3];//48세 보험료 탁송
   //탁송인원 합계

	$generalDaeriTotalE=$inwonE[0]+$inwonE[1]+$inwonE[2];
if($generalDaeriTotalE>0){
	$RateInwonE[0]=round(($inwonE[0]/$generalDaeriTotalE)*100,-1);
	$RateInwonE[1]=round(($inwonE[1]/$generalDaeriTotalE)*100,-1);
	$RateInwonE[2]=round(($inwonE[2]/$generalDaeriTotalE)*100,-1);

	$DaeriComP2=round($monthsEP[0]*($inwonE[0]/$generalDaeriTotalE)+$monthsEP[1]*($inwonE[1]/$generalDaeriTotalE)+$monthsEP[2]*($inwonE[2]/$generalDaeriTotalE),-1);
	$InsuP2=round(($aE[0]/12)*($inwonE[0]/$generalDaeriTotalE)+($aE[1]/12)*($inwonE[1]/$generalDaeriTotalE)+($aE[2]/12)*($inwonE[2]/$generalDaeriTotalE),-1);
}
	*/	

//대리운전회사로부터 받는 보험료 
	//연령별 보험료 * 연령별 비율

	$DaeriComP=($DaeriComP1+$DaeriComP2);

//보험회사에 내는 보험료 

$InsuP=($InsuP1+$InsuP2);
	
   include "./php/monthDay.php";//일일보험료 산출 하기우해
		$message="조회완료 !!";
	}else{

			$message="입력하세요 !!";
	}


		echo"<data>\n";
			echo "<sql>".$startDay.$Sql.$Dsql."</sql>\n";
			

		if($row[yearP1]>0){
		//if($startDay){
			for($_u_=0;$_u_<5;$_u_++){
				echo "<name".$_u_.">".number_format($a[$_u_])."</name".$_u_.">\n";////년간보험료
				echo "<nbme".$_u_.">".number_format($b[$_u_])."</nbme".$_u_.">\n";//1회차
				echo "<ncme".$_u_.">".number_format($c[$_u_])."</ncme".$_u_.">\n";//2회~8회차
				echo "<ndme".$_u_.">".number_format($d[$_u_])."</ndme".$_u_.">\n";//9~10회차
				echo "<neme".$_u_.">".number_format($inwon[$_u_])."[".$RateInwon[$_u_]."%]"."</neme".$_u_.">\n";///연령별 일반 대리기사
			/*	//탁송보험
				echo "<naEme".$_u_.">".number_format($aE[$_u_])."</naEme".$_u_.">\n";////년간보험료
				echo "<nbEme".$_u_.">".number_format($bE[$_u_])."</nbEme".$_u_.">\n";//1회차
				echo "<ncEme".$_u_.">".number_format($cE[$_u_])."</ncEme".$_u_.">\n";//2회~8회차
				echo "<ndEme".$_u_.">".number_format($dE[$_u_])."</ndEme".$_u_.">\n";//9~10회차
				echo "<neEme".$_u_.">".number_format($inwonE[$_u_])."[".$RateInwonE[$_u_]."%]"."</neEme".$_u_.">\n";///연령별 일반 대리기사*/
			}
			echo "<in1won>".$generalDaeriTotal."</in1won>\n";  //일반인원 총계
			echo "<in2won>".$generalDaeriTotalE."</in2won>\n"; //탁송인원총계
			echo "<in3won>".number_format($DaeriComP)."</in3won>\n";//대리운전회사로 부터 받는 보험료 
			echo "<in4won>".number_format($InsuP1)."</in4won>\n";    //보험회사에 내는 보험료
			//echo "<yearP2>".number_format($row[yearP2])."</yearP2>\n";
			//echo "<yearP3>".number_format($row[yearP3])."</yearP3>\n";
			echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";
			
			echo "<yearP>".number_format($yearP)."</yearP>\n";
			echo "<sunso>".$sunso."</sunso>\n";


			echo "<FirstStartDay>".$startDay."</FirstStartDay>\n";
			
			echo "<SigiDay>".$SigiDay[2]."</SigiDay>\n";
			//년령별 보험료   

			echo "<P26P>".number_format($monthsP[0])."</P26P>\n";
			echo "<P35P>".number_format($monthsP[1])."</P35P>\n";
			echo "<P48P>".number_format($monthsP[2])."</P48P>\n";
			echo "<P55P>".number_format($monthsP[3])."</P55P>\n";
		//	echo "<P50P>".number_format($monthsP[4])."</P50P>\n";

		/*	echo "<PE26P>".number_format($monthsEP[0])."</PE26P>\n";
			echo "<PE34P>".number_format($monthsEP[1])."</PE34P>\n";
			echo "<PE48P>".number_format($monthsEP[2])."</PE48P>\n";*/
			
			echo "<thisDay>".$thisDay."</thisDay>\n";
			echo "<aPeriod>".$Period."</aPeriod>\n";
			//일일보험료 
			for($_u_=0;$_u_<$Period;$_u_++){
				// echo "<smsNum".$_u_.">".$_k_."</smsNum".$_u_.">\n";
				echo "<d".$_u_.">".$p[$_u_]."</d".$_u_.">\n";//일자
				echo "<f".$_u_.">".$p2[$_u_]."</f".$_u_.">\n";//26세미만
				echo "<g".$_u_.">".$q2[$_u_]."</g".$_u_.">\n";//26~30

				echo "<h".$_u_.">".$r2[$_u_]."</h".$_u_.">\n";//31세~44세
				echo "<i".$_u_.">".$s2[$_u_]."</i".$_u_.">\n";//45세~49세

				echo "<j".$_u_.">".$t2[$_u_]."</j".$_u_.">\n";//50세
				


			 }
			 echo "<message>".$message."</message>\n";
			  echo "<eco>"."2"."</eco>\n";
		}else{
			echo "<FirstStartDay>".$startDay."</FirstStartDay>\n";
			echo "<message>".$message."</message>\n";
			 echo "<eco>"."1"."</eco>\n";
		}

		echo"</data>";


?>