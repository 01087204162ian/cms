<?php
include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
	echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");

	

	$num	=iconv("utf-8","euc-kr",$_GET['num']);//
	$a1	    =iconv("utf-8","euc-kr",$_GET['a1']);//성명
	$a2	    =iconv("utf-8","euc-kr",$_GET['a2']);//주민번호
	$a3	    =iconv("utf-8","euc-kr",$_GET['a3']);//핸드폰번호
	$a4	    =iconv("utf-8","euc-kr",$_GET['a4']);//email
	
	$a29	    =iconv("utf-8","euc-kr",$_GET['a29']); //주소
	$a8	    =iconv("utf-8","euc-kr",$_GET['a8']); //챠랑변호
	$a9	    =iconv("utf-8","euc-kr",$_GET['a9']); //차대번호
	$a10	    =iconv("utf-8","euc-kr",$_GET['a10']); //가입시주행거리
	$maker	    =iconv("utf-8","euc-kr",$_GET['maker']); //제조사
	$kind	    =iconv("utf-8","euc-kr",$_GET['kind']); //승용RV
	$hiautoCarNum	    =iconv("utf-8","euc-kr",$_GET['hiautoCarNum']);
	$bunanb	    =iconv("utf-8","euc-kr",$_GET['bunanb']);
	$gigan       =iconv("utf-8","euc-kr",$_GET['gigan']);
	$taie       =iconv("utf-8","euc-kr",$_GET['taie']);

	
	$a10=iconv("utf-8","euc-kr",$_GET['a10']);

	$ju=explode(",",$a10);

	$a10=$ju[0].$ju[1].$ju[2];



/*
	$D2	=iconv("utf-8","euc-kr",$_GET['D2']); //개인정보 수집이용 동의 1 동의 2 동의 안함
	$D3	=iconv("utf-8","euc-kr",$_GET['D3']); //개인(신용)정보의 제공에 관한 사항
	$D4	=iconv("utf-8","euc-kr",$_GET['D4']); //고유식별정보*/
if($num){

		$update="UPDATE 2012hiauto SET Name='$a1',Jumin='$a2',hphone='$a3',email='$a4',wdate=now(), ";
		$update.="address='$a29',maker='$maker',kind='$kind',hiautoCarNum='$hiautoCarNum',juhang='$a10',bunanb='$bunanb',gigan='$gigan',taie='$taie', ";
		$update.="a8='$a8',a9='$a9',a10='$a6' WHERE num='$num'";


		mysql_query($update,$connect);

}else{


$insert="INSERT into 2012hiauto (Name,Jumin,hphone,email,wdate, ";
$insert.="address,maker,kind,hiautoCarNum,juhang,bunanb,gigan,taie, ";
$insert.="a8,a9,a10) ";
$insert.="values('$a1','$a2','$a3','$a4',now(), ";
$insert.="'$a29','$maker','$kind','$hiautoCarNum','$a10','$bunanb','$gigan','$taie',";
$insert.="'$a8','$a9','$a6' )";
//$rs=mysql_query($insert,$connect);
//신청 시간과 요일에 따라 

}
echo"<data>";
  // echo "<insert_sql>".$insert_sql."</insert_sql>\n";
 
   echo "<message>".$msg."</message>\n";
   echo "<a11>".$insert.$update."</a11>\n";
   echo "<a1>".$a1."</a1>\n";
   echo "<a2>".$a2."</a2>\n";
   echo "<a3>".$a3."</a3>\n";
   echo "<a4>".$a4."</a4>\n";
   
   echo "<a29>".$a29."</a29>\n";
   echo "<a6>".$a6."</a6>\n";
   echo "<a8>".$a8."</a8>\n";
   echo "<a9>".$a9."</a9>\n";
   echo "<a10>".$a10."</a10>\n";
   echo "<maker>".$maker."</maker>\n";
   echo "<kind>".$kind."</kind>\n";
   echo "<hiautoCarNum>".$hiautoCarNum."</hiautoCarNum>\n";
   echo "<bunanb>".$bunanb."</bunanb>\n";
   echo "<gigan>".$gigan."</gigan>\n";
   echo "<taie>".$taie."</taie>\n";
   echo "<a15>".$a15."</a15>\n";
   echo "<a16>".$a16."</a16>\n";
echo"</data>";

	?>