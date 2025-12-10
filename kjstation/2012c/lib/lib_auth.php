<?session_start();	
//로그린 체크 함수 인증이 필요한 페이지에만 include문으로 사용한다 한 세가지 값을 모두 충족하면 로그인한 상태로 파악한다
/*
echo "userID $userId  <br>";
echo "userseq $userseq <br>";
echo "ip $userIp <br>";
echo "nae $nAme <br>";*/
function getURL(){
		$server=getenv("SERVER_NAME");
		$file=getenv("SCRIPT_NAME");
		$query=getenv("QUERY_STRING");
		$url="$file";
	if($query) $url.="?$query";
		return $url;
  	}
function loginchk($userId,$userseq,$userIp)
{

	if(!$userId or !$userseq or (md5($userId."csj") !=$userseq) or $userIp!=$_SERVER["REMOTE_ADDR"])
	{
		return false;
	}else{

		return true;
	}

}

if (!loginchk($HTTP_SESSION_VARS[userId],$HTTP_COOKIE_VARS[userseq],$HTTP_SESSION_VARS[userIp]))
{
	if ($redirectURL == 'DMM_System')//pop창에서 로그아웃 될 경우
	{	
		header("Location: ../../sjLogin.php");
		exit;
	}
	else
	{		
		header("Location: ../../clogin.php?url=".urlencode($PHP_SELF));
		
		exit;
	}
}

include '../../dbcon.php';
$current_dir      		= getURL();
	$current_path     		= split("/",$current_dir);


/*
$size_current_path=count($current_path);
for($i=0;$i<$size_current_path;$i++){

echo"$i 번";	echo $current_path[$i]; echo "<br>";
}
*/	if($current_path[2]==ssangyong)$Pcompany=1;
	if($current_path[2]==dongbu)$Pcompany=2;
	if($current_path[2]==dongbu2)$Pcompany=3;
	if($current_path[2]==lig)$Pcompany=4;
	if($current_path[2]==hyundai2)$Pcompany=5;
	if($current_path[2]==intro)$Pcompany=6;			

//echo $Pcompany;
	?>
