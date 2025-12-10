<?php 
		//session_start();

		include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";

	$table="2015ilban";
		if($proc == "cord") {

			$content=iconv("utf-8","euc-kr",$_POST['content']);//
			switch($kind){

				case 1 : //계약자
					$whereKind="WHERE jijem='$content'";
					break;
				case 2 : //증권번호
					$whereKind="WHERE cord='$content'";
					break;
				case 3 : //핸드폰
					$whereKind="WHERE hphone='$content'";
					break;

			}
			if($insuranceComNum==99){  


					if($product==99){

						$sql="SELECT * FROM ".$table." $whereKind order by insuCompany asc";

						//echo "sql $sql ";
						$result2=mysql_query($sql,$connect);
						$total=mysql_num_rows($result2);


					}else{
						$sql="SELECT * FROM ".$table." WHERE product='$product' order by insuCompany asc";

						echo "sql $sql ";
						$result2=mysql_query($sql,$connect);
						$total=mysql_num_rows($result2);
					}


					
		    }else{

					if($product==99){

						$sql="SELECT * FROM ".$table." WHERE  insuCompany='$insuranceComNum' order by insuCompany asc";
						$result2=mysql_query($sql,$connect);
						$total=mysql_num_rows($result2);
					}else{
						
					  	$sql="SELECT * FROM ".$table." WHERE  insuCompany='$insuranceComNum' and product='$product' order by insuCompany asc";
						$result2=mysql_query($sql,$connect);
						$total=mysql_num_rows($result2);					


					}
			}

			include "./php/page_before.php";
			for($k=$count;$k<$last;$k++){

				//$row=mysql_fetch_array($result2);				
			
			    $a[1]=mysql_result($result2,$count,"num");
			    $a[2]=mysql_result($result2,$count,"insuCompany");	
			    $a[3]=mysql_result($result2,$count,"product");
				$a[4]=mysql_result($result2,$count,"sigi");
				$a[5]=mysql_result($result2,$count,"cord");
				$a[6]=mysql_result($result2,$count,"cord2");
				$a[7]=mysql_result($result2,$count,"password");
				$a[8]=mysql_result($result2,$count,"verify");
				$a[9]=mysql_result($result2,$count,"wdate");

				$wdate=explode("-",$a[9]);
				$a[9]=$wdate[0].$wdate[1].$wdate[2];

				$a[10]=mysql_result($result2,$count,"jijem");
				$a[11]=mysql_result($result2,$count,"hphone");
				$a[12]=mysql_result($result2,$count,"phone");
				$a[13]=mysql_result($result2,$count,"fax");
				$a[14]=mysql_result($result2,$count,"gita");
				$a[15]=mysql_result($result2,$count,"carNumber");
				$a[16]=mysql_result($result2,$count,"jumin");
				
			//$a[3]=htmlspecialchars($a[3],ENT_NOQUOTES,"EUC-KR");

			//$row[product]=iconv("utf-8","euc-kr",$row[product]);
			//$row[cord]=htmlspecialchars($row[cord],ENT_NOQUOTES,"UTF-8");
			//$row[cord2]=htmlspecialchars($row[cord2],ENT_NOQUOTES,"UTF-8");
			//$row[product]=htmlspecialchars($row[product],ENT_NOQUOTES,"UTF-8");
			echo("\t<item>\n");
				echo("\t\t<num>".$a[1]."</num>\n");
				echo("\t\t<insuCompany>".$a[2]."</insuCompany>\n");
				echo("\t\t<product>".$a[3]."</product>\n");
				echo("\t\t<sigi>".$a[4]."</sigi>\n");
				echo("\t\t<cord>".$a[5]."</cord>\n");
				echo("\t\t<cord2>".number_format($a[6])."</cord2>\n");
				echo("\t\t<password>".$a[7]."</password>\n");
				echo("\t\t<verify>".$a[8]."</verify>\n");
				echo("\t\t<wdate>".$a[9]."</wdate>\n");
				echo("\t\t<jijem>".$a[10]."</jijem>\n");
				echo("\t\t<hphone>".$a[11]."</hphone>\n");
				echo("\t\t<phone>".$a[12]."</phone>\n");
				echo("\t\t<fax>".$a[13]."</fax>\n");
				echo("\t\t<gita>".$a[14]."</gita>\n");
				echo("\t\t<carNumber>".$a[15]."</carNumber>\n");
				echo("\t\t<jumin>".$a[16]."</jumin>\n");
				$count++;
				include "./php/page.php";
					echo"<pages>".$pages."</pages>\n";
					echo"<totalpage>".$total_page."</totalpage>\n";
			echo("\t</item>\n");
			
			}
			
		}else if($proc == "cord_sujeong") {
			
			//$sigi=iconv("utf-8","euc-kr",$_POST['sigi']);// 
			$jijem=iconv("utf-8","euc-kr",$_POST['jijem']);//
			$hphone=iconv("utf-8","euc-kr",$_POST['hphone']);//
			$gita=iconv("utf-8","euc-kr",$_POST['gita']);// 

			$verify=iconv("utf-8","euc-kr",$_POST['verify']);// 피보험자
			$carNumber=iconv("utf-8","euc-kr",$_POST['carNumber']);
			$cord2_=explode(",",$cord2);  //보험료
			$cord2=$cord2_[0].$cord2_[1].$cord2_[2];

				//사용인 코드 기준으로 조회 하여 없을 경우만 

				if(!$num){
				   $sql="SELECT * FROM ".$table." WHERE cord='$cord'";

				   $rs=mysql_query($sql,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];
				}
				
			if($num){


					$sql="update ".$table." SET insuCompany='$insCom',product='$product',sigi='$sigi',  ";
					$sql.=" cord='$cord',cord2='$cord2',password='$password',";
					$sql.=" verify='$verify',wdate=now(),jijem='$jijem',";
					$sql.=" hphone='$hphone',phone='$phone',fax='$fax',gita='$gita',";
					$sql.=" carNumber='$carNumber',jumin='$jumin' ";
					$sql.="WHERE num='$num'";

					mysql_query($sql,$connect);

			}else{


				    $sql="INSERT into ".$table." (insuCompany,product,sigi,  ";
					$sql.=" cord,cord2,password,";
					$sql.=" verify,wdate,jijem,";
					$sql.=" hphone,phone,fax,gita,carNumber,jumin )";
					$sql.="VALUES ('$insCom','$product','$sigi',  ";
					$sql.=" '$cord','$cord2','$password',";
					$sql.=" '$verify',wdate=now(),'$jijem',";
					$sql.=" '$hphone','$phone','$fax','$gita','$carNumber','$jumin' )";


					mysql_query($sql,$connect);

					//저장후 num 값을 찾는다

					$sql="SELECT * FROM ".$table." WHERE cord='$cord'";

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