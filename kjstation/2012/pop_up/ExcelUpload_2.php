<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src="../js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="../js/create.js" type="text/javascript"></script><!--ajax-->	
<script type="text/javascript" src="./js/endorse_gisa_excel.js"></script>
<script type="text/javascript" src="/ajax/ajaxfileupload.js"></script>
<script type="text/javascript" src="../js/juminCheck.js"></script>
<link rel="stylesheet" href="./css/common.css" type="text/css" />
<link rel="stylesheet" href="./css/preminum2.css" type="text/css" />
</head>

<body style="background:#fafafa;" class="jui" >

<div class="wrap" id="wrap">
	
	<div class="bau_blue_bar" id="bau_blue_bar"></div>
	

     <div id="excelUpload" >
	

		  <input type='hidden' id='page' />
		  <input type='hidden' id='store' />
		  <input type='hidden' id='daeriCompanyNum' value='<?=$Dnum?>' />
		  <input type='hidden' id='certiTableNum' value='<?=$CertiNum?>' />
		  <input type='hidden' id='fileName' />
		
			 <!--<form action='upload_ok.php' name='upload' method='post' enctype='multipart/form-data'>-->
			 <form name="form" action="" method="POST" enctype="multipart/form-data">
				   <table id="d_table">
						<tbody id="sjo">
						
						<tbody>
						
					</table>
					<table id="d_table">
						<tbody id="sj">
						
						<tbody>
						
					</table>
			</form>

			
	
	</div> <!--listPart-->
		<!-- //bau_contents -->
		
		
	</div>
	<!-- //contents -->
</div>

</body>
</html>
<?
//echo "Dnum $Dnum <br>";
//echo "CertiNum $CertiNum";

?>