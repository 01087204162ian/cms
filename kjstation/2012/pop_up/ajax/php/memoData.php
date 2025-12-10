<?
if(!$a[9]){//사업자번호가 없으면 주민번호로
	$a[9]=$a[3];
}
		//$msql="SELECT * FROM ssang_c_memo WHERE c_number='$c_number' order by num desc limit 5";
		$msql="SELECT * FROM ssang_c_memo WHERE c_number='$a[9]' order by num ";

		
			$mRs=mysql_query($msql,$connect);
			$mNum=mysql_num_rows($mRs);

		/*	if($mNum<5){

				$mNum=5;
			}*/
		echo "<msql>".$msql."</msql>\n";
		echo "<sNum>".$mNum."</sNum>\n";
	   for($_p=0;$_p<$mNum;$_p++){
			$mRow=mysql_fetch_array($mRs);
				 //$mem[$_p]=$mRow[memo];//메모내용
				 //$wdate[$_p]=$mRow[wdate];
				 //$memkind[$_p]=$mRow[memokind];

				 switch($mRow[memokind]){
					 case 1 :
							$memkindName='일반';
						 break;
					case  2 :

							$memkindName='결재';
						break;
					case  3 :

							$memkindName='가상';
						break;
					case  4 :

							$memkindName='환급';
						break;
				 }
				// $memoNum[$_p]=$mRow[num];
		
		echo "<mNum>".$mRow[num]."</mNum>\n";
		echo "<mContent>".$mRow[memo]."</mContent>\n";
		echo "<mWdate>".$mRow[wdate]."</mWdate>\n";
		echo "<mKind>".$memkindName."</mKind>\n";
		

	   }

?>