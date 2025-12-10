<?include '../../2012c/lib/lib_auth.php';?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="euc-kr">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>검증</title>
	<link rel="stylesheet" href="../../2012/pop_up/css/common.css" type="text/css" />
	<link rel="stylesheet" href="../../2012/pop_up/css/preminum2.css" type="text/css" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/jumbotron/">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
     <!--<link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">-->
	 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.css">
  </head>
  <body>
    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script
  src="https://code.jquery.com/jquery-3.6.1.js"
  integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
  crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css"><!--2017-09-05 sj 추가 datepicker
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script><!--2017-09-05 sj 추가 datepicker-->
  
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
  </body>
</html>


		  <input type='hidden' id='page' />
		  <input type='hidden' id='store' />
		  <input type='hidden' id='daeriCompanyNum' value='<?=$DaeriCompanyNum?>' />
		  <input type='hidden' id='etage' value='<?=$etage?>' />
      <div class="container">
		   
		       
				<label for='formFile' class='form-label'>Excel 명단 파일을 선택하고 '신청하기' 클릭하세요</label>
			    <div class=' input-group mb-3'>
				  
				  <input class='form-control' type='file' id='upload_file'>
				  <span class='btn btn-success'id='upload'>신청하기</span><span id='url'></span>
				</div>
				

				<br>
				<table width='100%' class='table'>
					<tbody id='pj'></tbody>
				</table>
			  
			</div>
	  </div>
<script src="./js/excell.js"></script>


