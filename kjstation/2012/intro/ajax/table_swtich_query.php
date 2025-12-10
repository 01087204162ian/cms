<?  switch($table_name_num){
				case '1' :

					$tableName="kibs_list";
					$name="com_name";
					$driver_name='name_1';
					$com_name='chubb';
					$post_gubun=$table_name_num;
					break;
				case '2' :
					$tableName="new_cs_dongbu";
					$name="con_name";
					$driver_name="oun_name";
					$company_name="company";
					$certi_number="certi_number";
					$com_name='dongbu';
					$post_gubun=$table_name_num;
					break;

				case '3':
					$tableName="lig_c";
					$name="c_name";
					$company_name="company";
					$certi_number="certi_number";
					$com_name='LiG';
					$post_gubun=$table_name_num;
					break;
				case '4':
					$tableName="lig_drive";
					$name="name";
					$lig_c_num="lig_c_num";
					$com_name='LiG';
					$post_gubun=$table_name_num;
					break;
				case '5':
					$tableName="ssang_c";
					$name="c_name";
					$company_name="company";
					$certi_number="certi_number";
					$com_name='쌍용';
					$post_gubun=$table_name_num;
					break;
				case '6':
					$tableName="ssang_drive";
					$name="name";
					$ssang_c_num="ssang_c_num";
					$com_name='쌍용';
					$post_gubun=$table_name_num;
					break;
				case '8' :

					$tableName="dongbu2_c";
					$name="c_name";
					$ssang_c_num="num";
					//$driver_name="oun_name";
					//$company_name="company";
					$certi_number="certi_number";
					$com_name='동부단체';
					$post_gubun=$table_name_num;
					break;
				case '9' :

					$tableName="dongbu2_drive";
					$name="name";
					$ssang_c_num="ssang_c_num";
					//$driver_name="oun_name";
					//$company_name="company";
					$certi_number="certi_number";
					$com_name='동부단체';
					$post_gubun=$table_name_num;
					break;

				 case '10'://현대단체
					$tableName="hyundai_c";
					$name="c_name";
					$company_name="company";
					$certi_number="certi_number";
					$com_name='현대';
					$post_gubun=$table_name_num;
					break;

				case '11':
					$tableName="hyundai_drive";
					$name="name";
					$hyundai_c_num="hyundai_c_num";
					$com_name='현대';
					$post_gubun=$table_name_num;
					break;
				case '12':
					$tableName="2012DaeriCompany";
					$name="name";
					$hyundai_c_num="hyundai_c_num";
					///$com_name='현대';
					$post_gubun=$table_name_num;
					break;

			}
			//echo "ga $ga_date <br>";
			//echo "table_name_num $table_name_num <br>";
       //data table 에 따라 query 값이 달라짐
	switch($table_name_num){
		case '1' ://kibs_list
			$sql="SELECT $name as company,$driver_name as oun_name FROM $tableName WHERE num='$table_num'";
		//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_company=$row['company'];
			$new_name=$row['oun_name'];//담당자
			break;
		case '2' ://new_cs_dongbu
			$sql="SELECT $name as new_name,$driver_name as oun_name,$company_name as company,$certi_number as certi_number ";
			$sql.="FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_name=$row['new_name'];
			$new_oun_name=$row['oun_name'];
			$new_company=$row['company'];
			$certi_number=$row['certi_number'];
			break;
		case '3'://lig_c
			$sql="SELECT $name as new_name,$company_name as company,$certi_number as certi_number ";
			$sql.="FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_name=$row['new_name'];
			$new_company=$row['company'];
			$certi_number=$row['certi_number'];
			break;
		case '4'://lig_drive
			$sql="SELECT $name as new_name,$lig_c_num as lig_c_num FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_oun_name=$row['new_name'];
			$lig_c_num=$row['lig_c_num'];
			//계약자 및 대리운전회사 보험  조회
				$lig_sql="SELECT c_name,certi_number,company FROM lig_c WHERE num='$lig_c_num'";
				//echo "lig_sql $lig_sql <br>";
				$lig_rs=mysql_query($lig_sql,$connect);
				$lig_row=mysql_fetch_array($lig_rs);
				$new_name=$lig_row['c_name'];
				$new_company=$lig_row['company'];
				$certi_number=$lig_row['certi_number'];
			//계약자 회사 조회를 위해 
			$table_num=$lig_c_num;
			break;
		case '5'://ssang_c
			$sql="SELECT $name as new_name,$company_name as company,$certi_number as certi_number ";
			$sql.="FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_name=$row['new_name'];
			$new_company=$row['company'];
			$certi_number=$row['certi_number'];
			break;
		case '6'://ssang_drive
			$sql="SELECT $name as new_name,$ssang_c_num as ssang_c_num FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$ssang_drive_num=$table_num;//증권발급 할때 사용하기 위해
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_oun_name=$row['new_name'];
			$ssang_c_num=$row['ssang_c_num'];
			//계약자 및 대리운전회사 보험  조회
				$lig_sql="SELECT c_name,certi_number,company FROM ssang_c WHERE num='$ssang_c_num'";
				$lig_rs=mysql_query($lig_sql,$connect);
				$lig_row=mysql_fetch_array($lig_rs);
				$new_name=$lig_row['c_name'];
				$new_company=$lig_row['company'];
				$certi_number=$lig_row['certi_number'];
			//계약자 회사 조회를 위해 
			$table_num=$ssang_c_num;
			break;
		case '8'://ssang_drive
			$sql="SELECT $name as new_name,$ssang_c_num as ssang_c_num FROM $tableName WHERE num='$table_num'";

			//echo "sql $sql <br>";
			$ssang_drive_num=$table_num;//증권발급 할때 사용하기 위해
			$rs=mysql_query($sql,$connect);

			
			$row=mysql_fetch_array($rs);

			$new_oun_name=$row['new_name'];

			$ssang_c_num=$row['ssang_c_num'];

			$certi_number=$row['certiNum'];
			//계약자 및 대리운전회사 보험  조회

			
				$lig_sql="SELECT c_name,certi_number,company FROM dongbu2_c WHERE num='$ssang_c_num'";
				//echo "lig_sql $lig_sql <br>";
				$lig_rs=mysql_query($lig_sql,$connect);

				$lig_row=mysql_fetch_array($lig_rs);

				$new_name=$lig_row['c_name'];
				$new_company=$lig_row['company'];
				
			//계약자 회사 조회를 위해 
			$table_num=$ssang_c_num;

			break;
		case '9'://ssang_drive
			$sql="SELECT $name as new_name,$ssang_c_num as ssang_c_num,certiNum FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$ssang_drive_num=$table_num;//증권발급 할때 사용하기 위해
			$rs=mysql_query($sql,$connect);	
			$row=mysql_fetch_array($rs);
			$new_oun_name=$row['new_name'];
			$ssang_c_num=$row['ssang_c_num'];
			$certi_number=$row['certiNum'];
			//계약자 및 대리운전회사 보험  조회
				$lig_sql="SELECT c_name,certi_number,company FROM dongbu2_c WHERE num='$ssang_c_num'";
				//echo "lig_sql $lig_sql <br>";
				$lig_rs=mysql_query($lig_sql,$connect);

				$lig_row=mysql_fetch_array($lig_rs);

				$new_name=$lig_row['c_name'];
				$new_company=$lig_row['company'];
				
			//계약자 회사 조회를 위해 
			$table_num=$ssang_c_num;
			break;
		case '10'://hyundai_c
			$sql="SELECT $name as new_name,$company_name as company,$certi_number as certi_number ";
			$sql.="FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_name=$row['new_name'];
			$new_company=$row['company'];
			$certi_number=$row['certi_number'];
			break;
		case '11'://hyundai_drive
			$sql="SELECT $name as new_name,$hyundai_c_num as hyundai_c_num FROM $tableName WHERE num='$table_num'";
			//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			$new_oun_name=$row['new_name'];
			$hyundai_c_num=$row['hyundai_c_num'];
			//계약자 및 대리운전회사 보험  조회
				$lig_sql="SELECT c_name,certi_number,company FROM hyundai_c WHERE num='$hyundai_c_num'";
				//echo "lig_sql $lig_sql <br>";
				$lig_rs=mysql_query($lig_sql,$connect);
				$lig_row=mysql_fetch_array($lig_rs);
				$new_name=$lig_row['c_name'];
				$new_company=$lig_row['company'];
				$certi_number=$lig_row['certi_number'];
			//계약자 회사 조회를 위해 
			$table_num=$hyundai_c_num;
			break;
		case '12':

			$sql="SELECT * FROM $tableName WHERE num='$table_num'";
		//echo "sql $sql <br>";
			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			
				$new_name=$row['Pname'];
				$new_company=$row['company'];
				//$certi_number=$lig_row['certi_number'];
			//계약자 회사 조회를 위해 
			$table_num=$row[num];


			break;
	}
?>