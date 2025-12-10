<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	
	
	$CertiTableNum=iconv("utf-8","euc-kr",$_GET['CertiTableNum']);// 
	$DaeriCompanyNum=iconv("utf-8","euc-kr",$_GET['DaeriCompanyNum']);//
	$InsuraneCompany=iconv("utf-8","euc-kr",$_GET['InsuraneCompany']);
	$sunso=iconv("utf-8","euc-kr",$_GET['sunso']);
	
	$message="보험료 테이블!!";

	
	for($i=0;$i<$sunso;$i++){
	  $a[$i] =iconv("utf-8","euc-kr",$_GET['s'.$i]);//시기
	  $b[$i] =iconv("utf-8","euc-kr",$_GET['e'.$i]);//종기

	  //입력이 되어 있느지 여부에 따라 

	  $sql="SELECT * FROM 2012Cpreminum WHERE CertiTableNum='$CertiTableNum' and DaeriCompanyNum='$DaeriCompanyNum' ";
	  $sql.="and sunso='$i' ";
	  $rs=mysql_query($sql,$connect);

	  $row=mysql_fetch_array($rs);
		//마직막에는 나이가 최고
		if(!$b[$i]){$b[$i]='999';}
	  if($row[num]){//update
			$update[$i]="UPDATE 2012Cpreminum SET ";
			$update[$i].="InsuraneCompany='$InsuraneCompany',CertiTableNum='$CertiTableNum', ";
			$update[$i].="DaeriCompanyNum='$DaeriCompanyNum',sPreminum='$a[$i]',ePreminum='$b[$i]',sunso='$i' ";
			$update[$i].="WHERE num='$row[num]'";
			mysql_query($update[$i],$connect);
			$message="수정완료!!";
			
	  }else{

			$insert[$i]="INSERT into 2012Cpreminum (InsuraneCompany,CertiTableNum,DaeriCompanyNum,sPreminum,ePreminum,sunso) ";
			$insert[$i].="values ('$InsuraneCompany','$CertiTableNum','$DaeriCompanyNum','$a[$i]','$b[$i]','$i')";

			$rs=mysql_query($insert[$i],$connect);
			if($rs){
			$message="저장완료!!";
			}
	  }

	  
		
    }	
	
		echo"<data>\n";
			echo "<sql>".$sql."</sql>\n";
			echo "<CertiTableNum>".$CertiTableNum."</CertiTableNum>\n";
			echo "<message>".$message."</message>\n";
			echo "<DaeriCompanyNum>".$DaeriCompanyNum."</DaeriCompanyNum>\n";
			echo "<InsuraneCompany>".$InsuraneCompany."</InsuraneCompany>\n";//1회차
			
			for($i=0;$i<$sunso;$i++){

			echo "<s".$i.">".$a[$i]."</s".$i.">\n";//
			echo "<e".$i.">".$b[$i]."</e".$i.">\n";//
			echo "<d".$i.">".$insert[$i]."</d".$i.">\n";//



			}
		echo"</data>";



	?>