<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?echo "다중일련번호정리하기위해";?></title>
	<link href="../css/member.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/MemberList.js" type="text/javascript"></script><!--ajax-->
	<script language="JavaScript" src="/kibs_admin/jsfile/lib_numcheck.js"></script>
</head>
<?$redirectURL='DMM_System'; ?>
<form>
<body id="popUp">

<?
if (!function_exists('json_decode')) {

    function json_decode($content, $assoc=false) {

        require_once '/_db/libs/JSON.php';

        if ($assoc) {

            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

        }

        else {

            $json = new Services_JSON;

        }

        return $json->decode($content);

    }

}



if (!function_exists('json_encode')) {

    function json_encode($content) {

        require_once '/_db/libs/JSON.php';

        $json = new Services_JSON;

        return $json->encode($content);

    }
$data = array("bunho"=>'1',"bunho2"=>'1',"name"=>iconv("euc-kr","utf-8","t"),'jumin' =>'1', 'daeriCompanyNum'=>'1','etage'=>'1');

//echo json_encode($data);
}?>
<? 
	//2024-12-11
	// 현대해상 체결동의 테스트
	$url = "https://center-api.simg.kr/v1/api/simg/hyundai/in/driver";
	$body_data=array(
	  "siteGubun" => "RS",
	  "driverCompony" =>"팔천대리",
	  "driverName" =>"홍길동",
	  "driverCell"=> "QegoHppwdivu4zhDu0R4Qg==",
	  "driverJumin" => "wonsRZIZQO1bNoL/dHAXRQ==",
	  "policyNumber" => "",
	  "validStartDay" => "20241201",
	  "validEndDay" => "20251130"
	)

$body = json_encode(body_data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec (iconv("utf-8","euc-kr",$ch));
?>

</body>
</html>