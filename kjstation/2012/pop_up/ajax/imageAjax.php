<?php
  include '../../../dbcon.php';
	header("Content-Type: text/xml; charset=euc-kr");
echo("<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n");
	$uPhoneNumer = $_POST['uPhoneNumer'];
	$uimagefile = $_POST['uimagefile'];



$im=explode("/",$uimagefile);
$m=substr($im[3], 11, 15);
$dm=explode("_",$m);

$date = substr($dm[0],0,4)."-".substr($dm[0],4,2)."-".substr($dm[0],6,2);
$time = substr($dm[1],0,2)."-".substr($dm[1],2,2)."-".substr($dm[1],4,2);
	$sql="SELECT * FROM 2014Valet  WHERE mem_id='$uPhoneNumer'";

	$rs=mysql_query($sql,$connect);

	$row=mysql_fetch_array($rs);

	$jumin=$row[jumin1]."-".$row[jumin2];
   echo"<data>\n";
		echo "<name>"."성명".$row[name]."</name>\n";
		echo "<jumin>".$jumin."</jumin>\n";
        echo "<phone>"."핸드폰".$row[hphone]."</phone>\n";
	    echo "<uPhoneNumer>".$uPhoneNumer."</uPhoneNumer>\n";
	    echo "<date>".$date."</date>\n";
	    echo "<time>".$time."</time>\n";
  echo"</data>";

	?>