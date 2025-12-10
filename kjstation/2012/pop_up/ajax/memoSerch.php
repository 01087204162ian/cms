<?php
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	$memoNum=iconv("utf-8","euc-kr",$_GET['memoNum']);
	//$FirstartDay=iconv("utf-8","euc-kr",$_GET['FirstartDay']);


include '../../../dbcon.php';
		//$msql="SELECT * FROM ssang_c_memo WHERE c_number='$c_number' order by num desc limit 5";
		$msql="SELECT * FROM ssang_c_memo WHERE num='$memoNum' ";
			$mRs=mysql_query($msql,$connect);
			

			$mRow=mysql_fetch_array($mRs);
				// $mem=$mRow[memo];//메모내용
				 //$wdate[$_p]=$mRow[wdate];
				 //$memkind[$_p]=$mRow[memokind];

				 switch($mRow[memokind]){
					 case 1 :
							$memkindName[$_p]='일반';
						 break;
					case  2 :

							$memkindName[$_p]='결재';
						break;
					case  3 :

							$memkindName[$_p]='납부';
						break;
					case  4 :

							$memkindName[$_p]='환급';
						break;
				 }
				 $memoNum[$_p]=$mRow[num];
	echo"<data>\n";	
		echo "<mNum>".$memoNum."</mNum>\n";
		echo "<mContent>".$mRow[memo]."</mContent>\n";
		echo "<mWdate>".$mRow[wdate]."</mWdate>\n";
		echo "<mKind>".$mRow[memokind]."</mKind>\n";
		echo "<mStore>".'메모수정'."</mStore>\n";
	echo"</data>";	
   
	   

?>