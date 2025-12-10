<?session_start();	
			include '../../../dbcon.php';
		header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
		echo "<values>\n";

		$proc		= $_POST["proc"];
		$title=iconv("utf-8","euc-kr",$_POST['title']);	
		$content=iconv("utf-8","euc-kr",$_POST['content']);	
		$dcontent=iconv("utf-8","euc-kr",$_POST['dcontent']);	
		if(isset($HTTP_RAW_POST_DATA)) {
			parse_str($HTTP_RAW_POST_DATA);
		}

		//echo "userid $userId $name";
		
		$level=$_SESSION['level'];
		//$dnum=$_SESSION['dNum']; //  _c 모줄에 있을 때 된다


		$uSql="SELECT * FROM 2014Member WHERE mem_id='$userId'";
		$uRs=mysql_query($uSql,$connect);
		$uRow=mysql_fetch_array($uRs);

		
		
		//$DB = new DB_Class();

		if($proc == "gasipan_W") {

			if($gasiNum){

				$update="UPDATE gasipan SET ";
				$update.=" title='$title', content='$content',notice_chk='$gongi',damdanga='$damdanga' ";
				$update.=" WHERE num='$gasiNum'";
				mysql_query($update,$connect);

				$message="수정 되었습니다";

			}else{

					$tSql="SELECT MAX(thread) FROM gasipan";
					$tRs=mysql_query($tSql,$connect);
					
					$thread=mysql_result($tRs,0,0)+1;

					$update ="UPDATE gasipan SET pnum=pnum+1 WHERE pnum>0";
					mysql_query($update,$connect);


					$name=$uRow[name];
					$sql="INSERT INTO gasipan (pnum,depth,thread,id,level,name,email,homepage,title,content,hit, ";
					$sql.=" temp_1,temp_2,gubun,passwd1,wdate,notice_chk,dnum,damdanga )";
					$sql.="  Values ('1','1','$thread','$userId','$level','$nAme','$email','$homepage','$title','$content','', ";
					$sql.=" '$temp_1', '$temp_2', '$gubun','$passwd1',now(),'$gongi','$dnum','$damdanga' )";

					$rs=mysql_query($sql,$connect);

					$message="저장 되었습니다";

			}



					echo("\t<item>\n");
						echo("\t\t<message>".$message."</message>\n");
						echo("\t\t<gongi>".$gongi."</gongi>\n");
						echo("\t\t<title>".$title."</title>\n");
						echo("\t\t<content>".$content.$sql."</content>\n");
					echo("\t</item>\n");
					
			
			@mysql_close($DB->Connect_ID);

		}else if($proc == "gasipan_R") {


			//$_SESSION['level'] 값이 5,6은 거래처 

			if($level>=5){



			}else{

				$table="gasipan";
				$where=" WHERE depth='1' ";
				$order = " order by notice_chk asc,num desc ";			
				$sql = "select * from " . $table . $where . $order;	
			}
			//echo "sql $sql <br>";
			$result2 = mysql_query($sql,$connect);
			$total  = mysql_num_rows($result2);
			
	include "./php/page_before_gasi.php";

			for($k=$count;$k<$last;$k++){
		
				

				 $a[1]=mysql_result($result2,$count,"num");

				 
				 $a[2]=mysql_result($result2,$count,"pnum");
//				 $a[3]=mysql_result($result2,$count,"etag");
				 $a[4]=mysql_result($result2,$count,"depth");
				 $a[5]=mysql_result($result2,$count,"thread");
				 $a[6]=mysql_result($result2,$count,"id");
				 $a[7]=mysql_result($result2,$count,"name");
				 $a[8]=mysql_result($result2,$count,"title");
				 $a[9]=mysql_result($result2,$count,"content");
				 $a[10]=mysql_result($result2,$count,"hit");
				 $a[11]=mysql_result($result2,$count,"temp_1");  //
				 $a[12]=mysql_result($result2,$count,"temp_2"); //특정일 기준 나이

				 $a[13]=mysql_result($result2,$count,"gubun");
				 $a[14]=mysql_result($result2,$count,"passwd1");
				 $a[15]=mysql_result($result2,$count,"wdate");

				 $a[15]=substr($a[15],0,16);
				 $a[16]=mysql_result($result2,$count,"notice_chk");

						$a[8]=htmlspecialchars($a[8],ENT_NOQUOTES,"euc-kr");
						$a[9]=htmlspecialchars($a[9],ENT_NOQUOTES,"euc-kr");

					//	$company=htmlspecialchars($company,ENT_NOQUOTES,"UTF-8");

						//if($inputDay=='00.00.00'){$inputDay='';}
				echo("\t<item>\n");
					echo("\t\t<num>".$a[1]."</num>\n");
					echo("\t\t<notice_chk>".$a[16]."</notice_chk>\n");
					echo("\t\t<pnum>".$a[2]."</pnum>\n");
					echo("\t\t<title>".$a[8]."</title>\n");
					echo("\t\t<content>".$a[9]."</content>\n");
					echo("\t\t<wdate>".$a[15]."</wdate>\n");
					echo("\t\t<write>".$a[7]."</write>\n");
					echo("\t\t<hit>".$a[10]."</hit>\n");
				
					 $count++;
					include "./php/gasipage.php";
					echo"<pages>".$pages."</pages>\n";
					echo"<totalpage>".$total_page."</totalpage>\n";

				echo("\t</item>\n");
			}			
			//@mysql_close($DB->Connect_ID);

		}else if($proc == "gasipan_A") {

			$sql1="SELECT * FROM gasipan WHERE num='$gasiNum'";
			$rs=mysql_query($sql1,$connect);
			$row=mysql_fetch_array($rs);

			$thread=$row[thread];
			$pnum=$row[pnum];
		    $depth=$row[depth]+1;;


		/*	//댓글이 몇개 달렸는지에 가장마지막 댓글이 되게 만들기 위해
				
				$gSql="SELECT max(depth) FROM gasipan WHERE thread='$thread'";
				$tRs=mysql_query($gSql,$connect);
				$depth=mysql_result($tRs,0,0);


				//가장마지막 댓글 다음에 댓글을 단다

				$hsql="SELECT * FROM gasipan WHERE thread='$thread' AND depth='$depth'";
				$hrs=mysql_query($hsql,$connect);
				$hrow=mysql_fetch_array($hrs);
				

				$pnum=$hrow[pnum];
		     	$depth=$hrow[depth]+1;;*/
			
			$update ="UPDATE gasipan SET pnum=pnum+1 WHERE pnum> $pnum ";
			mysql_query($update,$connect);

			$pnum=$pnum+1;


			$name=$uRow[name];
			$sql="INSERT INTO gasipan (pnum,depth,thread,id,level,name,email,homepage,title,content,hit, ";
			$sql.=" temp_1,temp_2,gubun,passwd1,wdate,notice_chk )";
			$sql.="  Values ('$pnum','$depth','$thread','$userId','1','$nAme','$email','$homepage','$title','$dcontent','', ";
			$sql.=" '$temp_1', '$temp_2', '$gubun','$passwd1',now(),'$gongi' )";

			$rs=mysql_query($sql,$connect);

			$message="저장 되었습니다";

			echo("\t<item>\n");
				echo("\t\t<message>".$message."</message>\n");
				echo("\t\t<gongi>".$gasiNum."</gongi>\n");
				echo("\t\t<title>".$title."</title>\n");
				echo("\t\t<content>".$content.$userId.$sql.$update."</content>\n");
			echo("\t</item>\n");

			@mysql_close($DB->Connect_ID);
		}else if($proc == "respon_R") {

			//댓글을 읽기 위해 

				$sSql="SELECT * FROM gasipan WHERE num='$gasiNum'";
				$srs=mysql_query($sSql,$connect);
				$sRow=mysql_fetch_array($srs);

				$thread=$sRow[thread];


				$dSql="SELECT * FROM gasipan WHERE thread='$thread' and depth>='2'  order by pnum asc";
				$dRs=mysql_query($dSql,$connect);
				$dNum=mysql_num_rows($dRs);
				for($_p=0;$_p<$dNum;$_p++){

					$dRow=mysql_fetch_array($dRs);

					$wdate=substr($dRow[wdate],0,16);
					echo("\t<thItem>\n");
						echo("\t\t<wdate>".$wdate."</wdate>\n");
					    echo("\t\t<dcontent>".$dRow[content]."</dcontent>\n");
						echo("\t\t<thread>".$sSql.$thread."</thread>\n");
						echo("\t\t<rNum>".$dRow[num]."</rNum>\n");
						echo("\t\t<depth>".$dRow[depth]."</depth>\n");
						echo("\t\t<id>".$dRow[name]."</id>\n");
					echo("\t</thItem>\n");

				}

				@mysql_close($DB->Connect_ID);

		}else if($proc == "reply") { //댓글 수정 

				  
				$usql="UPDATE gasipan SET  content='$dcontent' WHERE num='$gasiNum'";
				 mysql_query($usql,$connect);
				$message='수정 되었습니다';

			        echo("\t<item>\n");
						echo("\t\t<message>".$usql.$message."</message>\n");
					echo("\t</item>\n");

		}
		echo "</values>";
?>