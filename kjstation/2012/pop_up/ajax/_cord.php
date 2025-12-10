<?php 
		//session_start();

		include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";


		

	$table="2015cord";
		
		

		if($proc == "cord") {
			
			if($insuranceComNum==99){
					$sql="SELECT * FROM ".$table." order by insuCompany asc";

					//echo "sql $sql ";
					$result2=mysql_query($sql,$connect);
					$total=mysql_num_rows($result2);
		    }else{

				$sql="SELECT * FROM ".$table." WHERE  insuCompany='$insuranceComNum' order by insuCompany asc";
					$result2=mysql_query($sql,$connect);
					$total=mysql_num_rows($result2);
			}

			include "./php/page_before.php";
			for($k=$count;$k<$last;$k++){

				//$row=mysql_fetch_array($result2);				
			
			$a[1]=mysql_result($result2,$count,"num");
			$a[2]=mysql_result($result2,$count,"insuCompany");	
			$a[3]=mysql_result($result2,$count,"agent");
				$a[4]=mysql_result($result2,$count,"name");
				$a[5]=mysql_result($result2,$count,"cord");
				$a[6]=mysql_result($result2,$count,"cord2");
				$a[7]=mysql_result($result2,$count,"password");
				$a[8]=mysql_result($result2,$count,"verify");
				$a[9]=mysql_result($result2,$count,"wdate");
				$a[10]=mysql_result($result2,$count,"jijem");
				$a[11]=mysql_result($result2,$count,"jijemLady");
				$a[12]=mysql_result($result2,$count,"phone");
				$a[13]=mysql_result($result2,$count,"fax");
				$a[14]=mysql_result($result2,$count,"gita");

			$a[3]=htmlspecialchars($a[3],ENT_NOQUOTES,"EUC-KR");

			//$row[agent]=iconv("utf-8","euc-kr",$row[agent]);
			//$row[cord]=htmlspecialchars($row[cord],ENT_NOQUOTES,"UTF-8");
			//$row[cord2]=htmlspecialchars($row[cord2],ENT_NOQUOTES,"UTF-8");
			//$row[agent]=htmlspecialchars($row[agent],ENT_NOQUOTES,"UTF-8");
			echo("\t<item>\n");
				echo("\t\t<num>".$a[1]."</num>\n");
				echo("\t\t<insuCompany>".$a[2]."</insuCompany>\n");
				echo("\t\t<agent>".$a[3]."</agent>\n");
				echo("\t\t<s_name>".$a[4]."</s_name>\n");
				echo("\t\t<cord>".$a[5]."</cord>\n");
				echo("\t\t<cord2>".$a[6]."</cord2>\n");
				echo("\t\t<password>".$a[7]."</password>\n");
				echo("\t\t<verify>".$a[8]."</verify>\n");
				echo("\t\t<wdate>".$a[9]."</wdate>\n");
				echo("\t\t<jijem>".$a[10]."</jijem>\n");
				echo("\t\t<jijemLady>".$a[11]."</jijemLady>\n");
				echo("\t\t<phone>".$a[12]."</phone>\n");
				echo("\t\t<fax>".$a[13]."</fax>\n");
				echo("\t\t<gita>".$a[14]."</gita>\n");
				$count++;
				include "./php/page.php";
					echo"<pages>".$pages."</pages>\n";
					echo"<totalpage>".$total_page."</totalpage>\n";
			echo("\t</item>\n");
			
			}
			
		}else if($proc == "cord_sujeong") {
			$agent=iconv("utf-8","euc-kr",$_POST['agent']);// 
			$s_name=iconv("utf-8","euc-kr",$_POST['s_name']);// 
			$jijem=iconv("utf-8","euc-kr",$_POST['jijem']);//
			$jijemLady=iconv("utf-8","euc-kr",$_POST['jijemLady']);//
			$gita=iconv("utf-8","euc-kr",$_POST['gita']);// 



				//사용인 코드 기준으로 조회 하여 없을 경우만 

				if(!$num){
				   $sql="SELECT * FROM ".$table." WHERE cord2='$cord2'";

				   $rs=mysql_query($sql,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];
				}
				
			if($num){


					$sql="update ".$table." SET insuCompany='$insCom',agent='$agent',name='$s_name',  ";
					$sql.=" cord='$cord',cord2='$cord2',password='$password',";
					$sql.=" verify='$verify',wdate=now(),jijem='$jijem',";
					$sql.=" jijemLady='$jijemLady',phone='$phone',fax='$fax',gita='$gita'";
					$sql.="WHERE num='$num'";

					mysql_query($sql,$connect);

			}else{


				    $sql="INSERT into ".$table." (insuCompany,agent,name,  ";
					$sql.=" cord,cord2,password,";
					$sql.=" verify,wdate,jijem,";
					$sql.=" jijemLady,phone,fax,gita )";
					$sql.="VALUES ('$insCom','$agent','$s_name',  ";
					$sql.=" '$cord','$cord2','$password',";
					$sql.=" '$verify',wdate=now(),'$jijem',";
					$sql.=" '$jijemLady','$phone','$fax','$gita' )";


					mysql_query($sql,$connect);

					//저장후 num 값을 찾는다

					$sql="SELECT * FROM ".$table." WHERE cord2='$cord2'";

				   $rs=mysql_query($sql,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];

				   
			}



			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}else if($proc == "pass_sujeong") {

				$sql="UPDATE ".$table." SET password='$password' WHERE num='$num'";

				mysql_query($sql,$connect);
			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}
		echo "</values>";
?>