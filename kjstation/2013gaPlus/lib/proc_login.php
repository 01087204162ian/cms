<?session_start();

include '../../dbcon.php';

$query = "SELECT mem_id,level,passwd,name FROM 2012gaMember WHERE mem_id='$mem_id' LIMIT 1";//새로운 회원가입 명단

//echo "query $query <br>";

$rs = mysql_query($query,$connect);
$rowCount = mysql_num_rows($rs);
$row = mysql_fetch_array($rs);


//$redirect = ($redirectURL) ? urldecode($redirectURL) : "../intro/index2.html";

$redirect = ($redirectURL) ? urldecode($redirectURL) : "../dajoong/index.html";
//echo "row $rowCount <br>";


//return false;
if($rowCount){
	if ($row[passwd] == md5($passwd)){
		$userId = $mem_id;
		
		$userseq = md5($userId."sj");
		$userIp =  $_SERVER["REMOTE_ADDR"];
		$name   = $row[name];

		session_register("userId");
		session_register("userseq");
		session_register("userIp");
		SetCookie("host_id",$userId,0,"/");
		SetCookie("user_ip",$userIp,0,"/");
		SetCookie("small_sid",$useruid,0,"/");
		setcookie("userId","$useruid","0","/");
		setcookie("userseq","$userseq","0","/");
		setcookie("nAme","$name","0","/");


		header("Location: $redirect");
	}else{
	echo "<script>alert('비밀번호가 틀렸습니다 ./n기다려 주세요.');history.back();</script>";
	}
}else{
	//header("/kibs_admin.php");
	echo "<script>alert('관리자가 승인을 하지 않았습니다./n기다려 주세요.');history.back();</script>";
}


?>