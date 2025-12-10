<?php 
		//session_start();
		include '../../../dbcon.php';
		//include '_DB_Class.php';
        
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];


		if(isset($HTTP_RAW_POST_DATA)) {
			parse_str($HTTP_RAW_POST_DATA);
		}

		$table = "gasipan";
		
		//$DB = new DB_Class();

		if($proc == "gasi_R") {
			
			$where = " where num='$gasiNum' ";		
			$sql = "select * from " . $table . $where . $order;
			
			//echo "sql $sql <br>";
			//$DB->query($sql);

			$rs=mysql_query($sql,$connect);
			$row=mysql_fetch_array($rs);
			//$DB->nextRecode();

						$userId=$_SESSION['ssID'];
						
					$title=htmlspecialchars($row[title],ENT_NOQUOTES,"euc-kr");
					$content=htmlspecialchars($row[content],ENT_NOQUOTES,"euc-kr");

					$title=iconv("euc-kr","utf-8",$title);	
					$content=iconv("euc-kr","utf-8",$content);	

					if($row[num]>=2){
						$beforNum=$row[num]-1;
					}

					// 댓글을 읽기 위해 thread 값을 찾기 위해 

					$thread=$row[thread];
					//max num 값과 같으면 다음글이 없다
					$tSql="SELECT MAX(num) FROM gasipan";
					$tRs=mysql_query($tSql,$connect);
					$maxNum2=mysql_result($tRs,0,0);

					//$maxNum=$tRow[thread];
					if($row[num]){
						if(($row[num])<$maxNum2){
							$afterNum=($row[num])+1;
						}
					}

					$update ="UPDATE gasipan SET hit=hit+1 WHERE num='$gasiNum'";
					mysql_query($update,$connect);
					

					$hit=($row[hit])+1;
					echo("\t<item>\n");
						echo("\t\t<beforNum>".$beforNum."</beforNum>\n");
						echo("\t\t<num>".$row[num]."</num>\n");
						echo("\t\t<afterNum>".$afterNum."</afterNum>\n");
						echo("\t\t<title>".$title."</title>\n");
						echo("\t\t<content>".$content."</content>\n");
						echo("\t\t<notice_chk><![CDATA[".$row[notice_chk]."]]></notice_chk>\n");			
						echo("\t\t<userId>".$userId."</userId>\n");
						echo("\t\t<hit>".$hit."</hit>\n");
						echo("\t\t<damdanga>".$row[damdanga]."</damdanga>\n");
					echo("\t</item>\n");



				//댓글을 읽기 위해 

				$dSql="SELECT * FROM gasipan WHERE thread='$thread' and depth>='2' order by pnum asc";
				$dRs=mysql_query($dSql,$connect);
				$dNum=mysql_num_rows($dRs);
				for($_p=0;$_p<$dNum;$_p++){

					$dRow=mysql_fetch_array($dRs);

					$dwdate=substr($dRow[wdate],0,11);

					$dRow[content]=htmlspecialchars($dRow[content],ENT_NOQUOTES,"euc-kr");
					$dRow[content]=iconv("euc-kr","utf-8",$dRow[content]);	

					$dRow[name]=htmlspecialchars($dRow[name],ENT_NOQUOTES,"euc-kr");
					$dRow[name]=iconv("euc-kr","utf-8",$dRow[name]);	
					echo("\t<thItem>\n");
						echo("\t\t<wdate>".$dwdate."</wdate>\n");
					    echo("\t\t<dcontent>".$dRow[content]."</dcontent>\n");
						echo("\t\t<thread>".$thread."</thread>\n");
						echo("\t\t<rNum>".$dRow[num]."</rNum>\n");
						echo("\t\t<depth>".$dRow[depth]."</depth>\n");

						echo("\t\t<id>".$dRow[name]."</id>\n");

					echo("\t</thItem>\n");

				}
					//댓글 일기



			//@mysql_close($DB->Connect_ID);

		}
		echo "</values>";
?>