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

		if($proc == "rateExcell") { //증권번호를 update 하기 위해
					
			$policy=$_POST[policy];
			


			

			
			require_once '../_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);



			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
					echo("\t<policy>\n");
				
						  //성명,주민번호,rate
						$pName=trim($data->sheets[0]['cells'][$i][1]);


						$jumin=trim($data->sheets[0]['cells'][$i][2]);
						$dongbuCerti=trim($data->sheets[0]['cells'][$i][3]); // 핸드폰
						

						$sql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$policy' and jumin='$jumin' and push='4'";
	
						$rs=mysql_query($sql,$connect);

						$row=mysql_fetch_array($rs);	
					

						if($row[num]){

							$update = "UPDATE 2012DaeriMember SET dongbuCerti='$dongbuCerti' WHERE num='$row[num]'";

							mysql_query($update,$connect);

						}
						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");


						echo("\t\t<name>".$pName."</name>\n");
						
						echo("\t\t<jumin>".$jumin."</jumin>\n");
						echo("\t\t<rate>".$dongbuCerti."</rate>\n");
						
						echo("\t\t<policyNum>".$policy."</policyNum>\n");
						
						echo("\t\t<sj>".$sj."</sj>\n");
					echo("\t</policy>\n");
					
				
				echo "\n";

			}
						   
				 unlink($fileName); //파일 삭제			
		
			
			@mysql_close($DB->Connect_ID);

		}else if($proc == "rateExcell_W") {
					
			$policy=$_POST[policy];
			
			require_once '../_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);



			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
					echo("\t<policy>\n");
				
						  //성명,주민번호,rate
						$pName=trim($data->sheets[0]['cells'][$i][1]);
						$jumin=trim($data->sheets[0]['cells'][$i][2]);
						$rate=trim($data->sheets[0]['cells'][$i][3]); // 핸드폰
						

						//저장하기 전 조회하여 없으면 입력 / 없으면 update
						

						$sql="SELECT * FROM 2019rate WHERE policy='$policy' and jumin='$jumin'";

						//echo $sql ;

						$rs=mysql_query($sql,$connect);

						$row=mysql_fetch_array($rs);

						if($row[num]){

							$update = "UPDATE 2019rate SET rate='$rate' WHERE num='$row[num]'";

							mysql_query($update,$connect);

						}else{

							$insert="INSERT into 2019rate (policy,jumin,rate ) ";
							$insert.="VALUES ('$policy','$jumin','$rate')";

							//echo $insert;
							mysql_query($insert,$connect);
						}

					
						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");


						echo("\t\t<name>".$pName."</name>\n");
						
						echo("\t\t<jumin>".$jumin."</jumin>\n");
						echo("\t\t<rate>".$rate."</rate>\n");
						
						echo("\t\t<policyNum>".$policy."</policyNum>\n");
						
						echo("\t\t<sj>".$sj."</sj>\n");
					echo("\t</policy>\n");
					
				
				echo "\n";

			}
						   
							
			 unlink($fileName); //파일 삭제
			
			@mysql_close($DB->Connect_ID);

		}else if($proc == "newInsert") { //증권번호를 update 하기 위해
					
			$policy=$_POST[policy];
			$DaeriCompanyNum=$_POST[DaeriCompanyNum];

			
			
			$c_sql="SELECT * FROM 2012CertiTable WHERE num='$policy'";
			$c_rs=mysql_query($c_sql,$connect);
			$c_row=mysql_fetch_array($c_rs);
			

			$insuranceCompany=$c_row[InsuraneCompany];
			$etage=$c_row[gita];
			require_once '../_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);



			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
					echo("\t<policy>\n");
				
						  //성명,주민번호,rate
						$pName1=trim($data->sheets[0]['cells'][$i][1]);
						
						$pName=iconv("utf-8","euc-kr",$pName1);
						$jumin=trim($data->sheets[0]['cells'][$i][2]);
						$dongbuCerti=trim($data->sheets[0]['cells'][$i][3]); // 증권번호
						$hphone=trim($data->sheets[0]['cells'][$i][4]); // 핸드폰
						

						
					    $insert="INSERT into 2012DaeriMember (moCertiNum,2012DaeriCompanyNum,InsuranceCompany,CertiTableNum,Name,Jumin,nai,push,etag,FirstStart, ";
						$insert.="state,cancel,sangtae,Hphone,InPutDay,OutPutDay,EndorsePnum,dongbuCerti,dongbuSelNumber,dongbusigi,dongbujeongi,nabang_1,ch, ";
						$insert.="changeCom,sPrem,sago,p_buho,a6b,a7b,a8b) ";
						$insert.="VALUES ('','$DaeriCompanyNum','$insuranceCompany','$policy','$pName','$jumin','','4','$etage','', ";
						$insert.="'','','2','$hphone',now(),'','','$dongbuCerti','','','','','', ";
						$insert.="'','','','','','','')";

						//echo $insert; 
						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");
						mysql_query($insert,$connect);

						echo("\t\t<name>".$pName1."</name>\n");
						
						echo("\t\t<jumin>".$jumin."</jumin>\n");
						echo("\t\t<rate>".$dongbuCerti."</rate>\n");
						
						echo("\t\t<policyNum>".$policy."</policyNum>\n");
						echo("\t\t<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n");
						echo("\t\t<hphone>".$hphone."</hphone>\n");
						
						echo("\t\t<sj>".$sj."</sj>\n");
					echo("\t</policy>\n");
					
				
				echo "\n";

			}
						   
				 unlink($fileName); //파일 삭제			
		
			
			@mysql_close($DB->Connect_ID);

		}
		echo "</values>";




//print_r($data);
//print_r($data->formatRecords);
?>
