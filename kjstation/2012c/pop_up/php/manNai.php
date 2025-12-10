<?//2012c,app에서도 이모줄을 사용한다 수정할 경우 이부분을 
	   if($insuranceNum==2){// 동부 화재 인경우
			
			
			//동부화재는 가입일 기준이지만 가입일이 2011.09.10일이고 
			//모증권의 보험시작일이 2012.09.11일인 경우  가입일보다 
			//2012.09.11일이 보험 나이  계산 기준일이 된다

		   //동부화재 배서일기준으로 만나이 계산
			
		/*	    $mSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);
			if(mysql_result($result2,$count,"InPutDay")>$mRow[startyDay]){
				$mRow[startyDay]=mysql_result($result2,$count,"InPutDay");
			}else{
				$mRow[startyDay]=$mRow[startyDay];//동부화재는 가입일 기준으로 
			}	*///

			$giDay=$endorseDay;
		}else{
			
			//만나이 계산을 위해 
				$mSql="SELECT * FROM 2012CertiTable WHERE num='$CertiTableNum'";
				
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);	
			//	echo "mSql $mSql <bR>";
				//동부화재를 제외한 나머지 회사는 가입일 기준으로 
			//
			$giDay=$mRow[startyDay];

		}
		//만나이 계산을 위해 
			//$p=explode("-",$a[3]);
			//$a[16]=$a[3];
		//2022-10-21 주민번호 없이 추가인 경우는 나이를 계산하지 않는다.
		if($a3b[$i]){
			$ju=explode('-',$a3b[$i]);
			$a[3]=$ju[0].$ju[1];
			$p[0]=substr($a[3],0,6);
			$s=explode("-",$giDay);
			$m1=substr($giDay,0,4);
			$m2=substr($giDay,5,2);
			$m3=substr($giDay,8,2);
			$Msigi=$m1.$m2.$m3;		
			//$t=$p[0];
			$birth="19".$p[0];
			$p[0]=$Msigi-$birth;
			$p[0]=floor(substr($p[0],0,2));

            $a6b[$i]=$p[0];
		}else{
			$a6b[$i]='';

		}

	//echo "나이";	echo $a6b[$i];
	/*	
		//만나이 계산을 완료 후  저장한다
		if($insuranceNum==7){ //Mg화재 증권번호 입력을 위해
				//include "./php/mgCerti.php"; 

				$pSql="SELECT * FROM 2012Cpreminum WHERE DaeriCompanyNum='$a[11]' ";
				$pSql.="and CertiTableNum='$CertiTableNum' and sPreminum<='$p[0]' and ePreminum>='$p[0]'";

				$pRs=mysql_query($pSql,$connect);
				$pRow=mysql_fetch_array($pRs);

			    $tupdate="UPDATE 2012DaeriMember SET dongbuCerti='$pRow[certi]', nai='$p[0]' WHERE num='$a[1]'";
				mysql_query($tupdate,$connect);
				$a[8]=$pRow[certi];
		}else{
			    $tupdate="UPDATE 2012DaeriMember SET nai='$p[0]' WHERE num='$a[1]'";

			//echo "tu $tupdate ";
			mysql_query($tupdate,$connect);
		//}

		}*/
	?>	