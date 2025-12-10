<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	$cNum=iconv("utf-8","euc-kr",$_GET['cNum']);
	$joongt=iconv("utf-8","euc-kr",$_GET['joongt']);

	//echo "num $num  <br> ch $ch <br> $kor_str"; 




	//echo "me $change ";


			$update =" UPDATE SMSData SET ";
			$update .="joong='$joongt' WHERE SeqNo='$cNum'";

			$rs=mysql_query($update,$connect);


//중복이 된것중 다른 것도 중복을 만들기 위해 


		$sql="SELECT * FROM SMSData   WHERE SeqNo='$cNum'";
		//echo "sql $sql ";
		$rs=mysql_query($sql,$connect);

		$row=mysql_fetch_array($rs);


		$DaeriMemberNum=$row['2012DaeriMemberNum'];
		$endorse_day=$row[endorse_day];
//주민번호로 해지 또는 추가가 있는가 확인해야 겠지요

if($DaeriMemberNum){

		$rSql="SELECT * FROM SMSData WHERE endorse_day='$endorse_day' AND 2012DaeriMemberNum='$DaeriMemberNum' AND joong='2' ";
		$rS=mysql_query($rSql,$connect);

		$rRow=mysql_fetch_array($rS);
		
			$update2 =" UPDATE SMSData SET ";
			$update2 .="joong='$joongt' WHERE SeqNo='$rRow[SeqNo]'";

			//echo "upda $update2 ";

			mysql_query($update2,$connect);
}

		
		if($rs){

			$message='처리완료';

		}
		echo"<data>\n";
			echo "<num>".$update."</num>\n";
			echo "<ch>".$get."</ch>\n";
			echo "<message>".$message."</message>\n";
			echo "<care>".$change_1."</care>\n";
		echo"</data>";



	?>