<?header("Content-Type: text/xml; charset=euc-kr");

include '../../dbcon.php';


$mem_id=iconv("utf-8","euc-kr",$_GET['user_name']);
$passwd=iconv("utf-8","euc-kr",$_GET['user_pass']);




//$num=$mem_id."|".$passwd;

//$num=$en_sql;

//$num=$insertSql;

//$num=$insuranceNum;

//$num='신규 추가 완료';;


$query = "SELECT * FROM 2012Costomer WHERE mem_id='$mem_id' LIMIT 1";//새로운 회원가입 명단

//$num=$query;


$rs = mysql_query($query,$connect);
$row = mysql_fetch_array($rs);
$dNumber=$row['2012DaeriCompanyNum'];


if($row[permit]==1){
	if ($row[passwd] == md5($passwd)){
			$num=$dNumber;
			$p=1;
			$ex="환형합니다";
		  echo("{\"num\":\"".$num."\","
				 ."\"ex\":\"".$ex."\","
				  ."\"p\":\"".$p."\"}");
	}else{
			
			$num='비밀번호가 오류!! ';
			$p=2;
			$ex="비밀번호 오류";
			echo("{\"num\":\"".$num."\","
				 ."\"ex\":\"".$ex."\","
				  ."\"p\":\"".$p."\"}");
	}
}else{

			$num='담당자에게 문의 하세요!! ';
			$ex="담당자에게 문의 하세요";
			$p=3;
			//echo("{\"p\":\"".$p."\"}");
		   echo("{\"num\":\"".$num."\","
				 ."\"ex\":\"".$ex."\","
				  ."\"p\":\"".$p."\"}");

}



//$num=$row[passwd]."|".md5($passwd);

//echo("{\"num\":\"".$num."\"}");
?>