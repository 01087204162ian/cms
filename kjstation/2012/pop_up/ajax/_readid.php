<?php 
		//session_start();

		include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";


		

	//$table="2015cord";
	  $table="2012Costomer";	
		$DaeriCompanyNum	    =iconv("utf-8","euc-kr",$_POST['daeriCompanyNum']);
		$permit					=iconv("utf-8","euc-kr",$_POST['permit']);
		$num						=iconv("utf-8","euc-kr",$_POST['num']);//
		$pass					=md5(iconv("utf-8","euc-kr",$_POST['password']));
		if($proc == "cord") {
			
			//if($insuranceComNum==99){
					$sql="SELECT * FROM ".$table." WHERE 2012DaeriCompanyNum='$DaeriCompanyNum' AND readIs='1' order by num asc";

					//echo "sql $sql ";
					$result2=mysql_query($sql,$connect);
					$total=mysql_num_rows($result2);
		  //  }else{

			//	$sql="SELECT * FROM ".$table." WHERE  insuCompany='$insuranceComNum' order by insuCompany asc";
			//		$result2=mysql_query($sql,$connect);
			//		$total=mysql_num_rows($result2);
			//}

			include "./php/page_before.php";
			for($k=$count;$k<$last;$k++){

				//$row=mysql_fetch_array($result2);				
			
			$a[1]=mysql_result($result2,$count,"num");
			//$a[2]=mysql_result($result2,$count,"insuCompany");	
			 //   $a[3]=iconv("utf-8","euc-kr",mysql_result($result2,$count,"name"));
				$a[3]=mysql_result($result2,$count,"mem_id");
				$a[5]=mysql_result($result2,$count,"hphone");
				$a[13]=mysql_result($result2,$count,"permit");
			/*	$a[5]=mysql_result($result2,$count,"cord");
				$a[6]=mysql_result($result2,$count,"cord2");
				$a[7]=mysql_result($result2,$count,"password");
				$a[8]=mysql_result($result2,$count,"verify");
				$a[9]=mysql_result($result2,$count,"wdate");
				$a[10]=mysql_result($result2,$count,"jijem");
				$a[11]=mysql_result($result2,$count,"jijemLady");
				
				$a[13]=mysql_result($result2,$count,"fax");
				$a[14]=mysql_result($result2,$count,"gita");*/
				$a[15]=mysql_result($result2,$count,"user");
			//$a[3]=htmlspecialchars($a[3],ENT_NOQUOTES,"EUC-KR");

			//$row[agent]=iconv("utf-8","euc-kr",$row[agent]);
			//$row[cord]=htmlspecialchars($row[cord],ENT_NOQUOTES,"UTF-8");
			//$row[cord2]=htmlspecialchars($row[cord2],ENT_NOQUOTES,"UTF-8");
			//$row[agent]=htmlspecialchars($row[agent],ENT_NOQUOTES,"UTF-8");
			echo("\t<item>\n");
				echo("\t\t<num>".$a[1]."</num>\n");
				echo("\t\t<user>".$a[15]."</user>\n");
				//echo("\t\t<insuCompany>".$a[2]."</insuCompany>\n");
				echo("\t\t<agent>".$a[3]."</agent>\n");
				//echo("\t\t<s_name>".$a[4]."</s_name>\n");
				echo("\t\t<cord>".$a[5]."</cord>\n");
				//echo("\t\t<cord2>".$a[6]."</cord2>\n");
				//echo("\t\t<password>".$a[7]."</password>\n");
				//echo("\t\t<verify>".$a[8]."</verify>\n");
				//echo("\t\t<wdate>".$a[9]."</wdate>\n");
				//echo("\t\t<jijem>".$a[10]."</jijem>\n");
				//echo("\t\t<jijemLady>".$a[11]."</jijemLady>\n");
				//echo("\t\t<phone>".$a[12]."</phone>\n");
				echo("\t\t<fax>".$a[13]."</fax>\n");
				//echo("\t\t<gita>".$a[14]."</gita>\n");
				echo("\t\t<permit>".$a[13]."</permit>\n");
				$count++;
				include "./php/page.php";
					echo"<pages>".$pages."</pages>\n";
					echo"<totalpage>".$total_page."</totalpage>\n";
			echo("\t</item>\n");
			
			}
			
		}else if($proc == "cord_sujeong") {

			//$permit					=iconv("utf-8","euc-kr",$_POST['permit']);
			$id						=iconv("utf-8","euc-kr",$_POST['mem_id']);// 
			$user						=iconv("utf-8","euc-kr",$_POST['user']);//
			$CostomerNum			=iconv("utf-8","euc-kr",$_POST['CostomerNum']);
			//$pass					=md5(iconv("utf-8","euc-kr",$_POST['password']));
			$DaeriCompanyName		=iconv("utf-8","euc-kr",$_POST['DaeriCompanyName']);
			$hphone					=iconv("utf-8","euc-kr",$_POST['hphone']);

	
			


				//대리운전회사를 조회 하여 

				$d_sql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
				$d_rs=mysql_query($d_sql,$connect);
				$d_row=mysql_fetch_array($d_rs);

				$daeriCompanyName=$d_row['company'];

				//아이디를 기준으로 조회 하여 없을 경우만 

				
				if(!$num){
				   $sql="SELECT * FROM ".$table." WHERE mem_id='$mem_id'";

				   $rs=mysql_query($sql,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];
				}
				
			if($num){

					//echo $daeriCompanyName;

					$update="UPDATE 2012Costomer SET 2012DaeriCompanyNum='$DaeriCompanyNum', mem_id='$id',name='$daeriCompanyName',hphone='$hphone',user='$user' ";
					$update.="WHERE num='$num'";

					//echo $update;
					mysql_query($update,$connect);

					$message='수정 되었습니다';

			}else{


				   $sql="INSERT INTO 2012Costomer (2012DaeriCompanyNum,mem_id ,passwd ,name ,jumin1 ,jumin2 ,hphone ,email ,level ,";
					$sql.="mail_check ,wdate ,inclu ,kind ,pAssKey ,pAssKeyoPen,permit,readIs,user )";
					$sql.="VALUES ('$DaeriCompanyNum','$id','$pass','$daeriCompanyName','','','$hphone','','5','', now(), '', '2', '', '','$permit','1','$user')";

					mysql_query($sql,$connect);

					$qsql="SELECT num FROM 2012Costomer WHERE mem_id='$mem_id'";
					$qrs=mysql_query($qsql,$connect);

					$qrow=mysql_fetch_array($qrs);

					$num=$qrow[num];

					//저장후 num 값을 찾는다
					$message='저장되었습니다';

				   
			}



			echo("\t<item>\n");
				echo "<update>".$update.$sql."</update>\n";
			    echo "<CostomerNum>".$num."</CostomerNum>\n";
				echo "<message>".$message."</message>\n";
			echo("\t</item>\n");

		}else if($proc == "pass_sujeong") {
				
				$sql="UPDATE ".$table." SET passwd='$pass' WHERE num='$num'";

				mysql_query($sql,$connect);
			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}else if($proc == "id_serch") {
				$mem_id=iconv("utf-8","euc-kr",$_POST['mem_id']);// 

				$sql= "SELECT * FROM ".$table." WHERE mem_id='$mem_id'";
				//echo $sql;
				$rs=mysql_query($sql,$connect);
				$total=mysql_num_rows($rs);

				if($total){

					$totalName='사용할 수 없는 ID 입니다';
					$check=1;
				}else{
					
					$totalName='사용할 수 있는 ID 입니다';
					$check=2;
				}
			echo("\t<item>\n");
				echo "<check>".$check."</check>\n";
				echo "<total>".$totalName."</total>\n";
			echo("\t</item>\n");

		}else if($proc == "permit_sujeong") {
				
				$sql="UPDATE ".$table." SET permit='$permit' WHERE num='$num'";

				mysql_query($sql,$connect);
			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}
		echo "</values>";
?>