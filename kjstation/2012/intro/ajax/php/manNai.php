<?
	   if($a[7]==2){// 동부 화재 인경우
			
			
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
			
			//만나이 계산을 위해 
				$mSql="SELECT * FROM 2012CertiTable WHERE num='$a[13]'";
				
				$mRs=mysql_query($mSql,$connect);
				$mRow=mysql_fetch_array($mRs);	
			//	echo "mSql $mSql <bR>";
				//동부화재를 제외한 나머지 회사는 가입일 기준으로 
			//


		}
		//만나이 계산을 위해 
			//$p=explode("-",$a[3]);

			$p[0]=substr($a[3],0,6);
			$s=explode("-",$mRow[startyDay]);
			$m1=substr($mRow[startyDay],0,4);
			$m2=substr($mRow[startyDay],5,2);
			$m3=substr($mRow[startyDay],8,2);
			$sigi=$m1.$m2.$m3;		
			//$t=$p[0];
			$birth="19".$p[0];
			$p[0]=$sigi-$birth;
			$p[0]=floor(substr($p[0],0,2));


		
		//만나이 계산을 완료 후  저장한다
		if($a[7]==7){ //Mg화재 증권번호 입력을 위해
				//include "./php/mgCerti.php"; 

				$pSql="SELECT * FROM 2012Cpreminum WHERE DaeriCompanyNum='$a[11]' ";
				$pSql.="and CertiTableNum='$a[13]' and sPreminum<='$p[0]' and ePreminum>='$p[0]'";

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

		}
	?>	