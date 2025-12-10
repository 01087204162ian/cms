<?php 
		//session_start();

		include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";

	$table="2015dongbu";
		if($proc == "cord") {

			$content=iconv("utf-8","euc-kr",$_POST['content']);//

			if($kind==99){
				if($product!=99){

						$whereKind="WHERE ch ='$product'";
				}

			}else{
					
				
				switch($kind){

					case 1 : //계약자
						$whereKind="WHERE name='$content'";
						break;
					case 2 : //주민번호

					

					//주민번호
					$contentLength=strlen($content);
					if($contentLength==13){
						$h1=substr($content,0,6);
						$h2=substr($content,6,7);
						

						$content=$h1."-".$h2;
					}
						$whereKind="WHERE jumin='$content'";
						break;
					case 3 : //핸드폰
						$contentLength=strlen($content);
						if($contentLength==11){
							$h1=substr($content,0,3);
							$h2=substr($content,3,4);
							$h3=substr($content,7,4);

							$content=$h1."-".$h2."-".$h3;
						}
						$whereKind="WHERE hphone='$content'";
						break;
					case 4 : //설계번호
						/*$contentLength=strlen($content);
						if($contentLength==11){
							$h1=substr($content,0,3);
							$h2=substr($content,3,4);
							$h3=substr($content,7,4);

							$content=$h1."-".$h2."-".$h3;
						}*/
						$whereKind="WHERE selNum ='$content'";
						break;

				}
				
			}
			

				$sql="SELECT * FROM ".$table." $whereKind  order by wdate asc";	
					  	
						$result2=mysql_query($sql,$connect);
						$total=mysql_num_rows($result2);					

			//echo "sql $sql ";

			include "./php/page_before.php";
			for($k=$count;$k<$last;$k++){

				//$row=mysql_fetch_array($result2);				
			
			    $a[1]=mysql_result($result2,$count,"num");
			//    $a[2]=mysql_result($result2,$count,"name");	
			   $a[3]=mysql_result($result2,$count,"ch");
				$a[4]=mysql_result($result2,$count,"bank");
			//	$a[5]=mysql_result($result2,$count,"cord");
			//	$a[6]=mysql_result($result2,$count,"cord2");
			//	$a[7]=mysql_result($result2,$count,"password");
				$a[8]=mysql_result($result2,$count,"selNum");
				$a[9]=mysql_result($result2,$count,"wdate");

				$wdate=explode("-",$a[9]);
				$a[9]=$wdate[0].$wdate[1].$wdate[2];

				$a[10]=mysql_result($result2,$count,"name");
				$a[11]=mysql_result($result2,$count,"hphone");
				$a[12]=mysql_result($result2,$count,"address");

			//	$a[13]=mysql_result($result2,$count,"address2");
			//	$a[14]=mysql_result($result2,$count,"gita");
			//	$a[15]=mysql_result($result2,$count,"carNumber");
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
				echo("\t\t<selNum>".$a[8]."</selNum>\n");
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
			$jijem=iconv("utf-8","euc-kr",$_POST['jijem']);//성명
			$hphone=iconv("utf-8","euc-kr",$_POST['hphone']);//
			$bank=iconv("utf-8","euc-kr",$_POST['sigi']);// 은행정보

			$verify=iconv("utf-8","euc-kr",$_POST['verify']);// 피보험자
			$phone=iconv("utf-8","euc-kr",$_POST['phone']);// 주소
			

				//사용인 코드 기준으로 조회 하여 없을 경우만 

				if(!$num){
				   $sql="SELECT * FROM ".$table." WHERE jumin='$jumin'";

				   $rs=mysql_query($sql,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];
				}
				
			if($num){


					$sql="update ".$table." SET name='$jijem',jumin='$jumin',hphone='$hphone',address='$phone',  ";
					$sql.=" bank='$bank',ch='$product',selNum='$selNum', wdate=now() ";
					$sql.="WHERE num='$num'";

					mysql_query($sql,$connect);

			}else{


				    $sql="INSERT into ".$table." (name,jumin,  ";
					$sql.=" hphone,address,bank,";
					$sql.=" ch,selNum,wdate ";
					$sql.="  )";
					$sql.="VALUES ('$jijem','$jumin',  ";
					$sql.=" '$hphone','$phone','$bank',";
					$sql.=" '1','$selNum',now() ";
					$sql.=" )";


					mysql_query($sql,$connect);

					//저장후 num 값을 찾는다

					$sql2="SELECT * FROM ".$table." WHERE jumin='$jumin'";

				   $rs=mysql_query($sql2,$connect);

				   $row=mysql_fetch_array($rs);

				   $num=$row[num];

				   
			}



			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}else if($proc == "selNum") {

				$sql="UPDATE ".$table." SET selNum='$selNum' WHERE num='$num'";

				mysql_query($sql,$connect);
			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}else if($proc == "ch") {

				$sql="UPDATE ".$table." SET ch='$ch' WHERE num='$num'";

				mysql_query($sql,$connect);
			echo("\t<item>\n");
				echo("\t\t<message>".$sql."</message>\n");
				echo("\t\t<num>".$num."</num>\n");
			echo("\t</item>\n");

		}
		echo "</values>";
?>