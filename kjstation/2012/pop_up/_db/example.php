<?session_start();
	include '../../../dbcon.php';
//include "./escapeChange.php";
?>
<?echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];

		$daeriCompanyNum 	= $_POST["dNum"];
		$certiTableNum = $_POST["cNum"];
		$date = $_POST["date"];
		$fileName= $_POST["fileName"];
		$sj=$_POST["sj"];
		//$proc="gias_new_W";
		//$daeriCompanyNum=1;
		//$certiTableNum=1;
		//$fileName="1.xlsx";
		//$keyword="대치동";
		//$date = "2014-05-28";

		if(isset($HTTP_RAW_POST_DATA)) {
			parse_str($HTTP_RAW_POST_DATA);
		}
	//$daeriCompanyNum=3;
		//$table = "2014DaeriCompany ";
		
		//$DB = new DB_Class();

		//$proc="gias_new_W";

		if($proc == "gias_new_W") {
					
			$endorse=$_POST[endorse];
			$endorseDay =$_POST[endorseDay];


			// 대리운전회사의 특정일을 찾기위해 
			$dsql="SELECT * FROM 2012DaeriCompany   WHERE num='$daeriCompanyNum'";

			//echo "dsq $dsql ";
			$drs=mysql_query($dsql,$connect);
			$drow=mysql_fetch_array($drs);

			$dongliStartDay=$drow[FirstStartDay];
			//보험회사 
				$sQl="SELECT * FROM 2012CertiTable   WHERE num='$certiTableNum'";
				$sRs=mysql_query($sQl,$connect);
				$sRow=mysql_fetch_array($sRs);

				$FirstStartDay=$sRow['FirstStart'];
				$nabang=$sRow['nabang'];
				$nabang_1=$sRow['nabang_1'];
				$state=$sRow['state'];
				switch($sRow[InsuraneCompany]){
					case 1 :
						$insCompany='흥국';
						break;
					case 2 :
						$insCompany='DB';
						break;
					case 3 :
						$insCompany='KB';
						break;
					case 4 :
						$insCompany='현대';
						break;
					case 5 :
						$insCompany='더 케이';
						break;
					case 6 :
						$insCompany='한화';
						break;
					case 7 :
						$insCompany='MG';
						if($state==2){  //유예인경우 
							$nabang_1+=1;
						}

						break;
					case 8 :
						$insCompany='삼성';
						break;
				}

				//$insCompany=htmlspecialchars($insCompany,ENT_NOQUOTES,"UTF-8");
				//$insCompany=iconv("utf-8","euc-kr",$insCompany);
				//기준일 - 생일 = 앞 두자리가 만나이
				
				$startyDay=$sRow[startyDay];	
				$policyNum=$sRow[policyNum];
				$gita=$sRow[gita];

			
			require_once '../_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);



			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
					echo("\t<policy>\n");
				
						  //나이 계산 ,증권번호, 중복,주민오류 검사

						$pName1=trim($data->sheets[0]['cells'][$i][1]);
						$pName=iconv("utf-8","euc-kr",$pName1);
						$jumin=trim($data->sheets[0]['cells'][$i][2]);
						$hphone=trim($data->sheets[0]['cells'][$i][3]); // 핸드폰
						$shp=trim($data->sheets[0]['cells'][$i][4]);    //통신사

						switch($shp){
							case 1 :
								$shpname='SK';
								break;
							case 2 :
								$shpname='KT';
								break;
							case 3 :
								$shpname='LG';
								break;
							case 4 :
								$shpname='SK알뜰폰';
								break;
							case 5 :
								$shpname='KT알뜰폰';
								break;
							case 6 :
								$shpname='LG알뜰폰';
								break;
						}
						$nhp=trim($data->sheets[0]['cells'][$i][5]);//명의
						switch($nhp){
							case 1 :
								$nhpname='본인';
								break;
							case 2 :
								$nhpname='타인';
								break;
							
						}

						$address=trim($data->sheets[0]['cells'][$i][6]);//주소

					if($sRow[InsuraneCompany]==2){// DB화재인경우만
						$policyNum=trim($data->sheets[0]['cells'][$i][7]);// DB화재 인경우 
					}	
						$juminLength=strlen($jumin);
						include "./php/excell_nai.php";

						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");


						echo("\t\t<name>".$pName1."</name>\n");
						echo("\t\t<nai>".$nai."</nai>\n");
						echo("\t\t<nai2>".$nai2."</nai2>\n");
						echo("\t\t<jumin>".$jumin."</jumin>\n");
						echo("\t\t<hphone>".$hphone."</hphone>\n");
						echo("\t\t<local>".$shpname."</local>\n");//통신사
						echo("\t\t<nhp>".$nhpname."</nhp>\n");//명의
						echo("\t\t<jisa>".$address."</jisa>\n");//주소
						echo("\t\t<daeriCompanyNum>".$daeriCompanyNum."</daeriCompanyNum>\n");
						echo("\t\t<certiTableNum>".$certiTableNum."</certiTableNum>\n");
						echo("\t\t<insCompany>".$insCompany."</insCompany>\n");
						echo("\t\t<policyNum>".$policyNum."</policyNum>\n");
						echo("\t\t<date>".$startyDay."</date>\n");//증권의 시기가 내리운전기사가 등록한날
						echo("\t\t<gita>".$gita."</gita>\n");
						echo("\t\t<duplicte>".$duplicte[$i]."</duplicte>\n");
						echo("\t\t<Lcount>".$Lcount[$j]."</Lcount>\n");  //지역또는 지사 불일치
						echo("\t\t<l_button>".$l_button."</l_button>\n"); //지역또는 지사 불일치 경우 등록 버튼이 안나오게
						echo("\t\t<n_duplicate>".$n_duplicate[$j]."</n_duplicate>\n");
						echo("\t\t<n_duplicate2>".$n_duplicate2[$j]."</n_duplicate2>\n");
						echo("\t\t<sj>".$sj."</sj>\n");
					echo("\t</policy>\n");
					
				
				echo "\n";

			}
						   
							
		
			
			@mysql_close($DB->Connect_ID);

		}
		echo "</values>";




//print_r($data);
//print_r($data->formatRecords);
?>
