<?session_start();
	include '../../../dbcon.php';
//include "./escapeChange.php";
?>
<?echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];

		$daeriCompanyNum 	= $_POST["dNum"];
		$etage = $_POST["etage"];
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
				
					
					//대리운전회사의 대리,탁송별 인원과 당사 인원 비교
			


			// 대리운전회사의 특정일을 찾기위해 
			

			
			require_once '../../../2012/pop_up/_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../../../2012/pop_up/_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);


			$m=0;
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				$m++;
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

						
						$juminLength=strlen($jumin);
						//include "../../../2012/pop_up/_db/php/kdriveCompare.php";

						$sql="SELECT * FROM  2012DaeriMember WHERE Jumin='$jumin' AND 2012DaeriCompanyNum='$daeriCompanyNum' AND etag='$etage' AND push='4'";

						$insert="INSERT INTO kdriveMember (Name,Jumin,etag,2012DaeriCompanyNum,endorse_day) ";
						$insert.="values ('$pName','$jumin','$etage','$daeriCompanyNum',now())";
						//echo $insert;
						//$rs=mysql_query($insert,$connect);
						$rs=mysql_query($sql,$connect);
						$count=mysql_num_rows($rs);

						$row=mysql_fetch_array($rs);

							//echo $row[Jumin];
						if($count==0){
							$m__=1;  //계약없음
						}elseif($count==1){
						  $m__=2;
						}else{
						  $m__=3; // 중복 게약
						}



						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");

						echo("\t\t<bunho>".$m."</bunho>\n");
						echo("\t\t<bunho2>".$m__."</bunho2>\n");
						echo("\t\t<name>".$pName1."</name>\n");
						
						echo("\t\t<jumin>".$jumin."</jumin>\n");
						
						echo("\t\t<daeriCompanyNum>".$daeriCompanyNum."</daeriCompanyNum>\n");
						echo("\t\t<etage>".$etage."</etage>\n");
						
					echo("\t</policy>\n");
					
				
				echo "\n";

			}
						   
							
			
			
			@mysql_close($DB->Connect_ID);

		}
		echo "</values>";




//print_r($data);
//print_r($data->formatRecords);
?>
