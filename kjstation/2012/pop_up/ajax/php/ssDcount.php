<? // 2012DaeriMember  에서 흥국화재 연령별 기사 구하기 위해 
		$inwonTotal[$rCertiRow[InsuraneCompany]]='';
		$tmPreminumTotal[$rCertiRow[InsuraneCompany]]='';
		for($_j=1;$_j<7;$_j++){

			switch ($_j){

				case 1 :
						$where="(nai>='26' and nai<='34')";
						$nai='26세~34세';
						$etag='일반';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum1];
					break;
				case 2 :
					    $where="(nai>='35' and nai<='47')";
						$nai='34세~47세';
						$etag='일반';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum2];
				break;
				case 3 :
						$where="(nai>='48')";
						$nai='48세이상';
						$etag='일반';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum3];
					break;
				case 4 :
						$where="(nai>='26' and nai<='34')";
						$nai='26세~34세';
						$etag='탁송';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE1];
					break;
				case 5 :
					    $where="(nai>='35' and nai<='47')";
						$nai='34세~47세';
						$etag='탁송';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE2];
				break;
				case 6 :
						$where="(nai>='48')";
						$nai='48세이상';
						$etag='탁송';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE3];
					break;
			}
				


		$sql2="SELECT num FROM 2012DaeriMember  WHERE $where and push='4' and 2012DaeriCompanyNum='$num' and InsuranceCompany='$rCertiRow[InsuraneCompany]' $ewhere ";
		$rs2=mysql_query($sql2);

		 $inwon[$_j]=mysql_num_rows($rs2);//인원
		 $inwonTotal[$rCertiRow[InsuraneCompany]]+=$inwon[$_j];//인원계
		 $naiGuBun[$_j]=$nai;
		 $mothPreminum[$_j]=$monThP;
		 $etage[$_j]=$etag;
		 $Tm[$_j]= $mothPreminum[$_j]*$inwon[$_j];//인원*월보험료
		 $tmPreminumTotal[$rCertiRow[InsuraneCompany]]+=$Tm[$_j];// 인원*월보험료계

		echo "<inComNum".$rCertiRow[InsuraneCompany].$_j.">".$iCompany."</inComNum".$rCertiRow[InsuraneCompany].$_j.">\n";//
		echo "<etag".$rCertiRow[InsuraneCompany].$_j.">".$etag."</etag".$rCertiRow[InsuraneCompany].$_j.">\n";//
		echo "<naiGuBun".$rCertiRow[InsuraneCompany].$_j.">".$naiGuBun[$_j]."</naiGuBun".$rCertiRow[InsuraneCompany].$_j.">\n";//
		echo "<inWon".$rCertiRow[InsuraneCompany].$_j.">".$inwon[$_j]."</inWon".$rCertiRow[InsuraneCompany].$_j.">\n";//
		echo "<mothPreminum".$rCertiRow[InsuraneCompany].$_j.">".number_format($mothPreminum[$_j])."</mothPreminum".$rCertiRow[InsuraneCompany].$_j.">\n";// 나이별 월 보험료 
		echo "<Tm".$rCertiRow[InsuraneCompany].$_j.">".number_format($Tm[$_j])."</Tm".$rCertiRow[InsuraneCompany].$_j.">\n";// //인원*나이별 월 보험료 */
		}
		echo "<inWonTotal".$rCertiRow[InsuraneCompany].">".$inwonTotal[$rCertiRow[InsuraneCompany]]."</inWonTotal".$rCertiRow[InsuraneCompany].">\n";//
		echo "<tmPreminumTotal".$rCertiRow[InsuraneCompany].">".number_format($tmPreminumTotal[$rCertiRow[InsuraneCompany]])."</tmPreminumTotal".$rCertiRow[InsuraneCompany].">\n";//



?>