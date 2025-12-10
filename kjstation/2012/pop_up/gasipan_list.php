<?php 
	include "../../dbcon.php";
	$mDir = "/2014";
	$css  = $mDir."/css/";
	$js	  = $mDir."/js/";
	$imgs = $mDir."/imgs/";
	$page = $mDir."/_pages/";
	
	$j_css  = "/jui/";
	$j_js  = "/jui/";
	
	$to_month = date("m");
	$to_day = date("d");
	
	$st_key = $_GET["st_key"];
	//echo "now $now_time ";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title></title>

<link rel="stylesheet" href="./css/common.css" type="text/css" />
<link rel="stylesheet" href="./css/preminum2.css" type="text/css" />



<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript" src="<?=$js?>jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?=$js?>jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?=$js?>common_util.js"></script>
<script type="text/javascript" src="./js/gaesipanList.js"></script>
</head>

<body style="background:#fafafa;" class="jui" >

<div class="wrap" id="wrap">
	
	<div class="bau_blue_bar" id="bau_blue_bar"></div>
	

   <div id="listPartE" >
		  <input type='hidden' id='page' />
		  <input type='hidden' id='store' />
		  <input type='hidden' id='daeriCompanyNum' />
		  <input type='hidden' id='certiTableNum' />
			<table id="d_table">	
				<tbody id="sjo">
				</tbody>
			</table>
	</div> <!--listPart-->
		<!-- //bau_contents -->
	<div id="thirdPartE" >	
		<table id="d_table">	
			<tbody id="kr">
			</tbody>
	   </table>
	</div>	
	<div id="secondPartE" >	
		<table id="d_table">	
			<tbody id="mr">
			</tbody>
	   </table>
	</div>	
	
	
	<!-- //contents -->
</div>

</body>
</html>